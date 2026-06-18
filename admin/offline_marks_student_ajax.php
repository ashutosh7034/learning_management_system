<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

header('Content-Type: application/json');

if (!isset($_SESSION['user_session'])) {
    echo json_encode(array('success' => false, 'message' => 'Session expired. Please login again.'));
    exit;
}

include_once("../database/db_connect.php");
$db_handle = new DBController();

if (!$db_handle || !($db_handle->conn instanceof mysqli)) {
    echo json_encode(array('success' => false, 'message' => 'Unable to connect with database.'));
    exit;
}

$studentId = intval($_GET['student_id'] ?? 0);
if ($studentId <= 0) {
    echo json_encode(array('success' => false, 'message' => 'Invalid student selected.'));
    exit;
}

$details = array(
    'student_id' => $studentId,
    'registration_no' => '',
    'student_name' => '',
    'department_id' => '',
    'department_name' => '',
    'class_id' => '',
    'class_name' => '',
    'specialization_id' => '',
    'specialization_subject_id' => '',
    'current_course_name' => '',
    'semester_id' => '',
    'semester_name' => '',
    'course_name' => '',
    'nptel_status' => 'Pass',
    'nptel_exam_score' => '',
    'nptel_assignment_raw' => '',
    'ise1_marks' => '',
    'ise2_marks' => '',
    'ese_written_marks' => '',
    'remarks' => ''
);

function setSemesterName($conn, &$details)
{
    $semesterId = intval($details['semester_id'] ?? 0);
    if ($semesterId <= 0) {
        return;
    }

    $semesterSql = "SELECT semester_name FROM lms_semester_master WHERE semester_id = ? LIMIT 1";
    $semesterStmt = mysqli_prepare($conn, $semesterSql);
    if ($semesterStmt) {
        mysqli_stmt_bind_param($semesterStmt, 'i', $semesterId);
        mysqli_stmt_execute($semesterStmt);
        $semesterResult = mysqli_stmt_get_result($semesterStmt);
        if ($semesterResult && ($semesterRow = mysqli_fetch_assoc($semesterResult))) {
            $details['semester_name'] = (string)($semesterRow['semester_name'] ?? '');
        }
        mysqli_stmt_close($semesterStmt);
    }
}

function deriveSemesterFromClass($className)
{
    $className = strtoupper(trim((string)$className));

    if (in_array($className, array('FY', 'FE'), true)) {
        return 1;
    }
    if (in_array($className, array('SY', 'SE'), true)) {
        return 3;
    }
    if (in_array($className, array('TY', 'TE'), true)) {
        return 5;
    }
    if ($className === 'BE') {
        return 7;
    }

    return 0;
}

$studentSql = "SELECT sm.student_id, sm.registration_no, sm.fname, sm.department_id, sm.class_id,
                      sm.specialization_id, sm.specialization_subject_id,
                      dm.department_name, cm.class_name, ssm.subject_name AS current_course_name
               FROM lms_student_master sm
               LEFT JOIN lms_department_master dm ON dm.department_id = sm.department_id
               LEFT JOIN lms_class_master cm ON cm.class_id = sm.class_id
               LEFT JOIN lms_specialization_subject_master ssm ON ssm.subject_id = sm.specialization_subject_id
               WHERE sm.student_id = ?
               LIMIT 1";
$studentStmt = mysqli_prepare($db_handle->conn, $studentSql);
if ($studentStmt) {
    mysqli_stmt_bind_param($studentStmt, 'i', $studentId);
    mysqli_stmt_execute($studentStmt);
    $studentResult = mysqli_stmt_get_result($studentStmt);
    if ($studentResult && ($studentRow = mysqli_fetch_assoc($studentResult))) {
        $details['registration_no'] = (string)($studentRow['registration_no'] ?? '');
        $details['student_name'] = (string)($studentRow['fname'] ?? '');
        $details['department_id'] = (string)($studentRow['department_id'] ?? '');
        $details['department_name'] = (string)($studentRow['department_name'] ?? '');
        $details['class_id'] = (string)($studentRow['class_id'] ?? '');
        $details['class_name'] = (string)($studentRow['class_name'] ?? '');
        $details['specialization_id'] = (string)($studentRow['specialization_id'] ?? '');
        $details['specialization_subject_id'] = (string)($studentRow['specialization_subject_id'] ?? '');
        $details['current_course_name'] = (string)($studentRow['current_course_name'] ?? '');
    }
    mysqli_stmt_close($studentStmt);
}

$enrollmentSql = "SELECT e.semester_id, sem.semester_name
                  FROM lms_enrollment e
                  LEFT JOIN lms_semester_master sem ON sem.semester_id = e.semester_id
                  WHERE e.student_id = ?
                  ORDER BY e.enrolled_at DESC, e.enrollment_id DESC
                  LIMIT 1";
