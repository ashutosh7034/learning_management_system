<?php
/**
 * Student enrolled courses and resume entry point.
 */

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../includes/models/enrollment_model.php';
require_once __DIR__ . '/../includes/models/progress_model.php';

require_student();

$courses = lms_student_courses(current_user_id());

lms_layout_header([
    'title'      => 'My Courses',
    'heading'    => 'My Courses',
    'icon'       => 'fa fa-bookmark',
    'breadcrumb' => ['My Courses' => null],
]);
?>

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-graduation-cap"></i> Enrolled Courses</h3>
        <div class="box-tools pull-right">
            <a href="<?php echo e(url('student/catalog.php')); ?>" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Browse Catalog</a>
        </div>
    </div>
    <div class="box-body table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Course</th>
                    <th>Teacher</th>
                    <th>Level</th>
                    <th class="text-center">Lessons</th>
                    <th class="text-center">Progress</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($courses)): ?>
                <tr><td colspan="7" class="text-center text-muted">You are not enrolled in any courses yet.</td></tr>
            <?php else: foreach ($courses as $course): $pct = max(0, min(100, (float) $course['overall_pct'])); $first = lms_first_lesson_for_enrollment((int) $course['id']); ?>
                <tr>
                    <td>
                        <strong><?php echo e($course['title']); ?></strong>
                        <?php if (!empty($course['summary'])): ?><br><small class="text-muted"><?php echo e($course['summary']); ?></small><?php endif; ?>
                    </td>
                    <td><?php echo e($course['teacher_name'] ?: 'Teacher'); ?></td>
                    <td><?php echo e($course['level']); ?></td>
                    <td class="text-center"><?php echo (int) $course['lessons']; ?></td>
                    <td style="min-width:160px;">
                        <div class="progress progress-xs" style="margin-bottom:4px;">
                            <div class="progress-bar progress-bar-success" style="width: <?php echo $pct; ?>%"></div>
                        </div>
                        <small><?php echo number_format($pct, 0); ?>%</small>
                    </td>
                    <td class="text-center"><span class="label label-<?php echo $course['status'] === 'completed' ? 'success' : 'primary'; ?>"><?php echo e(ucfirst($course['status'])); ?></span></td>
                    <td class="text-center">
                        <a href="<?php echo e($first ? url('student/lesson.php?id=' . (int) $first['id']) : url('student/course.php?id=' . (int) $course['course_id'])); ?>" class="btn btn-sm btn-success"><i class="fa fa-play"></i> Resume</a>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php lms_layout_footer(); ?>
