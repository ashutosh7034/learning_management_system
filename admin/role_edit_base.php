<?php
session_start();
require "../database/db_connect.php";

$db_handle = new DBController();

$roleId = intval($roleId ?? 0);
$processFile = $processFile ?? '';

if ($roleId <= 0 || $processFile === '') {
	echo "<div class='alert alert-danger'>Role configuration is missing.</div>";
	exit;
}

$userId = intval($_POST['id'] ?? 0);
if ($userId <= 0) {
	echo "<div class='alert alert-danger'>Invalid user id.</div>";
	exit;
}

$sql = "SELECT * FROM lms_user_master WHERE user_id = $userId AND role_id = $roleId LIMIT 1";
$result = $db_handle->query($sql);
$row = $result ? $result->fetch_assoc() : null;

if (!$row) {
	echo "<div class='alert alert-danger'>Record not found.</div>";
	exit;
}
?>

<form method="post" action="<?php echo htmlspecialchars($processFile); ?>" autocomplete="off">
	<input type="hidden" name="user_id" value="<?php echo intval($userId); ?>">
	<input type="hidden" name="role_id" value="<?php echo intval($roleId); ?>">

	<div class="form-group">
		<label>Name <span style="color:red;">*</span></label>
		<input
			type="text"
			name="user_name"
			class="form-control"
			value="<?php echo htmlspecialchars($row['user_name'] ?? ''); ?>"
			required
		>
	</div>

	<div class="form-group">
		<label>Email <span style="color:red;">*</span></label>
		<input
			type="email"
			name="email_id"
			class="form-control"
			value="<?php echo htmlspecialchars($row['email_id'] ?? ''); ?>"
			required
		>
	</div>

	<div class="form-group">
		<label>Phone Number</label>
		<input
			type="text"
			name="phone_number"
			class="form-control"
			maxlength="15"
			value="<?php echo htmlspecialchars($row['phone_number'] ?? ''); ?>"
		>
	</div>

	<div class="form-group">
		<label>Department <span style="color:red;">*</span></label>
		<select name="department_id" class="form-control" required>
			<option value="">Select Department</option>
			<?php
			$deptSql = "SELECT department_id, department_name FROM lms_department_master ORDER BY department_name ASC";
			$deptResult = $db_handle->query($deptSql);
			while ($dept = $deptResult->fetch_assoc()) {
				$selected = (intval($row['department_id']) === intval($dept['department_id'])) ? 'selected' : '';
			?>
				<option value="<?php echo intval($dept['department_id']); ?>" <?php echo $selected; ?>>
					<?php echo htmlspecialchars($dept['department_name']); ?>
				</option>
			<?php } ?>
		</select>
	</div>

	<div style="margin-top: 12px;">
		<button
			type="submit"
			class="btn-submit"
		>Save Changes</button>
		<button
			type="reset"
			class="btn-reset"
		>Reset</button>
	</div>
</form>