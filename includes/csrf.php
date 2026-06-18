<?php
/**
 * CSRF protection.
 *
 * Usage in a form:    echo csrf_field();
 * On POST handling:   csrf_require();   // dies on mismatch
 * In an AJAX header:  X-CSRF-Token: <token from csrf_token()>
 */

if (!function_exists('csrf_token')) {
    function csrf_token()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (empty($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf'];
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field()
    {
        return '<input type="hidden" name="_csrf" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
    }
}

if (!function_exists('csrf_validate')) {
    /** Return true if the request carries a valid token (POST field or header). */
    function csrf_validate()
    {
        $token = $_POST['_csrf']
            ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
        $expected = $_SESSION['_csrf'] ?? '';
        return $token !== '' && $expected !== '' && hash_equals($expected, $token);
    }
}

if (!function_exists('csrf_require')) {
    /** Enforce a valid CSRF token on state-changing requests. */
    function csrf_require()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (!csrf_validate()) {
            http_response_code(419);
            die('Invalid or expired security token. Please refresh the page and try again.');
        }
    }
}
?>
