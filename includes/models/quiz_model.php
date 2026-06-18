<?php
/**
 * Quiz authoring, runner and grading helpers.
 */

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/progress_model.php';

function lms_quiz_for_lesson($lessonId)
{
    return db_one("SELECT * FROM lms_quizzes WHERE lesson_id = ? AND deleted_at IS NULL ORDER BY id DESC LIMIT 1", 'i', [(int) $lessonId]);
}

function lms_quiz_save($lessonId, array $d)
{
    $existing = lms_quiz_for_lesson($lessonId);
    if ($existing) {
        return db_execute(
            "UPDATE lms_quizzes SET title = ?, description = ?, time_limit_min = ?, max_attempts = ?, pass_percent = ? WHERE id = ?",
            'ssiidi',
            [$d['title'], $d['description'], (int) $d['time_limit_min'], (int) $d['max_attempts'], (float) $d['pass_percent'], (int) $existing['id']]
        );
    }
    return db_execute(
        "INSERT INTO lms_quizzes (lesson_id, title, description, time_limit_min, max_attempts, pass_percent)
         VALUES (?, ?, ?, ?, ?, ?)",
        'issiid',
        [(int) $lessonId, $d['title'], $d['description'], (int) $d['time_limit_min'], (int) $d['max_attempts'], (float) $d['pass_percent']]
    );
}

function lms_quiz_questions($quizId)
{
    $questions = db_query("SELECT * FROM lms_questions WHERE quiz_id = ? AND deleted_at IS NULL ORDER BY sort_order, id", 'i', [(int) $quizId]);
    foreach ($questions as $i => $question) {
        $questions[$i]['options'] = db_query("SELECT * FROM lms_question_options WHERE question_id = ? ORDER BY sort_order, id", 'i', [(int) $question['id']]);
    }
    return $questions;
}

function lms_quiz_add_question($quizId, $text, array $options, $correctIndex)
{
    $next = db_one("SELECT COALESCE(MAX(sort_order),0)+1 AS n FROM lms_questions WHERE quiz_id = ?", 'i', [(int) $quizId]);
    $questionId = db_execute(
        "INSERT INTO lms_questions (quiz_id, text, marks, sort_order) VALUES (?, ?, 1, ?)",
        'isi',
        [(int) $quizId, $text, (int) ($next['n'] ?? 1)]
    );
    if (!$questionId) {
        return false;
    }
    foreach (array_values($options) as $i => $optionText) {
        db_execute(
            "INSERT INTO lms_question_options (question_id, text, is_correct, sort_order) VALUES (?, ?, ?, ?)",
            'isii',
            [(int) $questionId, $optionText, ((int) $correctIndex === $i) ? 1 : 0, $i + 1]
        );
    }
    return $questionId;
}

function lms_quiz_delete_question($questionId)
{
    return db_execute("UPDATE lms_questions SET deleted_at = NOW() WHERE id = ?", 'i', [(int) $questionId]);
}

function lms_quiz_get($quizId)
{
    return db_one(
        "SELECT q.*, l.course_id, l.title AS lesson_title, c.title AS course_title
           FROM lms_quizzes q
           JOIN lms_lessons l ON l.id = q.lesson_id
           JOIN lms_courses c ON c.id = l.course_id
          WHERE q.id = ? AND q.deleted_at IS NULL",
        'i',
        [(int) $quizId]
    );
}

function lms_quiz_attempt_count($enrollmentId, $quizId)
{
    $row = db_one("SELECT COUNT(*) AS total FROM lms_quiz_attempts WHERE enrollment_id = ? AND quiz_id = ?", 'ii', [(int) $enrollmentId, (int) $quizId]);
    return (int) ($row['total'] ?? 0);
}

function lms_quiz_submit($enrollmentId, $quizId, array $answers)
{
    $quiz = lms_quiz_get($quizId);
    if (!$quiz) {
        return false;
    }
    $questions = lms_quiz_questions($quizId);
    $total = count($questions);
    $correct = 0;
    $cleanAnswers = [];

    foreach ($questions as $question) {
        $qid = (int) $question['id'];
        $selected = (int) ($answers[$qid] ?? 0);
        $cleanAnswers[$qid] = $selected;
        foreach ($question['options'] as $option) {
            if ((int) $option['id'] === $selected && (int) $option['is_correct'] === 1) {
                $correct++;
                break;
            }
        }
    }

    $score = $total > 0 ? round(($correct / $total) * 100, 2) : 0.0;
    $passed = $score >= (float) $quiz['pass_percent'] ? 1 : 0;
    $ok = db_execute(
        "INSERT INTO lms_quiz_attempts (enrollment_id, quiz_id, score_percent, passed, answers_json, started_at, submitted_at)
         VALUES (?, ?, ?, ?, ?, NOW(), NOW())",
        'iidis',
        [(int) $enrollmentId, (int) $quizId, $score, $passed, json_encode($cleanAnswers)]
    );
    if ($ok !== false) {
        lms_recalculate_course_progress($enrollmentId);
    }
    return ['score' => $score, 'passed' => $passed === 1, 'attempt_id' => $ok];
}

function lms_student_quizzes($studentId)
{
    return db_query(
        "SELECT q.*, l.title AS lesson_title, c.title AS course_title, c.id AS course_id,
                e.id AS enrollment_id,
                COUNT(qa.id) AS attempts,
                MAX(qa.score_percent) AS best_score,
                MAX(qa.passed) AS passed
           FROM lms_enrollments e
           JOIN lms_courses c ON c.id = e.course_id
           JOIN lms_lessons l ON l.course_id = c.id AND l.type = 'quiz' AND l.deleted_at IS NULL
           JOIN lms_quizzes q ON q.lesson_id = l.id AND q.deleted_at IS NULL
           LEFT JOIN lms_quiz_attempts qa ON qa.quiz_id = q.id AND qa.enrollment_id = e.id
          WHERE e.student_id = ? AND e.deleted_at IS NULL AND e.status <> 'dropped'
          GROUP BY q.id, l.id, c.id, e.id
          ORDER BY c.title, l.sort_order, l.id",
        'i',
        [(int) $studentId]
    );
}
?>
