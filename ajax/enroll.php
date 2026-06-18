<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/models/enrollment_model.php';

header('Content-Type: application/json');
require_student();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'POST required']);
    exit;
}

csrf_require();

$courseId = (int) ($_POST['course_id'] ?? 0);
$ok = lms_student_enroll(current_user_id(), $courseId);

echo json_encode([
    'ok' => $ok,
    'course_id' => $courseId,
    'redirect' => $ok ? url('student/course.php?id=' . $courseId) : null,
    'error' => $ok ? null : 'Could not enroll in course',
]);
?>
