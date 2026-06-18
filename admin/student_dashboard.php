<?php
require "header/header.php";

$student = null;
$linkedStudentId = 0;

if ((int) ($usertype ?? 0) !== 5) {
    header("location: index.php");
    exit;
}

if (!empty($userid)) {
    $userCheckSql = "SELECT student_id FROM lms_user_master WHERE user_id = ? AND student_id > 0 LIMIT 1";
    $userCheckStmt = mysqli_prepare($db_handle->conn, $userCheckSql);

    if ($userCheckStmt) {
        mysqli_stmt_bind_param($userCheckStmt, 'i', $userid);
        mysqli_stmt_execute($userCheckStmt);
        $userCheckResult = mysqli_stmt_get_result($userCheckStmt);
        if ($userCheckResult && ($userRow = mysqli_fetch_assoc($userCheckResult))) {
            $linkedStudentId = intval($userRow['student_id']);
        }
        mysqli_stmt_close($userCheckStmt);
    }
}

if ($linkedStudentId > 0) {
    $studentSql = "SELECT s.student_id, s.academic_year_id, s.registration_no, s.roll_no, s.grad_year,
                          s.cgpa, s.fname, s.mobile, s.email, s.status, s.created_at,
                          c.class_name, sec.sections AS division_name, d.department_name,
                          sp.specialization_name, sub.subject_name, ay.session_name
                   FROM lms_student_master s
                   LEFT JOIN lms_class_master c ON c.class_id = s.class_id
                   LEFT JOIN lms_section_master sec ON sec.id = s.division_id
                   LEFT JOIN lms_department_master d ON d.department_id = s.department_id
                   LEFT JOIN lms_specialization_master sp ON sp.specialization_id = s.specialization_id
                   LEFT JOIN lms_specialization_subject_master sub ON sub.subject_id = s.specialization_subject_id
                   LEFT JOIN lms_session_master ay ON ay.session_id = s.academic_year_id
                   WHERE s.student_id = ?
                   LIMIT 1";

    $studentStmt = mysqli_prepare($db_handle->conn, $studentSql);
    if ($studentStmt) {
        mysqli_stmt_bind_param($studentStmt, 'i', $linkedStudentId);
        mysqli_stmt_execute($studentStmt);
        $studentResult = mysqli_stmt_get_result($studentStmt);
        if ($studentResult) {
            $student = mysqli_fetch_assoc($studentResult);
        }
        mysqli_stmt_close($studentStmt);
    }
}
$statusText = 'Pending';
$statusClass = 'label-warning';
if ($student && (int) $student['status'] === 1) {
    $statusText = 'Approved';
    $statusClass = 'label-success';
}

function student_dashboard_value($value)
{
    $value = trim((string) ($value ?? ''));
    return $value !== '' ? htmlspecialchars($value) : 'N/A';
}
?>

<style>
    .student-hero {
        background: #ffffff;
        border-top: 3px solid #3c8dbc;
        padding: 22px;
        margin-bottom: 20px;
    }

    .student-hero h2 {
        margin: 0 0 6px;
        font-weight: 600;
        color: #2f3b45;
    }

    .student-info-list {
        margin: 0;
    }

    .student-info-list dt {
        color: #6b7785;
        font-weight: 600;
    }

    .student-info-list dd {
        margin-bottom: 14px;
        color: #2f3b45;
    }

    .student-stat {
        min-height: 108px;
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-dashboard"></i> Student Dashboard</h1>
        <ol class="breadcrumb">
            <li><a href="student_dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Student Dashboard</li>
        </ol>
    </section>

    <section class="content">
        <?php if (!$student): ?>
            <div class="alert alert-warning" style="padding: 15px;">
                <i class="fa fa-info-circle"></i> 
                <strong>No profile found.</strong> Please complete your admission form to see your dashboard.
                <br>
                <a href="student_admission.php" class="btn btn-primary btn-sm" style="margin-top: 10px;">
                    <i class="fa fa-arrow-right"></i> Go to Admission Form
                </a>
            </div>
        <?php else: ?>
            <div class="box student-hero">
                <h2>Welcome, <?php echo student_dashboard_value($student['fname']); ?></h2>
                <p class="text-muted">
                    Registration No: <strong><?php echo student_dashboard_value($student['registration_no']); ?></strong>
                    &nbsp; | &nbsp;
                    Status: <span class="label <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                </p>
            </div>

            <div class="row">
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-aqua student-stat">
                        <div class="inner">
                            <h3><?php echo student_dashboard_value($student['cgpa']); ?></h3>
                            <p>CGPA</p>
                        </div>
                        <div class="icon"><i class="fa fa-line-chart"></i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-green student-stat">
                        <div class="inner">
                            <h3><?php echo student_dashboard_value($student['class_name']); ?></h3>
                            <p>Class</p>
                        </div>
                        <div class="icon"><i class="fa fa-graduation-cap"></i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-yellow student-stat">
                        <div class="inner">
                            <h3><?php echo student_dashboard_value($student['division_name']); ?></h3>
                            <p>Division</p>
                        </div>
                        <div class="icon"><i class="fa fa-users"></i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-red student-stat">
                        <div class="inner">
                            <h3><?php echo student_dashboard_value($student['roll_no']); ?></h3>
                            <p>Roll No</p>
                        </div>
                        <div class="icon"><i class="fa fa-id-card"></i></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-7">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-user"></i> My Profile</h3>
                        </div>
                        <div class="box-body">
                            <dl class="row student-info-list">
                                <dt class="col-sm-4">Name</dt>
                                <dd class="col-sm-8"><?php echo student_dashboard_value($student['fname']); ?></dd>
                                <dt class="col-sm-4">Email</dt>
                                <dd class="col-sm-8"><?php echo student_dashboard_value($student['email']); ?></dd>
                                <dt class="col-sm-4">Mobile</dt>
                                <dd class="col-sm-8"><?php echo student_dashboard_value($student['mobile']); ?></dd>
                                <dt class="col-sm-4">Academic Year</dt>
                                <dd class="col-sm-8"><?php echo student_dashboard_value($student['session_name'] ?? $student['academic_year_id']); ?></dd>
                                <dt class="col-sm-4">Graduation Year</dt>
                                <dd class="col-sm-8"><?php echo student_dashboard_value($student['grad_year']); ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-book"></i> Academic Details</h3>
                        </div>
                        <div class="box-body">
                            <dl class="row student-info-list">
                                <dt class="col-sm-5">Department</dt>
                                <dd class="col-sm-7"><?php echo student_dashboard_value($student['department_name']); ?></dd>
                                <dt class="col-sm-5">Specialization</dt>
                                <dd class="col-sm-7"><?php echo student_dashboard_value($student['specialization_name']); ?></dd>
                                <dt class="col-sm-5">Subject</dt>
                                <dd class="col-sm-7"><?php echo student_dashboard_value($student['subject_name']); ?></dd>
                                <dt class="col-sm-5">Applied On</dt>
                                <dd class="col-sm-7"><?php echo student_dashboard_value($student['created_at']); ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </section>
</div>

<?php include "header/footer.php"; ?>
