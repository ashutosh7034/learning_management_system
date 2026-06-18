<?php
session_start();
require "../database/db_connect.php";
require_once __DIR__ . '/../includes/password.php';
$db_handle = new DBController();

$roleId = 3;
$infoFile = 'coordinator_info.php';

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

mysqli_begin_transaction($db_handle->conn);

try {
    if ($userId > 0) {
        // Update existing coordinator in lms_user_master
        $sql = "UPDATE lms_user_master SET user_name='$userNameEsc', email_id='$emailEsc', phone_number='$phoneEsc', department_id=$departmentId WHERE user_id=$userId AND role_id=$roleId";
        $db_handle->query($sql);
        
        // Also update the username in lms_login if email changed
        $updateLoginSql = "UPDATE lms_login SET username='$emailEsc' WHERE user_id=$userId";
        $db_handle->query($updateLoginSql);
    } else {
        // Insert new coordinator into lms_user_master
        $sql = "INSERT INTO lms_user_master (user_name, email_id, phone_number, department_id, role_id, student_id) VALUES ('$userNameEsc', '$emailEsc', '$phoneEsc', $departmentId, $roleId, 0)";
        $db_handle->query($sql);
        $newUserId = $db_handle->conn->insert_id;
        
        // Ensure data is inserted into lms_login
        $hashedPassword = lms_hash_password("123456");
        $loginSql = "INSERT INTO lms_login (username, password, user_id) VALUES ('$emailEsc', '$hashedPassword', $newUserId)";
        $db_handle->query($loginSql);
        $loginId = $db_handle->conn->insert_id;
        
        // Ensure data is inserted into lms_coordinator table using generated login_id
        $coordSql = "INSERT INTO lms_coordinator (login_id) VALUES ($loginId)";
        $db_handle->query($coordSql);
    }
    
    mysqli_commit($db_handle->conn);
} catch (Exception $e) {
    mysqli_rollback($db_handle->conn);
    echo "<script>alert('An error occurred during registration.'); window.history.back();</script>";
    exit;
}

header('Location: ' . $infoFile);
exit;
?>
