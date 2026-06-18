<?php
// Suppress PHP output buffering issues and set JSON header
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors in output (they break JSON)

header('Content-Type: application/json');

try {
    require_once "../database/db_connect.php";
    $database = new DBController();

    $data = array();
    $error = null;

    // Handle AJAX request for loading minor subjects
    if(isset($_POST['course_id']) && $_POST['course_id'] !== '') {
        $course_id = intval($_POST['course_id']); // Use intval for safety
        
        if($course_id <= 0) {
            $error = "Invalid course ID: Course must be selected";
        } else {
            // Use prepared statement for security
            $query = "SELECT subject_id, subject_name FROM lms_minorsubject WHERE course_id = ? ORDER BY subject_name";
            $stmt = $database->conn->prepare($query);
            
            if($stmt) {
                $stmt->bind_param("i", $course_id);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if($result) {
                    while($row = $result->fetch_assoc()) {
                        $data[] = array(
                            'subject_id' => $row['subject_id'],
                            'subject_name' => $row['subject_name']
                        );
                    }
                }
                $stmt->close();
            } else {
                $error = "Database prepare failed: " . $database->conn->error;
            }
        }
    } else {
        $error = "Please select a minor course first";
    }
    
    // Clear any output that may have been buffered
    ob_end_clean();
    
    // Return JSON response
    echo json_encode(array(
        'success' => ($error === null),
        'data' => $data,
        'error' => $error
    ));
    
} catch(Exception $e) {
    ob_end_clean();
    header('Content-Type: application/json');
    echo json_encode(array(
        'success' => false,
        'data' => array(),
        'error' => $e->getMessage()
    ));
}
exit;
?>