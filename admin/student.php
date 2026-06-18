<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once "../database/db_connect.php";
$database = new DBController();

if (isset($_POST['save'])) {
  $conn = $database->conn;

  // Get form data - DON'T add quotes here, just escape
  $academic_year_id = mysqli_real_escape_string($conn, $_POST['academic'] ?? '');
  $registration_no = mysqli_real_escape_string($conn, $_POST['registration_no'] ?? '');
  $class_id = mysqli_real_escape_string($conn, $_POST['class'] ?? '');
  $division_id = mysqli_real_escape_string($conn, $_POST['batch'] ?? '');
  $current_semester_id = mysqli_real_escape_string($conn, $_POST['current_semester_id'] ?? '');
  $fname = mysqli_real_escape_string($conn, $_POST['fname'] ?? '');

  // Handle grad_year - properly quoted
  $grad_year = 'NULL';
  if (!empty($_POST['academic_year_id']) && $_POST['academic_year_id'] != 'Select Year' && is_numeric($_POST['academic_year_id'])) {
    $grad_year = "'" . mysqli_real_escape_string($conn, $_POST['academic_year_id']) . "'";
  }

  // Handle roll_no - properly quoted
  $roll_no = 'NULL';
  if (!empty($_POST['roll_no'])) {
    $roll_no = "'" . mysqli_real_escape_string($conn, $_POST['roll_no']) . "'";
  }

  // Handle department_id
  $department_id = 'NULL';
  if (!empty($_POST['department_id']) && $_POST['department_id'] != 'Select Department' && is_numeric($_POST['department_id'])) {
    $department_id = "'" . mysqli_real_escape_string($conn, $_POST['department_id']) . "'";
  }

  // Default specialization tracker fields to NULL for LMS
  $specialization_id = 'NULL';
  $specialization_subject_id = 'NULL';
  $cgpa = 'NULL';
  $minor_course_id = 'NULL';
  $minor_subject_id = 'NULL';

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

  // Semester marks data
  $m_sem1 = "'[]'";
  $m_sem2 = "'[]'";
  $m_sem3 = "'[]'";
  $status = 1;

  // Check if registration number exists
  $check_sql = "SELECT registration_no FROM lms_student_master WHERE registration_no = '$registration_no'";
  $check_result = mysqli_query($conn, $check_sql);

  if (mysqli_num_rows($check_result) > 0) {
    echo '<script type="text/javascript">alert("Registration number already exists!");</script>';
    echo "<script>window.open('student_admission.php','_self')</script>";
    exit;
  }

  // Build the INSERT query - USING PROPERLY QUOTED VALUES
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
    $grad_year,
    $roll_no,
    $department_id,
    $specialization_id,
    $specialization_subject_id,
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
  // Debug - print the query to see what's wrong
  // echo "<pre>" . htmlspecialchars($sql) . "</pre>";
  // exit;

  $result = mysqli_query($conn, $sql);

  if ($result === TRUE) {
    $student_id = mysqli_insert_id($conn);
    echo '<script type="text/javascript">alert("Student registered successfully! Student ID: ' . $student_id . '");</script>';
    echo "<script>window.open('student-info.php','_self')</script>";
  } else {
    echo "Error: " . mysqli_error($conn);
    echo "<br><br>SQL Query: <pre>" . htmlspecialchars($sql) . "</pre>";
  }
  exit;
}
?>
<?php include "header/header.php"; ?>
