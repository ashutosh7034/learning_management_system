<?php include "header/header.php"; ?>
<?php

$student_id = intval($_REQUEST['id'] ?? 0);
$student = null;
$uploadedFiles = [];

if ($student_id > 0) {
    $sql = "SELECT s.student_id, s.academic_year_id, s.registration_no, s.roll_no, s.grad_year,
                   s.cgpa, s.fname, s.mobile, s.email, s.status, s.created_at, s.mark_list,
                   s.class_id, s.division_id, s.department_id, s.specialization_id, 
                   s.specialization_subject_id, s.minor_course_id, s.minor_subject_id,
                   s.current_semester_id,
                   c.class_name, sec.sections AS division_name, d.department_name,
                   sp.specialization_name, sub.subject_name,
                   ay.session_name, sem.semester_name
            FROM lms_student_master s
            LEFT JOIN lms_class_master c ON c.class_id = s.class_id
            LEFT JOIN lms_section_master sec ON sec.id = s.division_id
            LEFT JOIN lms_department_master d ON d.department_id = s.department_id
            LEFT JOIN lms_specialization_master sp ON sp.specialization_id = s.specialization_id
            LEFT JOIN lms_specialization_subject_master sub ON sub.subject_id = s.specialization_subject_id
            LEFT JOIN lms_session_master ay ON ay.session_id = s.academic_year_id
            LEFT JOIN lms_semester_master sem ON sem.semester_id = s.current_semester_id
            WHERE s.student_id = ?
            LIMIT 1";
    
    $stmt = mysqli_prepare($db_handle->conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $student_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $student = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        
        // Parse uploaded files
        if ($student && !empty($student['mark_list'])) {
            $uploadedFiles = explode(',', $student['mark_list']);
        }
    }
}

if (!$student) {
    echo '<div class="alert alert-danger">Student not found!</div>';
    exit;
}

$statusText = 'Pending';
$statusClass = 'label-warning';
if ((int)$student['status'] === 1) {
    $statusText = 'Approved';
    $statusClass = 'label-success';
}

function formatValue($value) {
    return !empty($value) ? htmlspecialchars($value) : 'N/A';
}
?>

<style>
    .detail-section {
        background: white;
        border-left: 4px solid #2563eb;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .detail-section h3 {
        color: #2563eb;
        margin-top: 0;
        margin-bottom: 15px;
        font-weight: 600;
        font-size: 16px;
    }

    .detail-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 15px;
    }

    .detail-item {
        background: #f8f9fa;
        padding: 12px;
        border-radius: 6px;
    }

    .detail-label {
        font-size: 12px;
        color: #666;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .detail-value {
        font-size: 14px;
        color: #2f3b45;
        font-weight: 500;
    }

    .btn-group-custom {
        margin-bottom: 20px;
    }

    .btn-group-custom .btn {
        margin-right: 10px;
    }

    .upload-box {
        background: white;
        border: 2px dashed #cbd5e1;
        border-radius: 8px;
        padding: 15px;
        margin: 10px 0;
        text-align: center;
    }

    .upload-box a {
        display: inline-block;
        color: #2563eb;
        text-decoration: none;
        margin: 5px;
        padding: 8px 12px;
        background: #eff6ff;
        border-radius: 4px;
        transition: all 0.2s;
    }

    .upload-box a:hover {
        background: #2563eb;
        color: white;
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-file-alt"></i> Student Details</h1>
        <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="student_admission.php">Admission</a></li>
            <li class="active">Student #<?php echo $student_id; ?></li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="btn-group-custom">
                    <a href="student_admission.php?edit=1&id=<?php echo $student_id; ?>" class="btn btn-primary"><i class="fa fa-edit"></i> Edit Details</a>
                    <span class="label <?php echo $statusClass; ?>" style="padding: 8px 12px; font-size: 12px;">
                        <?php echo $statusText; ?>
                    </span>
                </div>

                <!-- Official Details -->
                <div class="detail-section">
                    <h3><i class="fa fa-building"></i> Official Details</h3>
                    <div class="detail-row">
                        <div class="detail-item">
                            <div class="detail-label">Academic Year</div>
                            <div class="detail-value"><?php echo formatValue($student['session_name']); ?></div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">ERP ID (Registration No.)</div>
                            <div class="detail-value"><?php echo formatValue($student['registration_no']); ?></div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Roll No.</div>
                            <div class="detail-value"><?php echo formatValue($student['roll_no']); ?></div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Class</div>
                            <div class="detail-value"><?php echo formatValue($student['class_name']); ?></div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Semester</div>
                            <div class="detail-value"><?php echo formatValue($student['semester_name']); ?></div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Division</div>
                            <div class="detail-value"><?php echo formatValue($student['division_name']); ?></div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Graduating Year</div>
                            <div class="detail-value"><?php echo formatValue($student['grad_year']); ?></div>
                        </div>
                    </div>
                </div>

                <!-- Academic Details -->
                <div class="detail-section">
                    <h3><i class="fa fa-graduation-cap"></i> Academic Details</h3>
                    <div class="detail-row">
                        <div class="detail-item">
                            <div class="detail-label">Department</div>
                            <div class="detail-value"><?php echo formatValue($student['department_name']); ?></div>
                        </div>
                    </div>
                </div>

                <!-- Personal Details -->
                <div class="detail-section">
                    <h3><i class="fa fa-user"></i> Personal Details</h3>
                    <div class="detail-row">
                        <div class="detail-item">
                            <div class="detail-label">Full Name</div>
                            <div class="detail-value"><?php echo formatValue($student['fname']); ?></div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Email</div>
                            <div class="detail-value"><?php echo formatValue($student['email']); ?></div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Mobile</div>
                            <div class="detail-value"><?php echo formatValue($student['mobile']); ?></div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Submitted On</div>
                            <div class="detail-value"><?php echo formatValue(date('d M Y H:i', strtotime($student['created_at']))); ?></div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </section>
</div>

<?php include "header/footer.php"; ?>
