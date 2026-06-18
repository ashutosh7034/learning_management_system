<?php
session_start();
require "../database/db_connect.php";

header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'Invalid request'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ids']) && isset($_POST['table'])) {
    $db_handle = new DBController();
    $ids = $_POST['ids'];
    $table = mysqli_real_escape_string($db_handle->conn, $_POST['table']);
    
    if (!empty($ids) && is_array($ids)) {
        $ids_escaped = array_map(function($id) use ($db_handle) {
            return mysqli_real_escape_string($db_handle->conn, $id);
        }, $ids);
        
        $ids_string = implode("','", $ids_escaped);
        $query = "DELETE FROM $table WHERE student_id IN ('$ids_string')";
        
        if ($db_handle->query($query)) {
            $response = ['status' => 'success', 'message' => count($ids) . ' student(s) deleted successfully.'];
        } else {
            $response = ['status' => 'error', 'message' => 'Database error: ' . mysqli_error($db_handle->conn)];
        }
    } else {
        $response = ['status' => 'error', 'message' => 'No valid IDs provided'];
    }
}

echo json_encode($response);
exit;