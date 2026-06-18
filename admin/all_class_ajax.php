<?php
require "../database/db_connect.php";
$db_handle = new DBController();
// storing  request (ie, get/post) global array to a variable
$requestData = $_REQUEST;

$columns = array(
// datatable column index  => database column name
    0 => 'class_id',
    1 => 'class_name',
    2 => 'class_id',
    3 => 'class_id',

);

$sql = "SELECT * FROM lms_class_master where 1 ";

if (!empty($requestData['search']['value']))
{
     $sql .= " AND ( lms_class_master.class_id LIKE '" . $requestData['search']['value'] . "%' ";
    
     $sql .= " OR lms_class_master.class_name LIKE '" . $requestData['search']['value'] . "%') ";
}

$result = $db_handle->query($sql);
$totalData = $db_handle->numRows($sql);  
$totalFiltered = $totalData;

$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . " ";

$result = $db_handle->query($sql);
//echo $sql;
$data = array();
while ($row = $result->fetch_assoc()) { 
    $nestedData = array();
    $nestedData[] = $requestData['start'] = $requestData['start'] + 1; 
    $nestedData[] = $row["class_name"];
    $nestedData[] = " <a data-toggle='modal' data-target='#edit' data-id='" . $row["class_id"] . "' id='class_edit'><button class='btn bg-olive btn-sm' type='button'> <i class='fa fa-pencil'></i> </button></a>";
    $nestedData[] = "<a onclick='delete_class(" . $row["class_id"] . ")'><button class='btn btn-danger btn-sm' type='button'> <i class='fa fa-trash'></i> </button></a>";
    $data[] = $nestedData;
}

$json_data = array(
    "recordsTotal" => intval($totalData),  // total number of records
    "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
    "data" => $data   // total data array
);

echo json_encode($json_data);  // send data as json format
?>
