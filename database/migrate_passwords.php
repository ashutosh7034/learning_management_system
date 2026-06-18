<?php
/**
 * One-time migration: hash any remaining plaintext passwords in lms_login.
 *
 * Run from CLI:   php database/migrate_passwords.php
 * Or in browser as Super Admin:  /database/migrate_passwords.php
 *
 * Safe to run repeatedly — already-hashed rows are skipped.
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/password.php';
require_once __DIR__ . '/db_connect.php';

$isCli = (PHP_SAPI === 'cli');

if (!$isCli) {
    require_once __DIR__ . '/../includes/auth.php';
    require_role(ROLE_SUPER_ADMIN);
    header('Content-Type: text/plain; charset=utf-8');
}

$db = new DBController();
if (!$db || !($db->conn instanceof mysqli)) {
    die("Database connection failed.\n");
}

$result = mysqli_query($db->conn, "SELECT login_id, password FROM lms_login");
$migrated = 0;
$skipped = 0;

while ($row = mysqli_fetch_assoc($result)) {
    if (lms_is_hashed($row['password'])) {
        $skipped++;
        continue;
    }
    $hash = lms_hash_password($row['password']);
    $stmt = mysqli_prepare($db->conn, "UPDATE lms_login SET password = ? WHERE login_id = ?");
    mysqli_stmt_bind_param($stmt, 'si', $hash, $row['login_id']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    $migrated++;
}

echo "Password migration complete.\n";
echo "  Migrated (plaintext -> bcrypt): {$migrated}\n";
echo "  Already hashed (skipped):       {$skipped}\n";
?>
