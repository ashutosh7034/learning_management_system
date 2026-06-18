<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

if (!isset($_SESSION['user_session'])) {
	header("location: ../index.php");
	exit;
}

if ((int) ($_SESSION['user_type'] ?? 0) !== 1) {
	header("location: index.php");
	exit;
}

require "header/header.php";

$db_handle->ensureAuditLogTable();

$actionType = trim($_GET['action_type'] ?? '');
$search = trim($_GET['search'] ?? '');
$fromDate = trim($_GET['from_date'] ?? '');
$toDate = trim($_GET['to_date'] ?? '');

$where = array();
$params = array();
$types = '';

if ($actionType !== '') {
	$where[] = 'a.action_type = ?';
	$params[] = $actionType;
	$types .= 's';
}

if ($search !== '') {
	$where[] = '(a.action_type LIKE ? OR a.description LIKE ? OR a.username LIKE ? OR a.ip_address LIKE ? OR CAST(a.user_id AS CHAR) LIKE ?)';
	$searchLike = '%' . $search . '%';
	$params[] = $searchLike;
	$params[] = $searchLike;
	$params[] = $searchLike;
	$params[] = $searchLike;
	$params[] = $searchLike;
	$types .= 'sssss';
}

if ($fromDate !== '') {
	$where[] = 'DATE(a.performed_at) >= ?';
	$params[] = $fromDate;
	$types .= 's';
}

if ($toDate !== '') {
	$where[] = 'DATE(a.performed_at) <= ?';
	$params[] = $toDate;
	$types .= 's';
}

$auditTypes = array();
$typeResult = mysqli_query($db_handle->conn, "SELECT DISTINCT action_type FROM lms_audit_log ORDER BY action_type ASC");
if ($typeResult) {
	while ($typeRow = mysqli_fetch_assoc($typeResult)) {
		$actionName = trim((string) ($typeRow['action_type'] ?? ''));
		if ($actionName !== '') {
			$auditTypes[] = $actionName;
		}
	}
}
if (!in_array('NEVER_LOGGED_IN', $auditTypes, true)) {
	$auditTypes[] = 'NEVER_LOGGED_IN';
}

$auditSql = "SELECT a.audit_id, a.user_id, a.action_type, a.affected_table, a.affected_record, a.description, a.username, a.ip_address, a.browser_user_agent, a.session_duration_seconds, a.logout_at, a.performed_at FROM lms_audit_log a";
if (!empty($where)) {
	$auditSql .= ' WHERE ' . implode(' AND ', $where);
}
$auditSql .= ' ORDER BY a.performed_at DESC, a.audit_id DESC LIMIT 500';

$auditLogs = array();
$auditStmt = mysqli_prepare($db_handle->conn, $auditSql);
if ($auditStmt) {
	if (!empty($params)) {
		$bindParams = array();
		$bindParams[] = $types;
		foreach ($params as $index => $value) {
			$bindParams[] = &$params[$index];
		}
		call_user_func_array(array($auditStmt, 'bind_param'), $bindParams);
	}

	mysqli_stmt_execute($auditStmt);
	$auditResult = mysqli_stmt_get_result($auditStmt);
	if ($auditResult) {
		while ($row = mysqli_fetch_assoc($auditResult)) {
			$auditLogs[] = $row;
		}
	}
	mysqli_stmt_close($auditStmt);
}

