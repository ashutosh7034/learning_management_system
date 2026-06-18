<?php
require_once __DIR__ . '/../database/db_connect.php';

$db_handle = new DBController();
$conn = $db_handle->conn;

if (!$conn) {
    die('Unable to connect with database');
}
?>