<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

require "../database/db_connect.php";
$db_handle = new DBController();

// Get current user's role and department
$user_id = $_SESSION['user_id'] ?? 0;
$user_role = $_SESSION['user_type'] ?? ($_SESSION['role_id'] ?? 0);

// Get mentor's department if user is a mentor
$mentor_department_id = null;
$is_mentor = false;

if ($user_role == 3) {
    $is_mentor = true;
    $mentor_query = "SELECT department_id FROM lms_user_master WHERE user_id = '$user_id'";
    $mentor_result = $db_handle->query($mentor_query);
    if ($mentor_result && $row = $mentor_result->fetch_assoc()) {
        $mentor_department_id = $row['department_id'];
    }
}

$requestData = $_REQUEST;

$totalDataQuery = $db_handle->query("SELECT COUNT(*) as total FROM lms_student_master");
$totalDataRow = mysqli_fetch_assoc($totalDataQuery);
$totalData = intval($totalDataRow['total'] ?? 0);

// Get filter values
$select_class = $_POST['select_class'] ?? '';
$select_section = $_POST['select_section'] ?? '';
$select_session = $_POST['select_session'] ?? '';
$select_academic_year = $_POST['select_academic_year'] ?? '';
$select_semester = $_POST['select_semester'] ?? '';
$select_department = $_POST['select_department'] ?? '';

// Build the main query with proper joins for both honors and minor subjects
$sql = "SELECT
    sm.student_id,
    sm.registration_no,
    sm.academic_year_id,
    sm.roll_no,
    sm.fname,
    sm.class_id,
    sm.division_id,
    sm.grad_year,
    sm.department_id,
    sm.mobile,
    sm.email,
    sm.status,
    sm.created_at,
    sm.current_semester_id,
    IFNULL(cl.class_name, '') AS class_display,
    IFNULL(sec.sections, '') AS section_display,
    IFNULL(dep.department_name, '') AS department_name,
    IFNULL(sess.session_name, '') AS academic_year_name,
    IFNULL(sem.semester_name, '') AS semester_name
FROM lms_student_master sm
LEFT JOIN lms_class_master cl ON cl.class_id = sm.class_id
LEFT JOIN lms_section_master sec ON sec.id = sm.division_id
LEFT JOIN lms_department_master dep ON dep.department_id = sm.department_id
LEFT JOIN lms_session_master sess ON sess.session_id = sm.academic_year_id
LEFT JOIN lms_semester_master sem ON sem.semester_id = sm.current_semester_id
WHERE 1=1";

// RESTRICTION FOR MENTORS
if ($is_mentor && $mentor_department_id) {
    $sql .= " AND sm.department_id = '" . mysqli_real_escape_string($db_handle->conn, $mentor_department_id) . "'";
    $select_department = $mentor_department_id;
}

// Apply filters
if (!empty($select_class)) {
    $sql .= " AND sm.class_id = '" . mysqli_real_escape_string($db_handle->conn, $select_class) . "'";
}
if (!empty($select_section)) {
    $sql .= " AND sm.division_id = '" . mysqli_real_escape_string($db_handle->conn, $select_section) . "'";
}
if (!empty($select_academic_year)) {
    $sql .= " AND sm.academic_year_id = '" . mysqli_real_escape_string($db_handle->conn, $select_academic_year) . "'";
}
if (!empty($select_semester)) {
    $sql .= " AND sm.current_semester_id = '" . mysqli_real_escape_string($db_handle->conn, $select_semester) . "'";
}

if (!empty($searchValue)) {
    $sql .= " AND (
        sm.registration_no LIKE '%$searchValue%'
        OR sm.fname LIKE '%$searchValue%'
        OR sm.roll_no LIKE '%$searchValue%'
        OR sm.email LIKE '%$searchValue%'
        OR sm.mobile LIKE '%$searchValue%'
        OR cl.class_name LIKE '%$searchValue%'
        OR sec.sections LIKE '%$searchValue%'
        OR dep.department_name LIKE '%$searchValue%'
    )";
}

if (!empty($departmentFilterSql)) {
    $sql .= $departmentFilterSql;
}

// Add search functionality
if (!empty($requestData['search']['value'])) {
    $search = mysqli_real_escape_string($db_handle->conn, $requestData['search']['value']);
    $sql .= " AND (sm.registration_no LIKE '%$search%' 
                OR sm.fname LIKE '%$search%' 
                OR sm.roll_no LIKE '%$search%'
                OR sm.email LIKE '%$search%'
                OR sm.mobile LIKE '%$search%')";
}

