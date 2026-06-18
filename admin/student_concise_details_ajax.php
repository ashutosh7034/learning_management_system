<?php
session_start();
require "../database/db_connect.php";

$db_handle = new DBController();
$requestData = $_REQUEST;
$user_id = $_SESSION['user_id'] ?? 0;
$role_id = $_SESSION['user_type'] ?? 0;

// 🔥 GET USER DEPARTMENT
$deptQuery = $db_handle->query("
    SELECT department_id 
    FROM lms_user_master 
    WHERE user_id = '" . intval($user_id) . "'
");

$deptRow = mysqli_fetch_assoc($deptQuery);
$userDeptId = $deptRow['department_id'] ?? 0;
$isExport = isset($_POST['export']) && $_POST['export'] == 'true';

// FILTERS
$select_class = $_POST['select_class'] ?? '';
$select_section = $_POST['select_section'] ?? '';
$select_department = $_POST['select_department'] ?? '';


//   BASE QUERY
$baseSql = " FROM lms_student_master sm

INNER JOIN lms_class_master cm 
    ON cm.class_id = sm.class_id

INNER JOIN lms_section_master sec 
    ON sec.id = sm.division_id

INNER JOIN lms_department_master dept 
    ON dept.department_id = sm.department_id

WHERE sm.status = '0'
";

//   APPLY FILTERS
if (!empty($select_class)) {
    $baseSql .= " AND sm.class_id = '" . mysqli_real_escape_string($db_handle->conn, $select_class) . "'";
}

if (!empty($select_section)) {
    $baseSql .= " AND sm.division_id = '" . mysqli_real_escape_string($db_handle->conn, $select_section) . "'";
}

if ($role_id == 3 || $role_id == 4) {

    // coordinator / mentor
    $baseSql .= " AND sm.department_id = '" . intval($userDeptId) . "'";

} else {

    // admin / super admin
    if (!empty($select_department)) {

        $baseSql .= " AND sm.department_id = '" .
            mysqli_real_escape_string($db_handle->conn, $select_department) . "'";
    }
}



//   TOTAL RECORDS
$countSql = "SELECT COUNT(*) as total " . $baseSql;
$countResult = $db_handle->query($countSql);
$totalRow = mysqli_fetch_assoc($countResult);

$totalData = $totalRow['total'] ?? 0;
$totalFiltered = $totalData;

//   MAIN QUERY
$sql = "SELECT 
    cm.class_name AS class,
    sec.sections AS division,
    dept.department_name AS department,
    COUNT(*) AS student_count
    $baseSql
    GROUP BY 
        sm.class_id, 
        sm.division_id, 
        sm.department_id
    ORDER BY cm.class_name ASC
";

//   PAGINATION
$start  = $requestData['start'] ?? 0;
$length = $requestData['length'] ?? 10;

if (!$isExport && $length != -1) {
    $sql .= " LIMIT $start, $length";
}

//   EXECUTE QUERY
$result = $db_handle->query($sql);

//   DATA BUILD
$data = [];
$srNo = $start;
$totalStudentCount = 0;

while ($row = mysqli_fetch_assoc($result)) {

    $count = (int) $row["student_count"];

    $data[] = [
        ++$srNo,
        $row["class"],
        $row["division"],
        $row["department"],
        $count
    ];

    $totalStudentCount += $count;
}

//   TOTAL ROW (ONLY FOR TABLE VIEW)
if (!$isExport) {
    $data[] = [
        '',
        '<b>Total</b>',
        '',
        '',
        '<b>' . $totalStudentCount . '</b>'
    ];
}

//   RETURN RESPONSE
echo json_encode([
    "draw" => intval($requestData['draw'] ?? 0),
    "recordsTotal" => intval($totalData),
    "recordsFiltered" => intval($totalFiltered),
    "data" => $data
]);
?>