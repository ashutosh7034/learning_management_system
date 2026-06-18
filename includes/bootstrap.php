<?php
/**
 * One-line bootstrap for LMS pages.
 *
 *   require_once __DIR__ . '/../includes/bootstrap.php';
 *
 * Loads config, DB ($db_handle / $conn / db helpers), session, auth, roles,
 * csrf and view helpers. Also applies secure session cookie params on first start.
 */

require_once __DIR__ . '/config.php';

// Harden session cookies before the session starts.
if (session_status() !== PHP_SESSION_ACTIVE) {
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    @ini_set('session.cookie_httponly', '1');
    @ini_set('session.use_strict_mode', '1');
    if (PHP_VERSION_ID >= 70300) {
        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => '/',
            'httponly' => true,
            'secure'   => $secure,
            'samesite' => 'Lax',
        ]);
    }
    session_start();
}

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/roles.php';
require_once __DIR__ . '/csrf.php';
require_once __DIR__ . '/auth.php';
?>
