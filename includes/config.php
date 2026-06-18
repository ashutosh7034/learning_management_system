<?php
/**
 * LMS — central configuration.
 *
 * Path-agnostic constants so /admin, /teacher, /student and /ajax pages can all
 * resolve absolute filesystem paths and a correct browser base URL regardless of
 * whether the app is deployed at /lms, /st or the domain root.
 */

if (!defined('LMS_CONFIG_LOADED')) {
    define('LMS_CONFIG_LOADED', true);

    // Filesystem root of the project (this file lives in /includes).
    define('LMS_ROOT', dirname(__DIR__));
    define('LMS_INCLUDES', LMS_ROOT . DIRECTORY_SEPARATOR . 'includes');

    // Browser base URL (e.g. "/lms"). Derived from the running script's location.
    if (!defined('LMS_BASE_URL')) {
        $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/');
        // Strip the trailing "/<area>/<file>.php" down to the app root.
        $base = $script;
        foreach (['/admin/', '/teacher/', '/student/', '/ajax/', '/login/', '/includes/'] as $area) {
            $pos = strpos($script, $area);
            if ($pos !== false) {
                $base = substr($script, 0, $pos);
                break;
            }
        }
        if ($base === $script) {
            $base = rtrim(dirname($script), '/');
        }
        define('LMS_BASE_URL', rtrim($base, '/'));
    }

    // Upload roots.
    define('LMS_UPLOADS', LMS_ROOT . DIRECTORY_SEPARATOR . 'uploads');
    define('LMS_UPLOAD_URL', LMS_BASE_URL . '/uploads');

    // Progress-engine weights (Module 8).
    define('LMS_W_VIDEO', 0.40);
    define('LMS_W_PDF', 0.30);
    define('LMS_W_QUIZ', 0.20);
    define('LMS_W_ASSIGNMENT', 0.10);

    // Certificate is issued once overall progress reaches this percent.
    define('LMS_CERT_THRESHOLD', 100);
}
?>
