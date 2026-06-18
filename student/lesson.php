<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../includes/models/content_model.php';
require_once __DIR__ . '/../includes/models/progress_model.php';
require_once __DIR__ . '/../includes/models/quiz_model.php';

require_student();

$lessonId = (int) ($_GET['id'] ?? 0);
$lesson = lms_student_lesson(current_user_id(), $lessonId);
if (!$lesson) {
    flash('danger', 'Lesson not found or not available.');
    redirect('student/learning.php');
}

$enrollmentId = (int) $lesson['enrollment_id'];
$nextLessonId = lms_next_lesson((int) $lesson['course_id'], $lessonId);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_require();
    if ($lesson['type'] === 'video') {
        lms_mark_video_complete($enrollmentId, $lessonId);
        flash('success', 'Video marked complete.');
    } elseif ($lesson['type'] === 'pdf') {
        lms_mark_pdf_complete($enrollmentId, $lessonId);
        flash('success', 'PDF marked complete.');
    }
    redirect('student/lesson.php?id=' . $lessonId);
}

$video = $lesson['type'] === 'video' ? lms_video_for_lesson($lessonId) : null;
$pdf = $lesson['type'] === 'pdf' ? lms_pdf_for_lesson($lessonId) : null;
$quiz = $lesson['type'] === 'quiz' ? lms_quiz_for_lesson($lessonId) : null;
$progress = lms_lesson_progress_status($enrollmentId, $lessonId, $lesson['type']);

lms_layout_header([
    'title' => $lesson['title'],
    'heading' => 'Lesson',
    'icon' => 'fa fa-play-circle',
    'breadcrumb' => [
        'My Learning' => url('student/learning.php'),
        $lesson['course_title'] => url('student/course.php?id=' . (int) $lesson['course_id']),
        $lesson['title'] => null,
    ],
]);
?>

<div class="row">
    <div class="col-md-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo e($lesson['title']); ?></h3>
            </div>
            <div class="box-body">
                <?php if ($lesson['type'] === 'video'): ?>
                    <?php $src = !empty($video['file_path']) ? LMS_UPLOAD_URL . '/' . $video['file_path'] : ($video['external_url'] ?? ''); ?>
                    <?php if ($src): ?>
                        <video controls preload="metadata" style="width:100%;background:#000;border-radius:4px;">
                            <source src="<?php echo e($src); ?>">
                            Your browser does not support HTML5 video.
                        </video>
                    <?php else: ?>
                        <p class="text-muted">No video content has been attached yet.</p>
                    <?php endif; ?>
                <?php elseif ($lesson['type'] === 'pdf'): ?>
                    <?php if (!empty($pdf['file_path'])): ?>
                        <iframe src="<?php echo e(LMS_UPLOAD_URL . '/' . $pdf['file_path']); ?>" style="width:100%;height:620px;border:1px solid #ddd;border-radius:4px;"></iframe>
                    <?php else: ?>
                        <p class="text-muted">No PDF content has been attached yet.</p>
                    <?php endif; ?>
                <?php else: ?>
                    <?php if ($quiz): ?>
                        <p>Quiz: <strong><?php echo e($quiz['title']); ?></strong></p>
                        <p class="text-muted"><?php echo e($quiz['description']); ?></p>
                        <a href="<?php echo e(url('student/quiz.php?id=' . (int) $quiz['id'])); ?>" class="btn btn-primary"><i class="fa fa-question-circle"></i> Start Quiz</a>
                    <?php else: ?>
                        <p class="text-muted">No quiz has been authored yet.</p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="box-footer">
                <?php if (in_array($lesson['type'], ['video', 'pdf'], true)): ?>
                    <form method="post" style="display:inline;">
                        <?php echo csrf_field(); ?>
                        <button class="btn btn-success"><i class="fa fa-check"></i> Mark Complete</button>
                    </form>
                <?php endif; ?>
                <?php if ($nextLessonId > 0): ?>
                    <a class="btn btn-default pull-right" href="<?php echo e(url('student/lesson.php?id=' . $nextLessonId)); ?>">Next Lesson <i class="fa fa-arrow-right"></i></a>
                <?php else: ?>
                    <a class="btn btn-default pull-right" href="<?php echo e(url('student/progress.php')); ?>">View Progress</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="box box-solid">
            <div class="box-body">
                <p><strong>Course:</strong> <?php echo e($lesson['course_title']); ?></p>
                <p><strong>Section:</strong> <?php echo e($lesson['section_title']); ?></p>
                <p><strong>Type:</strong> <?php echo e(ucfirst($lesson['type'])); ?></p>
                <p><strong>Status:</strong>
                    <?php echo !empty($progress['completed']) ? '<span class="label label-success">Complete</span>' : '<span class="label label-default">In progress</span>'; ?>
                </p>
            </div>
        </div>
    </div>
</div>

<?php lms_layout_footer(); ?>
