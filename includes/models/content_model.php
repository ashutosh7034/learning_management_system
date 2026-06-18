<?php
/**
 * Lesson content data access  (Module 5 & 6 — Video / PDF content).
 *
 * One video or one PDF per lesson (upsert). Progress tracking lives in a later
 * module; this layer just stores/serves the media attached to a lesson.
 */

require_once __DIR__ . '/../db.php';

/** Lesson joined with its course (for ownership checks + breadcrumbs). */
function lms_lesson_full($lessonId)
{
    return db_one(
        "SELECT l.*, s.title AS section_title, c.id AS course_id, c.title AS course_title, c.teacher_id
           FROM lms_lessons l
           JOIN lms_course_sections s ON s.id = l.section_id
           JOIN lms_courses c ON c.id = l.course_id
          WHERE l.id = ? AND l.deleted_at IS NULL",
        'i',
        [(int) $lessonId]
    );
}

/* -------------------------------------------------- Video */

function lms_video_for_lesson($lessonId)
{
    return db_one(
        "SELECT * FROM lms_videos WHERE lesson_id = ? AND deleted_at IS NULL ORDER BY id DESC LIMIT 1",
        'i',
        [(int) $lessonId]
    );
}

/**
 * Insert or update the single video attached to a lesson.
 * $d: title, file_path (nullable), external_url (nullable), duration_seconds
 */
function lms_video_save($lessonId, array $d)
{
    $existing = lms_video_for_lesson($lessonId);
    if ($existing) {
        return db_execute(
            "UPDATE lms_videos
                SET title = ?, file_path = ?, external_url = ?, duration_seconds = ?
              WHERE id = ?",
            'sssii',
            [
                $d['title'] ?? null,
                $d['file_path'] ?? null,
                $d['external_url'] ?? null,
                (int) ($d['duration_seconds'] ?? 0),
                (int) $existing['id'],
            ]
        );
    }
    return db_execute(
        "INSERT INTO lms_videos (lesson_id, title, file_path, external_url, duration_seconds)
         VALUES (?, ?, ?, ?, ?)",
        'isssi',
        [
            (int) $lessonId,
            $d['title'] ?? null,
            $d['file_path'] ?? null,
            $d['external_url'] ?? null,
            (int) ($d['duration_seconds'] ?? 0),
        ]
    );
}

/* -------------------------------------------------- PDF */

function lms_pdf_for_lesson($lessonId)
{
    return db_one(
        "SELECT * FROM lms_pdfs WHERE lesson_id = ? AND deleted_at IS NULL ORDER BY id DESC LIMIT 1",
        'i',
        [(int) $lessonId]
    );
}

/**
 * Insert or update the single PDF attached to a lesson.
 * $d: title, file_path, page_count
 */
function lms_pdf_save($lessonId, array $d)
{
    $existing = lms_pdf_for_lesson($lessonId);
    if ($existing) {
        return db_execute(
            "UPDATE lms_pdfs SET title = ?, file_path = ?, page_count = ? WHERE id = ?",
            'ssii',
            [$d['title'] ?? null, $d['file_path'] ?? '', (int) ($d['page_count'] ?? 0), (int) $existing['id']]
        );
    }
    return db_execute(
        "INSERT INTO lms_pdfs (lesson_id, title, file_path, page_count) VALUES (?, ?, ?, ?)",
        'issi',
        [(int) $lessonId, $d['title'] ?? null, $d['file_path'] ?? '', (int) ($d['page_count'] ?? 0)]
    );
}
?>
