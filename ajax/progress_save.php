<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/models/progress_model.php';

header('Content-Type: application/json');
require_student();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'POST required']);
    exit;
}

csrf_require();

$lessonId = (int) ($_POST['lesson_id'] ?? 0);
$lesson = lms_student_lesson(current_user_id(), $lessonId);
if (!$lesson) {
    http_response_code(404);
    echo json_encode(['ok' => false, 'error' => 'Lesson not found']);
    exit;
}

$ok = false;
if ($lesson['type'] === 'video') {
    $ok = lms_mark_video_complete((int) $lesson['enrollment_id'], $lessonId);
} elseif ($lesson['type'] === 'pdf') {
    $ok = lms_mark_pdf_complete((int) $lesson['enrollment_id'], $lessonId);
}

echo json_encode([
    'ok' => $ok,
    'lesson_id' => $lessonId,
    'type' => $lesson['type'],
    'error' => $ok ? null : 'Progress could not be saved for this lesson',
]);
?>
