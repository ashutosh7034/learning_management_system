<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../includes/models/quiz_model.php';

require_student();

$quizId = (int) ($_GET['id'] ?? 0);
$quiz = lms_quiz_get($quizId);
if (!$quiz) {
    flash('danger', 'Quiz not found.');
    redirect('student/quizzes.php');
}
$enrollment = lms_student_active_enrollment(current_user_id(), (int) $quiz['course_id']);
if (!$enrollment) {
    flash('danger', 'Enroll in the course before attempting this quiz.');
    redirect('student/catalog.php');
}

$attempts = lms_quiz_attempt_count((int) $enrollment['id'], $quizId);
if ((int) $quiz['max_attempts'] > 0 && $attempts >= (int) $quiz['max_attempts']) {
    flash('warning', 'Maximum attempts reached for this quiz.');
    redirect('student/quizzes.php');
}

$questions = lms_quiz_questions($quizId);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_require();
    $result = lms_quiz_submit((int) $enrollment['id'], $quizId, $_POST['answer'] ?? []);
    if ($result) {
        flash($result['passed'] ? 'success' : 'warning', 'Quiz submitted. Score: ' . number_format($result['score'], 0) . '%.');
    } else {
        flash('danger', 'Could not submit quiz.');
    }
    redirect('student/quizzes.php');
}

lms_layout_header([
    'title' => $quiz['title'],
    'heading' => 'Quiz',
    'icon' => 'fa fa-question-circle',
    'breadcrumb' => ['Quizzes' => url('student/quizzes.php'), $quiz['title'] => null],
]);
?>

<form method="post">
    <?php echo csrf_field(); ?>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo e($quiz['title']); ?></h3>
        </div>
        <div class="box-body">
            <?php if (empty($questions)): ?>
                <p class="text-muted">This quiz has no questions yet.</p>
            <?php else: foreach ($questions as $i => $question): ?>
                <div class="form-group">
                    <label><?php echo ($i + 1) . '. ' . e($question['text']); ?></label>
                    <?php foreach ($question['options'] as $option): ?>
                        <div class="radio">
                            <label>
                                <input type="radio" name="answer[<?php echo (int) $question['id']; ?>]" value="<?php echo (int) $option['id']; ?>" required>
                                <?php echo e($option['text']); ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
                <hr>
            <?php endforeach; endif; ?>
        </div>
        <div class="box-footer">
            <button class="btn btn-primary" <?php echo empty($questions) ? 'disabled' : ''; ?>><i class="fa fa-send"></i> Submit Quiz</button>
        </div>
    </div>
</form>

<?php lms_layout_footer(); ?>
