<?php
include "db_connection.php"; // Adjust this to your database connection file

if(isset($_POST['class_id'])) {
    $class_id = $_POST['class_id'];
    
    // Get class name
    $class_query = $db_handle->conn->query("SELECT class_name FROM lms_class_master WHERE class_id = '$class_id'");
    $class_data = $class_query->fetch_assoc();
    $class_name = $class_data['class_name'] ?? '';
    
    $response = [];
    
    // Check for FY (First Year)
    if(stripos($class_name, 'FY') !== false || stripos($class_name, 'First Year') !== false) {
        $response['warning'] = true;
        $response['data'] = [];
        echo json_encode($response);
        exit;
    }
    
    // Check for SY (Second Year)
    if(stripos($class_name, 'SY') !== false || stripos($class_name, 'Second Year') !== false) {
        $allowed_specializations = ['Honours', 'Minor', 'Minor Multidisciplinary'];
        $conditions = [];
        
        foreach($allowed_specializations as $spec) {
            $conditions[] = "specialization_name LIKE '%$spec%'";
        }
        
        $where_clause = implode(' OR ', $conditions);
        $result = $db_handle->conn->query("SELECT specialization_id, specialization_name FROM lms_specialization_master WHERE $where_clause ORDER BY specialization_name");
    } else {
        // For other classes, show all specializations
        $result = $db_handle->conn->query("SELECT specialization_id, specialization_name FROM lms_specialization_master ORDER BY specialization_name");
    }
    
    $specializations = [];
    if($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $specializations[] = $row;
        }
    }
    
    $response['warning'] = false;
    $response['data'] = $specializations;
    
    echo json_encode($response);
} else {
    echo json_encode(['warning' => false, 'data' => []]);
}
?>