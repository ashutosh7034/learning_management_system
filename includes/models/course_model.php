<?php
/**
 * Course / Section / Lesson data access  (Module 3 — Course Management).
 *
 * Thin model layer over the lms_* tables using the prepared-statement helpers
 * from includes/db.php. Keeps SQL out of the view pages (MVC).
 */

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../helpers.php';

/* -------------------------------------------------- Categories */

function lms_categories_all()
{
    return db_query("SELECT id, name FROM lms_categories WHERE deleted_at IS NULL ORDER BY name");
}

/* -------------------------------------------------- Courses */

/**
 * List courses. Teachers see only their own; admins see all.
 */
function lms_courses_list($teacherId = null)
{
    $sql = "SELECT c.*, cat.name AS category_name,
                   u.user_name AS teacher_name,
                   (SELECT COUNT(*) FROM lms_enrollments e WHERE e.course_id = c.id AND e.deleted_at IS NULL) AS enrolled,
                   (SELECT COUNT(*) FROM lms_lessons l WHERE l.course_id = c.id AND l.deleted_at IS NULL) AS lessons
              FROM lms_courses c
              LEFT JOIN lms_categories cat ON cat.id = c.category_id
              LEFT JOIN lms_user_master u ON u.user_id = c.teacher_id
             WHERE c.deleted_at IS NULL";
    if ($teacherId !== null) {
        $sql .= " AND c.teacher_id = ?";
        $sql .= " ORDER BY c.updated_at DESC";
        return db_query($sql, 'i', [(int) $teacherId]);
    }
    $sql .= " ORDER BY c.updated_at DESC";
    return db_query($sql);
}

function lms_course_get($id)
{
    return db_one(
        "SELECT * FROM lms_courses WHERE id = ? AND deleted_at IS NULL",
        'i',
        [(int) $id]
    );
}

function lms_course_owned_by($courseId, $teacherId)
{
    $row = db_one("SELECT teacher_id FROM lms_courses WHERE id = ? AND deleted_at IS NULL", 'i', [(int) $courseId]);
    return $row && (int) $row['teacher_id'] === (int) $teacherId;
}

/** Generate a slug unique within lms_courses. */
function lms_course_unique_slug($title, $ignoreId = 0)
{
    $base = slugify($title);
    if ($base === '') { $base = 'course'; }
    $slug = $base;
    $n = 1;
    while (true) {
        $row = db_one(
            "SELECT id FROM lms_courses WHERE slug = ? AND id <> ? LIMIT 1",
            'si',
            [$slug, (int) $ignoreId]
        );
        if (!$row) { return $slug; }
        $slug = $base . '-' . (++$n);
    }
}

function lms_course_create(array $d)
{
    $slug = lms_course_unique_slug($d['title']);
    return db_execute(
        "INSERT INTO lms_courses (category_id, teacher_id, title, slug, summary, description, thumbnail, intro_video_path, intro_video_url, level, status, published_at)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
        'iissssssssss',
        [
            $d['category_id'] !== '' ? (int) $d['category_id'] : null,
            (int) $d['teacher_id'],
            $d['title'],
            $slug,
            $d['summary'] ?? null,
            $d['description'] ?? null,
            $d['thumbnail'] ?? null,
            $d['intro_video_path'] ?? null,
            $d['intro_video_url'] ?? null,
            $d['level'] ?? 'Beginner',
            $d['status'] ?? 'draft',
            ($d['status'] ?? 'draft') === 'published' ? date('Y-m-d H:i:s') : null,
        ]
    );
}

