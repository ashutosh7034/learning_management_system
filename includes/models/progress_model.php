<?php
/**
 * Learning progress and certificate helpers.
 */

require_once __DIR__ . '/../db.php';

function lms_enrollment_get($enrollmentId)
{
    return db_one(
        "SELECT e.*, c.title AS course_title, c.status AS course_status, c.teacher_id
           FROM lms_enrollments e
           JOIN lms_courses c ON c.id = e.course_id
          WHERE e.id = ? AND e.deleted_at IS NULL",
        'i',
        [(int) $enrollmentId]
    );
}

function lms_student_active_enrollment($studentId, $courseId)
{
    return db_one(
        "SELECT * FROM lms_enrollments
          WHERE student_id = ? AND course_id = ? AND deleted_at IS NULL AND status <> 'dropped'
          LIMIT 1",
        'ii',
        [(int) $studentId, (int) $courseId]
    );
}

function lms_student_lesson($studentId, $lessonId)
{
    return db_one(
        "SELECT l.*, s.title AS section_title, c.title AS course_title, c.id AS course_id,
                e.id AS enrollment_id, e.status AS enrollment_status
           FROM lms_lessons l
           JOIN lms_course_sections s ON s.id = l.section_id AND s.deleted_at IS NULL
           JOIN lms_courses c ON c.id = l.course_id AND c.deleted_at IS NULL AND c.status = 'published'
           JOIN lms_enrollments e ON e.course_id = c.id AND e.student_id = ? AND e.deleted_at IS NULL AND e.status <> 'dropped'
          WHERE l.id = ? AND l.deleted_at IS NULL
          LIMIT 1",
        'ii',
        [(int) $studentId, (int) $lessonId]
    );
}

function lms_first_lesson_for_enrollment($enrollmentId)
{
    return db_one(
        "SELECT l.id
           FROM lms_enrollments e
           JOIN lms_lessons l ON l.course_id = e.course_id AND l.deleted_at IS NULL
           JOIN lms_course_sections s ON s.id = l.section_id AND s.deleted_at IS NULL
          WHERE e.id = ?
          ORDER BY s.sort_order, s.id, l.sort_order, l.id
          LIMIT 1",
        'i',
        [(int) $enrollmentId]
    );
}

function lms_next_lesson($courseId, $lessonId)
{
    $lessons = db_query(
        "SELECT l.id
           FROM lms_lessons l
           JOIN lms_course_sections s ON s.id = l.section_id
          WHERE l.course_id = ? AND l.deleted_at IS NULL AND s.deleted_at IS NULL
          ORDER BY s.sort_order, s.id, l.sort_order, l.id",
        'i',
        [(int) $courseId]
    );
    $seen = false;
    foreach ($lessons as $lesson) {
        if ($seen) {
            return (int) $lesson['id'];
        }
        if ((int) $lesson['id'] === (int) $lessonId) {
            $seen = true;
        }
    }
    return 0;
}

function lms_mark_video_complete($enrollmentId, $lessonId)
{
    $video = db_one("SELECT * FROM lms_videos WHERE lesson_id = ? AND deleted_at IS NULL ORDER BY id DESC LIMIT 1", 'i', [(int) $lessonId]);
    if (!$video) {
        return false;
    }
    $duration = max(0, (int) $video['duration_seconds']);
    $ok = db_execute(
        "INSERT INTO lms_video_progress (enrollment_id, video_id, watched_seconds, last_position, percent, completed)
         VALUES (?, ?, ?, ?, 100, 1)
         ON DUPLICATE KEY UPDATE watched_seconds = VALUES(watched_seconds), last_position = VALUES(last_position), percent = 100, completed = 1",
        'iiii',
        [(int) $enrollmentId, (int) $video['id'], $duration, $duration]
    );
    if ($ok !== false) {
        lms_recalculate_course_progress($enrollmentId);
    }
    return $ok !== false;
}

function lms_mark_pdf_complete($enrollmentId, $lessonId)
{
    $pdf = db_one("SELECT * FROM lms_pdfs WHERE lesson_id = ? AND deleted_at IS NULL ORDER BY id DESC LIMIT 1", 'i', [(int) $lessonId]);
    if (!$pdf) {
        return false;
    }
    $pages = max(1, (int) $pdf['page_count']);
    $ok = db_execute(
        "INSERT INTO lms_pdf_progress (enrollment_id, pdf_id, pages_read, last_page, percent, completed)
         VALUES (?, ?, ?, ?, 100, 1)
         ON DUPLICATE KEY UPDATE pages_read = VALUES(pages_read), last_page = VALUES(last_page), percent = 100, completed = 1",
        'iiii',
        [(int) $enrollmentId, (int) $pdf['id'], $pages, $pages]
    );
    if ($ok !== false) {
        lms_recalculate_course_progress($enrollmentId);
    }
    return $ok !== false;
}

