<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../includes/models/progress_model.php';

require_student();

$certificates = lms_student_certificates(current_user_id());

lms_layout_header([
    'title' => 'Certificates',
    'heading' => 'Certificates',
    'icon' => 'fa fa-certificate',
    'breadcrumb' => ['Certificates' => null],
]);
?>

<div class="box box-success">
    <div class="box-body table-responsive">
        <table class="table table-hover">
            <thead><tr><th>Certificate No</th><th>Course</th><th>Issued</th><th>Action</th></tr></thead>
            <tbody>
            <?php if (empty($certificates)): ?>
                <tr><td colspan="4" class="text-center text-muted">No certificates issued yet.</td></tr>
            <?php else: foreach ($certificates as $cert): ?>
                <tr>
                    <td><?php echo e($cert['certificate_no']); ?></td>
                    <td><?php echo e($cert['course_title']); ?></td>
                    <td><?php echo e($cert['issued_at']); ?></td>
                    <td><a class="btn btn-xs btn-success" href="<?php echo e(url('student/certificate.php?id=' . (int) $cert['id'])); ?>">View</a></td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php lms_layout_footer(); ?>
