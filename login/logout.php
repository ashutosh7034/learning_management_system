<?php
	session_start();

	include_once("../database/db_connect.php");

	try {
		$db_handle = new DBController();
		if ($db_handle && ($db_handle->conn instanceof mysqli)) {
			$userId = intval($_SESSION['user_id'] ?? 0);
			$loginId = intval($_SESSION['user_login_id'] ?? ($_SESSION['user_session'] ?? 0));
			$ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
			$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
			$username = 'unknown';
			$loginTime = intval($_SESSION['login_time'] ?? 0);
			$sessionDuration = $loginTime > 0 ? max(0, time() - $loginTime) : null;
			$auditLoginId = intval($_SESSION['audit_login_id'] ?? 0);

			// Fetch username from database
			if ($loginId > 0) {
				$usernameSql = "SELECT username FROM lms_login WHERE login_id = ? LIMIT 1";
				$usernameStmt = mysqli_prepare($db_handle->conn, $usernameSql);
				if ($usernameStmt) {
					mysqli_stmt_bind_param($usernameStmt, 'i', $loginId);
					mysqli_stmt_execute($usernameStmt);
					$usernameResult = mysqli_stmt_get_result($usernameStmt);
					if ($usernameResult && ($usernameRow = mysqli_fetch_assoc($usernameResult))) {
						$username = trim((string) ($usernameRow['username'] ?? 'unknown'));
					}
					mysqli_stmt_close($usernameStmt);
				}
			}

			// Update the original login audit row so one session stays in one row.
			if ($userId > 0 && $auditLoginId > 0) {
				$db_handle->completeAuditSession(
					$auditLoginId,
					$userId,
					"Logout: User '{$username}' logged out from IP {$ipAddress}. Browser: {$userAgent}",
					$sessionDuration
				);
			}
		}
	} catch (Throwable $e) {
		// Skip audit logging if the database is unavailable; logout should still complete.
	}

	unset($_SESSION['user_session']);
	unset($_SESSION['user_login_id']);
	unset($_SESSION['user_id']);
	unset($_SESSION['user_type']);
	unset($_SESSION['role_id']);
	unset($_SESSION['login_time']);
	unset($_SESSION['audit_login_id']);
	session_destroy();

	header("Location: ../");
	exit;
?>
