<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Include header (this already has database connection and session check)
require "header/header.php";
require_once __DIR__ . '/../includes/models/report_model.php';

// Authorization check: Admin or Super Admin only
if (!in_array((int)$usertype, [1, 2], true)) {
    echo "<script>window.location.href='../index.php';</script>";
    exit();
}

// Fetch LMS metrics and recent enrollments
$m = lms_admin_metrics();
$recent = lms_recent_enrollments(5);

// 1. Monthly Enrollments (last 6 months)
$monthly_res = mysqli_query($db_handle->conn, "
    SELECT DATE_FORMAT(enrolled_at, '%b %Y') AS month, COUNT(*) AS count
    FROM lms_enrollments
    WHERE deleted_at IS NULL
    GROUP BY DATE_FORMAT(enrolled_at, '%Y-%m')
    ORDER BY enrolled_at ASC
    LIMIT 6
");
$monthly_labels = [];
$monthly_counts = [];
while ($row = mysqli_fetch_assoc($monthly_res)) {
    $monthly_labels[] = $row['month'];
    $monthly_counts[] = (int) $row['count'];
}

// 2. Course Performance (top courses by avg progress)
$course_perf_res = mysqli_query($db_handle->conn, "
    SELECT c.title, COALESCE(AVG(cp.overall_pct), 0) AS avg_progress
    FROM lms_courses c
    JOIN lms_enrollments e ON e.course_id = c.id AND e.deleted_at IS NULL
    LEFT JOIN lms_course_progress cp ON cp.enrollment_id = e.id
    WHERE c.deleted_at IS NULL
    GROUP BY c.id
    ORDER BY avg_progress DESC
    LIMIT 5
");
$perf_labels = [];
$perf_pcts = [];
while ($row = mysqli_fetch_assoc($course_perf_res)) {
    $perf_labels[] = $row['title'];
    $perf_pcts[] = round((float) $row['avg_progress'], 1);
}

// 3. User Activity (login/action audit logs count by date for last 7 days)
$activity_res = mysqli_query($db_handle->conn, "
    SELECT DATE(performed_at) AS date, COUNT(*) AS count
    FROM lms_audit_log
    GROUP BY DATE(performed_at)
    ORDER BY date DESC
    LIMIT 7
");
$activity_labels = [];
$activity_counts = [];
while ($row = mysqli_fetch_assoc($activity_res)) {
    $activity_labels[] = date('d M', strtotime($row['date']));
    $activity_counts[] = (int) $row['count'];
}
$activity_labels = array_reverse($activity_labels);
$activity_counts = array_reverse($activity_counts);

// Calculate completion rate
$completion_rate = $m['enrollments'] > 0 ? ($m['certificates'] / $m['enrollments']) * 100 : 0.0;
?>

<style>
    :root {
        --card-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
        --border-radius: 8px;
    }
    .small-box {
        border-radius: var(--border-radius);
        box-shadow: var(--card-shadow);
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .small-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
    }
    .box {
        border-radius: var(--border-radius);
        box-shadow: var(--card-shadow);
        border: none;
        margin-bottom: 25px;
    }
    .box-header {
        border-bottom: 1px solid #f3f4f6;
        padding: 15px 20px;
    }
    .box-header .box-title {
        font-weight: 600;
        font-size: 16px;
        color: #1f2937;
    }
    .chart-container {
        position: relative;
        height: 260px;
        width: 100%;
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-dashboard"></i> <span><strong>Admin Dashboard</strong></span>
        </h1>
        <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>

    <section class="content">
        <!-- Metrics Cards -->
        <div class="row">
            <!-- Total Users Card -->
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><?php echo htmlspecialchars($m['users']); ?></h3>
                        <p>Total Users</p>
                    </div>
                    <div class="icon"><i class="fa fa-users"></i></div>
                    <a href="user_management.php" class="small-box-footer">Manage Users <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- Total Courses Card -->
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3><?php echo htmlspecialchars($m['courses']); ?></h3>
                        <p>Total Courses</p>
                    </div>
                    <div class="icon"><i class="fa fa-book"></i></div>
                    <a href="../teacher/courses.php" class="small-box-footer">Manage Courses <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- Enrollments Card -->
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3><?php echo htmlspecialchars($m['enrollments']); ?></h3>
                        <p>Enrollments</p>
                    </div>
                    <div class="icon"><i class="fa fa-graduation-cap"></i></div>
                    <a href="../teacher/enrollments.php" class="small-box-footer">View Enrollments <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- Completion Rate Card -->
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3><?php echo number_format($completion_rate, 1); ?>%</h3>
                        <p>Completion Rate</p>
                    </div>
                    <div class="icon"><i class="fa fa-certificate"></i></div>
                    <a href="reports.php" class="small-box-footer">View Reports <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row">
            <!-- Monthly Enrollments -->
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-bar-chart"></i> Monthly Enrollments</h3>
                    </div>
                    <div class="box-body">
                        <div class="chart-container">
                            <canvas id="monthlyEnrollmentsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Course Performance -->
            <div class="col-md-6">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-line-chart"></i> Course Performance (Avg. Progress)</h3>
                    </div>
                    <div class="box-body">
                        <div class="chart-container">
                            <canvas id="coursePerformanceChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- User Activity -->
            <div class="col-md-6">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-bolt"></i> Platform User Activity</h3>
                    </div>
                    <div class="box-body">
                        <div class="chart-container">
                            <canvas id="userActivityChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Recent Enrollments Table -->
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-clock-o"></i> Recent Enrollments</h3>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Course</th>
                                    <th>Progress</th>
                                    <th>Enrolled At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($recent)): ?>
                                    <tr><td colspan="4" class="text-center text-muted">No enrollments yet.</td></tr>
                                <?php else: foreach ($recent as $r): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($r['student_name'] ?: 'Student'); ?></strong></td>
                                        <td><?php echo htmlspecialchars($r['course_title']); ?></td>
                                        <td>
                                            <div class="progress progress-xs" style="margin-bottom:0; margin-top:5px; width:70px; display:inline-block; vertical-align:middle;">
                                                <div class="progress-bar progress-bar-success" style="width: <?php echo (float)$r['overall_pct']; ?>%"></div>
                                            </div>
                                            <span style="font-size:12px; margin-left:5px;"><?php echo number_format((float)$r['overall_pct'], 0); ?>%</span>
                                        </td>
                                        <td><small class="text-muted"><?php echo date('d M Y, h:i A', strtotime($r['enrolled_at'])); ?></small></td>
                                    </tr>
                                <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Chart.js and rendering scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Monthly Enrollments Chart
    var ctxMonthly = document.getElementById('monthlyEnrollmentsChart');
    if (ctxMonthly) {
        new Chart(ctxMonthly, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($monthly_labels); ?>,
                datasets: [{
                    label: 'Enrollments',
                    data: <?php echo json_encode($monthly_counts); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }

    // 2. Course Performance Chart
    var ctxPerf = document.getElementById('coursePerformanceChart');
    if (ctxPerf) {
        new Chart(ctxPerf, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($perf_labels); ?>,
                datasets: [{
                    label: 'Avg Progress %',
                    data: <?php echo json_encode($perf_pcts); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.7)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        beginAtZero: true,
                        max: 100
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }

    // 3. User Activity Chart
    var ctxActivity = document.getElementById('userActivityChart');
    if (ctxActivity) {
        new Chart(ctxActivity, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($activity_labels); ?>,
                datasets: [{
                    label: 'Actions Logged',
                    data: <?php echo json_encode($activity_counts); ?>,
                    borderColor: 'rgba(255, 159, 64, 1)',
                    backgroundColor: 'rgba(255, 159, 64, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }
});
</script>

<?php include "header/footer.php"; ?>