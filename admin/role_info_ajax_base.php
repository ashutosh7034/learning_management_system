<?php
session_start();
require "../database/db_connect.php";
$db_handle = new DBController();

$requestData = $_REQUEST;

$columns = array(
  0 => 'u.user_id',
  1 => 'u.user_name',
  2 => 'u.email_id',
  3 => 'u.phone_number',
  4 => 'd.department_name',
  5 => 'r.role_name',
  6 => 'u.user_id',
  7 => 'u.user_id',
  8 => 'u.user_id'
);

$baseSql = "SELECT u.user_id, u.user_name, u.email_id, u.phone_number, d.department_name, r.role_name FROM lms_user_master u LEFT JOIN lms_department_master d ON d.department_id = u.department_id LEFT JOIN lms_role_master r ON r.role_id = u.role_id WHERE u.role_id = " . intval($roleId);

if (!empty($requestData['search']['value'])) {
  $search = mysqli_real_escape_string($db_handle->conn, $requestData['search']['value']);
  $baseSql .= " AND (u.user_name LIKE '%$search%' OR u.email_id LIKE '%$search%' OR u.phone_number LIKE '%$search%' OR d.department_name LIKE '%$search%')";
}

$totalData = $db_handle->numRows("SELECT user_id FROM lms_user_master WHERE role_id = " . intval($roleId));
$totalFiltered = $db_handle->numRows($baseSql);

$orderColumnIndex = isset($requestData['order'][0]['column']) ? intval($requestData['order'][0]['column']) : 0;
$orderColumn = $columns[$orderColumnIndex] ?? 'u.user_id';
$orderDir = (isset($requestData['order'][0]['dir']) && strtolower($requestData['order'][0]['dir']) === 'desc') ? 'DESC' : 'ASC';
$start = isset($requestData['start']) ? intval($requestData['start']) : 0;
$length = isset($requestData['length']) ? intval($requestData['length']) : 15;

$dataSql = $baseSql . " ORDER BY $orderColumn $orderDir LIMIT $start, $length";
$result = $db_handle->query($dataSql);

$data = array();
$srNo = $start + 1;
while ($row = $result->fetch_assoc()) {
  $userId = intval($row['user_id']);
  $nestedData = array();
  $nestedData[] = $srNo++;
  $nestedData[] = htmlspecialchars($row['user_name'] ?? '');
  $nestedData[] = htmlspecialchars($row['email_id'] ?? '');
  $nestedData[] = htmlspecialchars($row['phone_number'] ?? '');
  $nestedData[] = htmlspecialchars($row['department_name'] ?? '');
  $nestedData[] = htmlspecialchars($row['role_name'] ?? '');
  $nestedData[] = "<button class='btn btn-primary btn-sm role-view-btn' data-id='" . $userId . "'><i class='fa fa-eye'></i></button>";
  $nestedData[] = "<button class='btn bg-olive btn-sm role-edit-btn' data-id='" . $userId . "'><i class='fa fa-pencil'></i></button>";
  $nestedData[] = "<button class='btn btn-danger btn-sm' onclick='deleteRoleUser(" . $userId . ")'><i class='fa fa-trash'></i></button>";
  $data[] = $nestedData;
}

echo json_encode(array(
  'recordsTotal' => intval($totalData),
  'recordsFiltered' => intval($totalFiltered),
  'data' => $data
));
?>