$filteredResult = $db_handle->query($sql);
$totalFiltered = mysqli_num_rows($filteredResult);

// Ordering
$orderColumn = 'sm.student_id';
$orderDir = 'DESC';

if (isset($requestData['order'][0]['column'])) {
    $columns = [
        0 => 'sm.student_id',
        1 => 'sm.student_id',
        2 => 'sm.fname',
        3 => 'sm.registration_no',
        4 => 'sm.fname',
        5 => 'class_display',
        6 => 'section_display',
        7 => 'academic_year_name',
        8 => 'semester_name',
        9 => 'department_name',
        10 => 'sm.grad_year',
        11 => 'sm.mobile',
        12 => 'sm.roll_no',
        13 => 'sm.email'
    ];
    $colIndex = intval($requestData['order'][0]['column']);
    if (isset($columns[$colIndex])) {
        $orderColumn = $columns[$colIndex];
    }
    $orderDir = strtoupper($requestData['order'][0]['dir']) === 'ASC' ? 'ASC' : 'DESC';
}

// Pagination
$start = intval($requestData['start'] ?? 0);
$length = intval($requestData['length'] ?? 15);

$sql .= " ORDER BY $orderColumn $orderDir LIMIT $start, $length";
$result = $db_handle->query($sql);

// Prepare data
$data = [];
$counter = $start + 1;

while ($row = mysqli_fetch_assoc($result)) {
    $nestedData = [];
    $nestedData[] = "<input type='checkbox' class='selectRow' value='{$row['student_id']}' />";
    $nestedData[] = $counter++;

    $full_name = $row['fname'] ?? '';
    $mobile = $row['mobile'];
    $reg_no = $row['registration_no'];
    $student_name = urlencode($full_name);
    $message = "Dear%20" . $student_name . "%2C%20Welcome%20to%20Thakur%20College.%20Your%20Reg%20No%3A%20" . $reg_no;

    if (!empty($mobile)) {
        $nestedData[] = "<a href='https://wa.me/91$mobile?text=$message' target='_blank' class='btn btn-success btn-sm'><i class='fa fa-whatsapp'></i></a>";
    } else {
        $nestedData[] = "-";
    }

    $nestedData[] = "<strong>" . htmlspecialchars($row['registration_no']) . "</strong>";
    $nestedData[] = "<div align='left'><strong>" . htmlspecialchars($full_name) . "</strong></div>";
    $nestedData[] = !empty($row['class_display']) ? $row['class_display'] : '-';
    $nestedData[] = !empty($row['section_display']) ? $row['section_display'] : '-';
    $nestedData[] = !empty($row['academic_year_name']) ? $row['academic_year_name'] : '-';
    $nestedData[] = !empty($row['semester_name']) ? $row['semester_name'] : '-';
    $nestedData[] = !empty($row['department_name']) ? $row['department_name'] : '-';
    $nestedData[] = !empty($row['grad_year']) ? $row['grad_year'] : '-';
    $nestedData[] = !empty($row['mobile']) ? $row['mobile'] : '-';
    $nestedData[] = !empty($row['roll_no']) ? $row['roll_no'] : '-';
    $nestedData[] = !empty($row['email']) ? "<a href='mailto:{$row['email']}'>" . htmlspecialchars($row['email']) . "</a>" : '-';
    $nestedData[] = "<button class='btn btn-primary btn-sm student_view' data-id='{$row['student_id']}'><i class='fa fa-eye'></i></button>";
    $nestedData[] = "<button class='btn bg-olive btn-sm student_edit' data-id='{$row['student_id']}'><i class='fa fa-pencil'></i></button>";
    $nestedData[] = "<button class='btn btn-danger btn-sm' onclick='delete_user({$row['student_id']}, \"lms_student_master\")'><i class='fa fa-trash'></i></button>";

    $data[] = $nestedData;
}

while (ob_get_level()) {
    ob_end_clean();
}

header('Content-Type: application/json');
echo json_encode([
    "draw" => intval($requestData['draw']),
    "recordsTotal" => intval($totalData),
    "recordsFiltered" => intval($totalFiltered),
    "data" => $data
]);
exit;
?>