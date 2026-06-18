<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once "../database/db_connect.php";
$database = new DBController();

// Get current user ID from session
$userid = intval($_SESSION['user_id'] ?? $_SESSION['user_session'] ?? 0);

if (isset($_POST['save'])) {
    $conn = $database->conn;

    $_SESSION['student_admission_form'] = [
        'academic' => $_POST['academic'] ?? '',
        'registration_no' => $_POST['registration_no'] ?? '',
        'roll_no' => $_POST['roll_no'] ?? '',
        'class' => $_POST['class'] ?? '',
        'current_semester_id' => $_POST['current_semester_id'] ?? '',
        'batch' => $_POST['batch'] ?? '',
        'graduation_year' => $_POST['graduation_year'] ?? '',
        'department_id' => $_POST['department_id'] ?? '',
        'specialization_id' => $_POST['specialization_id'] ?? '',
        'cgpa' => $_POST['cgpa'] ?? '',
        'minor_course_id' => $_POST['minor_course_id'] ?? '',
        'minor_subject_id' => $_POST['minor_subject_id'] ?? '',
        'minor_cgpa' => $_POST['minor_cgpa'] ?? '',
        'unaided_subject' => $_POST['unaided_subject'] ?? '',
        'fname' => $_POST['fname'] ?? '',
        'email' => $_POST['email'] ?? '',
        'mobile' => $_POST['mobile'] ?? '',
    ];

    // Get form data
    $academic_year_id = mysqli_real_escape_string($conn, $_POST['academic'] ?? '');
    $registration_no = mysqli_real_escape_string($conn, $_POST['registration_no'] ?? '');
    $class_id = mysqli_real_escape_string($conn, $_POST['class'] ?? '');
    $division_id = mysqli_real_escape_string($conn, $_POST['batch'] ?? '');
    $current_semester_id = mysqli_real_escape_string($conn, $_POST['current_semester_id'] ?? '');
    $fname = mysqli_real_escape_string($conn, $_POST['fname'] ?? '');
    
    // FIXED: Handle grad_year properly
    $grad_year = 'NULL';
    if (!empty($_POST['graduation_year']) && $_POST['graduation_year'] != 'Select Year' && $_POST['graduation_year'] != '') {
        // If it's numeric, use as is
        if (is_numeric($_POST['graduation_year'])) {
            $grad_year = $_POST['graduation_year'];
        } else {
            // If it's not numeric, try to extract year or use as string (but your DB expects INT)
            // Better to lookup the actual year from a reference table
            $grad_year = 0; // Default value
        }
    }
    
    // Convert to proper SQL value (NULL or the number)
    $grad_year_sql = ($grad_year !== 'NULL' && $grad_year > 0) ? $grad_year : 'NULL';
    
    // Handle roll_no
    $roll_no = 'NULL';
    if (!empty($_POST['roll_no'])) {
        $roll_no = "'" . mysqli_real_escape_string($conn, $_POST['roll_no']) . "'";
    }
    
    // Handle department_id
    $department_id = 'NULL';
    if (!empty($_POST['department_id']) && $_POST['department_id'] != 'Select Department' && is_numeric($_POST['department_id'])) {
        $department_id = "'" . mysqli_real_escape_string($conn, $_POST['department_id']) . "'";
    }
    
    // Default specialization/minor/cgpa fields to NULL for LMS
    $specialization_id = 'NULL';
    $specialization_subject_id = 'NULL';
    $minor_course_id = 'NULL';
    $minor_subject_id = 'NULL';
    $cgpa = 'NULL';
    
    // Handle mobile
    $mobile = 'NULL';
    if (!empty($_POST['mobile'])) {
        $mobile = "'" . mysqli_real_escape_string($conn, $_POST['mobile']) . "'";
    }
    
    // Handle email
    $email = 'NULL';
    if (!empty($_POST['email'])) {
        $email = "'" . mysqli_real_escape_string($conn, $_POST['email']) . "'";
    }
    
    // Mark list is not used in LMS
    $mark_list = 'NULL';
    
    // Check if current user already has a linked student record
    $existingStudentId = 0;
    if (!empty($userid)) {
        $checkUserSql = "SELECT student_id FROM lms_user_master WHERE user_id = " . intval($userid) . " AND student_id > 0 LIMIT 1";
        $checkUserResult = mysqli_query($conn, $checkUserSql);
        if ($checkUserResult && mysqli_num_rows($checkUserResult) > 0) {
            $userRow = mysqli_fetch_assoc($checkUserResult);
            $existingStudentId = intval($userRow['student_id']);
        }
    }

    // Semester marks data
    $m_sem1 = "'[]'";
    $m_sem2 = "'[]'";
    $m_sem3 = "'[]'";
    $status = 1;
    
    // Check if registration number exists
    $check_sql = "SELECT registration_no FROM lms_student_master WHERE registration_no = '$registration_no'";
    if ($existingStudentId > 0) {
        $check_sql .= " AND student_id != " . $existingStudentId;
    }
    $check_result = mysqli_query($conn, $check_sql);
    
    if (mysqli_num_rows($check_result) > 0) {
        echo '<script type="text/javascript">alert("Registration number already exists!");</script>';
        echo "<script>window.open('student_admission.php','_self')</script>";
        exit;
    }
    
    // If user has existing linked student, UPDATE that record; otherwise INSERT new
    if ($existingStudentId > 0) {
        // UPDATE existing student record
        $sql = "UPDATE `lms_student_master` SET
            `academic_year_id` = '$academic_year_id',
            `registration_no` = '$registration_no',
            `class_id` = '$class_id',
            `division_id` = '$division_id',
            `grad_year` = $grad_year_sql,
            `roll_no` = $roll_no,
            `department_id` = $department_id,
            `specialization_id` = $specialization_id,
            `specialization_subject_id` = $specialization_subject_id,
            `minor_course_id` = $minor_course_id,
            `minor_subject_id` = $minor_subject_id,
            `cgpa` = $cgpa,
            `fname` = '$fname',
            `mobile` = $mobile,
            `email` = $email,
            `mark_list` = $mark_list,
            `status` = $status,
            `m_sem1` = $m_sem1,
            `m_sem2` = $m_sem2,
            `m_sem3` = $m_sem3,
            `current_semester_id` = '$current_semester_id'
            WHERE `student_id` = $existingStudentId";
        
        $result = mysqli_query($conn, $sql);
        $student_id = $existingStudentId;
    } else {
        // Build the INSERT query for new student
        $sql = "INSERT INTO `lms_student_master`(
            `academic_year_id`,
            `registration_no`,
            `class_id`,
            `division_id`,
            `grad_year`,
            `roll_no`,
            `department_id`,
            `specialization_id`,
            `specialization_subject_id`,
            `minor_course_id`,
            `minor_subject_id`,
            `cgpa`,
            `fname`,
            `mobile`,
            `email`,
            `mark_list`,
            `status`,
            `m_sem1`,
            `m_sem2`,
            `m_sem3`,
            `created_at`,
            `current_semester_id`
        ) VALUES (
            '$academic_year_id',
            '$registration_no',
            '$class_id',
            '$division_id',
            $grad_year_sql,
            $roll_no,
            $department_id,
            $specialization_id,
            $specialization_subject_id,
            $minor_course_id,
            $minor_subject_id,
            $cgpa,
            '$fname',
            $mobile,
            $email,
            $mark_list,
            $status,
            $m_sem1,
            $m_sem2,
            $m_sem3,
            NOW(),
            '$current_semester_id'
        )";
        
        $result = mysqli_query($conn, $sql);
        $student_id = mysqli_insert_id($conn);
    }
    
    if ($result === TRUE) {
        // Link the student to the current user
        if (!empty($userid)) {
            $updateUserSql = "UPDATE lms_user_master SET student_id = $student_id WHERE user_id = " . intval($userid);
            mysqli_query($conn, $updateUserSql);
        }
        
        // Store in session and redirect to view page
        $_SESSION['student_admission_success'] = 'Student admitted successfully! Student ID: ' . $student_id;
        $_SESSION['viewed_student_id'] = $student_id;
        
        echo '<script type="text/javascript">alert("Student admitted successfully! Student ID: ' . $student_id . '");</script>';
        echo "<script>window.open('student_admission_view.php?id=" . $student_id . "','_self')</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
        echo "<br><br>SQL Query: <pre>" . htmlspecialchars($sql) . "</pre>";
    }
    exit;
}
?>