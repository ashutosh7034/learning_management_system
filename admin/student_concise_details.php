<?php include "header/header.php"; ?>
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>

<!-- DataTables + Buttons -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">

<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<style>
  .wrapper2 {
    border-radius: 12px;
    overflow: hidden;
  }

  .page-header-box {
    background: #dde4f5;
    padding: 5px 5px;
    border-radius: 5px;
    margin-bottom: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  }

  .page-header-box h1 {
    margin: 0;
    color: #060608;
  }

  table thead th {
    background: #1e3a8a !important;
    padding: 10px;
  }

  select.form-control {
    border-radius: 8px;
    border: 1px solid #3b82f6;
    height: 40px;
  }

  #resetFilters {
    background: #ea0e20;
    color: #fff;
    border: none;
    padding: 8px 14px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    transition: 0.3s;
  }

  #myTable thead th {
    background: #2563eb;
    color: #fff;
  }

  #myTable tbody tr:hover {
    background: #e0f2fe;
  }

  .table thead tr:first-child {
    position: sticky;
    top: 0;
    z-index: 5;
  }

  .dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: #2563eb !important;
    color: #fff !important;
  }

  .skeleton {
    height: 15px;
    background: linear-gradient(90deg, #eee, #ddd, #eee);
    margin: 8px 0;
    border-radius: 4px;
    animation: shimmer 1.5s infinite;
  }

  @keyframes shimmer {
    0% {
      background-position: -200px 0;
    }

    100% {
      background-position: 200px 0;
    }
  }

  @media (max-width:768px) {
    table thead tr {
      display: flex;
      flex-direction: column;
    }

    table thead th {
      width: 100%;
    }


    #resetFilters {
      width: 100%;
      margin-top: 5px;
    }

    #myTable {
      display: block;
      overflow-x: auto;
      white-space: nowrap;
    }

  }

  #exportData {
    background: #2563eb;
    color: #fff;
    border: none;
    padding: 8px 14px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    transition: 0.3s;
  }

  #exportData:hover {
    background: #1e40af;
  }
</style>
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-12">

        <div class="page-header-box">
          <h1>STUDENT CONCISE DETAILS</h1>
        </div>

        <div id="loadingSkeleton" style="display:none;">
          <div class="skeleton"></div>
          <div class="skeleton"></div>
          <div class="skeleton"></div>
        </div>

        <div class="wrapper2 box box-primary">

          <!-- FILTER ROW -->
          <table class="table table-bordered">
            <thead>
              <tr>

                <th>
                  <select class="form-control" id="select_class">
                    <option value="" hidden>Class</option>
                    <?php
                    $result = $db_handle->query("SELECT * FROM lms_class_master");
                    while ($row = $result->fetch_assoc()) {
                      echo '<option value="' . $row['class_id'] . '">' . $row['class_name'] . '</option>';
                    }
                    ?>
                  </select>
                </th>

                <th>
                  <select class="form-control" id="select_section">
                    <option value="" hidden>Division</option>
                    <?php
                    $result = $db_handle->query("SELECT * FROM lms_section_master");
                    while ($row = $result->fetch_assoc()) {
                      echo '<option value="' . $row['id'] . '">' . $row['sections'] . '</option>';
                    }
                    ?>
                  </select>
                </th>



                <?php if ($_SESSION['user_type'] == 1 || $_SESSION['user_type'] == 2) { ?>

                  <th>
                    <select class="form-control" id="select_department">
                      <option value="" hidden>Department</option>

                      <?php
                      $result = $db_handle->query("SELECT * FROM lms_department_master");

                      while ($row = $result->fetch_assoc()) {

                        echo '<option value="' . $row['department_id'] . '">'
                          . $row['department_name'] .
                          '</option>';
                      }
                      ?>
                    </select>
                  </th>

                <?php } ?>


                <th>
                  <button id="exportData" class="btn">Export data</button>
                  <button id="resetFilters" class="btn">Reset</button>
                </th>

              </tr>
            </thead>
          </table>

          <!-- DATA TABLE -->
          <table id="myTable" class="table table-bordered">
            <thead>
              <tr>
                <th>SR NO</th>
                <th>Class</th>
                <th>Division</th>
                <th>Department</th>

                <th>Student Count</th>
              </tr>
            </thead>
          </table>

        </div>
  </section>
</div>

<script>
  $(document).ready(function () {


    fetch_data();

    function fetch_data() {

      $('#myTable').DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        searching: false,

        pageLength: 10,
        lengthMenu: [[10, 25, 100], [10, 25, 100]],

        //   FIXED (ADD 'l' FOR DROPDOWN)
        dom: 'lBfrtip',

        buttons: [
          { extend: 'excelHtml5', text: 'Excel' },
          { extend: 'pdfHtml5', text: 'PDF' },
          { extend: 'csvHtml5', text: 'CSV' }
        ],

        ajax: {
          url: "student_concise_details_ajax.php",
          type: "POST",
          data: function (d) {
            d.select_class = $('#select_class').val();
            d.select_section = $('#select_section').val();
            d.select_department = $('#select_department').val();
          }
        }
      });
    }

    //   FILTER CHANGE (NO DUPLICATE CALLS)
    $('#select_class, #select_section, #select_department')
      .on('change', function () {
        $('#myTable').DataTable().destroy();
        fetch_data();
      });

    //   RESET (FIXED - RELOAD TABLE ALSO)
    $('#resetFilters').click(function () {
      $('#select_class, #select_section, #select_department').val('');
      $('#myTable').DataTable().destroy();
      fetch_data();
    });

    //   LOADING HANDLER (ATTACH AFTER INIT)
    $('#myTable').on('preXhr.dt', function () {
      $('#loadingSkeleton').show();
    });

    $('#myTable').on('xhr.dt', function () {
      $('#loadingSkeleton').hide();
    });

    //   EXPORT BUTTON
    $('#exportData').click(function () {

      let params = {
        export: true,
        select_class: $('#select_class').val(),
        select_section: $('#select_section').val(),
        select_department: $('#select_department').val()
      };

      $('#exportData').text('Exporting...');

      $.ajax({
        url: "student_concise_details_ajax.php",
        type: "POST",
        data: params,

        success: function (response) {

          let json = JSON.parse(response);
          let csv = '';

          
          csv += "SR NO,Class,Division,Department,Student Count\n";

          
          json.data.forEach(row => {
            csv += row.join(",") + "\n";
          });

          
          let blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
          let link = document.createElement("a");

          link.href = URL.createObjectURL(blob);
          link.download = "student_data.csv";
          link.click();

          $('#exportData').text('Export data');
        },

        error: function () {
          alert("Export failed!");
          $('#exportData').text('Export data');
        }
      });

    });

  });
</script>

<?php include "header/footer.php"; ?>