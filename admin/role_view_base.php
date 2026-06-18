<?php
session_start();
require "../database/db_connect.php";
$db_handle = new DBController();

$userId = intval($_POST['id'] ?? 0);
if ($userId <= 0) {
  echo "<div class='alert alert-danger'>Invalid user id.</div>";
  exit;
}

$sql = "SELECT u.user_name, u.email_id, u.phone_number, d.department_name, r.role_name FROM lms_user_master u LEFT JOIN lms_department_master d ON d.department_id = u.department_id LEFT JOIN lms_role_master r ON r.role_id = u.role_id WHERE u.user_id = $userId AND u.role_id = " . intval($roleId) . " LIMIT 1";
$result = $db_handle->query($sql);
$row = $result ? $result->fetch_assoc() : null;

if (!$row) {
  echo "<div class='alert alert-danger'>Record not found.</div>";
  exit;
}
?>
<div class="row">
  <div class="col-md-6"><p><strong>Name:</strong> <?php echo htmlspecialchars($row['user_name'] ?? ''); ?></p></div>
  <div class="col-md-6"><p><strong>Email:</strong> <?php echo htmlspecialchars($row['email_id'] ?? ''); ?></p></div>
</div>
<div class="row">
  <div class="col-md-6"><p><strong>Phone:</strong> <?php echo htmlspecialchars($row['phone_number'] ?? ''); ?></p></div>
  <div class="col-md-6"><p><strong>Department:</strong> <?php echo htmlspecialchars($row['department_name'] ?? ''); ?></p></div>
</div>
<div class="row">
  <div class="col-md-6"><p><strong>Role:</strong> <?php echo htmlspecialchars($row['role_name'] ?? ''); ?></p></div>
</div>
