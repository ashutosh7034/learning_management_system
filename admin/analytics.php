<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../includes/models/report_model.php';

require_admin();

$m = lms_admin_metrics();
$recent = lms_recent_enrollments(10);

lms_layout_header([
    'title' => 'Analytics',
    'heading' => 'Analytics',
    'icon' => 'fa fa-bar-chart',
    'breadcrumb' => ['Analytics' => null],
]);
?>

<div class="row">
    <?php foreach ([
        ['bg-aqua', 'Courses', $m['courses'], 'fa-book'],
        ['bg-green', 'Published', $m['published'], 'fa-check'],
        ['bg-yellow', 'Enrollments', $m['enrollments'], 'fa-users'],
        ['bg-red', 'Certificates', $m['certificates'], 'fa-certificate'],
    ] as $card): ?>
        <div class="col-md-3 col-sm-6">
            <div class="small-box <?php echo $card[0]; ?>">
                <div class="inner"><h3><?php echo (int) $card[2]; ?></h3><p><?php echo e($card[1]); ?></p></div>
                <div class="icon"><i class="fa <?php echo $card[3]; ?>"></i></div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="box box-primary">
    <div class="box-header with-border"><h3 class="box-title">Recent Enrollments</h3></div>
    <div class="box-body table-responsive">
        <table class="table table-hover">
            <thead><tr><th>Student</th><th>Course</th><th>Status</th><th>Progress</th><th>Enrolled</th></tr></thead>
            <tbody>
            <?php if (empty($recent)): ?>
                <tr><td colspan="5" class="text-center text-muted">No enrollments yet.</td></tr>
            <?php else: foreach ($recent as $row): ?>
                <tr>
                    <td><?php echo e($row['student_name'] ?: 'Student #' . (int) $row['student_id']); ?></td>
                    <td><?php echo e($row['course_title']); ?></td>
                    <td><?php echo e(ucfirst($row['status'])); ?></td>
                    <td><?php echo number_format((float) ($row['overall_pct'] ?? 0), 0); ?>%</td>
                    <td><?php echo e($row['enrolled_at']); ?></td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php lms_layout_footer(); ?>
