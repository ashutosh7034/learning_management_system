<?php
session_start();
require "../database/db_connect.php";
header('Content-Type: application/json');

$db_handle = new DBController();
$userId = intval($_POST['user_id'] ?? 0);

if ($userId <= 0) {
  echo json_encode(array('success' => false, 'message' => 'Invalid user id.'));
  exit;
}

mysqli_begin_transaction($db_handle->conn);
try {
  $db_handle->query("DELETE FROM lms_login WHERE user_id = $userId");
  $db_handle->query("DELETE FROM lms_user_master WHERE user_id = $userId");
  mysqli_commit($db_handle->conn);
  $ok = true;
} catch (Exception $e) {
  mysqli_rollback($db_handle->conn);
  $ok = false;
}

if ($ok) {
  echo json_encode(array('success' => true));
} else {
  echo json_encode(array('success' => false, 'message' => 'Unable to delete record.'));
}
?>
