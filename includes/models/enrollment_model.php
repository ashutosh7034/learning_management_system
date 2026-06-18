<?php
/**
 * Enrollment / student catalog data access.
 *
 * Keeps the student-facing pages thin and centralizes the rules for published
 * course visibility and enrollment reactivation.
 */

require_once __DIR__ . '/../db.php';

function lms_catalog_filters()
{
    return [
        'q'        => trim($_GET['q'] ?? ''),
        'category' => (int) ($_GET['category'] ?? 0),
        'level'    => $_GET['level'] ?? '',
    ];
}

function lms_catalog_courses(array $filters = [], $studentId = 0)
{
    $sql = "SELECT c.*, cat.name AS category_name, u.user_name AS teacher_name,
                   (SELECT COUNT(*) FROM lms_lessons l WHERE l.course_id = c.id AND l.deleted_at IS NULL) AS lessons,
                   (SELECT COUNT(*) FROM lms_enrollments e WHERE e.course_id = c.id AND e.deleted_at IS NULL AND e.status <> 'dropped') AS enrolled,
                   e.id AS enrollment_id, e.status AS enrollment_status
              FROM lms_courses c
              LEFT JOIN lms_categories cat ON cat.id = c.category_id
              LEFT JOIN lms_user_master u ON u.user_id = c.teacher_id
              LEFT JOIN lms_enrollments e ON e.course_id = c.id
                   AND e.student_id = ? AND e.deleted_at IS NULL
             WHERE c.deleted_at IS NULL AND c.status = 'published'";
    $types = 'i';
    $params = [(int) $studentId];

    $q = trim($filters['q'] ?? '');
    if ($q !== '') {
        $like = '%' . $q . '%';
        $sql .= " AND (c.title LIKE ? OR c.summary LIKE ? OR c.description LIKE ? OR u.user_name LIKE ?)";
        $types .= 'ssss';
        array_push($params, $like, $like, $like, $like);
    }

    $category = (int) ($filters['category'] ?? 0);
    if ($category > 0) {
        $sql .= " AND c.category_id = ?";
        $types .= 'i';
        $params[] = $category;
    }

    $level = $filters['level'] ?? '';
    if (in_array($level, ['Beginner', 'Intermediate', 'Advanced'], true)) {
        $sql .= " AND c.level = ?";
        $types .= 's';
        $params[] = $level;
    }

    $sql .= " ORDER BY c.published_at DESC, c.updated_at DESC";
    return db_query($sql, $types, $params);
}

function lms_published_course_get($courseId, $studentId = 0)
{
    return db_one(
        "SELECT c.*, cat.name AS category_name, u.user_name AS teacher_name,
                (SELECT COUNT(*) FROM lms_lessons l WHERE l.course_id = c.id AND l.deleted_at IS NULL) AS lessons,
                (SELECT COUNT(*) FROM lms_course_sections s WHERE s.course_id = c.id AND s.deleted_at IS NULL) AS sections,
                e.id AS enrollment_id, e.status AS enrollment_status
           FROM lms_courses c
           LEFT JOIN lms_categories cat ON cat.id = c.category_id
           LEFT JOIN lms_user_master u ON u.user_id = c.teacher_id
           LEFT JOIN lms_enrollments e ON e.course_id = c.id
                AND e.student_id = ? AND e.deleted_at IS NULL
          WHERE c.id = ? AND c.deleted_at IS NULL AND c.status = 'published'
          LIMIT 1",
        'ii',
        [(int) $studentId, (int) $courseId]
    );
}

function lms_student_enrollment($studentId, $courseId)
{
    return db_one(
        "SELECT * FROM lms_enrollments WHERE student_id = ? AND course_id = ? AND deleted_at IS NULL LIMIT 1",
        'ii',
        [(int) $studentId, (int) $courseId]
    );
}

function lms_student_enroll($studentId, $courseId)
{
    $course = db_one(
        "SELECT id FROM lms_courses WHERE id = ? AND status = 'published' AND deleted_at IS NULL LIMIT 1",
        'i',
        [(int) $courseId]
    );
    if (!$course) {
        return false;
    }

    $result = db_execute(
        "INSERT INTO lms_enrollments (student_id, course_id, status, enrolled_at, completed_at, deleted_at)
         VALUES (?, ?, 'active', NOW(), NULL, NULL)
         ON DUPLICATE KEY UPDATE status = 'active', completed_at = NULL, deleted_at = NULL, updated_at = NOW()",
        'ii',
        [(int) $studentId, (int) $courseId]
    );

    return $result !== false;
}

function lms_student_courses($studentId)
{
    return db_query(
        "SELECT e.*, c.title, c.summary, c.thumbnail, c.level, c.slug,
                cat.name AS category_name, u.user_name AS teacher_name,
                COALESCE(cp.overall_pct, 0) AS overall_pct,
                (SELECT COUNT(*) FROM lms_lessons l WHERE l.course_id = c.id AND l.deleted_at IS NULL) AS lessons
           FROM lms_enrollments e
           JOIN lms_courses c ON c.id = e.course_id
           LEFT JOIN lms_categories cat ON cat.id = c.category_id
           LEFT JOIN lms_user_master u ON u.user_id = c.teacher_id
           LEFT JOIN lms_course_progress cp ON cp.enrollment_id = e.id
          WHERE e.student_id = ? AND e.deleted_at IS NULL AND c.deleted_at IS NULL
          ORDER BY e.updated_at DESC, e.enrolled_at DESC",
        'i',
        [(int) $studentId]
    );
}

function lms_course_curriculum($courseId)
{
    $sections = db_query(
        "SELECT * FROM lms_course_sections WHERE course_id = ? AND deleted_at IS NULL ORDER BY sort_order, id",
        'i',
        [(int) $courseId]
    );

    foreach ($sections as $i => $section) {
        $sections[$i]['lessons'] = db_query(
            "SELECT * FROM lms_lessons WHERE section_id = ? AND deleted_at IS NULL ORDER BY sort_order, id",
            'i',
            [(int) $section['id']]
        );
    }

    return $sections;
}
?>
