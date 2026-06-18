<?php
require "header/header.php";
$user_type = $_SESSION['user_type'];
$selected = "";

/* $total_income = $db_handle->conn->query("SELECT SUM(total_fees) as total_income FROM dsms_fees_master")->fetch_assoc()['total_income'] ?? 0;
    $total_expenses = $db_handle->conn->query("SELECT SUM(total) as total_expenses FROM vouchers")->fetch_assoc()['total_expenses'] ?? 0;
    $net_balance = $total_income - $total_expenses;*/
$total_income = 5000;
$total_expenses = 6000;
$net_balance = 7000;
?>
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.2/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.2/main.min.js'></script>

<style>
  .table thead th {
    background-color: #0DF387;
    /* Green background for headers */
    color: #000;
    /* Black text for visibility */
  }

  .table tbody td {
    color: #000;
    /* Ensuring black text for data cells */
  }

  .table-responsive {
    width: 100%;
    overflow-x: auto;
    overflow-y: auto;
    max-height: 500px;
    /* Adjust this value as needed */
  }

  /* Custom styling for pagination controls */
  .dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 2px 8px;
    /* Adjust padding to shrink button size */
    margin: 2px;
    /* Adjust margin between buttons */
    font-size: 0.85em;
    /* Adjust font size */
    border: 1px solid #ddd;
    /* Add border */
    background-color: #f9f9f9;
    /* Background color */
    color: #333;
    /* Text color */
  }

  .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background-color: #e9e9e9;
    /* Background color on hover */
  }

  .dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background-color: #007bff;
    /* Background color for current page */
    color: white;
    /* Text color for current page */
  }

  .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
    background-color: #e9ecef;
    /* Background color for disabled button */
    color: #6c757d;
    /* Text color for disabled button */
  }
</style>
<?php
/*$income_query = "SELECT MONTH(date) as month, SUM(total_fees) as total_income FROM dsms_fees_master GROUP BY MONTH(date)";
$expense_query = "SELECT MONTH(created_at) as month, SUM(total) as total_expenses FROM vouchers GROUP BY MONTH(created_at)";
$income_result = $db_handle->conn->query($income_query);
$expense_result = $db_handle->conn->query($expense_query);

$income_data = [];
$expense_data = [];

while ($row = $income_result->fetch_assoc()) {
    $income_data[$row['month']] = $row['total_income'];
}

while ($row = $expense_result->fetch_assoc()) {
    $expense_data[$row['month']] = $row['total_expenses'];
}

// Make sure both income and expense arrays have data for all 12 months
for ($i = 1; $i <= 12; $i++) {
    $income_data[$i] = $income_data[$i] ?? 0;
    $expense_data[$i] = $expense_data[$i] ?? 0;

}*/
$income_data[0] =  0;
$expense_data[0] =  0;
?>

<script>
  var ctxBar = document.getElementById('barChart').getContext('2d');
  var barChart = new Chart(ctxBar, {
    type: 'bar',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
      datasets: [{
          label: 'Income',
          data: [<?php echo implode(',', $income_data); ?>],
          backgroundColor: '#4caf50'
        },
        {
          label: 'Expenses',
          data: [<?php echo implode(',', $expense_data); ?>],
          backgroundColor: '#f44336'
        }
      ]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>
<script>
  var ctxPie = document.getElementById('pieChart').getContext('2d');
  var pieChart = new Chart(ctxPie, {
    type: 'pie',
    data: {
      labels: ['Monthly Fees', 'Admission Fees', 'Late Fees', 'Extra Fees'],
      datasets: [{
        data: [<?php echo $fees_distribution['monthly_fee']; ?>, <?php echo $fees_distribution['admission_fee']; ?>, <?php echo $fees_distribution['late_fee']; ?>, <?php echo $fees_distribution['extra_fee']; ?>],
        backgroundColor: ['#4caf50', '#ffeb3b', '#2196f3', '#f44336'],
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'top',
        },
        tooltip: {
          callbacks: {
            label: function(tooltipItem) {
              return tooltipItem.label + ': ' + tooltipItem.raw;
            }
          }
        }
      }
    }
  });
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js">
</script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
  function showUser(str) {
    if (str == "") {
      document.getElementById("moneyreturn").innerHTML = "";
      return;
    } else {
      if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
      } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      }
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("moneyreturn").innerHTML = this.responseText;
        }
      };
      xmlhttp.open("GET", "getresult.php?q=" + str, true);
      xmlhttp.send();
    }
  }
