<?php
session_start();
require "../database/db_connect.php";
$db_handle = new DBController();

if (!isset($_REQUEST['id'])) {
    echo "<div class='alert alert-danger'>No student ID provided.</div>";
    exit;
}

$student_id = intval($_REQUEST['id']);

$sql = "SELECT
    sm.student_id,
    sm.registration_no,
    sm.class_id,
    sm.division_id,
    sm.grad_year,
    sm.roll_no,
    sm.department_id,
    sm.specialization_id,
    sm.specialization_subject_id,
    sm.minor_course_id,
    sm.minor_subject_id,
    sm.cgpa,
    sm.fname,
    sm.mobile,
    sm.email,
    sm.mark_list,
    sm.status,
    sm.m_sem1,
    sm.m_sem2,
    sm.m_sem3,
    sm.created_at,
    sm.academic_year_id,
    sm.current_semester_id,
    IFNULL(cl.class_name, '') AS class_name,
    IFNULL(sec.sections, '') AS section_name,
    IFNULL(dep.department_name, '') AS department_name,
    IFNULL(sp.specialization_name, '') AS specialization_name,
    IFNULL(ssb.subject_name, '') AS specialization_subject_name,
    IFNULL(mc.course_name, '') AS minor_course_name,
    IFNULL(ms.subject_name, '') AS minor_subject_name,
    IFNULL(sess.session_name, '') AS academic_year_name,
    IFNULL(sem.semester_name, '') AS semester_name
FROM lms_student_master sm
LEFT JOIN lms_class_master cl ON cl.class_id = sm.class_id
LEFT JOIN lms_section_master sec ON sec.id = sm.division_id
LEFT JOIN lms_department_master dep ON dep.department_id = sm.department_id
LEFT JOIN lms_specialization_master sp ON sp.specialization_id = sm.specialization_id
LEFT JOIN lms_specialization_subject_master ssb ON ssb.subject_id = sm.specialization_subject_id
LEFT JOIN lms_minorcourse mc ON mc.course_id = sm.minor_course_id
LEFT JOIN lms_minorsubject ms ON ms.subject_id = sm.minor_subject_id
LEFT JOIN lms_session_master sess ON sess.session_id = sm.academic_year_id
LEFT JOIN lms_semester_master sem ON sem.semester_id = sm.current_semester_id
WHERE sm.student_id = $student_id";

$result = $db_handle->query($sql);
$row = $result ? $result->fetch_assoc() : null;

if (!$row) {
    echo "<div class='alert alert-danger'>Student record not found.</div>";
    exit;
}

