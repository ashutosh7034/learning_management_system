<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../includes/models/enrollment_model.php';
require_once __DIR__ . '/../includes/models/progress_model.php';

require_student();

$courses = lms_student_courses(current_user_id());

lms_layout_header([
    'title' => 'My Learning',
    'heading' => 'My Learning',
    'icon' => 'fa fa-graduation-cap',
    'breadcrumb' => ['My Learning' => null],
]);
?>

<div class="row">
<?php if (empty($courses)): ?>
    <div class="col-xs-12"><div class="callout callout-info">Enroll in a course to start learning.</div></div>
<?php else: foreach ($courses as $course): ?>
    <?php
        $first = lms_first_lesson_for_enrollment((int) $course['id']);
        $href = $first ? url('student/lesson.php?id=' . (int) $first['id']) : url('student/course.php?id=' . (int) $course['course_id']);
        $pct = max(0, min(100, (float) $course['overall_pct']));
    ?>
    <div class="col-md-4 col-sm-6">
        <div class="box box-solid">
            <div class="box-body">
                <h4 style="margin-top:0;"><?php echo e($course['title']); ?></h4>
                <p class="text-muted"><?php echo e($course['summary'] ?: 'Continue your course curriculum.'); ?></p>
                <div class="progress progress-sm">
                    <div class="progress-bar progress-bar-success" style="width: <?php echo $pct; ?>%"></div>
                </div>
                <small><?php echo number_format($pct, 0); ?>% complete</small>
            </div>
            <div class="box-footer">
                <a class="btn btn-success btn-block" href="<?php echo e($href); ?>"><i class="fa fa-play"></i> Resume Learning</a>
            </div>
        </div>
    </div>
<?php endforeach; endif; ?>
</div>

<?php lms_layout_footer(); ?>
