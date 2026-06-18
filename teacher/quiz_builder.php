<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../includes/models/content_model.php';
require_once __DIR__ . '/../includes/models/quiz_model.php';

require_teacher();

$lessonId = (int) ($_GET['lesson'] ?? 0);
$lesson = lms_lesson_full($lessonId);
if (!$lesson || $lesson['type'] !== 'quiz') {
    flash('danger', 'Quiz lesson not found.');
    redirect('teacher/courses.php');
}
if (!is_admin_role(current_role_id()) && (int) $lesson['teacher_id'] !== current_user_id()) {
    http_response_code(403);
    flash('danger', 'You can only manage quizzes for your own courses.');
    redirect('teacher/courses.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_require();
    $action = $_POST['action'] ?? '';

    if ($action === 'save_quiz') {
        $title = trim($_POST['title'] ?? '');
        if ($title === '') {
            $errors[] = 'Quiz title is required.';
        } else {
            lms_quiz_save($lessonId, [
                'title' => $title,
                'description' => trim($_POST['description'] ?? ''),
                'time_limit_min' => (int) ($_POST['time_limit_min'] ?? 0),
                'max_attempts' => (int) ($_POST['max_attempts'] ?? 0),
                'pass_percent' => (float) ($_POST['pass_percent'] ?? 60),
            ]);
            flash('success', 'Quiz settings saved.');
            redirect('teacher/quiz_builder.php?lesson=' . $lessonId);
        }
    } elseif ($action === 'add_question') {
        $quiz = lms_quiz_for_lesson($lessonId);
        if (!$quiz) {
            $errors[] = 'Save quiz settings before adding questions.';
        } else {
            $text = trim($_POST['text'] ?? '');
            $options = array_map('trim', $_POST['options'] ?? []);
            $options = array_values(array_filter($options, function ($value) { return $value !== ''; }));
            $correct = (int) ($_POST['correct'] ?? 0);
            if ($text === '' || count($options) < 2) {
                $errors[] = 'Add a question and at least two options.';
            } elseif (!isset($options[$correct])) {
                $errors[] = 'Choose the correct option.';
            } else {
                lms_quiz_add_question((int) $quiz['id'], $text, $options, $correct);
                flash('success', 'Question added.');
                redirect('teacher/quiz_builder.php?lesson=' . $lessonId);
            }
        }
    } elseif ($action === 'delete_question') {
        lms_quiz_delete_question((int) ($_POST['question_id'] ?? 0));
        flash('success', 'Question deleted.');
        redirect('teacher/quiz_builder.php?lesson=' . $lessonId);
    }
}

$quiz = lms_quiz_for_lesson($lessonId);
$questions = $quiz ? lms_quiz_questions((int) $quiz['id']) : [];

lms_layout_header([
    'title' => 'Quiz Builder',
    'heading' => 'Quiz Builder',
    'icon' => 'fa fa-question-circle',
    'breadcrumb' => [
        'Course Management' => url('teacher/courses.php'),
        $lesson['course_title'] => url('teacher/course_builder.php?id=' . (int) $lesson['course_id']),
        'Quiz Builder' => null,
    ],
]);
?>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger"><ul style="margin:0;padding-left:18px;"><?php foreach ($errors as $err): ?><li><?php echo e($err); ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<div class="row">
    <div class="col-md-5">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title">Quiz Settings</h3></div>
            <form method="post">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="action" value="save_quiz">
                <div class="box-body">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" required value="<?php echo e($quiz['title'] ?? $lesson['title']); ?>">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3"><?php echo e($quiz['description'] ?? ''); ?></textarea>
                    </div>
                    <div class="row">
                        <div class="col-sm-4"><label>Time min</label><input type="number" min="0" name="time_limit_min" class="form-control" value="<?php echo (int) ($quiz['time_limit_min'] ?? 0); ?>"></div>
                        <div class="col-sm-4"><label>Attempts</label><input type="number" min="0" name="max_attempts" class="form-control" value="<?php echo (int) ($quiz['max_attempts'] ?? 0); ?>"></div>
                        <div class="col-sm-4"><label>Pass %</label><input type="number" min="0" max="100" step="0.01" name="pass_percent" class="form-control" value="<?php echo e($quiz['pass_percent'] ?? '60.00'); ?>"></div>
                    </div>
                </div>
                <div class="box-footer"><button class="btn btn-primary"><i class="fa fa-save"></i> Save Quiz</button></div>
            </form>
        </div>

        <div class="box box-success">
            <div class="box-header with-border"><h3 class="box-title">Add Question</h3></div>
            <form method="post">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="action" value="add_question">
                <div class="box-body">
                    <div class="form-group">
                        <label>Question</label>
                        <textarea name="text" class="form-control" rows="3" required></textarea>
                    </div>
                    <?php for ($i = 0; $i < 4; $i++): ?>
                        <div class="input-group" style="margin-bottom:8px;">
                            <span class="input-group-addon"><input type="radio" name="correct" value="<?php echo $i; ?>" <?php echo $i === 0 ? 'checked' : ''; ?>></span>
                            <input type="text" name="options[]" class="form-control" placeholder="Option <?php echo $i + 1; ?>" <?php echo $i < 2 ? 'required' : ''; ?>>
                        </div>
                    <?php endfor; ?>
                </div>
                <div class="box-footer"><button class="btn btn-success" <?php echo !$quiz ? 'disabled' : ''; ?>><i class="fa fa-plus"></i> Add Question</button></div>
            </form>
        </div>
    </div>

    <div class="col-md-7">
        <div class="box box-solid">
            <div class="box-header with-border"><h3 class="box-title">Questions</h3></div>
            <div class="box-body">
                <?php if (empty($questions)): ?>
                    <p class="text-muted">No questions yet.</p>
                <?php else: foreach ($questions as $i => $question): ?>
                    <div style="margin-bottom:18px;">
                        <form method="post" class="pull-right" onsubmit="return confirm('Delete this question?');">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="action" value="delete_question">
                            <input type="hidden" name="question_id" value="<?php echo (int) $question['id']; ?>">
                            <button class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>
                        </form>
                        <strong><?php echo ($i + 1) . '. ' . e($question['text']); ?></strong>
                        <ol type="A">
                            <?php foreach ($question['options'] as $option): ?>
                                <li><?php echo e($option['text']); ?> <?php echo (int) $option['is_correct'] === 1 ? '<span class="label label-success">Correct</span>' : ''; ?></li>
                            <?php endforeach; ?>
                        </ol>
                    </div>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </div>
</div>

<?php lms_layout_footer(); ?>