// Determine specialization type for display
$specialization_name = strtolower($row['specialization_name'] ?? '');
$is_minor_multidisciplinary = strpos($specialization_name, 'minor multidisciplinary') !== false;
$is_honours = strpos($specialization_name, 'honour') !== false || strpos($specialization_name, 'honor') !== false;
?>
<style>
    .view-section {
        margin-bottom: 25px;
        border: 1px solid #ddd;
        border-radius: 5px;
        overflow: hidden;
    }
    .view-section-header {
        background-color: #423cbc;
        color: white;
        padding: 10px 15px;
        font-size: 16px;
        font-weight: bold;
    }
    .view-field {
        margin-bottom: 15px;
        padding: 0 15px;
    }
    .view-label {
        font-weight: bold;
        color: #333;
        margin-bottom: 5px;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .view-value {
        color: #666;
        padding: 8px 12px;
        background-color: #f9f9f9;
        border-radius: 4px;
        font-size: 14px;
        word-break: break-word;
    }
    .status-active {
        color: green;
        font-weight: bold;
    }
    .status-inactive {
        color: red;
        font-weight: bold;
    }
    .table-marks {
        width: 100%;
        background-color: #f9f9f9;
        border-collapse: collapse;
    }
    .table-marks td {
        padding: 8px;
        border: 1px solid #ddd;
        vertical-align: top;
    }
    .table-marks td:first-child {
        font-weight: bold;
        width: 30%;
        background-color: #e9ecef;
    }
    .badge-minor {
        background-color: #ff9800;
        color: white;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 11px;
        margin-left: 8px;
    }
    .badge-honours {
        background-color: #9c27b0;
        color: white;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 11px;
        margin-left: 8px;
    }
</style>

<!-- STUDENT BASIC INFORMATION -->
<div class="view-section">
    <div class="view-section-header">
        <i class="fa fa-graduation-cap"></i> STUDENT BASIC INFORMATION
    </div>
    <div class="row" style="padding: 15px;">
        <div class="col-md-6">
            <div class="view-field">
                <div class="view-label">Registration Number:</div>
                <div class="view-value"><strong><?php echo htmlspecialchars($row['registration_no'] ?? 'N/A'); ?></strong></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="view-field">
                <div class="view-label">Student Name:</div>
                <div class="view-value"><?php echo htmlspecialchars($row['fname'] ?? 'N/A'); ?></div>
            </div>
        </div>
    </div>
    <div class="row" style="padding: 15px;">
        <div class="col-md-6">
            <div class="view-field">
                <div class="view-label">Status:</div>
                <div class="view-value <?php echo ($row['status'] == '1') ? 'status-active' : 'status-inactive'; ?>">
                    <?php echo ($row['status'] == '1') ? 'Active' : 'Inactive'; ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="view-field">
                <div class="view-label">Created Date:</div>
                <div class="view-value"><?php echo date('d-m-Y H:i:s', strtotime($row['created_at'] ?? 'now')); ?></div>
            </div>
        </div>
    </div>
</div>

<!-- ACADEMIC DETAILS -->
<div class="view-section">
    <div class="view-section-header">
        <i class="fa fa-book"></i> ACADEMIC DETAILS
    </div>
    <div class="row" style="padding: 15px;">
        <div class="col-md-4">
            <div class="view-field">
                <div class="view-label">Academic Year:</div>
                <div class="view-value"><?php echo htmlspecialchars($row['academic_year_name'] ?? 'N/A'); ?></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="view-field">
                <div class="view-label">Current Semester:</div>
                <div class="view-value"><?php echo htmlspecialchars($row['semester_name'] ?? 'N/A'); ?></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="view-field">
                <div class="view-label">Graduation Year:</div>
                <div class="view-value">
                    <?php 
                    $grad_year = $row['grad_year'] ?? '';
                    if (!empty($grad_year) && $grad_year > 0) {
                        echo htmlspecialchars($grad_year);
                    } else {
                        echo 'Not Specified';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="padding: 15px;">
        <div class="col-md-4">
            <div class="view-field">
                <div class="view-label">Class:</div>
                <div class="view-value"><?php echo htmlspecialchars($row['class_name'] ?? 'N/A'); ?></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="view-field">
                <div class="view-label">Division:</div>
                <div class="view-value"><?php echo htmlspecialchars($row['section_name'] ?? 'N/A'); ?></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="view-field">
                <div class="view-label">Roll Number:</div>
                <div class="view-value"><?php echo htmlspecialchars($row['roll_no'] ?? 'N/A'); ?></div>
            </div>
        </div>
    </div>
    <div class="row" style="padding: 15px;">
        <div class="col-md-4">
            <div class="view-field">
                <div class="view-label">Department:</div>
                <div class="view-value"><?php echo htmlspecialchars($row['department_name'] ?? 'N/A'); ?></div>
            </div>
        </div>
    </div>
</div>

<!-- CONTACT DETAILS -->
<div class="view-section">
    <div class="view-section-header">
        <i class="fa fa-phone"></i> CONTACT DETAILS
    </div>
    <div class="row" style="padding: 15px;">
        <div class="col-md-6">
            <div class="view-field">
                <div class="view-label">Mobile Number:</div>
                <div class="view-value" style="display: flex; gap: 4px">
                    <?php echo htmlspecialchars($row['mobile'] ?? 'N/A'); ?>
                    <?php if (!empty($row['mobile'])): ?>
                        <a href="tel:<?php echo $row['mobile']; ?>" class="btn btn-xs btn-info" style="margin-left: 10px;">
                            <i class="fa fa-phone"></i> Call
                        </a>
                        <a href="https://wa.me/91<?php echo $row['mobile']; ?>" target="_blank" class="btn btn-xs btn-success">
                            <i class="fa fa-whatsapp"></i> WhatsApp
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="view-field">
                <div class="view-label">Email:</div>
                <div class="view-value">
                    <?php echo !empty($row['email']) ? '<a href="mailto:' . htmlspecialchars($row['email']) . '">' . htmlspecialchars($row['email']) . '</a>' : 'N/A'; ?>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    $(document).ready(function() {
        console.log("Student view loaded for ID: <?php echo $student_id; ?>");
    });
</script>