<?php
// Root entry point — send visitors to the login screen.
// Path-agnostic: works whether the app is served from /lms, /st, or domain root.
$base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
header('Location: ' . $base . '/login/index.php');
exit();
?>
