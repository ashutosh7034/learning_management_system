<?php
/**
 * Role definitions and helpers.
 *
 * The legacy lms_role_master IDs are preserved so existing data keeps working.
 * Per the migration decision, role 3 (Coordinator/HOD) becomes TEACHER and
 * role 4 (Mentor) is merged into TEACHER as well.
 */

require_once __DIR__ . '/config.php';

if (!defined('ROLE_SUPER_ADMIN')) {
    define('ROLE_SUPER_ADMIN', 1);
    define('ROLE_ADMIN', 2);
    define('ROLE_TEACHER', 3);   // was Coordinator/HOD
    define('ROLE_MENTOR', 4);    // legacy — treated as Teacher
    define('ROLE_STUDENT', 5);
}

if (!function_exists('role_label')) {
    function role_label($roleId)
    {
        switch ((int) $roleId) {
            case ROLE_SUPER_ADMIN: return 'Super Admin';
            case ROLE_ADMIN:       return 'Admin';
            case ROLE_TEACHER:     return 'Teacher';
            case ROLE_MENTOR:      return 'Teacher';
            case ROLE_STUDENT:     return 'Student';
            default:               return 'User';
        }
    }
}

if (!function_exists('is_teacher_role')) {
    /** Mentor is merged into Teacher. */
    function is_teacher_role($roleId)
    {
        return in_array((int) $roleId, [ROLE_TEACHER, ROLE_MENTOR], true);
    }
}

if (!function_exists('is_admin_role')) {
    /** Admin or Super Admin. */
    function is_admin_role($roleId)
    {
        return in_array((int) $roleId, [ROLE_ADMIN, ROLE_SUPER_ADMIN], true);
    }
}

if (!function_exists('role_home')) {
    /** Landing page for a role after login, as an app-relative path. */
    function role_home($roleId)
    {
        $roleId = (int) $roleId;
        if ($roleId === ROLE_STUDENT) {
            return 'student/dashboard.php';
        }
        if (is_teacher_role($roleId)) {
            return 'teacher/dashboard.php';
        }
        return 'admin/index.php';
    }
}
?>
