<?php include "header/header.php"; ?>
<style>
  #roleTable th,
  #roleTable td {
    vertical-align: middle;
    text-align: center;
    white-space: nowrap;
  }
  #roleTable thead th {
    background-color: #0DF387;
  }
  #roleTable thead th:nth-last-child(-n+3) {
    background-color: #F97161;
  }
  .role-info-box .box-header {
    border-bottom: 2px solid #9C27B0 !important;
  }
</style>
<div class="content-wrapper">
  <section class="content-header">
    <h1><i class="fa fa-list"></i> <?php echo htmlspecialchars($roleLabel); ?> DETAILS</h1>
    <ol class="breadcrumb">
      <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active"><?php echo htmlspecialchars($roleLabel); ?> Info</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary role-info-box">
          <div class="box-header with-border">
            <a href="<?php echo htmlspecialchars($registerFile); ?>" class="btn btn-primary">
              <i class="fa fa-plus"></i> Register <?php echo htmlspecialchars($roleLabel); ?>
            </a>
          </div>
          <div class="box-body table-responsive">
            <table id="roleTable" class="table table-bordered table-striped text-center" width="100%">
              <thead>
                <tr>
                  <th>Sr. No</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Department</th>
                  <th>Role</th>
                  <th>View</th>
                  <th>Edit</th>
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

<div id="viewModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display:none;">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h4 class="modal-title"><i class="fa fa-eye"></i> View <?php echo htmlspecialchars($roleLabel); ?></h4>
      </div>
      <div class="modal-body">
        <div id="view-dynamic-content"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display:none;">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h4 class="modal-title"><i class="fa fa-pencil"></i> Edit <?php echo htmlspecialchars($roleLabel); ?></h4>
      </div>
      <div class="modal-body">
        <div id="edit-dynamic-content"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
<script>
$(document).ready(function() {
  $('#roleTable').DataTable({
    processing: true,
    serverSide: true,
    pageLength: 15,
    order: [[0, 'asc']],
    ajax: {
      url: '<?php echo $ajaxFile; ?>',
      type: 'POST'
    },
    columnDefs: [
      { orderable: false, targets: [6, 7, 8] }
    ]
  });

  $(document).on('click', '.role-view-btn', function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    $('#view-dynamic-content').html('Loading...');
    $('#viewModal').modal('show');
    $.post('<?php echo $viewFile; ?>', { id: id }, function(data) {
      $('#view-dynamic-content').html(data);
    }).fail(function() {
      $('#view-dynamic-content').html('Unable to load data.');
    });
  });

  $(document).on('click', '.role-edit-btn', function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    $('#edit-dynamic-content').html('Loading...');
    $('#editModal').modal('show');
    $.post('<?php echo $editFile; ?>', { id: id }, function(data) {
      $('#edit-dynamic-content').html(data);
    }).fail(function() {
      $('#edit-dynamic-content').html('Unable to load data.');
    });
  });
});

function deleteRoleUser(userId) {
  if (!confirm('Are you sure you want to remove this record?')) {
    return;
  }
  $.ajax({
    url: '<?php echo $deleteFile; ?>',
    type: 'POST',
    dataType: 'json',
    data: { user_id: userId },
    success: function(resp) {
      if (resp.success) {
        $('#roleTable').DataTable().ajax.reload(null, false);
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
