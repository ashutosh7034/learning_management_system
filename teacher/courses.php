<?php
/**
 * Course Management — list view (Teacher / Admin).
 * Teachers see their own courses; admins see all.
 */

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../includes/models/course_model.php';

require_teacher();

$roleId    = current_role_id();
$isAdmin   = is_admin_role($roleId);
$teacherId = current_user_id();

/* ---------------- Handle POST actions (status change / delete) ---------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_require();
    $action   = $_POST['action'] ?? '';
    $courseId = (int) ($_POST['course_id'] ?? 0);

    // Authorization: non-admins may only act on their own courses.
    if (!$isAdmin && !lms_course_owned_by($courseId, $teacherId)) {
        http_response_code(403);
        flash('danger', 'You can only manage your own courses.');
        redirect('teacher/courses.php');
    }

    if ($action === 'set_status') {
        $status = $_POST['status'] ?? '';
        if (lms_course_set_status($courseId, $status)) {
            flash('success', 'Course status updated to ' . ucfirst($status) . '.');
        } else {
            flash('danger', 'Could not update course status.');
        }
    } elseif ($action === 'delete') {
        if (lms_course_soft_delete($courseId)) {
            flash('success', 'Course deleted.');
        } else {
            flash('danger', 'Could not delete course.');
        }
    }
    redirect('teacher/courses.php');
}

$courses = lms_courses_list($isAdmin ? null : $teacherId);

lms_layout_header([
    'title'      => 'Course Management',
    'heading'    => 'Course Management',
    'icon'       => 'fa fa-folder-open',
    'breadcrumb' => ['Course Management' => null],
]);
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-book"></i> <?php echo $isAdmin ? 'All Courses' : 'My Courses'; ?></h3>
                <div class="box-tools pull-right">
                    <a href="<?php echo e(url('teacher/course_edit.php')); ?>" class="btn btn-success btn-sm">
                        <i class="fa fa-plus"></i> New Course
                    </a>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table id="coursesTable" class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Thumbnail</th>
                            <th>Title</th>
                            <th>Category</th>
                            <?php if ($isAdmin): ?><th>Teacher</th><?php endif; ?>
                            <th>Level</th>
                            <th class="text-center">Lessons</th>
                            <th class="text-center">Enrolled</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($courses)): $i = 1; foreach ($courses as $c): ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td>
                                    <?php if (!empty($c['thumbnail'])): ?>
                                        <img class="lms-thumb" src="<?php echo e(LMS_UPLOAD_URL . '/' . $c['thumbnail']); ?>" alt="">
                                    <?php else: ?>
                                        <span class="lms-thumb" style="display:inline-block;"></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?php echo e($c['title']); ?></strong>
                                    <?php if (!empty($c['summary'])): ?><br><small class="text-muted"><?php echo e($c['summary']); ?></small><?php endif; ?>
                                </td>
                                <td><?php echo e($c['category_name'] ?? '—'); ?></td>
                                <?php if ($isAdmin): ?><td><?php echo e($c['teacher_name'] ?? '—'); ?></td><?php endif; ?>
                                <td><?php echo e($c['level']); ?></td>
                                <td class="text-center"><?php echo (int) $c['lessons']; ?></td>
                                <td class="text-center"><?php echo (int) $c['enrolled']; ?></td>
                                <td class="text-center"><?php echo lms_status_badge($c['status']); ?></td>
                                <td class="text-center" style="white-space:nowrap;">
                                    <a href="<?php echo e(url('teacher/course_builder.php?id=' . (int) $c['id'])); ?>" class="btn btn-xs btn-info" title="Curriculum"><i class="fa fa-list"></i></a>
                                    <a href="<?php echo e(url('teacher/course_edit.php?id=' . (int) $c['id'])); ?>" class="btn btn-xs btn-primary" title="Edit"><i class="fa fa-pencil"></i></a>
                                    <?php
                                        // Publish/Archive toggle.
                                        $next = $c['status'] === 'published' ? 'archived' : 'published';
                                        $label = $next === 'published' ? 'Publish' : 'Archive';
                                        $btn = $next === 'published' ? 'btn-success' : 'btn-warning';
                                        $ico = $next === 'published' ? 'fa-check' : 'fa-archive';
                                    ?>
                                    <form method="post" style="display:inline;">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="action" value="set_status">
                                        <input type="hidden" name="course_id" value="<?php echo (int) $c['id']; ?>">
                                        <input type="hidden" name="status" value="<?php echo e($next); ?>">
                                        <button class="btn btn-xs <?php echo $btn; ?>" title="<?php echo e($label); ?>"><i class="fa <?php echo $ico; ?>"></i></button>
                                    </form>
                                    <form method="post" style="display:inline;" onsubmit="return confirm('Delete this course? Enrollments and content will be removed.');">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="course_id" value="<?php echo (int) $c['id']; ?>">
                                        <button class="btn btn-xs btn-danger" title="Delete"><i class="fa fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
lms_layout_footer([
    'scripts' => '<script>$(function(){ $("#coursesTable").DataTable({ "order": [], "autoWidth": false, "language": { "emptyTable": "No courses yet. Click New Course to create one." } }); });</script>',
]);
?>
