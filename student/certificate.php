<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../includes/models/progress_model.php';

require_student();

$cert = lms_certificate_get_for_student((int) ($_GET['id'] ?? 0), current_user_id());
if (!$cert) {
    flash('danger', 'Certificate not found.');
    redirect('student/certificates.php');
}

lms_layout_header([
    'title' => 'Certificate',
    'heading' => 'Certificate',
    'icon' => 'fa fa-certificate',
    'breadcrumb' => ['Certificates' => url('student/certificates.php'), $cert['certificate_no'] => null],
    'head' => '<style>@media print{.main-header,.main-sidebar,.content-header,.main-footer,.btn{display:none!important}.content-wrapper{margin:0!important}.certificate-sheet{box-shadow:none!important}}</style>',
]);
?>

<div class="box box-solid certificate-sheet" style="border:3px solid #3c8dbc;padding:30px;text-align:center;">
    <h1>Certificate of Completion</h1>
    <p class="lead">This certifies that</p>
    <h2><?php echo e($cert['student_name'] ?: 'Student'); ?></h2>
    <p class="lead">has successfully completed</p>
    <h2><?php echo e($cert['course_title']); ?></h2>
    <p>Certificate No: <strong><?php echo e($cert['certificate_no']); ?></strong></p>
    <p>Issued: <?php echo e($cert['issued_at']); ?></p>
    <p>Verification Token: <code><?php echo e($cert['qr_token']); ?></code></p>
    <button onclick="window.print()" class="btn btn-primary"><i class="fa fa-print"></i> Print</button>
</div>

<?php lms_layout_footer(); ?>