$neverLoggedRows = array();
$includeNeverLogged = ($actionType === '' || $actionType === 'NEVER_LOGGED_IN') && $fromDate === '' && $toDate === '';
if ($includeNeverLogged) {
	$neverSql = "SELECT u.user_id,
		COALESCE(NULLIF(TRIM(u.email_id), ''), NULLIF(TRIM(l.username), ''), NULLIF(TRIM(u.user_name), ''), CONCAT('User #', u.user_id)) AS username
		FROM lms_user_master u
		LEFT JOIN lms_login l ON l.user_id = u.user_id
		LEFT JOIN lms_audit_log a ON a.user_id = u.user_id AND a.action_type = 'LOGIN_SUCCESS'
		WHERE a.audit_id IS NULL";

	$neverParams = array();
	$neverTypes = '';
	if ($search !== '') {
		$neverSql .= " AND (
			u.user_name LIKE ? OR u.email_id LIKE ? OR l.username LIKE ? OR CAST(u.user_id AS CHAR) LIKE ?
		)";
		$searchLike = '%' . $search . '%';
		$neverParams = array($searchLike, $searchLike, $searchLike, $searchLike);
		$neverTypes = 'ssss';
	}

	$neverSql .= ' ORDER BY u.user_id DESC LIMIT 300';
	$neverStmt = mysqli_prepare($db_handle->conn, $neverSql);
	if ($neverStmt) {
		if (!empty($neverParams)) {
			mysqli_stmt_bind_param($neverStmt, $neverTypes, $neverParams[0], $neverParams[1], $neverParams[2], $neverParams[3]);
		}

		mysqli_stmt_execute($neverStmt);
		$neverResult = mysqli_stmt_get_result($neverStmt);
		if ($neverResult) {
			while ($neverRow = mysqli_fetch_assoc($neverResult)) {
				$neverLoggedRows[] = array(
					'audit_id' => 0,
					'user_id' => (int) ($neverRow['user_id'] ?? 0),
					'action_type' => 'NEVER_LOGGED_IN',
					'affected_table' => '',
					'affected_record' => '',
					'description' => 'User has not logged in yet.',
					'username' => (string) ($neverRow['username'] ?? ''),
					'ip_address' => '',
					'browser_user_agent' => '',
					'session_duration_seconds' => null,
					'logout_at' => '',
					'performed_at' => ''
				);
			}
		}
		mysqli_stmt_close($neverStmt);
	}
}

if (!empty($neverLoggedRows)) {
	$auditLogs = array_merge($auditLogs, $neverLoggedRows);
}

$totalCountResult = mysqli_query($db_handle->conn, "SELECT COUNT(*) AS total FROM lms_audit_log");
$totalCount = $totalCountResult ? (int) (mysqli_fetch_assoc($totalCountResult)['total'] ?? 0) : 0;
$totalCount += count($neverLoggedRows);
$filteredCount = count($auditLogs);
$loginCountResult = mysqli_query($db_handle->conn, "SELECT COUNT(*) AS total FROM lms_audit_log WHERE action_type = 'LOGIN_SUCCESS'");
$loginCount = $loginCountResult ? (int) (mysqli_fetch_assoc($loginCountResult)['total'] ?? 0) : 0;
$logoutCountResult = mysqli_query($db_handle->conn, "SELECT COUNT(*) AS total FROM lms_audit_log WHERE action_type = 'LOGIN_SUCCESS' AND logout_at IS NOT NULL");
$logoutCount = $logoutCountResult ? (int) (mysqli_fetch_assoc($logoutCountResult)['total'] ?? 0) : 0;
$activeCountResult = mysqli_query($db_handle->conn, "SELECT COUNT(*) AS total FROM lms_audit_log WHERE action_type = 'LOGIN_SUCCESS' AND logout_at IS NULL");
$activeCount = $activeCountResult ? (int) (mysqli_fetch_assoc($activeCountResult)['total'] ?? 0) : 0;

function audit_format_datetime($value)
{
	$value = trim((string) $value);
	if ($value === '' || $value === '0000-00-00 00:00:00') {
		return '-';
	}

	$timestamp = strtotime($value);
	return $timestamp ? date('d M Y, h:i A', $timestamp) : $value;
}

function audit_session_status($logRow)
{
	$actionType = (string) ($logRow['action_type'] ?? '');
	$logoutAt = trim((string) ($logRow['logout_at'] ?? ''));

	if ($actionType === 'LOGIN_SUCCESS' && $logoutAt !== '') {
		return array('Completed', 'label-success', 'Login and logout recorded');
	}

	if ($actionType === 'LOGIN_SUCCESS') {
		return array('Logged In', 'label-warning', 'Logout pending');
	}

	if ($actionType === 'LOGIN_FAILED') {
		return array('Failed Login', 'label-danger', 'Invalid login attempt');
	}

	if ($actionType === 'LOGOUT_SUCCESS') {
		return array('Logged Out', 'label-default', 'Old separate logout record');
	}

	if ($actionType === 'NEVER_LOGGED_IN') {
		return array('Never Logged In', 'label-default', 'User exists but has not visited website yet');
	}

	return array($actionType, 'label-info', trim((string) ($logRow['description'] ?? '')));
}
?>