function lms_lesson_progress_status($enrollmentId, $lessonId, $type)
{
    if ($type === 'video') {
        return db_one(
            "SELECT vp.percent, vp.completed
               FROM lms_videos v
               LEFT JOIN lms_video_progress vp ON vp.video_id = v.id AND vp.enrollment_id = ?
              WHERE v.lesson_id = ? AND v.deleted_at IS NULL
              ORDER BY v.id DESC LIMIT 1",
            'ii',
            [(int) $enrollmentId, (int) $lessonId]
        );
    }
    if ($type === 'pdf') {
        return db_one(
            "SELECT pp.percent, pp.completed
               FROM lms_pdfs p
               LEFT JOIN lms_pdf_progress pp ON pp.pdf_id = p.id AND pp.enrollment_id = ?
              WHERE p.lesson_id = ? AND p.deleted_at IS NULL
              ORDER BY p.id DESC LIMIT 1",
            'ii',
            [(int) $enrollmentId, (int) $lessonId]
        );
    }
    if ($type === 'quiz') {
        return db_one(
            "SELECT MAX(qa.score_percent) AS percent, MAX(qa.passed) AS completed
               FROM lms_quizzes q
               LEFT JOIN lms_quiz_attempts qa ON qa.quiz_id = q.id AND qa.enrollment_id = ?
              WHERE q.lesson_id = ? AND q.deleted_at IS NULL",
            'ii',
            [(int) $enrollmentId, (int) $lessonId]
        );
    }
    return null;
}

function lms_component_average($enrollmentId, $type)
{
    $enrollment = lms_enrollment_get($enrollmentId);
    if (!$enrollment) {
        return ['count' => 0, 'pct' => 0.0];
    }
    $courseId = (int) $enrollment['course_id'];

    if ($type === 'video') {
        $row = db_one(
            "SELECT COUNT(v.id) AS total, COALESCE(AVG(COALESCE(vp.percent, 0)), 0) AS pct
               FROM lms_videos v
               JOIN lms_lessons l ON l.id = v.lesson_id AND l.deleted_at IS NULL
               LEFT JOIN lms_video_progress vp ON vp.video_id = v.id AND vp.enrollment_id = ?
              WHERE l.course_id = ? AND v.deleted_at IS NULL",
            'ii',
            [(int) $enrollmentId, $courseId]
        );
    } elseif ($type === 'pdf') {
        $row = db_one(
            "SELECT COUNT(p.id) AS total, COALESCE(AVG(COALESCE(pp.percent, 0)), 0) AS pct
               FROM lms_pdfs p
               JOIN lms_lessons l ON l.id = p.lesson_id AND l.deleted_at IS NULL
               LEFT JOIN lms_pdf_progress pp ON pp.pdf_id = p.id AND pp.enrollment_id = ?
              WHERE l.course_id = ? AND p.deleted_at IS NULL",
            'ii',
            [(int) $enrollmentId, $courseId]
        );
    } else {
        $row = db_one(
            "SELECT COUNT(q.id) AS total, COALESCE(AVG(COALESCE(best.best_score, 0)), 0) AS pct
               FROM lms_quizzes q
               JOIN lms_lessons l ON l.id = q.lesson_id AND l.deleted_at IS NULL
               LEFT JOIN (
                    SELECT quiz_id, MAX(score_percent) AS best_score
                      FROM lms_quiz_attempts
                     WHERE enrollment_id = ?
                     GROUP BY quiz_id
               ) best ON best.quiz_id = q.id
              WHERE l.course_id = ? AND q.deleted_at IS NULL",
            'ii',
            [(int) $enrollmentId, $courseId]
        );
    }

    return ['count' => (int) ($row['total'] ?? 0), 'pct' => (float) ($row['pct'] ?? 0)];
}

