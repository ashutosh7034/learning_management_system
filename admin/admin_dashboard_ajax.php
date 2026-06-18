<?php
session_start();
include_once("../database/db_connect.php");
$db_handle = new DBController();

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'get_rejected_students') {
        $spec_id = isset($_GET['spec_id']) ? intval($_GET['spec_id']) : 0;
        
        // REAL DATABASE QUERY FOR REJECTED STUDENTS
        $query = "SELECT 
            s.fname,
            s.lname,
            s.register_number,
            d.department_name,
            CASE 
                WHEN s.cgpa < 2.0 THEN 'Low CGPA'
                WHEN s.email_id IS NULL OR s.email_id = '' THEN 'Incomplete documents'
                WHEN s.status = 0 THEN 'Prerequisite not met'
                ELSE 'Missed deadline'
            END as rejection_reason,
            CASE 
                WHEN s.mobile IS NOT NULL AND s.mobile != '' THEN s.mobile
                ELSE 'N/A'
            END as mobile
        FROM dsms_student_master s
        LEFT JOIN lms_department_master d ON s.department_id = d.department_id
        WHERE s.status = 0";
        
        if ($spec_id > 0) {
            $query .= " AND s.specialization_id = " . $spec_id;
        }
        
        $query .= " ORDER BY s.std_id DESC LIMIT 50";
        
        $result = mysqli_query($db_handle->conn, $query);
        $data = [];
        
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = [
                    'fname' => $row['fname'],
                    'lname' => $row['lname'],
                    'registration_no' => $row['register_number'],
                    'department_name' => $row['department_name'] ?: 'Unknown',
                    'rejection_reason' => $row['rejection_reason'],
                    'mobile' => $row['mobile']
                ];
            }
        } else {
            error_log("Rejected students AJAX query failed: " . mysqli_error($db_handle->conn));
        }
        
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
?>