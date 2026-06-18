<?php
/**
 * LMS-native teacher dashboard.
 */

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../includes/models/course_model.php';
require_once __DIR__ . '/../includes/models/report_model.php';

require_teacher();

$isAdmin = is_admin_role(current_role_id());
$teacherId = current_user_id();
$courses = lms_courses_list($isAdmin ? null : $teacherId);
$reportRows = lms_report_course_rows();

if (!$isAdmin) {
    $reportRows = array_values(array_filter($reportRows, function ($row) use ($teacherId) {
        $course = lms_course_get((int) $row['id']);
        return $course && (int) $course['teacher_id'] === (int) $teacherId;
    }));
}

$published = 0;
$enrollments = 0;
foreach ($reportRows as $row) {
    if (($row['status'] ?? '') === 'published') {
        $published++;
    }
    $enrollments += (int) $row['enrollments'];
}

lms_layout_header([
    'title' => 'Teacher Dashboard',
    'heading' => 'Teacher Dashboard',
    'icon' => 'fa fa-dashboard',
    'breadcrumb' => ['Teacher Dashboard' => null],
]);
?>

<div class="row">
    <?php foreach ([
        ['bg-aqua', 'Courses', count($courses), 'fa-folder-open'],
        ['bg-green', 'Published', $published, 'fa-check'],
        ['bg-yellow', 'Enrollments', $enrollments, 'fa-users'],
        ['bg-red', 'Reports', count($reportRows), 'fa-file-text'],
    ] as $card): ?>
        <div class="col-md-3 col-sm-6">
            <div class="small-box <?php echo $card[0]; ?>">
                <div class="inner"><h3><?php echo (int) $card[2]; ?></h3><p><?php echo e($card[1]); ?></p></div>
                <div class="icon"><i class="fa <?php echo e($card[3]); ?>"></i></div>
                <a href="<?php echo e(url($card[1] === 'Reports' ? 'admin/reports.php' : 'teacher/courses.php')); ?>" class="small-box-footer">Open <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">My Courses</h3>
        <div class="box-tools pull-right"><a class="btn btn-success btn-sm" href="<?php echo e(url('teacher/course_edit.php')); ?>"><i class="fa fa-plus"></i> New Course</a></div>
    </div>
    <div class="box-body table-responsive">
        <table class="table table-hover">
            <thead><tr><th>Course</th><th>Status</th><th>Enrollments</th><th>Avg Progress</th><th>Action</th></tr></thead>
            <tbody>
            <?php if (empty($reportRows)): ?>
                <tr><td colspan="5" class="text-center text-muted">No course activity yet.</td></tr>
            <?php else: foreach ($reportRows as $row): ?>
                <tr>
                    <td><?php echo e($row['title']); ?></td>
                    <td><?php echo lms_status_badge($row['status']); ?></td>
                    <td><?php echo (int) $row['enrollments']; ?></td>
                    <td><?php echo number_format((float) $row['avg_progress'], 0); ?>%</td>
                    <td><a class="btn btn-xs btn-primary" href="<?php echo e(url('teacher/course_builder.php?id=' . (int) $row['id'])); ?>">Manage</a></td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php lms_layout_footer(); ?>
