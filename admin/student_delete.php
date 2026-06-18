<?php
session_start();
header('Content-Type: application/json');
require "../database/db_connect.php";
$db_handle = new DBController();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = intval($_POST['id']);
    $table = mysqli_real_escape_string($db_handle->conn, $_POST['table'] ?? 'lms_student_master');
    $delete_type = $_POST['delete_type'] ?? 'hard'; // 'hard' or 'soft'
    
    // Validate table name to prevent SQL injection
    $allowed_tables = ['lms_student_master'];
    if (!in_array($table, $allowed_tables)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid table name']);
        exit;
    }
    
    if ($delete_type == 'soft') {
        // Soft delete - just mark as inactive (status = 1)
        $sql = "UPDATE $table SET status = '1' WHERE student_id = $student_id";
        $message = 'Student moved to inactive list';
    } else {
        // Hard delete - permanently remove from database
        $sql = "DELETE FROM $table WHERE student_id = $student_id";
        $message = 'Student deleted permanently';
    }
    
    if ($db_handle->conn->query($sql)) {
        echo json_encode(['status' => 'success', 'message' => $message]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $db_handle->conn->error]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>