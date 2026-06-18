<?php include "header/header.php"; ?>
<?php
$roleKey = strtolower(trim($_GET['role'] ?? 'admin'));
$roleMap = array(
  'admin' => array('id' => 2, 'label' => 'Admin'),
  'coordinator' => array('id' => 3, 'label' => 'Coordinator / HOD'),
  'mentor' => array('id' => 4, 'label' => 'Mentor')
);
if (!isset($roleMap[$roleKey])) {
  $roleKey = 'admin';
}
$roleId = $roleMap[$roleKey]['id'];
$roleLabel = $roleMap[$roleKey]['label'];
?>
<div class="content-wrapper">
  <section class="content-header">
    <!--<h1><i class="fa fa-users"></i> <?php echo htmlspecialchars($roleLabel); ?> Info</h1>-->
    <h1><i class="fa fa-users"></i> USER LIST</h1>
    <ol class="breadcrumb">
      <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active"><?php echo htmlspecialchars($roleLabel); ?> Info</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
           <!-- <div class="box-header with-border">
            <a href="user_register.php?role=<?php echo urlencode($roleKey); ?>" class="btn btn-primary">
            <i class="fa fa-plus"></i> Register <?php echo htmlspecialchars($roleLabel); ?>
            </a>
          </div>-->
          <div class="box-body table-responsive">
            <table id="userRoleTable" class="table table-bordered table-striped text-center" width="100%">
              <thead>
                <tr>
                  <th>Sr. No</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Department</th>
                  <th>Role</th>
                  <th>Remove</th>
                </tr>
              </thead>
            </table>
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
  var roleId = <?php echo intval($roleId); ?>;

  $('#userRoleTable').DataTable({
    processing: true,
    serverSide: true,
    pageLength: 15,
    order: [[0, 'asc']],
    ajax: {
      url: 'user_info_ajax.php',
      type: 'POST',
      data: { role_id: roleId }
    },
    columnDefs: [
      { orderable: false, targets: [6] }
    ]
  });
});

function deleteUser(userId, roleKey) {
  if (!confirm('Are you sure you want to remove this record?')) {
    return;
  }

  $.ajax({
    url: 'user_delete.php',
    type: 'POST',
    dataType: 'json',
    data: { user_id: userId },
    success: function(resp) {
      if (resp.success) {
        window.location.href = 'user-info.php?role=' + encodeURIComponent(roleKey);
      } else {
        alert(resp.message || 'Delete failed.');
      }
    },
    error: function() {
      alert('Something went wrong while deleting.');
    }
  });
}
</script>
<?php include "header/footer.php"; ?>
