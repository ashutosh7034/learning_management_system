<?php
/**
 * CLI smoke test: render the new LMS pages with a simulated logged-in session
 * and report any PHP errors. Not shipped — a dev harness.
 *
 *   php tests/smoke.php
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');

// Pick real teacher/student users + login ids from the DB.
require __DIR__ . '/../database/db_connect.php';
$db = new DBController();
$teacherRow = mysqli_fetch_assoc(mysqli_query($db->conn,
    "SELECT l.login_id, l.user_id FROM lms_login l JOIN lms_user_master u ON u.user_id=l.user_id WHERE u.role_id=3 LIMIT 1"));
if (!$teacherRow) { fwrite(STDERR, "No role-3 user found to test with.\n"); exit(1); }
$studentRow = mysqli_fetch_assoc(mysqli_query($db->conn,
    "SELECT l.login_id, l.user_id FROM lms_login l JOIN lms_user_master u ON u.user_id=l.user_id WHERE u.role_id=5 LIMIT 1"));
if (!$studentRow) { fwrite(STDERR, "No role-5 user found to test with.\n"); exit(1); }
$adminRow = mysqli_fetch_assoc(mysqli_query($db->conn,
    "SELECT l.login_id, l.user_id FROM lms_login l JOIN lms_user_master u ON u.user_id=l.user_id WHERE u.role_id=2 LIMIT 1"));
if (!$adminRow) { fwrite(STDERR, "No role-2 user found to test with.\n"); exit(1); }
$teacherId = (int) $teacherRow['user_id'];
$studentId = (int) $studentRow['user_id'];

// Create throwaway courses so detail pages have something to render.
mysqli_query($db->conn, "DELETE FROM lms_enrollments WHERE course_id IN (SELECT id FROM lms_courses WHERE slug IN ('smoke-test-course-harness','smoke-test-published-course-harness'))");
mysqli_query($db->conn, "DELETE FROM lms_courses WHERE slug IN ('smoke-test-course-harness','smoke-test-published-course-harness')");
mysqli_query($db->conn, "INSERT INTO lms_courses (teacher_id,title,slug,status) VALUES ($teacherId,'SMOKE TEST COURSE','smoke-test-course-harness','draft')");
$smokeCourseId = (int) mysqli_insert_id($db->conn);
mysqli_query($db->conn, "INSERT INTO lms_courses (teacher_id,title,slug,summary,status,published_at) VALUES ($teacherId,'SMOKE TEST PUBLISHED COURSE','smoke-test-published-course-harness','Student smoke catalog course','published',NOW())");
$smokePublishedCourseId = (int) mysqli_insert_id($db->conn);
mysqli_query($db->conn, "INSERT INTO lms_enrollments (student_id,course_id,status) VALUES ($studentId,$smokePublishedCourseId,'active')");
$smokeEnrollmentId = (int) mysqli_insert_id($db->conn);
mysqli_query($db->conn, "INSERT INTO lms_course_sections (course_id,title,sort_order) VALUES ($smokePublishedCourseId,'Smoke Section',1)");
$smokeSectionId = (int) mysqli_insert_id($db->conn);
mysqli_query($db->conn, "INSERT INTO lms_lessons (section_id,course_id,title,type,sort_order) VALUES ($smokeSectionId,$smokePublishedCourseId,'Smoke Video','video',1)");
$smokeVideoLessonId = (int) mysqli_insert_id($db->conn);
mysqli_query($db->conn, "INSERT INTO lms_videos (lesson_id,title,external_url,duration_seconds) VALUES ($smokeVideoLessonId,'Smoke Video','https://example.com/video.mp4',60)");
mysqli_query($db->conn, "INSERT INTO lms_lessons (section_id,course_id,title,type,sort_order) VALUES ($smokeSectionId,$smokePublishedCourseId,'Smoke PDF','pdf',2)");
$smokePdfLessonId = (int) mysqli_insert_id($db->conn);
mysqli_query($db->conn, "INSERT INTO lms_pdfs (lesson_id,title,file_path,page_count) VALUES ($smokePdfLessonId,'Smoke PDF','smoke.pdf',2)");
mysqli_query($db->conn, "INSERT INTO lms_lessons (section_id,course_id,title,type,sort_order) VALUES ($smokeSectionId,$smokePublishedCourseId,'Smoke Quiz','quiz',3)");
$smokeQuizLessonId = (int) mysqli_insert_id($db->conn);
mysqli_query($db->conn, "INSERT INTO lms_quizzes (lesson_id,title,pass_percent) VALUES ($smokeQuizLessonId,'Smoke Quiz',60)");
$smokeQuizId = (int) mysqli_insert_id($db->conn);
mysqli_query($db->conn, "INSERT INTO lms_questions (quiz_id,text,marks,sort_order) VALUES ($smokeQuizId,'Smoke question?',1,1)");
$smokeQuestionId = (int) mysqli_insert_id($db->conn);
mysqli_query($db->conn, "INSERT INTO lms_question_options (question_id,text,is_correct,sort_order) VALUES ($smokeQuestionId,'Yes',1,1),($smokeQuestionId,'No',0,2)");
mysqli_query($db->conn, "INSERT INTO lms_course_progress (enrollment_id,overall_pct) VALUES ($smokeEnrollmentId,100)");
mysqli_query($db->conn, "INSERT INTO lms_certificates (enrollment_id,certificate_no,qr_token) VALUES ($smokeEnrollmentId,'LMS-SMOKE-000001','smoketoken')");
$smokeCertificateId = (int) mysqli_insert_id($db->conn);

$pages = [
    // page => [role_id, get_id]
    'teacher/courses.php'        => [3, (int) $teacherRow['login_id'], $teacherId, 0],
    'teacher/dashboard.php'      => [3, (int) $teacherRow['login_id'], $teacherId, 0],
    'teacher/course_edit.php'    => [3, (int) $teacherRow['login_id'], $teacherId, 0],
    'teacher/course_builder.php' => [3, (int) $teacherRow['login_id'], $teacherId, $smokeCourseId],
    'teacher/enrollments.php'    => [3, (int) $teacherRow['login_id'], $teacherId, 0],
    'student/dashboard.php'      => [5, (int) $studentRow['login_id'], $studentId, 0],
    'student/catalog.php'        => [5, (int) $studentRow['login_id'], $studentId, 0],
    'student/course.php'         => [5, (int) $studentRow['login_id'], $studentId, $smokePublishedCourseId],
    'student/my_courses.php'     => [5, (int) $studentRow['login_id'], $studentId, 0],
    'student/learning.php'       => [5, (int) $studentRow['login_id'], $studentId, 0],
    'student/lesson.php'         => [5, (int) $studentRow['login_id'], $studentId, $smokeVideoLessonId],
    'student/progress.php'       => [5, (int) $studentRow['login_id'], $studentId, 0],
    'student/quizzes.php'        => [5, (int) $studentRow['login_id'], $studentId, 0],
    'student/quiz.php'           => [5, (int) $studentRow['login_id'], $studentId, $smokeQuizId],
    'student/certificates.php'   => [5, (int) $studentRow['login_id'], $studentId, 0],
    'student/certificate.php'    => [5, (int) $studentRow['login_id'], $studentId, $smokeCertificateId],
    'teacher/quiz_builder.php'   => [3, (int) $teacherRow['login_id'], $teacherId, $smokeQuizLessonId],
    'admin/user_management.php'  => [2, (int) $adminRow['login_id'], (int) $adminRow['user_id'], 0],
    'admin/reports.php'          => [2, (int) $adminRow['login_id'], (int) $adminRow['user_id'], 0],
    'admin/analytics.php'        => [2, (int) $adminRow['login_id'], (int) $adminRow['user_id'], 0],
];

$failures = 0;
foreach ($pages as $page => $cfg) {
    [$role, $loginId, $userId, $getId] = $cfg;
    // Fresh child process per page for isolation.
    $cmd = sprintf(
        '%s %s %s %d %d %d %d',
        escapeshellarg(PHP_BINARY),
        escapeshellarg(__DIR__ . '/_render_one.php'),
        escapeshellarg($page),
        (int) $role,
        (int) $loginId,
        (int) $userId,
        (int) $getId
    );
    $output = shell_exec($cmd . ' 2>&1');
    $hasError = preg_match('/(Fatal error|Parse error|Uncaught|Warning:|Deprecated:|Notice:)/i', (string) $output);
    $rendered = strpos((string) $output, '</html>') !== false || strpos((string) $output, 'content-wrapper') !== false;

    if ($hasError || !$rendered) {
        $failures++;
        echo "FAIL  $page\n";
        echo "------ output ------\n" . substr((string) $output, 0, 1500) . "\n--------------------\n";
    } else {
        echo "PASS  $page  (" . strlen((string) $output) . " bytes)\n";
    }
}

// Clean up the throwaway course.
mysqli_query($db->conn, "DELETE FROM lms_enrollments WHERE course_id IN ($smokeCourseId,$smokePublishedCourseId)");
mysqli_query($db->conn, "DELETE FROM lms_courses WHERE id IN ($smokeCourseId,$smokePublishedCourseId)");

echo $failures ? "\n$failures page(s) failed.\n" : "\nAll pages rendered cleanly.\n";
exit($failures ? 1 : 0);