function lms_recalculate_course_progress($enrollmentId)
{
    $video = lms_component_average($enrollmentId, 'video');
    $pdf = lms_component_average($enrollmentId, 'pdf');
    $quiz = lms_component_average($enrollmentId, 'quiz');

    $weighted = 0.0;
    $weightTotal = 0.0;
    if ($video['count'] > 0) {
        $weighted += $video['pct'] * LMS_W_VIDEO;
        $weightTotal += LMS_W_VIDEO;
    }
    if ($pdf['count'] > 0) {
        $weighted += $pdf['pct'] * LMS_W_PDF;
        $weightTotal += LMS_W_PDF;
    }
    if ($quiz['count'] > 0) {
        $weighted += $quiz['pct'] * LMS_W_QUIZ;
        $weightTotal += LMS_W_QUIZ;
    }

    $overall = $weightTotal > 0 ? round($weighted / $weightTotal, 2) : 0.0;
    $ok = db_execute(
        "INSERT INTO lms_course_progress (enrollment_id, video_pct, pdf_pct, quiz_pct, assignment_pct, overall_pct)
         VALUES (?, ?, ?, ?, 0, ?)
         ON DUPLICATE KEY UPDATE video_pct = VALUES(video_pct), pdf_pct = VALUES(pdf_pct), quiz_pct = VALUES(quiz_pct), overall_pct = VALUES(overall_pct)",
        'idddd',
        [(int) $enrollmentId, $video['pct'], $pdf['pct'], $quiz['pct'], $overall]
    );

    if ($overall >= lms_certificate_threshold()) {
        db_execute("UPDATE lms_enrollments SET status = 'completed', completed_at = COALESCE(completed_at, NOW()) WHERE id = ?", 'i', [(int) $enrollmentId]);
        lms_certificate_issue($enrollmentId);
    }

    return $ok !== false;
}

function lms_certificate_threshold()
{
    $row = db_one("SELECT setting_value FROM lms_settings WHERE setting_key = 'certificate_threshold' LIMIT 1");
    $value = (float) ($row['setting_value'] ?? LMS_CERT_THRESHOLD);
    return $value > 0 ? $value : LMS_CERT_THRESHOLD;
}

function lms_certificate_issue($enrollmentId)
{
    $existing = db_one("SELECT * FROM lms_certificates WHERE enrollment_id = ? LIMIT 1", 'i', [(int) $enrollmentId]);
    if ($existing) {
        return $existing['id'];
    }
    $certNo = 'LMS-' . date('Y') . '-' . str_pad((string) (int) $enrollmentId, 6, '0', STR_PAD_LEFT);
    $token = bin2hex(random_bytes(16));
    return db_execute(
        "INSERT INTO lms_certificates (enrollment_id, certificate_no, qr_token) VALUES (?, ?, ?)",
        'iss',
        [(int) $enrollmentId, $certNo, $token]
    );
}

function lms_student_certificates($studentId)
{
    return db_query(
        "SELECT cert.*, e.student_id, c.title AS course_title, u.user_name AS student_name, cp.overall_pct
           FROM lms_certificates cert
           JOIN lms_enrollments e ON e.id = cert.enrollment_id
           JOIN lms_courses c ON c.id = e.course_id
           LEFT JOIN lms_user_master u ON u.user_id = e.student_id
           LEFT JOIN lms_course_progress cp ON cp.enrollment_id = e.id
          WHERE e.student_id = ?
          ORDER BY cert.issued_at DESC",
        'i',
        [(int) $studentId]
    );
}

function lms_certificate_get_for_student($certId, $studentId)
{
    return db_one(
        "SELECT cert.*, e.student_id, c.title AS course_title, u.user_name AS student_name, cp.overall_pct
           FROM lms_certificates cert
           JOIN lms_enrollments e ON e.id = cert.enrollment_id
           JOIN lms_courses c ON c.id = e.course_id
           LEFT JOIN lms_user_master u ON u.user_id = e.student_id
           LEFT JOIN lms_course_progress cp ON cp.enrollment_id = e.id
          WHERE cert.id = ? AND e.student_id = ?
          LIMIT 1",
        'ii',
        [(int) $certId, (int) $studentId]
    );
}

function lms_student_progress_rows($studentId)
{
    return db_query(
        "SELECT e.id AS enrollment_id, e.status, c.id AS course_id, c.title,
                COALESCE(cp.video_pct, 0) AS video_pct,
                COALESCE(cp.pdf_pct, 0) AS pdf_pct,
                COALESCE(cp.quiz_pct, 0) AS quiz_pct,
                COALESCE(cp.overall_pct, 0) AS overall_pct,
                cert.id AS certificate_id
           FROM lms_enrollments e
           JOIN lms_courses c ON c.id = e.course_id
           LEFT JOIN lms_course_progress cp ON cp.enrollment_id = e.id
           LEFT JOIN lms_certificates cert ON cert.enrollment_id = e.id
          WHERE e.student_id = ? AND e.deleted_at IS NULL
          ORDER BY e.updated_at DESC",
        'i',
        [(int) $studentId]
    );
}
?>