</script>

<script>
  function showUser1(str) {
    if (str == "") {
      document.getElementById("txtHint").innerHTML = "";
      return;
    } else {
      if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
      } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      }
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("txtHint").innerHTML = this.responseText;
        }
      };
      xmlhttp.open("GET", "getresult1.php?q=" + str, true);
      xmlhttp.send();
    }
  }
</script>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <i class="fa fa-dashboard"></i><span> <strong><a href="index.php">DASHBOARD </a></strong></span>
      <small>Control panel</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Dashboard</li>
    </ol>
  </section>
  <?php if ($user_type == 0) { ?>
    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">

        <?php
        $result = $db_handle->conn->query("SELECT * fROM dsms_student_master where class IN(4,5,6,7) AND status='1'");
        $rowcount = mysqli_num_rows($result);
        ?>
        <div class="col-lg-3 col-xs-12">
          <!-- small box -->
          <div class="small-box bg-green-gradient">
            <div class="inner">
              <h3><?php echo $rowcount; ?></h3>
              <p>Students In College</p>
            </div>

            <div class="icon">
              <i class="ion ion-android-contacts"></i>
            </div>
            <a href="student-info.php" class="small-box-footer"><i class="fa fa-asterisk"></i></a>
          </div>
        </div>

        <!-- ./col -->
        <div class="col-lg-3 col-xs-12">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <?php
              $result = $db_handle->conn->query("SELECT * fROM dsms_student_master where class IN(1,2,3,11) AND status='1'");
              $rowcount = mysqli_num_rows($result);
              ?>
              <h3><?php echo $rowcount; ?></h3>

              <p>Students In School</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-stalker"></i>
            </div>
            <a href="student-info.php" class="small-box-footer"><i class="fa fa-asterisk"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-12">
          <!-- small box -->
          <div class="small-box bg-blue">
            <div class="inner">
              <?php
              $today_fee = $db_handle->conn->query("SELECT SUM(total_fees) as total_fee FROM dsms_fees_master WHERE DATE(date) = CURDATE();")->fetch_assoc()['total_fee'] ?? 0;

              ?>
              <h3><?php echo $today_fee; ?></h3>

              <p>Today's Fee Collection</p>
            </div>
            <div class="icon">
              <i class="icon ion-university"></i>
            </div>
            <a href="fee-summary.php" class="small-box-footer"><i class="fa fa-asterisk"></i></a>
          </div>
        </div>

        <div class="col-lg-3 col-xs-12">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <?php
              $total_expense = $db_handle->conn->query("SELECT SUM(total) as total_expense FROM vouchers WHERE DATE(created_at) = CURDATE();")->fetch_assoc()['total_expense'] ?? 0;


              ?>
              <h3><?php echo $total_expense; ?></h3>

              <p>Today's Expences</p>
            </div>
            <div class="icon">
              <i class="icon ion-university"></i>
            </div>
            <a href="expence_list.php" class="small-box-footer"><i class="fa fa-asterisk"></i></a>
          </div>
        </div>
      </div>

      <div class="row">
        <section class="col-lg-6 connectedSortable">
          <!-- Calendar -->
          <div class="box box-solid bg-aqua">
            <div class="box-header">
              <i class="fa fa-inr"></i>
              <h3 class="box-title">Financial Bar Chart</h3>
              <!-- tools box -->
              <div class="pull-right box-tools">
                <!-- button with a dropdown -->
                <button type="button" class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-success btn-sm" data-widget="remove"><i class="fa fa-times"></i>
                </button>
              </div>
              <!-- /. tools -->
            </div>
            <div class="box-body no-padding">
              <canvas id="barChart_fee"></canvas>
            </div>
          </div>
        </section>

        <section class="col-lg-6 connectedSortable">
          <!-- Calendar -->
          <div class="box box-solid bg-blue">
            <div class="box-header">
              <i class="fa fa-inr"></i>
              <h3 class="box-title">Financial Pie Chart</h3>
              <!-- tools box -->
              <div class="pull-right box-tools">
                <!-- button with a dropdown -->
                <button type="button" class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-success btn-sm" data-widget="remove"><i class="fa fa-times"></i>
                </button>
              </div>
              <!-- /. tools -->
            </div>
            <div class="box-body no-padding">
              <canvas id="pieChart_fee"></canvas>
            </div>
          </div>
        </section>
      </div>

      <div class="row">
        <section class="col-lg-12 connectedSortable">
          <!-- Calendar -->
          <div class="box box-solid bg-blue">
            <div class="box-header">
              <i class="fa fa-inr"></i>
              <h3 class="box-title">Financial Analysis</h3>
              <!-- tools box -->
              <div class="pull-right box-tools">
                <!-- button with a dropdown -->

                <button type="button" class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-success btn-sm" data-widget="remove"><i class="fa fa-times"></i>
                </button>
              </div>
              <!-- /. tools -->
            </div>
            <div class="box-body no-padding">
              <div class="table-responsive">
                <table id="myTable" class="display text-center table table-striped table-bordered" width="100%">
                  <thead>
                    <tr>
                      <th>Income</th>
                      <th>Expenses</th>
                      <th>Net Balance</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><?php echo $today_fee; ?></td>
                      <td><?php echo $total_expenses; ?></td>
                      <td><?php echo $today_fee - $total_expenses; ?></td>
                    </tr>
                  </tbody>
                </table>
              </div>

            </div>
          </div>
        </section>
      </div>

      <div class="row">
        <section class="col-lg-6 connectedSortable">
          <!-- Calendar -->
          <div class="box box-solid bg-gray">
            <div class="box-header">
              <i class="fa fa-inr"></i>
              <h3 class="box-title" style="color: #4caf50;">Data Analysis</h3>
              <!-- tools box -->
              <div class="pull-right box-tools">
                <!-- button with a dropdown -->
                <button type="button" class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-success btn-sm" data-widget="remove"><i class="fa fa-times"></i>
                </button>
              </div>
              <!-- /. tools -->
            </div>
            <div class="box-body no-padding">
              <canvas id="barChart"></canvas>
            </div>
          </div>
        </section>

        <section class="col-lg-6 connectedSortable">
          <!-- Calendar -->
          <div class="box box-solid bg-white">
            <div class="box-header">
              <i class="fa fa-inr"></i>
              <h3 class="box-title" style="color: #4caf50;">Student Admission Analysis</h3>
              <!-- tools box -->
              <div class="pull-right box-tools">
                <!-- button with a dropdown -->
                <button type="button" class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-success btn-sm" data-widget="remove"><i class="fa fa-times"></i>
                </button>
              </div>
              <!-- /. tools -->
            </div>
            <div class="box-body no-padding">
              <canvas id="lineChart"></canvas>
            </div>
          </div>
        </section>
      </div>
      <div class="row" style="display: flex; flex-wrap: wrap;">
        <section class="col-lg-6 connectedSortable">
          <div class="box box-info" style="display: flex; flex-direction: column; height: 100%;">
            <div class="box-header">
              <i class="fa fa-envelope"></i>
              <h3 class="box-title">Quick Email</h3>
              <div class="pull-right box-tools">
                <button type="button" class="btn btn-info btn-sm" data-widget="remove" data-toggle="tooltip" title="Remove">
                  <i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box box-info">
              <div class="box-header">
                <i class="fa fa-envelope"></i>
                <h3 class="box-title">Quick Email</h3>
              </div>

              <form action="#" method="POST">
                <div class="box-body">
                  <div class="form-group">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-at"></i></span>
                      <input type="email" class="form-control" name="emailto" placeholder="Email to:">
                    </div>
                    <input type="hidden" name="vemail" value="support@tcetmumbai.in">
                  </div>

                  <div class="form-group">
                    <input type="text" class="form-control" name="sub" placeholder="Subject">
                    <input type="hidden" name="name" value="Divine Grace High School">
                  </div>

                  <div class="form-group">
                    <textarea class="form-control" name="msg" placeholder="Message"
                      style="width: 100%; height: 150px; resize: none;"></textarea>
                  </div>
                </div>

                <div class="box-footer clearfix">
                  <button type="submit" class="pull-right btn btn-primary btn-flat" name="send">
                    SEND <i class="fa fa-arrow-circle-right"></i>
                  </button>
                </div>
              </form>
            </div>
          </div>
        </section>


        <section class="col-lg-6 connectedSortable">
          <div class="box box-solid box-default" style="border: 1px solid #eee; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">

            <div class="box-header with-border" style="background-color: #fcfcfc;">
              <i class="fa fa-calendar-o text-muted"></i>
              <h3 class="box-title" style="color: #333; font-weight: 600;">Event Calendar</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                  <i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove">
                  <i class="fa fa-times"></i>
                </button>
              </div>
            </div>

            <div class="box-body no-padding">
              <div id="calendar" style="width: 100%; min-height: 350px; padding: 10px;"></div>
            </div>

            <div class="box-footer clearfix">
              <button class="btn btn-sm btn-default btn-flat pull-right">View All Events</button>
            </div>
          </div>
        </section>
      <?php } else { ?>
        <section class="content">
          <!-- Small boxes (Stat box) -->
          <div class="row">

            <?php
            /* $result=$db_handle->conn->query("SELECT * fROM dsms_student_master where class='$class_id'");
          $rowcount=mysqli_num_rows($result);*/
            ?>
            <div class="col-lg-4 col-xs-12">
              <!-- small box -->
              <div class="small-box bg-green-gradient">
                <div class="inner">
                  <h3><?php echo "500"; ?></h3>
                  <p>Total Students</p>
                </div>

                <div class="icon">
                  <i class="ion ion-android-contacts"></i>
                </div>
                <a href="student-info.php" class="small-box-footer"><i class="fa fa-asterisk"></i></a>
              </div>
            </div>

            <!-- ./col -->
            <div class="col-lg-4 col-xs-12">
              <!-- small box -->
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3><?php echo "MCA"; ?></h3>

                  <p>Class Name</p>
                </div>
                <div class="icon">
                  <i class="ion ion-person-stalker"></i>
                </div>
                <a href="" class="small-box-footer"><i class="fa fa-asterisk"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-4 col-xs-12">
              <!-- small box -->
              <div class="small-box bg-blue">
                <div class="inner">
                  <?php
                  /*  $result=$db_handle->conn->query("SELECT * fROM message_master");
                 $rowcount=mysqli_num_rows($result);*/
                  ?>
                  <h3><?php echo "500"; ?></h3>

                  <p>Total Messages</p>
                </div>
                <div class="icon">
                  <i class="icon ion-university"></i>
                </div>
                <a href="" class="small-box-footer"><i class="fa fa-asterisk"></i></a>
              </div>
            </div>
          </div>


          <div class="row" style="display: flex; flex-wrap: wrap;">
            <section class="col-lg-6 connectedSortable" style="display: flex;">
              <div class="box box-info" style="display: flex; flex-direction: column; height: 100%;">
                <div class="box-header">
                  <i class="fa fa-envelope"></i>
                  <h3 class="box-title">Quick Email</h3>
                  <div class="pull-right box-tools">
                    <button type="button" class="btn btn-info btn-sm" data-widget="remove" data-toggle="tooltip" title="Remove">
                      <i class="fa fa-times"></i></button>
                  </div>
                </div>
                <form action="#" method="POST" style="flex-grow: 1; display: flex; flex-direction: column;">
                  <div class="box-body" style="flex-grow: 1; display: flex; flex-direction: column;">
                    <div class="form-group">
                      <input type="email" class="form-control" name="emailto" placeholder="Email to:">
                      <input type="hidden" class="form-control" name="vemail" value="dignityitsolution@gmail.com">
                    </div>
                    <div class="form-group">
                      <input type="text" class="form-control" name="sub" placeholder="Subject">
                      <input type="hidden" class="form-control" name="name" value="Sainath Hindi High School">
                    </div>
                    <div style="flex-grow: 1;">
                      <textarea class="textarea" name="msg" placeholder="Message"
                        style="width: 100%;font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" rows="10"></textarea>
                    </div>
                  </div>
                  <div class="box-footer clearfix">
                    <button type="submit" class="pull-right btn btn-primary" name="send">SEND
                      <i class="fa fa-arrow-circle-right"></i></button>
                  </div>
                </form>
              </div>
            </section>

            <section class="col-lg-6 connectedSortable" style="display: flex;">
              <!-- Calendar -->
              <div class="box box-solid bg-white" style="flex-grow: 1;">
                <div class="box-header">
                  <i class="fa fa-calendar"></i>
                  <h3 class="box-title" style="color: #000000;">Calendar</h3>
                  <!-- tools box -->
                  <div class="pull-right box-tools">
                    <!-- button with a dropdown -->
                    <button type="button" class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-success btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                  <!-- /. tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                  <!-- The calendar -->
                  <div id="calendar" style="width: 100%; height: 100%;"></div>
                </div>
                <!-- /.box-body -->
              </div>
              <!-- /.box -->
            </section>
          </div>
        <?php } ?>


      </div>
    </section>
    <?php
    // Fetch data for charts
    /*$studentCount = $db_handle->conn->query("SELECT COUNT(*) as count FROM dsms_student_master")->fetch_assoc()['count'];
$employeeCount = $db_handle->conn->query("SELECT COUNT(*) as count FROM dsms_employee_master")->fetch_assoc()['count'];
$classOneCount = $db_handle->conn->query("SELECT COUNT(*) as count FROM dsms_employee_master WHERE emp_type='grade1'")->fetch_assoc()['count'];
$teacherCount = $db_handle->conn->query("SELECT COUNT(*) as count FROM dsms_employee_master WHERE emp_type='2'")->fetch_assoc()['count'];*/
    ?>
    <?php
    /*$fees_distribution_query = "SELECT SUM(tution_fees) as monthly_fee, SUM(admission_fees) as admission_fee, SUM(exam) as late_fee, SUM(library) as extra_fee FROM dsms_fees_master";
$fees_distribution = $db_handle->conn->query($fees_distribution_query)->fetch_assoc();

$expense_distribution_query = "SELECT SUM(total) as expense_total FROM vouchers";
$expense_distribution = $db_handle->conn->query($expense_distribution_query)->fetch_assoc();*/
    ?>

    <?php
    /*$income_query = "SELECT MONTH(date) as month, SUM(total_fees) as total_income FROM dsms_fees_master GROUP BY MONTH(date)";
$expense_query = "SELECT MONTH(created_at) as month, SUM(total) as total_expenses FROM vouchers GROUP BY MONTH(created_at)";
$income_result = $db_handle->conn->query($income_query);
$expense_result = $db_handle->conn->query($expense_query);

$income_data = [];
$expense_data = [];

while ($row = $income_result->fetch_assoc()) {
    $income_data[$row['month']] = $row['total_income'];
}

while ($row = $expense_result->fetch_assoc()) {
    $expense_data[$row['month']] = $row['total_expenses'];
}

// Make sure both income and expense arrays have data for all 12 months
for ($i = 1; $i <= 12; $i++) {
    $income_data[$i] = $income_data[$i] ?? 0;
    $expense_data[$i] = $expense_data[$i] ?? 0;
}*/
    ?>

    <?php
    /*$fees_distribution_query = "SELECT SUM(tution_fees) as monthly_fee, SUM(admission_fees) as admission_fee, SUM(exam) as late_fee, SUM(library) as extra_fee FROM dsms_fees_master";
$fees_distribution = $db_handle->conn->query($fees_distribution_query)->fetch_assoc();

$expense_distribution_query = "SELECT SUM(total) as expense_total FROM vouchers";
$expense_distribution = $db_handle->conn->query($expense_distribution_query)->fetch_assoc();*/
    ?>
    <script>
      var ctxBar = document.getElementById('barChart').getContext('2d');
      var barChart = new Chart(ctxBar, {
        type: 'bar',
        data: {
          labels: ['Students', 'Employees', 'Class 1 Employees', 'Teachers'],
          datasets: [{
            label: 'Counts',
            data: [<?php echo "$studentCount, $employeeCount, $classOneCount, $teacherCount"; ?>],
            backgroundColor: ['#4caf50', '#ffeb3b', '#2196f3', '#f44336'],
          }]
        },
        options: {
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });

      var ctxLine = document.getElementById('lineChart').getContext('2d');
      var lineChart = new Chart(ctxLine, {
        type: 'line',
        data: {
          labels: ['JUNE', 'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER'],
          datasets: [{
            label: 'Student Enrollments',
            data: [30, 25, 40, 35],
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgb(137, 75, 192)',
            borderWidth: 1
          }]
        },
        options: {
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });
    </script>

    <script>
      var ctxPie = document.getElementById('pieChart').getContext('2d');
      var pieChart = new Chart(ctxPie, {
        type: 'pie',
        data: {
          labels: ['Students', 'Employees', 'Class 1 Employees', 'Teachers'],
          datasets: [{
            data: [<?php echo "$studentCount, $employeeCount, $classOneCount, $teacherCount"; ?>],
            backgroundColor: ['#4caf50', '#ffeb3b', '#2196f3', '#f44336'],
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: 'top',
            },
            tooltip: {
              callbacks: {
                label: function(tooltipItem) {
                  return tooltipItem.label + ': ' + tooltipItem.raw;
                }
              }
            }
          }
        }
      });
    </script>

    <script>
      var ctxBar = document.getElementById('barChart_fee').getContext('2d');
      var barChart_fee = new Chart(ctxBar, {
        type: 'bar',
        data: {
          labels: ['Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May'],
          datasets: [{
              label: 'Income',
              data: [<?php echo implode(',', $income_data); ?>],
              backgroundColor: '#4caf50'
            },
            {
              label: 'Expenses',
              data: [<?php echo implode(',', $expense_data); ?>],
              backgroundColor: '#f44336'
            }
          ]
        },
        options: {
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });
    </script>

    <script>
      var ctxPie = document.getElementById('pieChart_fee').getContext('2d');
      var pieChart = new Chart(ctxPie, {
        type: 'pie',
        data: {
          labels: ['Monthly Fees', 'Admission Fees', 'Late Fees', 'Extra Fees'],
          datasets: [{
            data: [3000, 2000, 1500, 1000],
            backgroundColor: ['#4caf50', '#ffeb3b', '#2196f3', '#f44336'],
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false
        }
      });

      document.getElementById('pieChart_fee').style.width = '250px';
      document.getElementById('pieChart_fee').style.height = '260px';
    </script>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth',
          initialDate: new Date(), // This sets the calendar to today's date
          headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
          },
          editable: true,
          selectable: true,
          height: 400, // Adjust the height as needed (in pixels)
          events: [] // You can populate this with event data if needed
        });

        calendar.render();
      });
    </script>


    <?php include "header/footer.php"; ?>