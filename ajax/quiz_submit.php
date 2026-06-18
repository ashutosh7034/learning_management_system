<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/models/quiz_model.php';

header('Content-Type: application/json');
require_student();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'POST required']);
    exit;
}

csrf_require();

$quizId = (int) ($_POST['quiz_id'] ?? 0);
$quiz = lms_quiz_get($quizId);
if (!$quiz) {
    http_response_code(404);
    echo json_encode(['ok' => false, 'error' => 'Quiz not found']);
    exit;
}

$enrollment = lms_student_active_enrollment(current_user_id(), (int) $quiz['course_id']);
if (!$enrollment) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'Not enrolled']);
    exit;
}

$attempts = lms_quiz_attempt_count((int) $enrollment['id'], $quizId);
if ((int) $quiz['max_attempts'] > 0 && $attempts >= (int) $quiz['max_attempts']) {
    http_response_code(409);
    echo json_encode(['ok' => false, 'error' => 'Maximum attempts reached']);
    exit;
}

$answers = $_POST['answer'] ?? [];
if (is_string($answers)) {
    $decoded = json_decode($answers, true);
    $answers = is_array($decoded) ? $decoded : [];
}

$result = lms_quiz_submit((int) $enrollment['id'], $quizId, $answers);
echo json_encode([
    'ok' => $result !== false,
    'score' => $result ? $result['score'] : 0,
    'passed' => $result ? $result['passed'] : false,
    'error' => $result ? null : 'Quiz could not be submitted',
]);
?>