function lms_course_update($id, array $d)
{
    $slug = lms_course_unique_slug($d['title'], $id);
    $publishedAt = ($d['status'] ?? 'draft') === 'published' ? date('Y-m-d H:i:s') : null;
    return db_execute(
        "UPDATE lms_courses
            SET category_id = ?, teacher_id = ?, title = ?, slug = ?, summary = ?, description = ?, level = ?, status = ?,
                intro_video_path = ?, intro_video_url = ?,
                published_at = CASE WHEN ? = 'published' AND published_at IS NULL THEN ? ELSE published_at END
          WHERE id = ?",
        'iissssssssssi',
        [
            $d['category_id'] !== '' ? (int) $d['category_id'] : null,
            (int) $d['teacher_id'],
            $d['title'],
            $slug,
            $d['summary'] ?? null,
            $d['description'] ?? null,
            $d['level'] ?? 'Beginner',
            $d['status'] ?? 'draft',
            $d['intro_video_path'] ?? null,
            $d['intro_video_url'] ?? null,
            $d['status'] ?? 'draft',
            $publishedAt,
            (int) $id,
        ]
    );
}

function lms_course_set_thumbnail($id, $path)
{
    return db_execute("UPDATE lms_courses SET thumbnail = ? WHERE id = ?", 'si', [$path, (int) $id]);
}

function lms_course_set_status($id, $status)
{
    $allowed = ['draft', 'published', 'archived'];
    if (!in_array($status, $allowed, true)) { return false; }
    return db_execute("UPDATE lms_courses SET status = ? WHERE id = ?", 'si', [$status, (int) $id]);
}

function lms_course_soft_delete($id)
{
    return db_execute("UPDATE lms_courses SET deleted_at = NOW() WHERE id = ?", 'i', [(int) $id]);
}

/* -------------------------------------------------- Sections */

function lms_sections_for_course($courseId)
{
    return db_query(
        "SELECT * FROM lms_course_sections WHERE course_id = ? AND deleted_at IS NULL ORDER BY sort_order, id",
        'i',
        [(int) $courseId]
    );
}

function lms_section_create($courseId, $title)
{
    $next = db_one("SELECT COALESCE(MAX(sort_order),0)+1 AS n FROM lms_course_sections WHERE course_id = ?", 'i', [(int) $courseId]);
    return db_execute(
        "INSERT INTO lms_course_sections (course_id, title, sort_order) VALUES (?, ?, ?)",
        'isi',
        [(int) $courseId, $title, (int) ($next['n'] ?? 1)]
    );
}

function lms_section_update($id, $title)
{
    return db_execute("UPDATE lms_course_sections SET title = ? WHERE id = ?", 'si', [$title, (int) $id]);
}

function lms_section_delete($id)
{
    return db_execute("UPDATE lms_course_sections SET deleted_at = NOW() WHERE id = ?", 'i', [(int) $id]);
}

/* -------------------------------------------------- Lessons */

function lms_lessons_for_section($sectionId)
{
    return db_query(
        "SELECT * FROM lms_lessons WHERE section_id = ? AND deleted_at IS NULL ORDER BY sort_order, id",
        'i',
        [(int) $sectionId]
    );
}

function lms_lesson_create($sectionId, $courseId, $title, $type, $isFree = 0)
{
    $next = db_one("SELECT COALESCE(MAX(sort_order),0)+1 AS n FROM lms_lessons WHERE section_id = ?", 'i', [(int) $sectionId]);
    return db_execute(
        "INSERT INTO lms_lessons (section_id, course_id, title, type, is_free, sort_order) VALUES (?, ?, ?, ?, ?, ?)",
        'iissii',
        [(int) $sectionId, (int) $courseId, $title, $type, (int) $isFree, (int) ($next['n'] ?? 1)]
    );
}

function lms_lesson_update($id, $title, $type, $isFree = 0)
{
    return db_execute(
        "UPDATE lms_lessons SET title = ?, type = ?, is_free = ? WHERE id = ?",
        'ssii',
        [$title, $type, (int) $isFree, (int) $id]
    );
}

function lms_lesson_delete($id)
{
    return db_execute("UPDATE lms_lessons SET deleted_at = NOW() WHERE id = ?", 'i', [(int) $id]);
}

function lms_status_badge($status)
{
    $map = ['draft' => 'lms-badge-draft', 'published' => 'lms-badge-published', 'archived' => 'lms-badge-archived'];
    $cls = $map[$status] ?? 'lms-badge-draft';
    return '<span class="label ' . $cls . '">' . htmlspecialchars(ucfirst($status), ENT_QUOTES, 'UTF-8') . '</span>';
}
?>
