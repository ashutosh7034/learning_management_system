<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

require "../database/db_connect.php";
$db_handle = new DBController();

if (!isset($_SESSION['user_session'])) {
  header("location: ../index.php");
  exit();
}

function coordinator_allocation_fetch_coordinators($db_handle)
{
  $sql = "SELECT
            u.user_id AS coordinator_id,
            COALESCE(NULLIF(TRIM(u.user_name), ''), l.username) AS coordinator_name,
            l.username,
            COALESCE(d.department_name, '') AS department_name,
            COUNT(cm.id) AS assigned_mentors
          FROM lms_user_master u
          LEFT JOIN lms_login l ON l.user_id = u.user_id
          LEFT JOIN lms_department_master d ON d.department_id = u.department_id
          LEFT JOIN lms_coordinator_mentor cm ON cm.coordinator_id = u.user_id
          WHERE u.role_id = 3
          GROUP BY u.user_id, coordinator_name, l.username, d.department_name
          ORDER BY coordinator_name ASC";

  return $db_handle->runQuery($sql) ?? array();
}

function coordinator_allocation_build_mentor_where($conn, $filters)
{
  $where = " WHERE u.role_id = 4 ";

  if (!empty($filters['department_id'])) {
    $deptId = mysqli_real_escape_string($conn, $filters['department_id']);
    $where .= " AND u.department_id = '{$deptId}' ";
  }

  if (!empty($filters['coordinator_id'])) {
    $coordId = intval($filters['coordinator_id']);
    if ($coordId > 0) {
      $where .= " AND cm.coordinator_id = {$coordId} ";
    }
  }

  if (!empty($filters['assignment_status'])) {
    if ($filters['assignment_status'] === 'assigned') {
      $where .= " AND cm.coordinator_id IS NOT NULL ";
    } elseif ($filters['assignment_status'] === 'unassigned') {
      $where .= " AND cm.coordinator_id IS NULL ";
    }
  }

  if (!empty($filters['search'])) {
    $search = mysqli_real_escape_string($conn, $filters['search']);
    $where .= " AND (
      COALESCE(NULLIF(TRIM(u.user_name), ''), l.username) LIKE '%{$search}%'
      OR d.department_name LIKE '%{$search}%'
      OR COALESCE(NULLIF(TRIM(cu.user_name), ''), cl.username) LIKE '%{$search}%'
    ) ";
  }

  return $where;
}

function coordinator_allocation_fetch_mentor_ids($db_handle, $filters)
{
  $where = coordinator_allocation_build_mentor_where($db_handle->conn, $filters);
  $sql = "SELECT u.user_id AS mentor_id
          FROM lms_user_master u
          LEFT JOIN lms_login l ON l.user_id = u.user_id
          LEFT JOIN lms_department_master d ON d.department_id = u.department_id
          LEFT JOIN lms_coordinator_mentor cm ON cm.mentor_id = u.user_id
          LEFT JOIN lms_login cl ON cl.user_id = cm.coordinator_id
          LEFT JOIN lms_user_master cu ON cu.user_id = cm.coordinator_id AND cu.role_id = 3
          {$where}
          ORDER BY u.user_name ASC, u.user_id ASC";

  $rows = $db_handle->runQuery($sql) ?? array();
  $ids = array();

  foreach ($rows as $row) {
    $mentorId = intval($row['mentor_id'] ?? 0);
    if ($mentorId > 0) {
      $ids[] = $mentorId;
    }
  }

  return $ids;
}

function coordinator_allocation_assign_mentors($db_handle, $coordinatorId, $mentorIds)
{
  $coordinatorId = intval($coordinatorId);
  $cleanIds = array_values(array_unique(array_filter(array_map('intval', $mentorIds))));

  if ($coordinatorId <= 0 || empty($cleanIds)) {
    return false;
  }

  $idList = implode(',', $cleanIds);
  mysqli_begin_transaction($db_handle->conn);

  try {
    if (!mysqli_query($db_handle->conn, "DELETE FROM lms_coordinator_mentor WHERE mentor_id IN ({$idList})")) {
      throw new Exception('Unable to clear existing coordinator mapping.');
    }

    foreach ($cleanIds as $mentorId) {
      $insertSql = "INSERT INTO lms_coordinator_mentor (coordinator_id, mentor_id) VALUES ({$coordinatorId}, {$mentorId})";
      if (!mysqli_query($db_handle->conn, $insertSql)) {
        throw new Exception('Unable to save coordinator mapping.');
      }
    }

    mysqli_commit($db_handle->conn);
    return true;
  } catch (Throwable $e) {
    mysqli_rollback($db_handle->conn);
    return false;
  }
}

