<?php
/**
 * Compact report/analytics queries for the LMS admin pages.
 */

require_once __DIR__ . '/../db.php';

function lms_admin_metrics()
{
    return [
        'users' => (int) ((db_one("SELECT COUNT(*) AS n FROM lms_user_master")['n'] ?? 0)),
        'students' => (int) ((db_one("SELECT COUNT(*) AS n FROM lms_user_master WHERE role_id = 5")['n'] ?? 0)),
        'teachers' => (int) ((db_one("SELECT COUNT(*) AS n FROM lms_user_master WHERE role_id IN (3,4)")['n'] ?? 0)),
        'courses' => (int) ((db_one("SELECT COUNT(*) AS n FROM lms_courses WHERE deleted_at IS NULL")['n'] ?? 0)),
        'published' => (int) ((db_one("SELECT COUNT(*) AS n FROM lms_courses WHERE status = 'published' AND deleted_at IS NULL")['n'] ?? 0)),
        'enrollments' => (int) ((db_one("SELECT COUNT(*) AS n FROM lms_enrollments WHERE deleted_at IS NULL")['n'] ?? 0)),
        'certificates' => (int) ((db_one("SELECT COUNT(*) AS n FROM lms_certificates")['n'] ?? 0)),
    ];
}

function lms_report_course_rows()
{
    return db_query(
        "SELECT c.id, c.title, c.status, c.level, cat.name AS category_name, u.user_name AS teacher_name,
                COUNT(DISTINCT e.id) AS enrollments,
                COALESCE(AVG(cp.overall_pct), 0) AS avg_progress,
                COUNT(DISTINCT cert.id) AS certificates
           FROM lms_courses c
           LEFT JOIN lms_categories cat ON cat.id = c.category_id
           LEFT JOIN lms_user_master u ON u.user_id = c.teacher_id
           LEFT JOIN lms_enrollments e ON e.course_id = c.id AND e.deleted_at IS NULL
           LEFT JOIN lms_course_progress cp ON cp.enrollment_id = e.id
           LEFT JOIN lms_certificates cert ON cert.enrollment_id = e.id
          WHERE c.deleted_at IS NULL
          GROUP BY c.id
          ORDER BY c.updated_at DESC"
    );
}

function lms_recent_enrollments($limit = 10)
{
    return db_query(
        "SELECT e.*, c.title AS course_title, u.user_name AS student_name, cp.overall_pct
           FROM lms_enrollments e
           JOIN lms_courses c ON c.id = e.course_id
           LEFT JOIN lms_user_master u ON u.user_id = e.student_id
           LEFT JOIN lms_course_progress cp ON cp.enrollment_id = e.id
          WHERE e.deleted_at IS NULL
          ORDER BY e.enrolled_at DESC
          LIMIT " . (int) $limit
    );
}

function lms_users_for_management()
{
    return db_query(
        "SELECT u.user_id, u.user_name, u.email_id, u.phone_number, u.role_id, r.role_name,
                l.username
           FROM lms_user_master u
           LEFT JOIN lms_role_master r ON r.role_id = u.role_id
           LEFT JOIN lms_login l ON l.user_id = u.user_id
          ORDER BY u.user_id DESC
          LIMIT 500"
    );
}
?>
