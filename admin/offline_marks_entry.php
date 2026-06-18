<?php
include "header/header.php";

$alertType = '';
$alertMessage = '';

$createTableSql = "CREATE TABLE IF NOT EXISTS lms_offline_marks_entry (
    entry_id INT(11) NOT NULL AUTO_INCREMENT,
    student_id INT(11) NOT NULL,
    semester_id INT(11) NOT NULL,
    course_name VARCHAR(200) NOT NULL,
    nptel_status ENUM('Pass','Fail') NOT NULL,
    nptel_exam_score DECIMAL(5,2) DEFAULT NULL,
    nptel_assignment_raw DECIMAL(5,2) DEFAULT NULL,
    nptel_assignment_converted DECIMAL(5,2) DEFAULT NULL,
    ise1_marks DECIMAL(5,2) DEFAULT NULL,
    ise2_marks DECIMAL(5,2) DEFAULT NULL,
    ese_written_marks DECIMAL(5,2) DEFAULT NULL,
    college_total_score DECIMAL(6,2) DEFAULT NULL,
    final_score DECIMAL(6,2) DEFAULT NULL,
    remarks TEXT DEFAULT NULL,
    created_by INT(11) DEFAULT NULL,
    updated_by INT(11) DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (entry_id),
    UNIQUE KEY uniq_offline_marks (student_id, semester_id, course_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

mysqli_query($db_handle->conn, $createTableSql);

$students = array();
$studentSql = "SELECT student_id, registration_no, fname FROM lms_student_master ORDER BY fname ASC";
$studentResult = mysqli_query($db_handle->conn, $studentSql);
if ($studentResult) {
    while ($row = mysqli_fetch_assoc($studentResult)) {
        $students[] = array(
            'student_id' => (int)($row['student_id'] ?? 0),
            'registration_no' => (string)($row['registration_no'] ?? ''),
            'fname' => (string)($row['fname'] ?? '')
        );
    }
}

$semesters = array();
$semesterSql = "SELECT semester_id, semester_name FROM lms_semester_master ORDER BY semester_id ASC";
$semesterResult = mysqli_query($db_handle->conn, $semesterSql);
if ($semesterResult) {
    while ($row = mysqli_fetch_assoc($semesterResult)) {
        $semesters[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_offline_marks'])) {
    $studentId = intval($_POST['student_id'] ?? 0);
    $semesterId = intval($_POST['semester_id'] ?? 0);
    $courseName = trim((string)($_POST['course_name'] ?? ''));
    $nptelStatus = trim((string)($_POST['nptel_status'] ?? 'Pass'));
    $remarks = trim((string)($_POST['remarks'] ?? ''));
    $createdBy = intval($userid ?? 0);

    $nptelExamScore = ($_POST['nptel_exam_score'] !== '' ? (float)$_POST['nptel_exam_score'] : null);
    $nptelAssignmentRaw = ($_POST['nptel_assignment_raw'] !== '' ? (float)$_POST['nptel_assignment_raw'] : null);
    $ise1Marks = ($_POST['ise1_marks'] !== '' ? (float)$_POST['ise1_marks'] : null);
    $ise2Marks = ($_POST['ise2_marks'] !== '' ? (float)$_POST['ise2_marks'] : null);
    $eseWrittenMarks = ($_POST['ese_written_marks'] !== '' ? (float)$_POST['ese_written_marks'] : null);

    $validationErrors = array();

    if ($studentId <= 0) {
        $validationErrors[] = 'Please select a student.';
    }
    if ($semesterId <= 0) {
        $validationErrors[] = 'Please select a semester.';
    }
    if ($courseName === '') {
        $validationErrors[] = 'Please enter NPTEL course name.';
    }
    if ($nptelStatus !== 'Pass' && $nptelStatus !== 'Fail') {
        $validationErrors[] = 'Invalid NPTEL result selected.';
    }

    if ($nptelStatus === 'Pass') {
        if ($nptelExamScore === null) {
            $validationErrors[] = 'NPTEL exam score is required for pass case.';
        } elseif ($nptelExamScore < 0 || $nptelExamScore > 100) {
            $validationErrors[] = 'NPTEL exam score must be between 0 and 100.';
        }
    }

    $assignmentConverted = null;
    $collegeTotal = null;
    $finalScore = null;

    if ($nptelStatus === 'Fail') {
        if ($nptelAssignmentRaw === null || $ise1Marks === null || $ise2Marks === null || $eseWrittenMarks === null) {
            $validationErrors[] = 'For failed NPTEL case, fill ISE-1, ISE-2, NPTEL assignment and ESE written marks.';
        } else {
            if ($nptelAssignmentRaw < 0 || $nptelAssignmentRaw > 100) {
                $validationErrors[] = 'NPTEL assignment raw marks must be between 0 and 100.';
            }
            if ($ise1Marks < 0 || $ise1Marks > 20) {
                $validationErrors[] = 'ISE-1 marks must be between 0 and 20.';
            }
            if ($ise2Marks < 0 || $ise2Marks > 20) {
                $validationErrors[] = 'ISE-2 marks must be between 0 and 20.';
            }
            if ($eseWrittenMarks < 0 || $eseWrittenMarks > 40) {
                $validationErrors[] = 'ESE written marks must be between 0 and 40.';
            }

            $assignmentConverted = round(($nptelAssignmentRaw / 100) * 20, 2);
            $collegeTotal = round($ise1Marks + $ise2Marks + $assignmentConverted + $eseWrittenMarks, 2);
            $finalScore = $collegeTotal;
        }
    } else {
        $finalScore = $nptelExamScore;
    }

    if (empty($validationErrors)) {
        mysqli_begin_transaction($db_handle->conn);
        $allOk = true;

        $enrollmentId = 0;
        $enrollmentSql = "SELECT enrollment_id FROM lms_enrollment WHERE student_id = ? AND semester_id = ? ORDER BY enrollment_id DESC LIMIT 1";
        $enrollStmt = mysqli_prepare($db_handle->conn, $enrollmentSql);
        if ($enrollStmt) {
            mysqli_stmt_bind_param($enrollStmt, 'ii', $studentId, $semesterId);
            mysqli_stmt_execute($enrollStmt);
            $enrollResult = mysqli_stmt_get_result($enrollStmt);
            if ($enrollResult && mysqli_num_rows($enrollResult) > 0) {
                $enrollRow = mysqli_fetch_assoc($enrollResult);
                $enrollmentId = intval($enrollRow['enrollment_id'] ?? 0);
            }
            mysqli_stmt_close($enrollStmt);
        }

        $offlineFlag = ($nptelStatus === 'Fail') ? 1 : 0;
        $offlineScore = ($nptelStatus === 'Fail') ? $collegeTotal : null;
        $offlineDate = ($nptelStatus === 'Fail') ? date('Y-m-d') : null;

        $nptelCheckSql = "SELECT nptel_id FROM lms_nptel_records WHERE student_id = ? AND semester_id = ? AND course_name = ? LIMIT 1";
        $nptelCheckStmt = mysqli_prepare($db_handle->conn, $nptelCheckSql);
        $existingNptelId = 0;
        if ($nptelCheckStmt) {
            mysqli_stmt_bind_param($nptelCheckStmt, 'iis', $studentId, $semesterId, $courseName);
            mysqli_stmt_execute($nptelCheckStmt);
            $nptelCheckResult = mysqli_stmt_get_result($nptelCheckStmt);
            if ($nptelCheckResult && mysqli_num_rows($nptelCheckResult) > 0) {
                $nptelRow = mysqli_fetch_assoc($nptelCheckResult);
                $existingNptelId = intval($nptelRow['nptel_id'] ?? 0);
            }
            mysqli_stmt_close($nptelCheckStmt);
        } else {
            $allOk = false;
        }

        if ($allOk && $existingNptelId > 0) {
            $updateNptelSql = "UPDATE lms_nptel_records
                               SET enrollment_id = ?, score = ?, pass_fail = ?, offline_exam_flag = ?, offline_exam_score = ?, offline_exam_date = ?
                               WHERE nptel_id = ?";
            $updateNptelStmt = mysqli_prepare($db_handle->conn, $updateNptelSql);
            if ($updateNptelStmt) {
                mysqli_stmt_bind_param(
                    $updateNptelStmt,
                    'idsidsi',
                    $enrollmentId,
                    $nptelExamScore,
                    $nptelStatus,
                    $offlineFlag,
                    $offlineScore,
                    $offlineDate,
                    $existingNptelId
                );
                $allOk = $allOk && mysqli_stmt_execute($updateNptelStmt);
                mysqli_stmt_close($updateNptelStmt);
            } else {
                $allOk = false;
            }
        } elseif ($allOk) {
            $insertNptelSql = "INSERT INTO lms_nptel_records (enrollment_id, student_id, semester_id, course_name, score, pass_fail, offline_exam_flag, offline_exam_score, offline_exam_date)
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $insertNptelStmt = mysqli_prepare($db_handle->conn, $insertNptelSql);
            if ($insertNptelStmt) {
                mysqli_stmt_bind_param(
                    $insertNptelStmt,
                    'iiisdsids',
                    $enrollmentId,
                    $studentId,
                    $semesterId,
                    $courseName,
                    $nptelExamScore,
                    $nptelStatus,
                    $offlineFlag,
                    $offlineScore,
                    $offlineDate
                );
                $allOk = $allOk && mysqli_stmt_execute($insertNptelStmt);
                mysqli_stmt_close($insertNptelStmt);
            } else {
                $allOk = false;
            }
        }

        if ($allOk) {
            $saveOfflineSql = "INSERT INTO lms_offline_marks_entry
                (student_id, semester_id, course_name, nptel_status, nptel_exam_score, nptel_assignment_raw, nptel_assignment_converted, ise1_marks, ise2_marks, ese_written_marks, college_total_score, final_score, remarks, created_by, updated_by)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE
                    nptel_status = VALUES(nptel_status),
                    nptel_exam_score = VALUES(nptel_exam_score),
                    nptel_assignment_raw = VALUES(nptel_assignment_raw),
                    nptel_assignment_converted = VALUES(nptel_assignment_converted),
                    ise1_marks = VALUES(ise1_marks),
                    ise2_marks = VALUES(ise2_marks),
                    ese_written_marks = VALUES(ese_written_marks),
                    college_total_score = VALUES(college_total_score),
                    final_score = VALUES(final_score),
                    remarks = VALUES(remarks),
                    updated_by = VALUES(updated_by)";

            $saveOfflineStmt = mysqli_prepare($db_handle->conn, $saveOfflineSql);
            if ($saveOfflineStmt) {
                mysqli_stmt_bind_param(
                    $saveOfflineStmt,
                    'iissddddddddsii',
                    $studentId,
                    $semesterId,
                    $courseName,
                    $nptelStatus,
                    $nptelExamScore,
                    $nptelAssignmentRaw,
                    $assignmentConverted,
                    $ise1Marks,
                    $ise2Marks,
                    $eseWrittenMarks,
                    $collegeTotal,
                    $finalScore,
                    $remarks,
                    $createdBy,
                    $createdBy
                );
                $allOk = $allOk && mysqli_stmt_execute($saveOfflineStmt);
                mysqli_stmt_close($saveOfflineStmt);
            } else {
                $allOk = false;
            }
        }

        if ($allOk) {
            mysqli_commit($db_handle->conn);
            $alertType = 'success';
            $alertMessage = 'Offline marks entry saved successfully.';
        } else {
            mysqli_rollback($db_handle->conn);
            $alertType = 'danger';
            $alertMessage = 'Unable to save marks entry. Please try again.';
        }
    } else {
        $alertType = 'warning';
        $alertMessage = implode(' ', $validationErrors);
    }
}

$recentEntries = array();
$recentSql = "SELECT ome.entry_id, ome.course_name, ome.nptel_status, ome.nptel_exam_score, ome.nptel_assignment_converted,
                     ome.ise1_marks, ome.ise2_marks, ome.ese_written_marks, ome.final_score,
                     sm.registration_no, sm.fname, sem.semester_name
              FROM lms_offline_marks_entry ome
              LEFT JOIN lms_student_master sm ON sm.student_id = ome.student_id
              LEFT JOIN lms_semester_master sem ON sem.semester_id = ome.semester_id
              ORDER BY ome.updated_at DESC
              LIMIT 12";
$recentResult = mysqli_query($db_handle->conn, $recentSql);
if ($recentResult) {
    while ($row = mysqli_fetch_assoc($recentResult)) {
        $recentEntries[] = $row;
    }
}
?>

<div class="content-wrapper">
    <style>
        .student-autocomplete-wrap {
            position: relative;
        }

        .student-suggestions {
            display: none;
            position: absolute;
            left: 0;
            right: 0;
            top: 100%;
            z-index: 1050;
            max-height: 240px;
            overflow-y: auto;
            background: #fff;
            border: 1px solid #d2d6de;
            border-top: 0;
            border-radius: 0 0 4px 4px;
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.12);
        }

        .student-suggestion-item {
            padding: 10px 12px;
            cursor: pointer;
            border-bottom: 1px solid #f0f2f5;
        }

        .student-suggestion-item:hover,
        .student-suggestion-item.active {
            background: #f4f8fb;
        }

        .student-suggestion-name {
            font-weight: 600;
            color: #1f2d3d;
        }

        .student-suggestion-meta {
            font-size: 12px;
            color: #6b778c;
        }

        .student-suggestion-empty {
            padding: 10px 12px;
            color: #777;
        }
    </style>
    <section class="content-header">
        <h1><i class="fa fa-pencil-square-o"></i> Offline Marks Entry</h1>
        <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Offline Marks Entry</li>
        </ol>
    </section>

    <section class="content">
        <?php if ($alertMessage !== ''): ?>
            <div class="alert alert-<?php echo htmlspecialchars($alertType); ?> alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo htmlspecialchars($alertMessage); ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-7">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-edit"></i> Enter Student Marks</h3>
                    </div>
                    <form method="POST" action="">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Student</label>
                                        <div class="student-autocomplete-wrap">
                                            <input type="hidden" name="student_id" id="student_id" required>
                                            <input type="text" class="form-control" id="student_search" autocomplete="off" placeholder="Type student name or registration no" required>
                                            <div class="student-suggestions" id="student_suggestions"></div>
                                        </div>
                                        <small class="text-muted">Start typing to select a student.</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Department</label>
                                        <input type="text" class="form-control" id="department_name" placeholder="Auto-filled after student selection" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Semester</label>
                                        <select class="form-control" name="semester_id" id="semester_id" required>
                                            <option value="">Select Semester</option>
                                            <?php foreach ($semesters as $semester): ?>
                                                <option value="<?php echo (int)$semester['semester_id']; ?>">
                                                    <?php echo htmlspecialchars($semester['semester_name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>NPTEL Course Name</label>
                                        <input type="text" class="form-control" name="course_name" id="course_name" placeholder="e.g. Introduction to AI" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>NPTEL Result</label>
                                        <select class="form-control" name="nptel_status" id="nptel_status" required>
                                            <option value="Pass">Pass</option>
                                            <option value="Fail">Fail</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>NPTEL Exam Score (out of 100)</label>
                                        <input type="number" step="0.01" min="0" max="100" class="form-control" name="nptel_exam_score" id="nptel_exam_score" placeholder="Enter NPTEL exam marks">
                                    </div>
                                </div>
                            </div>

                            <div id="failFields" style="display:none;">
                                <div class="callout callout-warning" style="margin-bottom: 15px;">
                                    Failed in NPTEL exam: Enter college components. NPTEL assignment is auto-converted to 20 marks.
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>NPTEL Assignment Raw (out of 100)</label>
                                            <input type="number" step="0.01" min="0" max="100" class="form-control calc-trigger" name="nptel_assignment_raw" id="nptel_assignment_raw" placeholder="Enter assignment marks">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>NPTEL Assignment Converted (out of 20)</label>
                                            <input type="text" class="form-control" id="nptel_assignment_converted_view" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>ISE-1 (out of 20)</label>
                                            <input type="number" step="0.01" min="0" max="20" class="form-control calc-trigger" name="ise1_marks" id="ise1_marks" placeholder="ISE-1">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>ISE-2 (out of 20)</label>
                                            <input type="number" step="0.01" min="0" max="20" class="form-control calc-trigger" name="ise2_marks" id="ise2_marks" placeholder="ISE-2">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>ESE Written (out of 40)</label>
                                            <input type="number" step="0.01" min="0" max="40" class="form-control calc-trigger" name="ese_written_marks" id="ese_written_marks" placeholder="ESE Written">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>College Total (out of 100)</label>
                                            <input type="text" class="form-control" id="college_total_view" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Remarks</label>
                                <textarea class="form-control" name="remarks" rows="3" placeholder="Optional remarks"></textarea>
                            </div>
                        </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary" name="save_offline_marks">
                                <i class="fa fa-save"></i> Save Marks Entry
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-5">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-info-circle"></i> Evaluation Rule</h3>
                    </div>
                    <div class="box-body">
                        <ul style="padding-left: 18px; margin-bottom: 0;">
                            <li>If NPTEL result is Pass: final score = NPTEL exam score.</li>
                            <li>If NPTEL result is Fail: college exam marks are entered.</li>
                            <li>NPTEL assignment conversion formula: (Raw / 100) x 20.</li>
                            <li>College total = ISE-1 + ISE-2 + Converted Assignment + ESE Written.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-history"></i> Recent Offline Marks Entries</h3>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Course</th>
                                    <th>Semester</th>
                                    <th>NPTEL Result</th>
                                    <th>NPTEL Score</th>
                                    <th>College Components</th>
                                    <th>Final Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($recentEntries)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">No entries yet.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($recentEntries as $entry): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars(($entry['registration_no'] ?? '-') . ' - ' . ($entry['fname'] ?? '-')); ?></td>
                                            <td><?php echo htmlspecialchars($entry['course_name'] ?? '-'); ?></td>
                                            <td><?php echo htmlspecialchars($entry['semester_name'] ?? '-'); ?></td>
                                            <td>
                                                <?php if (($entry['nptel_status'] ?? '') === 'Pass'): ?>
                                                    <span class="label label-success">PASS</span>
                                                <?php else: ?>
                                                    <span class="label label-danger">FAIL</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo ($entry['nptel_exam_score'] !== null ? htmlspecialchars($entry['nptel_exam_score']) : '-'); ?></td>
                                            <td>
                                                <?php
                                                if (($entry['nptel_status'] ?? '') === 'Fail') {
                                                    echo 'ISE1: ' . htmlspecialchars((string)$entry['ise1_marks']) .
                                                        ', ISE2: ' . htmlspecialchars((string)$entry['ise2_marks']) .
                                                        ', Assign(conv): ' . htmlspecialchars((string)$entry['nptel_assignment_converted']) .
                                                        ', ESE: ' . htmlspecialchars((string)$entry['ese_written_marks']);
                                                } else {
                                                    echo '-';
                                                }
                                                ?>
                                            </td>
                                            <td><strong><?php echo htmlspecialchars((string)($entry['final_score'] ?? '0')); ?></strong></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
(function() {
    var students = <?php echo json_encode($students, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>;
    var studentSearch = document.getElementById('student_search');
    var studentIdInput = document.getElementById('student_id');
    var suggestionBox = document.getElementById('student_suggestions');
    var activeSuggestionIndex = -1;
    var currentMatches = [];

    function normalizeText(value) {
        return String(value || '').toLowerCase().trim();
    }

    function studentLabel(student) {
        return (student.registration_no || '-') + ' - ' + (student.fname || '-');
    }

    function hideStudentSuggestions() {
        suggestionBox.style.display = 'none';
        suggestionBox.innerHTML = '';
        activeSuggestionIndex = -1;
    }

    function setStudent(student) {
        studentIdInput.value = student.student_id;
        studentSearch.value = studentLabel(student);
        hideStudentSuggestions();
        loadStudentDetails(student.student_id);
    }

    function setValue(id, value) {
        var element = document.getElementById(id);
        if (element) {
            element.value = value || '';
        }
    }

    function clearStudentAutofill() {
        setValue('department_name', '');
        setValue('semester_id', '');
        setValue('course_name', '');
        setValue('nptel_exam_score', '');
        setValue('nptel_assignment_raw', '');
        setValue('ise1_marks', '');
        setValue('ise2_marks', '');
        setValue('ese_written_marks', '');
        var remarks = document.querySelector('textarea[name="remarks"]');
        if (remarks) {
            remarks.value = '';
        }
        updateFailCalculations();
    }

    function loadStudentDetails(studentId) {
        var url = 'offline_marks_student_ajax.php?student_id=' + encodeURIComponent(studentId);
        fetch(url, { credentials: 'same-origin' })
            .then(function(response) {
                return response.json();
            })
            .then(function(payload) {
                if (!payload || !payload.success || !payload.data) {
                    return;
                }

                var data = payload.data;
                setValue('department_name', data.department_name);
                setValue('semester_id', data.semester_id);
                setValue('course_name', data.course_name);
                setValue('nptel_status', data.nptel_status || 'Pass');
                setValue('nptel_exam_score', data.nptel_exam_score);
                setValue('nptel_assignment_raw', data.nptel_assignment_raw);
                setValue('ise1_marks', data.ise1_marks);
                setValue('ise2_marks', data.ise2_marks);
                setValue('ese_written_marks', data.ese_written_marks);

                var remarks = document.querySelector('textarea[name="remarks"]');
                if (remarks) {
                    remarks.value = data.remarks || '';
                }

                toggleFieldsByStatus();
                updateFailCalculations();
            })
            .catch(function() {
                setValue('department_name', '');
            });
    }

    function renderStudentSuggestions(matches) {
        suggestionBox.innerHTML = '';
        currentMatches = matches;
        activeSuggestionIndex = -1;

        if (matches.length === 0) {
            suggestionBox.innerHTML = '<div class="student-suggestion-empty">No matching student found.</div>';
            suggestionBox.style.display = 'block';
            return;
        }

        matches.forEach(function(student, index) {
            var item = document.createElement('div');
            item.className = 'student-suggestion-item';
            item.setAttribute('data-index', index);
            item.innerHTML =
                '<div class="student-suggestion-name"></div>' +
                '<div class="student-suggestion-meta"></div>';
            item.querySelector('.student-suggestion-name').textContent = student.fname || '-';
            item.querySelector('.student-suggestion-meta').textContent = 'Registration: ' + (student.registration_no || '-');
            item.addEventListener('mousedown', function(event) {
                event.preventDefault();
                setStudent(student);
            });
            suggestionBox.appendChild(item);
        });

        suggestionBox.style.display = 'block';
    }

    function updateActiveSuggestion() {
        var items = suggestionBox.querySelectorAll('.student-suggestion-item');
        for (var i = 0; i < items.length; i++) {
            items[i].classList.toggle('active', i === activeSuggestionIndex);
        }
    }

    function searchStudents(term) {
        var query = normalizeText(term);

        if (query.length < 1) {
            hideStudentSuggestions();
            return;
        }

        var matches = students.filter(function(student) {
            var haystack = normalizeText((student.registration_no || '') + ' ' + (student.fname || ''));
            return haystack.indexOf(query) !== -1;
        }).slice(0, 8);

        renderStudentSuggestions(matches);
    }

    if (studentSearch && studentIdInput && suggestionBox) {
        studentSearch.addEventListener('input', function() {
            studentIdInput.value = '';
            clearStudentAutofill();
            searchStudents(this.value);
        });

        studentSearch.addEventListener('focus', function() {
            if (this.value.trim() !== '' && studentIdInput.value === '') {
                searchStudents(this.value);
            }
        });

        studentSearch.addEventListener('keydown', function(event) {
            if (suggestionBox.style.display !== 'block') {
                return;
            }

            if (event.key === 'ArrowDown') {
                event.preventDefault();
                activeSuggestionIndex = Math.min(activeSuggestionIndex + 1, currentMatches.length - 1);
                updateActiveSuggestion();
            } else if (event.key === 'ArrowUp') {
                event.preventDefault();
                activeSuggestionIndex = Math.max(activeSuggestionIndex - 1, 0);
                updateActiveSuggestion();
            } else if (event.key === 'Enter' && activeSuggestionIndex >= 0 && currentMatches[activeSuggestionIndex]) {
                event.preventDefault();
                setStudent(currentMatches[activeSuggestionIndex]);
            } else if (event.key === 'Escape') {
                hideStudentSuggestions();
            }
        });

        document.addEventListener('click', function(event) {
            if (!event.target.closest('.student-autocomplete-wrap')) {
                hideStudentSuggestions();
            }
        });
    }

    function toFloat(value) {
        var parsed = parseFloat(value);
        return isNaN(parsed) ? 0 : parsed;
    }

    function updateFailCalculations() {
        var assignmentRaw = toFloat(document.getElementById('nptel_assignment_raw').value);
        var ise1 = toFloat(document.getElementById('ise1_marks').value);
        var ise2 = toFloat(document.getElementById('ise2_marks').value);
        var ese = toFloat(document.getElementById('ese_written_marks').value);

        var converted = ((assignmentRaw / 100) * 20);
        var total = ise1 + ise2 + converted + ese;

        document.getElementById('nptel_assignment_converted_view').value = converted.toFixed(2);
        document.getElementById('college_total_view').value = total.toFixed(2);
    }

    function toggleFieldsByStatus() {
        var status = document.getElementById('nptel_status').value;
        var failFields = document.getElementById('failFields');
        var examScore = document.getElementById('nptel_exam_score');

        if (status === 'Fail') {
            failFields.style.display = 'block';
            examScore.required = false;
        } else {
            failFields.style.display = 'none';
            examScore.required = true;
        }
        updateFailCalculations();
    }

    document.getElementById('nptel_status').addEventListener('change', toggleFieldsByStatus);

    var triggers = document.querySelectorAll('.calc-trigger');
    for (var i = 0; i < triggers.length; i++) {
        triggers[i].addEventListener('input', updateFailCalculations);
    }

    toggleFieldsByStatus();
})();
</script>

<?php include "header/footer.php"; ?>
