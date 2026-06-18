<?php
/**
 * Secure file-upload validation & storage (Phase 8 / content modules).
 */

require_once __DIR__ . '/config.php';

if (!defined('LMS_UPLOAD_RULES')) {
    define('LMS_UPLOAD_RULES', true);
}

if (!function_exists('lms_upload_categories')) {
    /** Allowed mime/extension/size rules per content category. */
    function lms_upload_categories()
    {
        return [
            'video' => [
                'dir'   => 'videos',
                'max'   => 512 * 1024 * 1024, // 512 MB
                'ext'   => ['mp4', 'webm', 'ogg', 'mov'],
                'mime'  => ['video/mp4', 'video/webm', 'video/ogg', 'video/quicktime'],
            ],
            'pdf' => [
                'dir'   => 'pdfs',
                'max'   => 64 * 1024 * 1024, // 64 MB
                'ext'   => ['pdf'],
                'mime'  => ['application/pdf'],
            ],
            'thumbnail' => [
                'dir'   => 'thumbnails',
                'max'   => 5 * 1024 * 1024, // 5 MB
                'ext'   => ['jpg', 'jpeg', 'png', 'webp'],
                'mime'  => ['image/jpeg', 'image/png', 'image/webp'],
            ],
        ];
    }
}

if (!function_exists('lms_store_upload')) {
    /**
     * Validate and move an uploaded file.
     *
     * @param array  $file     a single $_FILES entry
     * @param string $category 'video' | 'pdf' | 'thumbnail'
     * @return array  ['ok'=>bool, 'path'=>relative path under /uploads, 'url'=>browser url, 'error'=>string]
     */
    function lms_store_upload($file, $category)
    {
        $rules = lms_upload_categories();
        if (!isset($rules[$category])) {
            return ['ok' => false, 'error' => 'Unknown upload category.'];
        }
        $rule = $rules[$category];

        if (!isset($file['error']) || is_array($file['error'])) {
            return ['ok' => false, 'error' => 'Invalid upload.'];
        }
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['ok' => false, 'error' => 'Upload failed (code ' . (int) $file['error'] . ').'];
        }
        if ($file['size'] > $rule['max']) {
            return ['ok' => false, 'error' => 'File exceeds the maximum allowed size.'];
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $rule['ext'], true)) {
            return ['ok' => false, 'error' => 'Disallowed file extension: .' . $ext];
        }

        $finfo = function_exists('finfo_open') ? finfo_open(FILEINFO_MIME_TYPE) : false;
        $mime = $finfo ? finfo_file($finfo, $file['tmp_name']) : ($file['type'] ?? '');
        if ($finfo) { finfo_close($finfo); }
        if (!in_array($mime, $rule['mime'], true)) {
            return ['ok' => false, 'error' => 'Disallowed file type: ' . e_safe($mime)];
        }

        $destDir = LMS_UPLOADS . DIRECTORY_SEPARATOR . $rule['dir'];
        if (!is_dir($destDir)) {
            @mkdir($destDir, 0775, true);
        }

        $safeName = bin2hex(random_bytes(8)) . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', basename($file['name']));
        $absPath = $destDir . DIRECTORY_SEPARATOR . $safeName;

        if (!move_uploaded_file($file['tmp_name'], $absPath)) {
            return ['ok' => false, 'error' => 'Could not store the uploaded file.'];
        }

        $relPath = $rule['dir'] . '/' . $safeName;
        return [
            'ok'   => true,
            'path' => $relPath,
            'url'  => LMS_UPLOAD_URL . '/' . $relPath,
            'mime' => $mime,
            'size' => (int) $file['size'],
        ];
    }
}

if (!function_exists('e_safe')) {
    function e_safe($v) { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); }
}
?>
