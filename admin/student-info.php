<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

require "../database/db_connect.php";
$db_handle = new DBController();

include "header/header.php";
?>

<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-user-plus"></i> ENROLLED STUDENT DETAILS
    </h1>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="wrapper2 box box-primary">
          <div class="div2 box-header with-border">
            <!-- Filter Section -->
            <div class="row" style="margin-bottom: 20px;">
              <div class="col-md-1">
                <label for="select_all" class="btn btn-default" style="width: 100%;">
                  <input type="checkbox" id="select_all"> ALL
                </label>
              </div>

              <div class="col-md-4">
                <button type="button" onclick="window.location.href='student_admission.php';" class="btn btn-primary btn-block">
                  <i class="fa fa-plus"></i> ENROLL NEW STUDENT
                </button>
              </div>

              <div class="col-md-2">
                <select class="form-control" id="select_class" name="select_class">
                  <option value="">Select Class</option>
                  <?php
                  $result = $db_handle->query("SELECT class_id AS id, class_name AS class FROM `lms_class_master`");
                  while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row['id'] . '">' . $row['class'] . '</option>';
                  }
                  ?>
                </select>
              </div>

              <div class="col-md-2">
                <select class="form-control" id="select_section" name="select_section">
                  <option value="">Select Division</option>
                  <?php
                  $result = $db_handle->query("SELECT * FROM `lms_section_master`");
                  while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row['id'] . '">' . $row['sections'] . '</option>';
                  }
                  ?>
                </select>
              </div>

              <div class="col-md-2">
                <select class="form-control" id="select_session" name="select_session">
                  <option value="">Select Session</option>
                  <?php
                  $result = $db_handle->query("SELECT * FROM `lms_session_master`");
                  while ($row = $result->fetch_assoc()) {
                    $selected = ($row['session_id'] == 6) ? "selected" : "";
                    echo '<option value="' . $row['session_id'] . '" ' . $selected . '>' . $row['session_name'] . '</option>';
                  }
                  ?>
                </select>
              </div>

              <div class="col-md-1">
                <button type="button" id="search" class="btn btn-primary btn-block">
                  <i class="fa fa-arrow-circle-right"></i>
                </button>
              </div>
            </div>

            <!-- Additional Filters -->
            <div class="row" style="margin-bottom: 20px;">
              <div class="col-md-3">
                <select class="form-control" id="select_academic_year" name="select_academic_year">
                  <option value="">Select Academic Year</option>
                  <?php
                  $batch_result = $db_handle->query("SELECT session_id, session_name FROM `lms_session_master` ORDER BY session_id DESC");
                  while ($row = $batch_result->fetch_assoc()) {
                    $selected = ($row['session_id'] == 1) ? 'selected' : '';
                    echo "<option value='{$row['session_id']}' {$selected}>{$row['session_name']}</option>";
                  }
                  ?>
                </select>
              </div>

              <div class="col-md-3">
                <select class="form-control" id="select_semester" name="select_semester">
                  <option value="">Select Semester</option>
                  <?php
                  $semester_result = $db_handle->query("SELECT semester_id, semester_name FROM `lms_semester_master` ORDER BY semester_id");
                  while ($row = $semester_result->fetch_assoc()) {
                    echo "<option value='{$row['semester_id']}'>{$row['semester_name']}</option>";
                  }
                  ?>
                </select>
              </div>

              <div class="col-md-3">
                <select class="form-control" id="select_department" name="select_department">
                  <option value="">Select Department</option>
                  <?php
                  $dept_result = $db_handle->query("SELECT department_id, department_name FROM `lms_department_master` ORDER BY department_name");
                  while ($row = $dept_result->fetch_assoc()) {
                    echo "<option value='{$row['department_id']}'>{$row['department_name']}</option>";
                  }
                  ?>
                </select>
              </div>

              <div class="col-md-2">
                <button type="button" onclick="fnExcelReport();" class="btn btn-success btn-block">
                  <i class="fa fa-print"></i> EXCEL
                </button>
              </div>

              <div class="col-md-1">
                <button type="button" onclick="bulkDelete()" class="btn btn-danger btn-block">
                  <i class="fa fa-trash"></i> Bulk
                </button>
              </div>
            </div>

            <!-- Data Table -->
            <div class="text-center table table-striped table-bordered" style="overflow-x:auto;">
              <table id="myTable" class="text-center table table-striped table-bordered" width="100%">
                <thead>
                  <tr>
                    <th style="background-color: #423cbc; color: white; padding: 16px" data-orderable="false"><input type="checkbox" id="select_all_header"></th>
                    <th style="background-color: #423cbc; color: white; padding: 16px">SR. NO</th>
                    <th style="background-color: #423cbc; color: white; padding: 16px">MESSAGE</th>
                    <th style="background-color: #423cbc; color: white; padding: 16px">Reg. No</th>
                    <th style="background-color: #423cbc; color: white; padding: 16px">Name</th>
                    <th style="background-color: #423cbc; color: white; padding: 16px">Class</th>
                    <th style="background-color: #423cbc; color: white; padding: 16px">Division</th>
                    <th style="background-color: #423cbc; color: white; padding: 16px">Academic Year</th>
                    <th style="background-color: #423cbc; color: white; padding: 16px">Semester</th>
                    <th style="background-color: #423cbc; color: white; padding: 16px">Department</th>
                    <th style="background-color: #423cbc; color: white; padding: 16px">Graduation Year</th> <!-- NEW COLUMN -->
                    <th style="background-color: #423cbc; color: white; padding: 16px">Mobile No</th>
                    <th style="background-color: #423cbc; color: white; padding: 16px">Roll No</th>
                    <th style="background-color: #423cbc; color: white; padding: 16px">Email</th>
                    <th style="background-color: #F97161; padding: 16px">View</th>
                    <th style="background-color: #F97161; padding: 16px">Edit</th>
                    <th style="background-color: #F97161; padding: 16px">Remove</th>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- Modals -->
