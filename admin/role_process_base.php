<?php
session_start();
require "../database/db_connect.php";
$db_handle = new DBController();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: ' . $infoFile);
  exit;
}

$userId = intval($_POST['user_id'] ?? 0);
$userName = trim($_POST['user_name'] ?? '');
$emailId = trim($_POST['email_id'] ?? '');
$phoneNumber = trim($_POST['phone_number'] ?? '');
$departmentId = intval($_POST['department_id'] ?? 0);

if ($userName === '' || $emailId === '' || $departmentId <= 0) {
  echo "<script>alert('Please fill all required fields.'); window.history.back();</script>";
  exit;
}

$userNameEsc = mysqli_real_escape_string($db_handle->conn, $userName);
$emailEsc = mysqli_real_escape_string($db_handle->conn, $emailId);
$phoneEsc = mysqli_real_escape_string($db_handle->conn, $phoneNumber);

if ($userId > 0) {
  $dupSql = "SELECT user_id FROM lms_user_master WHERE email_id = '$emailEsc' AND user_id != $userId LIMIT 1";
} else {
  $dupSql = "SELECT user_id FROM lms_user_master WHERE email_id = '$emailEsc' LIMIT 1";
}
$dupResult = $db_handle->query($dupSql);
if ($dupResult && $dupResult->num_rows > 0) {
  echo "<script>alert('Email already exists.'); window.history.back();</script>";
  exit;
}

if ($userId > 0) {
  $sql = "UPDATE lms_user_master SET user_name='$userNameEsc', email_id='$emailEsc', phone_number='$phoneEsc', department_id=$departmentId WHERE user_id=$userId AND role_id=" . intval($roleId);
} else {
  $sql = "INSERT INTO lms_user_master (user_name, email_id, phone_number, department_id, role_id, student_id) VALUES ('$userNameEsc', '$emailEsc', '$phoneEsc', $departmentId, " . intval($roleId) . ", 0)";
}

$db_handle->query($sql);
header('Location: ' . $infoFile);
exit;
?>
