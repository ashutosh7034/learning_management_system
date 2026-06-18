<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

if ((int) ($_SESSION['user_type'] ?? 0) === 5) {
	header("location: student_dashboard.php");
	exit;
}


include "dashboard.php";

?>
