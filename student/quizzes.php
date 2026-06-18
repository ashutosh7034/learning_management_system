<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../includes/models/quiz_model.php';

require_student();

$quizzes = lms_student_quizzes(current_user_id());

lms_layout_header([
    'title' => 'Quizzes',
    'heading' => 'Quizzes',
    'icon' => 'fa fa-question-circle',
    'breadcrumb' => ['Quizzes' => null],
]);
?>

<div class="box box-primary">
    <div class="box-body table-responsive">
        <table class="table table-hover">
            <thead><tr><th>Quiz</th><th>Course</th><th>Attempts</th><th>Best</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
            <?php if (empty($quizzes)): ?>
                <tr><td colspan="6" class="text-center text-muted">No quizzes available yet.</td></tr>
            <?php else: foreach ($quizzes as $quiz): ?>
                <tr>
                    <td><?php echo e($quiz['title']); ?><br><small><?php echo e($quiz['lesson_title']); ?></small></td>
                    <td><?php echo e($quiz['course_title']); ?></td>
                    <td><?php echo (int) $quiz['attempts']; ?></td>
                    <td><?php echo $quiz['best_score'] !== null ? number_format((float) $quiz['best_score'], 0) . '%' : '-'; ?></td>
                    <td><?php echo !empty($quiz['passed']) ? '<span class="label label-success">Passed</span>' : '<span class="label label-default">Open</span>'; ?></td>
                    <td><a class="btn btn-xs btn-primary" href="<?php echo e(url('student/quiz.php?id=' . (int) $quiz['id'])); ?>">Open</a></td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php lms_layout_footer(); ?>
