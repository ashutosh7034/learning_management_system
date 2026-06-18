<?php
/**
 * Teacher/Admin view of course enrollments.
 */

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/layout.php';

require_teacher();

$isAdmin = is_admin_role(current_role_id());
$teacherId = current_user_id();

$sql = "SELECT e.*, c.title AS course_title, c.teacher_id, u.user_name AS student_name, cp.overall_pct
          FROM lms_enrollments e
          JOIN lms_courses c ON c.id = e.course_id
          LEFT JOIN lms_user_master u ON u.user_id = e.student_id
          LEFT JOIN lms_course_progress cp ON cp.enrollment_id = e.id
         WHERE e.deleted_at IS NULL";
$types = '';
$params = [];
if (!$isAdmin) {
    $sql .= " AND c.teacher_id = ?";
    $types = 'i';
    $params[] = $teacherId;
}
$sql .= " ORDER BY e.enrolled_at DESC";
$rows = db_query($sql, $types, $params);

lms_layout_header([
    'title' => 'Enrollments',
    'heading' => 'Enrollments',
    'icon' => 'fa fa-users',
    'breadcrumb' => ['Enrollments' => null],
]);
?>

<div class="box box-primary">
    <div class="box-body table-responsive">
        <table id="enrollmentsTable" class="table table-bordered table-hover">
            <thead><tr><th>Student</th><th>Course</th><th>Status</th><th>Progress</th><th>Enrolled</th><th>Completed</th></tr></thead>
            <tbody>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td><?php echo e($row['student_name'] ?: 'Student #' . (int) $row['student_id']); ?></td>
                    <td><?php echo e($row['course_title']); ?></td>
                    <td><?php echo e(ucfirst($row['status'])); ?></td>
                    <td><?php echo number_format((float) ($row['overall_pct'] ?? 0), 0); ?>%</td>
                    <td><?php echo e($row['enrolled_at']); ?></td>
                    <td><?php echo e($row['completed_at'] ?: '-'); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php lms_layout_footer(['scripts' => '<script>$(function(){ $("#enrollmentsTable").DataTable({ "order": [] }); });</script>']); ?>
