<?php
/**
 * Small view/flow helpers shared across the LMS.
 */

require_once __DIR__ . '/config.php';

if (!function_exists('e')) {
    /** HTML-escape for safe output (XSS protection). */
    function e($value)
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('url')) {
    /** Build an absolute URL from an app-relative path. */
    function url($path = '')
    {
        return LMS_BASE_URL . '/' . ltrim((string) $path, '/');
    }
}

if (!function_exists('redirect')) {
    /** Redirect to an app-relative path and stop. */
    function redirect($path)
    {
        header('Location: ' . (preg_match('#^https?://#', $path) ? $path : url($path)));
        exit;
    }
}

if (!function_exists('flash')) {
    /**
     * Set (write) or get+clear (read) a one-shot flash message.
     * flash('success', 'Saved!')  → set
     * flash('success')            → read & clear
     */
    function flash($key, $message = null)
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if ($message !== null) {
            $_SESSION['_flash'][$key] = $message;
            return null;
        }
        $value = $_SESSION['_flash'][$key] ?? null;
        unset($_SESSION['_flash'][$key]);
        return $value;
    }
}

if (!function_exists('render_flashes')) {
    /** Render any pending flash messages as AdminLTE alert boxes. */
    function render_flashes()
    {
        $map = [
            'success' => 'alert-success',
            'danger'  => 'alert-danger',
            'error'   => 'alert-danger',
            'warning' => 'alert-warning',
            'info'    => 'alert-info',
        ];
        $html = '';
        foreach ($map as $key => $cls) {
            $msg = flash($key);
            if ($msg !== null) {
                $html .= '<div class="alert ' . $cls . ' alert-dismissible">'
                    . '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'
                    . e($msg) . '</div>';
            }
        }
        return $html;
    }
}

if (!function_exists('slugify')) {
    /** Make a URL/identifier-safe slug. */
    function slugify($text)
    {
        $text = strtolower(trim((string) $text));
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        return trim($text, '-');
    }
}

if (!function_exists('old')) {
    /** Repopulate a form field from $_POST after a validation bounce. */
    function old($key, $default = '')
    {
        return isset($_POST[$key]) ? $_POST[$key] : $default;
    }
}
?>
