<?php
/**
 * Renders a single LMS page with a faked logged-in session (used by smoke.php).
 * argv: <page-relative-path> <role_id> <login_id> <user_id>
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');

$page    = $argv[1] ?? '';
$roleId  = (int) ($argv[2] ?? 0);
$loginId = (int) ($argv[3] ?? 0);
$userId  = (int) ($argv[4] ?? 0);
$getId   = (int) ($argv[5] ?? 0);
if ($getId > 0) {
    $_GET['id'] = $getId;
    $_GET['lesson'] = $getId;
}

// Simulate the web request environment.
$_SERVER['SCRIPT_NAME']   = '/lms/' . $page;
$_SERVER['REQUEST_URI']   = '/lms/' . $page;
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['HTTP_HOST']     = 'localhost';
$_SERVER['REMOTE_ADDR']   = '127.0.0.1';
$_SERVER['HTTP_USER_AGENT'] = 'smoke-test';

// Start session and inject the auth contract before the page boots.
$sessionDir = __DIR__ . '/.sessions';
if (!is_dir($sessionDir)) {
    mkdir($sessionDir, 0777, true);
}
ini_set('session.save_path', $sessionDir);
session_start();
$_SESSION['user_session']  = $loginId;
$_SESSION['user_login_id'] = $loginId;
$_SESSION['user_id']       = $userId;
$_SESSION['user_type']     = $roleId;
$_SESSION['role_id']       = $roleId;
$_SESSION['login_time']    = time();

chdir(__DIR__ . '/../' . dirname($page));
require __DIR__ . '/../' . $page;