<div id="view" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">
          <i class="glyphicon glyphicon-user"></i> Student Details
        </h4>
      </div>
      <div class="modal-body">
        <div id="modal-loader" style="display: none; text-align: center;">
          <img src="ajax-loader.gif">
        </div>
        <div id="dynamic-content"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div id="edit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">
          <i class="fa fa-pencil"></i> Edit Student Details
        </h4>
      </div>
      <div class="modal-body">
        <div id="edit-modal-loader" style="display: none; text-align: center;">
          <img src="ajax-loader.gif">
        </div>
        <div id="edit-dynamic-content"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>

<style>
  #myTable th,
  #myTable td {
    vertical-align: middle;
    white-space: nowrap;
    text-align: center;
  }

  #myTable td:nth-child(5) {
    text-align: left;
    white-space: normal;
    min-width: 150px;
  }
</style>

<script>
  // Global variable for DataTable
  var dataTable;

  // Delete single user
  function delete_user(id, table) {
    Swal.fire({
      title: "Are you sure?",
      text: "Once deleted, Student will be moved to left students!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes, delete it!",
      cancelButtonText: "No, cancel!",
      reverseButtons: true
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: 'student_delete.php',
          type: "POST",
          data: {
            id: id,
            table: table
          },
          dataType: "json",
          success: function(data) {
            if (data.status === 'success') {
              Swal.fire('Deleted!', 'Student details have been moved successfully!', 'success')
                .then(() => {
                  dataTable.ajax.reload();
                });
            } else {
              Swal.fire('Error!', data.message || 'There was a problem deleting the student.', 'error');
            }
          },
          error: function(error) {
            Swal.fire('Error!', 'There was a problem deleting the student.', 'error');
          }
        });
      }
    });
  }

  // Bulk delete
  function bulkDelete() {
    var selectedIds = [];
    $('.selectRow:checked').each(function() {
      selectedIds.push($(this).val());
    });

    if (selectedIds.length === 0) {
      Swal.fire('Warning!', 'Please select at least one student to delete.', 'warning');
      return;
    }

    Swal.fire({
      title: "Are you sure?",
      text: "You are about to delete " + selectedIds.length + " student(s). This action cannot be undone!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes, delete them!",
      cancelButtonText: "No, cancel!",
      reverseButtons: true
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: 'student_bulk_delete.php',
          type: "POST",
          data: {
            ids: selectedIds,
            table: 'lms_student_master'
          },
          dataType: "json",
          success: function(data) {
            if (data.status === 'success') {
              Swal.fire('Deleted!', data.message, 'success')
                .then(() => {
                  dataTable.ajax.reload();
                });
            } else {
              Swal.fire('Error!', data.message || 'There was a problem deleting the students.', 'error');
            }
          },
          error: function(error) {
            Swal.fire('Error!', 'There was a problem deleting the students.', 'error');
          }
        });
      }
    });
  }

  $(document).ready(function() {
    // Initialize DataTable
    if ($.fn.dataTable.isDataTable('#myTable')) {
      $('#myTable').DataTable().destroy();
    }

    dataTable = $('#myTable').DataTable({
      "processing": true,
      "serverSide": true,
      "ajax": {
        "url": "student_info_ajax.php",
        "type": "POST",
        "data": function(d) {
          d.select_class = $('#select_class').val();
          d.select_section = $('#select_section').val();
          d.select_session = $('#select_session').val();
          d.select_academic_year = $('#select_academic_year').val();
          d.select_semester = $('#select_semester').val();
          d.select_department = $('#select_department').val();
        }
      },
      "lengthMenu": [
        [15, 25, 50, 100, 500],
        [15, 25, 50, 100, 500]
      ],
      "pageLength": 15,
      "autoWidth": false,
      "scrollX": true,
      "columnDefs": [{
          "orderable": false,
          "targets": [0, 2, 14, 15, 16]
        },
        {
          "className": "text-left",
          "targets": [4]
        },
        {
          "className": "text-center",
          "targets": "_all"
        }
      ],
      "language": {
        "processing": "<span style='color:#8b0000;font-size:20px;'> Processing data.. <i class='fa fa-spinner fa-spin'></i> </span>",
        "search": "",
        "searchPlaceholder": "Search...",
        "paginate": {
          "previous": '<i class="fa fa-angle-double-left"></i> Previous',
          "next": 'Next <i class="fa fa-angle-double-right"></i>'
        }
      }
    });

    $('div.dataTables_filter input').addClass('form-control');
    $('div.dataTables_filter input').attr('placeholder', 'Search...');

    // Search button click
    $('#search').click(function() {
      dataTable.ajax.reload();
    });

    // Filter changes
    $('#select_class, #select_section, #select_session, #select_academic_year, #select_semester, #select_department').change(function() {
      dataTable.ajax.reload();
    });

    // Select All functionality
    $(document).on('click', '#select_all, #select_all_header', function() {
      var isChecked = $(this).is(':checked');
      $('.selectRow').prop('checked', isChecked);
    });
  });

  // View and Edit modals
  $(document).ready(function() {
    $(document).on('click', '.student_view', function(e) {
      e.preventDefault();
      var uid = $(this).data('id');
      $('#dynamic-content').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x"></i></div>');
      $('#view').modal('show');
      $.ajax({
        url: 'student_view.php',
        type: 'POST',
        data: 'id=' + uid,
        dataType: 'html'
      }).done(function(data) {
        $('#dynamic-content').html(data);
      }).fail(function() {
        $('#dynamic-content').html('<div class="alert alert-danger">Something went wrong, Please try again...</div>');
      });
    });

    $(document).on('click', '.student_edit', function(e) {
      e.preventDefault();
      var uid = $(this).data('id');
      $('#edit-dynamic-content').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x"></i></div>');
      $('#edit').modal('show');
      $.ajax({
        url: 'student-edit.php',
        type: 'POST',
        data: 'id=' + uid,
        dataType: 'html'
      }).done(function(data) {
        $('#edit-dynamic-content').html(data);
      }).fail(function() {
        $('#edit-dynamic-content').html('<div class="alert alert-danger">Something went wrong, Please try again...</div>');
      });
    });
  });

  // Excel Export
  function fnExcelReport() {
    var table = document.getElementById("myTable");
    var excludeCols = [0, 2, 14, 15, 16];
    var tableHTML = "<table border='1' style='border-collapse:collapse;'>";

    for (var i = 0; i < table.rows.length; i++) {
      tableHTML += "<tr>";
      var row = table.rows[i];
      for (var j = 0; j < row.cells.length; j++) {
        if (excludeCols.includes(j)) continue;
        var cell = row.cells[j];
        var tag = (i === 0) ? "th" : "td";
        var cellText = cell.innerText.trim();
        tableHTML += `<${tag} style="padding:5px;text-align:left;vertical-align:middle;">${cellText}</${tag}>`;
      }
      tableHTML += "</tr>";
    }
    tableHTML += "</table>";

    tableHTML = tableHTML.replace(/<a[^>]*>|<\/a>/gi, "");
    tableHTML = tableHTML.replace(/<img[^>]*>/gi, "");
    tableHTML = tableHTML.replace(/<input[^>]*>/gi, "");

    var blob = new Blob(['\ufeff', tableHTML], {
      type: 'application/vnd.ms-excel;charset=utf-8;'
    });
    var url = URL.createObjectURL(blob);
    var link = document.createElement("a");
    link.href = url;
    link.download = "student_details.xls";
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  }
</script>

<?php include "header/footer.php"; ?>