$enrollmentStmt = mysqli_prepare($db_handle->conn, $enrollmentSql);
if ($enrollmentStmt) {
    mysqli_stmt_bind_param($enrollmentStmt, 'i', $studentId);
    mysqli_stmt_execute($enrollmentStmt);
    $enrollmentResult = mysqli_stmt_get_result($enrollmentStmt);
    if ($enrollmentResult && ($enrollmentRow = mysqli_fetch_assoc($enrollmentResult))) {
        $details['semester_id'] = (string)($enrollmentRow['semester_id'] ?? '');
        $details['semester_name'] = (string)($enrollmentRow['semester_name'] ?? '');
    }
    mysqli_stmt_close($enrollmentStmt);
}

if ($details['semester_id'] === '') {
    $ledgerSql = "SELECT semester_id
                  FROM lms_credit_ledger
                  WHERE student_id = ?
                  ORDER BY recorded_at DESC, credit_id DESC
                  LIMIT 1";
    $ledgerStmt = mysqli_prepare($db_handle->conn, $ledgerSql);
    if ($ledgerStmt) {
        mysqli_stmt_bind_param($ledgerStmt, 'i', $studentId);
        mysqli_stmt_execute($ledgerStmt);
        $ledgerResult = mysqli_stmt_get_result($ledgerStmt);
        if ($ledgerResult && ($ledgerRow = mysqli_fetch_assoc($ledgerResult))) {
            $details['semester_id'] = (string)($ledgerRow['semester_id'] ?? '');
        }
        mysqli_stmt_close($ledgerStmt);
    }
}

$offlineSql = "SELECT semester_id, course_name, nptel_status, nptel_exam_score, nptel_assignment_raw,
                      ise1_marks, ise2_marks, ese_written_marks, remarks
               FROM lms_offline_marks_entry
               WHERE student_id = ?
               ORDER BY updated_at DESC, entry_id DESC
               LIMIT 1";
$offlineStmt = mysqli_prepare($db_handle->conn, $offlineSql);
if ($offlineStmt) {
    mysqli_stmt_bind_param($offlineStmt, 'i', $studentId);
    mysqli_stmt_execute($offlineStmt);
    $offlineResult = mysqli_stmt_get_result($offlineStmt);
    if ($offlineResult && ($offlineRow = mysqli_fetch_assoc($offlineResult))) {
        foreach ($offlineRow as $key => $value) {
            if (array_key_exists($key, $details) && $value !== null) {
                $details[$key] = (string)$value;
            }
        }
    }
    mysqli_stmt_close($offlineStmt);
}

if ($details['course_name'] === '' || $details['semester_id'] === '') {
    $nptelSql = "SELECT semester_id, course_name, pass_fail, score
                 FROM lms_nptel_records
                 WHERE student_id = ?
                 ORDER BY recorded_at DESC, nptel_id DESC
                 LIMIT 1";
    $nptelStmt = mysqli_prepare($db_handle->conn, $nptelSql);
    if ($nptelStmt) {
        mysqli_stmt_bind_param($nptelStmt, 'i', $studentId);
        mysqli_stmt_execute($nptelStmt);
        $nptelResult = mysqli_stmt_get_result($nptelStmt);
        if ($nptelResult && ($nptelRow = mysqli_fetch_assoc($nptelResult))) {
            if ($details['semester_id'] === '') {
                $details['semester_id'] = (string)($nptelRow['semester_id'] ?? '');
            }
            if ($details['course_name'] === '') {
                $details['course_name'] = (string)($nptelRow['course_name'] ?? '');
            }
            $passFail = (string)($nptelRow['pass_fail'] ?? '');
            if ($passFail === 'Pass' || $passFail === 'Fail') {
                $details['nptel_status'] = $passFail;
            }
            if ($nptelRow['score'] !== null) {
                $details['nptel_exam_score'] = (string)$nptelRow['score'];
            }
        }
        mysqli_stmt_close($nptelStmt);
    }
}

if ($details['semester_id'] === '') {
    $derivedSemesterId = deriveSemesterFromClass($details['class_name']);
    if ($derivedSemesterId > 0) {
        $details['semester_id'] = (string)$derivedSemesterId;
    }
}

setSemesterName($db_handle->conn, $details);

if ($details['course_name'] === '' && $details['current_course_name'] !== '') {
    $details['course_name'] = $details['current_course_name'];
}

if ($details['course_name'] === '' && $details['semester_id'] !== '') {
    $subjectSql = "SELECT subject_name
                   FROM lms_minorsubject
                   WHERE semester_id = ?
                   ORDER BY subject_id ASC
                   LIMIT 1";
    $subjectStmt = mysqli_prepare($db_handle->conn, $subjectSql);
    if ($subjectStmt) {
        $semesterId = intval($details['semester_id']);
        mysqli_stmt_bind_param($subjectStmt, 'i', $semesterId);
        mysqli_stmt_execute($subjectStmt);
        $subjectResult = mysqli_stmt_get_result($subjectStmt);
        if ($subjectResult && ($subjectRow = mysqli_fetch_assoc($subjectResult))) {
            $details['course_name'] = (string)($subjectRow['subject_name'] ?? '');
        }
        mysqli_stmt_close($subjectStmt);
    }
}

echo json_encode(array('success' => true, 'data' => $details));
exit;
