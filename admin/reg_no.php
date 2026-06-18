<?php
session_start();
require_once "../database/db_connect.php";

header('Content-Type: application/json; charset=utf-8');

$db_handle = new DBController();
$registerNo = trim($_POST['register1'] ?? '');

if ($registerNo === '') {
    echo json_encode([
        'exists' => false,
    ]);
    exit;
}

$registerNo = mysqli_real_escape_string($db_handle->conn, $registerNo);
$sql = "SELECT fname, email, mobile FROM lms_student_master WHERE registration_no = '$registerNo' LIMIT 1";
$result = mysqli_query($db_handle->conn, $sql);

if ($result && ($row = mysqli_fetch_assoc($result))) {
    echo json_encode([
        'exists' => true,
        'fname' => $row['fname'] ?? '',
        'email' => $row['email'] ?? '',
        'mobile' => $row['mobile'] ?? '',
    ]);
    exit;
}

echo json_encode([
    'exists' => false,
]);