<div class="content-wrapper">
<style>
	.audit-shell {
		background: #f6f8fb;
	}

	.audit-hero {
		background: #fff;
		border: 1px solid #e6ebf2;
		border-radius: 8px;
		padding: 18px 20px;
		margin-bottom: 16px;
		box-shadow: 0 8px 20px rgba(31, 45, 61, 0.05);
	}

	.audit-title {
		margin: 0;
		font-size: 24px;
		font-weight: 700;
		color: #1f2d3d;
	}

	.audit-subtitle {
		margin-top: 4px;
		color: #6b778c;
	}

	.audit-metric {
		background: #fff;
		border: 1px solid #e6ebf2;
		border-left: 4px solid #3c8dbc;
		border-radius: 8px;
		padding: 16px;
		margin-bottom: 14px;
		min-height: 92px;
		box-shadow: 0 6px 16px rgba(31, 45, 61, 0.04);
	}

	.audit-metric h3 {
		margin: 0 0 6px;
		font-size: 30px;
		font-weight: 700;
		color: #1f2d3d;
	}

	.audit-metric p {
		margin: 0;
		color: #6b778c;
		font-weight: 600;
	}

	.audit-metric.completed { border-left-color: #00a65a; }
	.audit-metric.active { border-left-color: #f39c12; }
	.audit-metric.failed { border-left-color: #dd4b39; }

	.audit-panel {
		background: #fff;
		border: 1px solid #e6ebf2;
		border-radius: 8px;
		box-shadow: 0 8px 20px rgba(31, 45, 61, 0.05);
	}

	.audit-panel-header {
		padding: 16px 18px;
		border-bottom: 1px solid #edf1f5;
	}

	.audit-panel-title {
		margin: 0;
		font-size: 18px;
		font-weight: 700;
		color: #1f2d3d;
	}

	.audit-filter {
		padding: 16px 18px 8px;
		border-bottom: 1px solid #edf1f5;
	}

	.audit-filter .form-control,
	.audit-filter .btn {
		margin-bottom: 8px;
	}

	.audit-legend {
		padding: 0 18px 14px;
		color: #6b778c;
	}

	.audit-legend .label {
		margin-right: 5px;
	}

	.audit-table {
		margin-bottom: 0;
	}

	.audit-table > thead > tr > th {
		background: #f8fafc;
		border-bottom: 1px solid #e6ebf2;
		color: #52616f;
		font-size: 12px;
		text-transform: uppercase;
		letter-spacing: .03em;
	}

	.audit-table > tbody > tr > td {
		vertical-align: middle;
		border-top: 1px solid #edf1f5;
	}

	.audit-user {
		font-weight: 700;
		color: #1f2d3d;
	}

	.audit-muted {
		color: #7b8794;
		font-size: 12px;
	}

	.audit-time {
		line-height: 1.5;
	}

	.audit-duration {
		font-weight: 700;
		color: #1f2d3d;
		white-space: nowrap;
	}

	.audit-browser {
		max-width: 360px;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}

	.audit-status {
		display: inline-block;
		min-width: 88px;
		text-align: center;
		padding: 5px 8px;
		border-radius: 12px;
	}
</style>
<section class="content-header">
	<h1>
		Login Activity

	</h1>

</section>

<section class="content audit-shell">


	<div class="row">
		<div class="col-sm-6 col-md-3">
			<div class="audit-metric">
				<h3><?php echo $totalCount; ?></h3>
				<p>Total Records</p>
			</div>
		</div>
		<div class="col-sm-6 col-md-3">
			<div class="audit-metric completed">
				<h3><?php echo $logoutCount; ?></h3>
				<p>Completed Sessions</p>
			</div>
		</div>
		<div class="col-sm-6 col-md-3">
			<div class="audit-metric active">
				<h3><?php echo $activeCount; ?></h3>
				<p>Currently Logged In</p>
			</div>
		</div>
		<div class="col-sm-6 col-md-3">
			<div class="audit-metric failed">
				<h3><?php echo max(0, $totalCount - $loginCount); ?></h3>
				<p>Failed / Old Records</p>
			</div>
		</div>
	</div>

	<div class="audit-panel">
		<div class="audit-panel-header">
			<h3 class="audit-panel-title">Session History</h3>
		</div>

		<div class="audit-filter">
			<form method="get" class="row">
				<div class="col-sm-4 col-md-3">
					<input type="text" name="search" class="form-control" placeholder="Search user or IP" value="<?php echo htmlspecialchars($search); ?>">
				</div>
				<div class="col-sm-4 col-md-2">
					<select name="action_type" class="form-control">
						<option value="">All Status</option>
						<?php foreach ($auditTypes as $typeName) { ?>
							<option value="<?php echo htmlspecialchars($typeName); ?>" <?php echo $actionType === $typeName ? 'selected' : ''; ?>><?php echo htmlspecialchars(str_replace('_', ' ', $typeName)); ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="col-sm-4 col-md-2">
					<input type="date" name="from_date" class="form-control" value="<?php echo htmlspecialchars($fromDate); ?>">
				</div>
				<div class="col-sm-4 col-md-2">
					<input type="date" name="to_date" class="form-control" value="<?php echo htmlspecialchars($toDate); ?>">
				</div>
				<div class="col-sm-4 col-md-3">
					<button type="submit" class="btn btn-primary">
						<i class="fa fa-filter"></i> Apply
					</button>
					<a href="audit_log.php" class="btn btn-default">
						<i class="fa fa-refresh"></i> Reset
					</a>
				</div>
			</form>
		</div>

		<div class="audit-legend">
			<span class="label label-success">Completed</span> login and logout recorded
			&nbsp;&nbsp;
			<span class="label label-warning">Logged In</span> logout pending
			&nbsp;&nbsp;
			<span class="label label-danger">Failed Login</span> invalid attempt
			&nbsp;&nbsp;
			<span class="label label-default">Never Logged In</span> user created but no website entry
		</div>

		<div class="table-responsive">
						<table class="table audit-table table-hover">
							<thead>
								<tr>
									<th>User</th>
									<th>Status</th>
									<th>Login</th>
									<th>Logout</th>
									<th>Duration</th>
									<th>Location</th>
									<th>Browser</th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($auditLogs)) { ?>
									<?php foreach ($auditLogs as $logRow) { ?>
										<?php
										$statusData = audit_session_status($logRow);
										$statusText = $statusData[0];
										$statusClass = $statusData[1];
										$statusDetail = $statusData[2];
										$actionType = (string) ($logRow['action_type'] ?? '');
										$isLegacyLogout = $actionType === 'LOGOUT_SUCCESS';
										$loginTime = $isLegacyLogout ? '-' : audit_format_datetime($logRow['performed_at'] ?? '');
										$logoutTime = $isLegacyLogout ? audit_format_datetime($logRow['performed_at'] ?? '') : audit_format_datetime($logRow['logout_at'] ?? '');
										$browser = trim((string) ($logRow['browser_user_agent'] ?? ''));
										?>
										<tr>
											<td>
												<div class="audit-user"><?php echo htmlspecialchars(trim((string) ($logRow['username'] ?? '')) !== '' ? $logRow['username'] : 'User #' . (int) $logRow['user_id']); ?></div>
												<div class="audit-muted">User ID: <?php echo (int) $logRow['user_id']; ?> · Log #<?php echo (int) $logRow['audit_id']; ?></div>
											</td>
											<td><span class="label audit-status <?php echo htmlspecialchars($statusClass); ?>"><?php echo htmlspecialchars($statusText); ?></span></td>
											<td><div class="audit-time"><?php echo htmlspecialchars($loginTime); ?></div></td>
											<td><div class="audit-time"><?php echo htmlspecialchars($logoutTime); ?></div></td>
											<td class="audit-duration">
												<?php
												$duration = $logRow['session_duration_seconds'];
												echo $duration !== null ? gmdate('H:i:s', (int) $duration) : '-';
												?>
											</td>
											<td>
												<?php
												$locationIp = trim((string) ($logRow['ip_address'] ?? ''));
												echo htmlspecialchars($locationIp !== '' ? $locationIp : '-');
												?>
											</td>
											<td>
												<?php if ($browser !== '') { ?>
													<div class="audit-browser" title="<?php echo htmlspecialchars($browser); ?>"><?php echo htmlspecialchars($browser); ?></div>
													<div class="audit-muted"><?php echo htmlspecialchars($statusDetail); ?></div>
												<?php } else { ?>
													<span class="audit-muted"><?php echo htmlspecialchars($statusDetail); ?></span>
												<?php } ?>
											</td>
										</tr>
									<?php } ?>
								<?php } else { ?>
									<tr>
										<td colspan="7" class="text-center text-muted">No audit log entries found.</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
	</div>
</section>
</div>

<?php require "header/footer.php"; ?>