if (isset($_GET['action']) && $_GET['action'] === 'load_mentors') {
  header('Content-Type: application/json');

  $requestData = $_REQUEST;
  $filters = array(
    'department_id' => $_POST['department_id'] ?? '',
    'coordinator_id' => $_POST['coordinator_filter'] ?? '',
    'assignment_status' => $_POST['assignment_status'] ?? '',
    'search' => $requestData['search']['value'] ?? ''
  );

  $where = coordinator_allocation_build_mentor_where($db_handle->conn, $filters);
  $baseSql = "FROM lms_user_master u
              LEFT JOIN lms_login l ON l.user_id = u.user_id
              LEFT JOIN lms_department_master d ON d.department_id = u.department_id
              LEFT JOIN lms_coordinator_mentor cm ON cm.mentor_id = u.user_id
              LEFT JOIN lms_login cl ON cl.user_id = cm.coordinator_id
              LEFT JOIN lms_user_master cu ON cu.user_id = cm.coordinator_id AND cu.role_id = 3
              {$where}";

  $totalRows = $db_handle->runQuery("SELECT COUNT(*) AS total FROM lms_user_master WHERE role_id = 4") ?? array();
  $filteredRows = $db_handle->runQuery("SELECT COUNT(*) AS total {$baseSql}") ?? array();
  $totalData = intval($totalRows[0]['total'] ?? 0);
  $totalFiltered = intval($filteredRows[0]['total'] ?? 0);

  $colMap = [
      2 => 'mentor_name',
      3 => 'department_name',
      4 => 'coordinator_name'
  ];
  
  $orderColumnIndex = isset($requestData['order'][0]['column']) ? intval($requestData['order'][0]['column']) : 2;
  $orderColumn = $colMap[$orderColumnIndex] ?? 'mentor_name';
  $orderDir = (isset($requestData['order'][0]['dir']) && strtolower($requestData['order'][0]['dir']) === 'desc') ? 'DESC' : 'ASC';
  $start = isset($requestData['start']) ? intval($requestData['start']) : 0;
  $length = isset($requestData['length']) ? intval($requestData['length']) : 15;

  $sql = "SELECT
            u.user_id AS mentor_id,
            COALESCE(NULLIF(TRIM(u.user_name), ''), l.username, '') AS mentor_name,
            COALESCE(d.department_name, '') AS department_name,
            COALESCE(NULLIF(TRIM(cu.user_name), ''), cl.username, '') AS coordinator_name
          {$baseSql}
          ORDER BY {$orderColumn} {$orderDir}
          LIMIT {$start}, {$length}";

  $rows = $db_handle->runQuery($sql) ?? array();
  $data = array();
  $srNo = $start + 1;

  foreach ($rows as $row) {
    $mentorId = intval($row['mentor_id'] ?? 0);
    $data[] = array(
      "<input type='checkbox' class='mentor-checkbox' value='{$mentorId}'>",
      $srNo++,
      htmlspecialchars($row['mentor_name'] ?? ''),
      htmlspecialchars($row['department_name'] ?? ''),
      htmlspecialchars($row['coordinator_name'] !== '' ? $row['coordinator_name'] : 'Unassigned')
    );
  }

  echo json_encode(array(
    'draw' => intval($requestData['draw'] ?? 0),
    'recordsTotal' => $totalData,
    'recordsFiltered' => $totalFiltered,
    'data' => $data
  ));
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'get_coordinator_dept') {
    header('Content-Type: application/json');
    $coordId = intval($_POST['coordinator_id'] ?? 0);
    
    $sql = "SELECT 
                COALESCE(u.department_id, sm.department_id) as department_id,
                COALESCE(d.department_name, dm.department_name) as department_name
            FROM lms_user_master u
            LEFT JOIN lms_login l ON l.user_id = u.user_id
            LEFT JOIN lms_department_master d ON d.department_id = u.department_id
            LEFT JOIN lms_student_master sm ON sm.student_id = u.user_id
            LEFT JOIN lms_department_master dm ON dm.department_id = sm.department_id
            WHERE u.user_id = {$coordId} AND u.role_id = 3
            LIMIT 1";
    
    $result = $db_handle->runQuery($sql) ?? array();
    
    if (!empty($result) && isset($result[0]['department_id']) && $result[0]['department_id'] > 0) {
        echo json_encode(array(
            'success' => true,
            'department_id' => $result[0]['department_id'],
            'department_name' => $result[0]['department_name']
        ));
    } else {
        // Return false but don't show error - department field will remain editable
        echo json_encode(array('success' => false));
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'assign_selected') {
  header('Content-Type: application/json');

  $coordinatorId = intval($_POST['coordinator_id'] ?? 0);
  $mentorIds = $_POST['mentor_ids'] ?? array();
  $success = coordinator_allocation_assign_mentors($db_handle, $coordinatorId, is_array($mentorIds) ? $mentorIds : array());

  if ($success) {
    if (method_exists($db_handle, 'writeAuditLog')) {
      $db_handle->writeAuditLog($_SESSION['user_session'] ?? 0, 'COORDINATOR_ALLOCATION_UPDATED', 'lms_coordinator_mentor', null, 'Assigned selected mentors to coordinator ID ' . $coordinatorId);
    }
    echo json_encode(array('success' => true, 'message' => 'Selected mentors allocated successfully.'));
  } else {
    echo json_encode(array('success' => false, 'message' => 'Unable to allocate selected mentors.'));
  }
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'assign_filtered') {
  header('Content-Type: application/json');

  $coordinatorId = intval($_POST['coordinator_id'] ?? 0);
  $filters = array(
    'department_id' => $_POST['department_id'] ?? '',
    'coordinator_id' => $_POST['coordinator_filter'] ?? '',
    'assignment_status' => $_POST['assignment_status'] ?? '',
    'search' => $_POST['search_value'] ?? ''
  );

  $mentorIds = coordinator_allocation_fetch_mentor_ids($db_handle, $filters);
  if (empty($mentorIds)) {
    echo json_encode(array('success' => false, 'message' => 'No mentors found for the selected filters.'));
    exit();
  }

  $success = coordinator_allocation_assign_mentors($db_handle, $coordinatorId, $mentorIds);

  if ($success) {
    if (method_exists($db_handle, 'writeAuditLog')) {
      $db_handle->writeAuditLog($_SESSION['user_session'] ?? 0, 'COORDINATOR_ALLOCATION_UPDATED', 'lms_coordinator_mentor', null, 'Assigned filtered mentors to coordinator ID ' . $coordinatorId . '. Total mentors: ' . count($mentorIds));
    }
    echo json_encode(array('success' => true, 'message' => 'Filtered mentors allocated successfully.', 'count' => count($mentorIds)));
  } else {
    echo json_encode(array('success' => false, 'message' => 'Unable to allocate filtered mentors.'));
  }
  exit();
}

$coordinators = coordinator_allocation_fetch_coordinators($db_handle);
$departmentRows = $db_handle->runQuery("SELECT department_id, department_name FROM lms_department_master ORDER BY department_name ASC") ?? array();

include "header/header.php";
?>
<div class="content-wrapper">
  <section class="content-header">
    <h1><i class="fa fa-users"></i> Coordinator Allocation</h1>
    <ol class="breadcrumb">
      <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Coordinator Allocation</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Assign One Coordinator To Every Mentor</h3>
          </div>
          <div class="box-body">
            <div class="row" style="margin-bottom: 15px;">
              <div class="col-md-3">
                <label>Coordinator</label>
                <select class="form-control" id="coordinator_id">
                  <option value="">Select Coordinator</option>
                  <?php foreach ($coordinators as $coord) { ?>
                    <option value="<?php echo (int) $coord['coordinator_id']; ?>">
                      <?php echo htmlspecialchars(($coord['coordinator_name'] ?? '') . ($coord['department_name'] ? ' - ' . $coord['department_name'] : '')); ?>
                    </option>
                  <?php } ?>
                </select>
              </div>
              <div class="col-md-3">
                <label>Department</label>
                <select class="form-control" id="department_id">
                  <option value="">All Departments</option>
                  <?php foreach ($departmentRows as $deptRow) { ?>
                    <option value="<?php echo (int) $deptRow['department_id']; ?>"><?php echo htmlspecialchars($deptRow['department_name']); ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="col-md-3">
                <label>Current Status</label>
                <select class="form-control" id="assignment_status">
                  <option value="">All Mentors</option>
                  <option value="assigned">Assigned</option>
                  <option value="unassigned">Unassigned</option>
                </select>
              </div>
            </div>

            <div class="row" style="margin-bottom: 15px;">
              <div class="col-md-3">
                <label>Search</label>
                <select class="form-control" id="coordinator_filter">
                  <option value="">All Coordinators</option>
                  <?php foreach ($coordinators as $coord) { ?>
                    <option value="<?php echo (int) $coord['coordinator_id']; ?>"><?php echo htmlspecialchars($coord['coordinator_name'] ?? ''); ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="col-md-9" style="padding-top: 25px;">
                <button type="button" class="btn btn-primary" id="apply_filters"><i class="fa fa-filter"></i> Apply Filters</button>
                <button type="button" class="btn btn-default" id="reset_filters"><i class="fa fa-refresh"></i> Reset</button>
                <button type="button" class="btn btn-success" id="assign_selected_btn"><i class="fa fa-check-square-o"></i> Assign Selected</button>
                <button type="button" class="btn btn-warning" id="assign_filtered_btn"><i class="fa fa-random"></i> Assign All Filtered</button>
              </div>
            </div>

            <div class="table-responsive">
              <table id="coordinatorAllocationTable" class="table table-bordered table-striped" width="100%">
                <thead>
                  <tr>
                    <th><input type="checkbox" id="select_all_mentors"></th>
                    <th>Sr. No</th>
                    <th>Mentor Name</th>
                    <th>Department</th>
                    <th>Current Coordinator</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
<script>
$(document).ready(function() {
  var allocationTable = $('#coordinatorAllocationTable').DataTable({
    processing: true,
    serverSide: true,
    pageLength: 15,
    order: [[2, 'asc']],
    ajax: {
      url: 'coordinator_allocation.php?action=load_mentors',
      type: 'POST',
      data: function(d) {
        d.department_id = $('#department_id').val();
        d.coordinator_filter = $('#coordinator_filter').val();
        d.assignment_status = $('#assignment_status').val();
      }
    },
    columnDefs: [
      { orderable: false, targets: [0] }
    ]
  });

  function selectedMentorIds() {
    var ids = [];
    $('.mentor-checkbox:checked').each(function() {
      ids.push($(this).val());
    });
    return ids;
  }

  function ensureCoordinatorSelected() {
    if (!$('#coordinator_id').val()) {
      alert('Please select a coordinator first.');
      return false;
    }
    return true;
  }

  $('#coordinator_id').on('change', function() {
    var coordId = $(this).val();
    if (coordId) {
      $.ajax({
        url: 'coordinator_allocation.php',
        type: 'POST',
        dataType: 'json',
        data: { 
            action: 'get_coordinator_dept',
            coordinator_id: coordId 
        },
        success: function(resp) {
          if (resp && resp.success) {
            $('#department_id').val(resp.department_id).prop('disabled', true);
          } else {
            $('#department_id').val('').prop('disabled', false);
          }
        },
        error: function() {
          $('#department_id').val('').prop('disabled', false);
        }
      });
    } else {
      $('#department_id').val('').prop('disabled', false);
    }
  });

  $('#apply_filters').on('click', function() {
    allocationTable.ajax.reload();
  });

  $('#reset_filters').on('click', function() {
    $('#department_id').prop('disabled', false);
    $('#department_id, #coordinator_filter, #assignment_status, #coordinator_id').val('');
    $('div.dataTables_filter input').val('');
    allocationTable.search('').draw();
  });

  $('#select_all_mentors').on('change', function() {
    $('.mentor-checkbox').prop('checked', this.checked);
  });

  $(document).on('change', '.mentor-checkbox', function() {
    if (!this.checked) {
      $('#select_all_mentors').prop('checked', false);
    }
  });

  $('#assign_selected_btn').on('click', function() {
    if (!ensureCoordinatorSelected()) {
      return;
    }

    var mentorIds = selectedMentorIds();
    if (!mentorIds.length) {
      alert('Please select at least one mentor.');
      return;
    }

    $.ajax({
      url: 'coordinator_allocation.php',
      type: 'POST',
      dataType: 'json',
      data: {
        action: 'assign_selected',
        coordinator_id: $('#coordinator_id').val(),
        mentor_ids: mentorIds
      },
      success: function(resp) {
        alert((resp && resp.message) ? resp.message : 'Assignment completed.');
        $('#select_all_mentors').prop('checked', false);
        allocationTable.ajax.reload(null, false);
      },
      error: function() {
        alert('Unable to assign selected mentors right now.');
      }
    });
  });

  $('#assign_filtered_btn').on('click', function() {
    if (!ensureCoordinatorSelected()) {
      return;
    }

    if (!confirm('Assign the selected coordinator to all mentors matching the current filters?')) {
      return;
    }

    $.ajax({
      url: 'coordinator_allocation.php',
      type: 'POST',
      dataType: 'json',
      data: {
        action: 'assign_filtered',
        coordinator_id: $('#coordinator_id').val(),
        department_id: $('#department_id').val(),
        coordinator_filter: $('#coordinator_filter').val(),
        assignment_status: $('#assignment_status').val(),
        search_value: $('div.dataTables_filter input').val()
      },
      success: function(resp) {
        alert((resp && resp.message) ? resp.message : 'Filtered allocation completed.');
        $('#select_all_mentors').prop('checked', false);
        allocationTable.ajax.reload(null, false);
      },
      error: function() {
        alert('Unable to assign filtered mentors right now.');
      }
    });
  });
});
</script>
<?php include "header/footer.php"; ?>
