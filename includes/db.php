<?php
/**
 * Database bootstrap.
 *
 * Reuses the existing DBController (database/db_connect.php) so the connection
 * logic, audit-log helpers and prepared-statement helpers stay in one place.
 * Exposes a shared $db_handle and the raw mysqli $conn, plus thin prepared-query
 * helpers for the new LMS pages.
 */

require_once __DIR__ . '/config.php';
require_once LMS_ROOT . '/database/db_connect.php';

if (!isset($db_handle) || !($db_handle instanceof DBController)) {
    $db_handle = new DBController();
}

if (!$db_handle || !($db_handle->conn instanceof mysqli)) {
    http_response_code(500);
    die('Unable to connect with database');
}

$conn = $db_handle->conn;
mysqli_set_charset($conn, 'utf8mb4');

if (!function_exists('db')) {
    /** Shared mysqli connection. */
    function db()
    {
        global $conn;
        return $conn;
    }
}

if (!function_exists('db_query')) {
    /**
     * Run a parameterized SELECT and return all rows.
     *
     * @param string $sql    SQL with ? placeholders
     * @param string $types  bind types e.g. "isd" (empty if no params)
     * @param array  $params values to bind
     * @return array         list of associative rows
     */
    function db_query($sql, $types = '', array $params = [])
    {
        $stmt = mysqli_prepare(db(), $sql);
        if (!$stmt) {
            error_log('db_query prepare failed: ' . mysqli_error(db()) . ' | ' . $sql);
            return [];
        }
        if ($types !== '' && !empty($params)) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $rows = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row;
            }
        }
        mysqli_stmt_close($stmt);
        return $rows;
    }
}

if (!function_exists('db_one')) {
    /** Run a parameterized SELECT and return the first row (or null). */
    function db_one($sql, $types = '', array $params = [])
    {
        $rows = db_query($sql, $types, $params);
        return $rows[0] ?? null;
    }
}

if (!function_exists('db_execute')) {
    /**
     * Run a parameterized INSERT/UPDATE/DELETE.
     *
     * @return int|false  insert id on INSERT, affected rows on others, false on error
     */
    function db_execute($sql, $types = '', array $params = [])
    {
        $stmt = mysqli_prepare(db(), $sql);
        if (!$stmt) {
            error_log('db_execute prepare failed: ' . mysqli_error(db()) . ' | ' . $sql);
            return false;
        }
        if ($types !== '' && !empty($params)) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }
        $ok = mysqli_stmt_execute($stmt);
        if (!$ok) {
            error_log('db_execute failed: ' . mysqli_stmt_error($stmt) . ' | ' . $sql);
            mysqli_stmt_close($stmt);
            return false;
        }
        $insertId = mysqli_insert_id(db());
        $affected = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        return $insertId > 0 ? $insertId : $affected;
    }
}
?>
