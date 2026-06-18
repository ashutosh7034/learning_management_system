<?php
/**
 * Authentication & authorization guard.
 *
 * Integrates with the existing session contract set by login/login.php:
 *   $_SESSION['user_session'], ['user_login_id'], ['user_id'], ['user_type'], ['role_id'], ['login_time']
 *
 * Drop `require_once '<path>/includes/auth.php';` at the top of any protected page,
 * then call require_login() and/or require_role(...).
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/roles.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!function_exists('current_user_id')) {
    function current_user_id()
    {
        return (int) ($_SESSION['user_id'] ?? 0);
    }
}

if (!function_exists('current_login_id')) {
    function current_login_id()
    {
        return (int) ($_SESSION['user_login_id'] ?? ($_SESSION['user_session'] ?? 0));
    }
}

if (!function_exists('current_role_id')) {
    function current_role_id()
    {
        return (int) ($_SESSION['user_type'] ?? ($_SESSION['role_id'] ?? 0));
    }
}

if (!function_exists('is_logged_in')) {
    function is_logged_in()
    {
        return !empty($_SESSION['user_session']) && current_role_id() > 0;
    }
}

if (!function_exists('require_login')) {
    /** Bounce to the login screen unless authenticated. */
    function require_login()
    {
        if (!is_logged_in()) {
            require_once __DIR__ . '/helpers.php';
            redirect('login/index.php');
        }
    }
}

if (!function_exists('require_role')) {
    /**
     * Allow only the listed role IDs. Pass the ROLE_* constants.
     * Example: require_role(ROLE_ADMIN, ROLE_SUPER_ADMIN);
     */
    function require_role(...$allowed)
    {
        require_login();
        $role = current_role_id();
        // Flatten in case an array was passed.
        $flat = [];
        foreach ($allowed as $a) {
            if (is_array($a)) { $flat = array_merge($flat, $a); }
            else { $flat[] = (int) $a; }
        }
        if (!in_array($role, array_map('intval', $flat), true)) {
            http_response_code(403);
            require_once __DIR__ . '/helpers.php';
            echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>403 Forbidden</title></head>'
                . '<body style="font-family:Arial,sans-serif;text-align:center;padding:60px;">'
                . '<h1>403 &mdash; Access Denied</h1>'
                . '<p>You do not have permission to view this page.</p>'
                . '<p><a href="' . e(url(role_home($role))) . '">Return to your dashboard</a></p>'
                . '</body></html>';
            exit;
        }
    }
}

if (!function_exists('require_admin')) {
    function require_admin() { require_role(ROLE_ADMIN, ROLE_SUPER_ADMIN); }
}

if (!function_exists('require_teacher')) {
    /** Teachers and admins (admins can manage everything teachers can). */
    function require_teacher() { require_role(ROLE_TEACHER, ROLE_MENTOR, ROLE_ADMIN, ROLE_SUPER_ADMIN); }
}

if (!function_exists('require_student')) {
    function require_student() { require_role(ROLE_STUDENT); }
}
?>
