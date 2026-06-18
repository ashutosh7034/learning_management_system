<?php
require "header/header.php";

// ========================
// 1. DUMMY DATA FOR HOD TABLE
// ========================
$departments_hod = [
    1 => ['dept' => 'Computer Science (CSE)', 'hod_name' => 'Dr. Arvind Kumar', 'phone' => '+91-9876543210', 'status' => 'Available'],
    2 => ['dept' => 'Information Technology (IT)', 'hod_name' => 'Prof. Meera Sharma', 'phone' => '+91-9876543211', 'status' => 'In Meeting'],
    3 => ['dept' => 'Artificial Intelligence & ML', 'hod_name' => 'Dr. Rahul Verma', 'phone' => '+91-9876543212', 'status' => 'Available'],
    4 => ['dept' => 'AI & Data Science', 'hod_name' => 'Prof. Sneha Patil', 'phone' => '+91-9876543213', 'status' => 'On Leave'],
    5 => ['dept' => 'Mechanical Engineering', 'hod_name' => 'Dr. Suresh Nair', 'phone' => '+91-9876543214', 'status' => 'Available'],
    6 => ['dept' => 'Civil Engineering', 'hod_name' => 'Prof. Anita Desai', 'phone' => '+91-9876543215', 'status' => 'In Meeting'],
    7 => ['dept' => 'Electrical Engineering', 'hod_name' => 'Dr. Manoj Gupta', 'phone' => '+91-9876543216', 'status' => 'Available'],
];

// 2. Fetch Top Summary Cards Data (DUMMY DATA)
$total_students = 520;
$total_users = 525;
$total_branches = 7;
$total_staff = 21;

// 3. Fetch Specialization Overview Data
$spec_data = [
    1 => ['name' => 'Honours Degree', 'total' => 220, 'approved' => 180, 'rejected' => 10, 'pending' => 30],
    2 => ['name' => 'Minor Degree', 'total' => 280, 'approved' => 220, 'rejected' => 15, 'pending' => 45],
    3 => ['name' => 'Honours with Research', 'total' => 140, 'approved' => 120, 'rejected' => 5, 'pending' => 15]
];

$spec_labels = [];
$spec_totals = [];
foreach ($spec_data as $sd) {
    $spec_labels[] = '"' . $sd['name'] . '"';
    $spec_totals[] = $sd['approved'];
}

// 4. Branch-wise Distribution (DUMMY DATA)
$branch_labels = ['"CSE"', '"IT"', '"AIML"', '"AIDS"', '"Mechanical"', '"Civil"', '"Electrical"'];
$branch_counts = [150, 120, 90, 80, 40, 20, 20];

// 5. User Roles Overview (DUMMY DATA)
$roles_data = [
    ['role_name' => 'Super Admin', 'count' => 1],
    ['role_name' => 'Admin', 'count' => 3],
    ['role_name' => 'Coordinator', 'count' => 6],
    ['role_name' => 'Mentor', 'count' => 15],
    ['role_name' => 'Student', 'count' => 500]
];

// 6. Recent Activity (Latest 5 registered students)
$recent_students = [
    ['fname' => 'Rahul', 'lname' => 'Sharma', 'registration_no' => 'Honors Allocation', 'created_at' => date('Y-m-d H:i:s', strtotime('-1 hours'))],
    ['fname' => 'System', 'lname' => '', 'registration_no' => 'App Approval', 'created_at' => date('Y-m-d H:i:s', strtotime('-3 hours'))],
    ['fname' => 'Priya', 'lname' => 'Singh', 'registration_no' => 'New Registration', 'created_at' => date('Y-m-d H:i:s', strtotime('-5 hours'))],
    ['fname' => 'Amit', 'lname' => 'Patel', 'registration_no' => 'Minor Allocation', 'created_at' => date('Y-m-d H:i:s', strtotime('-1 day'))],
    ['fname' => 'Neha', 'lname' => 'Gupta', 'registration_no' => 'Research Proposal', 'created_at' => date('Y-m-d H:i:s', strtotime('-2 days'))]
];

// 7. Academic Events (DUMMY DATA)
$current_year_month = date('Y-m');
$academic_events = [
    ['title' => 'Honors Application Deadline', 'start' => $current_year_month . '-22', 'type' => 'Deadline', 'color' => '#dd4b39', 'icon' => 'fa-warning'],
    ['title' => 'Mentor Review Meeting', 'start' => $current_year_month . '-25T10:00:00', 'type' => 'Meeting', 'color' => '#00c0ef', 'icon' => 'fa-users'],
    ['title' => 'Minor Approval Process', 'start' => $current_year_month . '-28', 'end' => $current_year_month . '-30', 'type' => 'Review', 'color' => '#f39c12', 'icon' => 'fa-search'],
    ['title' => 'Research Proposal Submission', 'start' => date('Y-m', strtotime('+1 month')) . '-05', 'type' => 'Deadline', 'color' => '#dd4b39', 'icon' => 'fa-warning'],
    ['title' => 'Coordination Committee Meeting', 'start' => date('Y-m', strtotime('+1 month')) . '-10T14:00:00', 'type' => 'Meeting', 'color' => '#00a65a', 'icon' => 'fa-users']
];

// Handle Edit Action (POST)
$edit_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_hod'])) {
    $dept_id = intval($_POST['dept_id']);
    $new_hod = htmlspecialchars($_POST['hod_name']);
    $new_phone = htmlspecialchars($_POST['phone']);
    $new_status = htmlspecialchars($_POST['status']);

    if (isset($departments_hod[$dept_id])) {
        $departments_hod[$dept_id]['hod_name'] = $new_hod;
        $departments_hod[$dept_id]['phone'] = $new_phone;
        $departments_hod[$dept_id]['status'] = $new_status;
        $edit_message = '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-check"></i> HOD details updated successfully!</div>';
    } else {
        $edit_message = '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-ban"></i> Department not found!</div>';
    }
}
?>

<!-- Font Awesome & Ionicons for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

<style>
    /* ========== IMPROVED CSS FOR MODERN DASHBOARD ========== */
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --card-shadow: 0 10px 20px rgba(0, 0, 0, 0.08), 0 6px 6px rgba(0, 0, 0, 0.1);
        --hover-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.15);
        --border-radius-lg: 8px;
        --border-radius-md: 6px;
    }

    /* Modern Card Styling */
    .small-box {
        border-radius: var(--border-radius-lg);
        box-shadow: var(--card-shadow);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        position: relative;
    }

    .small-box:hover {
        transform: translateY(-5px);
        box-shadow: var(--hover-shadow);
    }

    .small-box .inner {
        padding: 20px;
    }

    .small-box h3 {
        font-size: 38px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .small-box p {
        font-size: 15px;
        opacity: 0.9;
        margin-bottom: 0;
    }

    .small-box .icon {
        font-size: 70px;
        top: 15px;
        right: 10px;
        opacity: 0.25;
        transition: all 0.3s ease;
    }

    .small-box:hover .icon {
        transform: scale(1.1);
        opacity: 0.35;
    }

    /* Box styling */
    .box {
        border-radius: var(--border-radius-md);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        margin-bottom: 25px;
        border: none;
    }

    .box:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .box-header {
        border-bottom: 2px solid #f0f2f5;
        padding: 15px 20px;
    }

    .box-header .box-title {
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
    }

    /* Table styling */
    .table {
        margin-bottom: 0;
    }

    .table thead tr th {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 0.5px;
        padding: 12px 15px;
        border-bottom: 2px solid #dee2e6;
    }

    .table tbody tr {
        transition: background-color 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: #f8f9fc;
    }

    .table td {
        vertical-align: middle;
        padding: 12px 15px;
    }

    /* Status Badges */
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 11px;
        display: inline-block;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .status-available {
        background: linear-gradient(135deg, #00b09b, #96c93d);
        color: white;
        box-shadow: 0 2px 5px rgba(0, 176, 155, 0.3);
    }

    .status-meeting {
        background: linear-gradient(135deg, #f2994a, #f2c94c);
        color: white;
    }

    .status-leave {
        background: linear-gradient(135deg, #eb3349, #f45c43);
        color: white;
    }

    /* Action Buttons */
    .btn-action {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border: none;
        padding: 6px 14px;
        border-radius: 25px;
        color: white;
        font-size: 12px;
        font-weight: 500;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-action:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .btn-group{
        gap: 4px;
    }

    .btn{
        margin: 2px;
    }

    /* HOD Table special styling */
    .hod-table-container {
        background: white;
        border-radius: var(--border-radius-md);
        overflow: hidden;
        margin-top: 20px;
    }

    /* Modal styling */
    .modal-content {
        border-radius: var(--border-radius-lg);
        border: none;
    }

    .modal-header {
        background: var(--primary-gradient);
        color: white;
        border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0;
        padding: 20px;
    }

    .modal-header .close {
        color: white;
        opacity: 0.8;
    }

    .modal-header .close:hover {
        opacity: 1;
    }

    /* Chart containers */
    .chart-container {
        position: relative;
        height: 250px;
        width: 100%;
        margin: 0 auto;
    }

    /* Event list styling */
    .event-list>li>a {
        padding: 12px 15px;
        border-bottom: 1px solid #f4f4f4;
        transition: all 0.2s ease;
        display: block;
        color: #444;
    }

    .event-list>li>a:hover {
        background-color: #f9fafc;
        transform: scale(1.02);
        text-decoration: none;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .small-box h3 {
            font-size: 28px;
        }

        .box-header .box-title {
            font-size: 16px;
        }

        .table td,
        .table th {
            padding: 8px 10px;
            font-size: 13px;
        }
    }

    /* Counter animation */
    .counter {
        animation: fadeInUp 0.6s ease;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-dashboard"></i> <span><strong>Specialization Tracker Dashboard</strong></span>
        </h1>
        <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>

    <section class="content">
        <!-- Display edit message -->
        <?php echo $edit_message; ?>


        <!-- ==================== DASHBOARD STATS (from original) ==================== -->
        <div class="row" style="margin-top: 20px;">
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3 class="counter" data-target="<?php echo $total_students; ?>">0</h3>
                        <p>Total Students</p>
                    </div>
                    <div class="icon"><i class="ion ion-android-contacts"></i></div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3 class="counter" data-target="<?php echo $total_users; ?>">0</h3>
                        <p>Total Users</p>
                    </div>
                    <div class="icon"><i class="ion ion-ios-people"></i></div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3 class="counter" data-target="<?php echo $total_branches; ?>">0</h3>
                        <p>Total Branches</p>
                    </div>
                    <div class="icon"><i class="ion ion-ios-book"></i></div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3 class="counter" data-target="<?php echo $total_staff; ?>">0</h3>
                        <p>Mentors & Coordinators</p>
                    </div>
                    <div class="icon"><i class="ion ion-person-stalker"></i></div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        <!-- Specialization Overview & Application Status -->
        <div class="row">
            <div class="col-md-5">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-pie-chart"></i> Specialization Overview</h3>
                    </div>
                    <div class="box-body">
                        <div class="chart-container" style="max-width: 300px;">
                            <canvas id="specializationChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-list-alt"></i> Application Status Tracking</h3>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover table-bordered text-center">
                            <thead class="bg-gray">
                                <tr>
                                    <th>Specialization</th>
                                    <th>Total Applied</th>
                                    <th>Approved</th>
                                    <th>Pending</th>
                                    <th>Rejected</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($spec_data as $id => $sd): ?>
                                    <tr>
                                        <td class="text-left"><strong><?php echo $sd['name']; ?></strong></td>
                                        <td><span class="counter" data-target="<?php echo $sd['total']; ?>">0</span></td>
                                        <td><span class="status-badge status-available counter" data-target="<?php echo $sd['approved']; ?>">0</span></td>
                                        <td><span class="status-badge status-meeting counter" data-target="<?php echo $sd['pending']; ?>">0</span></td>
                                        <td>
                                            <?php if ($sd['rejected'] > 0): ?>
                                                <span class="status-badge status-leave" style="cursor: pointer;" onclick="alert('Rejected: <?php echo $sd['rejected']; ?> students')">
                                                    <span class="counter" data-target="<?php echo $sd['rejected']; ?>">0</span> <i class="fa fa-external-link"></i>
                                                </span>
                                            <?php else: ?>
                                                <span class="status-badge" style="background: #95a5a6; color: white;">0</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <!-- Add above HOD table -->
                        <div class="row" style="margin-bottom: 15px;">
                            <div class="col-xs-12">
                                <div class="btn-group" role="group">

                                    <button type="button" class="btn btn-primary" onclick="quickAction('export_hod')">
                                        <i class="fa fa-download"></i> Export Student List
                                    </button>
                                    <button type="button" class="btn btn-reset" onclick="quickAction('print_report')">
                                        <i class="fa fa-print"></i> Print Report
                                    </button>
                                    <button type="button" class="btn btn-default" onclick="quickAction('send_bulk_email')">
                                        <i class="fa fa-envelope-o"></i> Bulk Email HODs
                                    </button>
                                    <button type="button" class="btn btn-default" onclick="quickAction('add_hod')">
                                        <i class="fa fa-plus-circle text-green"></i> Add New HOD
                                    </button>
                                    <button type="button" class="btn btn-default" onclick="quickAction('add_hod')">
                                        <i class="fa fa-plus-circle text-green"></i> Add Student
                                    </button>
                                </div>
                            </div>
                        </div>

                        <script>
                            // Real-time search for HOD table
                            document.getElementById('hodSearch')?.addEventListener('keyup', function() {
                                let filter = this.value.toLowerCase();
                                let rows = document.querySelectorAll('.hod-table-container tbody tr');
                                rows.forEach(row => {
                                    let text = row.textContent.toLowerCase();
                                    row.style.display = text.includes(filter) ? '' : 'none';
                                });
                            });

                            function quickAction(action) {
                                switch (action) {
                                    case 'export_hod':
                                        // Export table to CSV
                                        let csv = [];
                                        let rows = document.querySelectorAll('.hod-table-container table tr');
                                        rows.forEach(row => {
                                            let rowData = [];
                                            row.querySelectorAll('td, th').forEach(cell => rowData.push(cell.innerText));
                                            csv.push(rowData.join(','));
                                        });
                                        let blob = new Blob([csv.join('\n')], {
                                            type: 'text/csv'
                                        });
                                        let link = document.createElement('a');
                                        link.href = URL.createObjectURL(blob);
                                        link.download = 'hod_list.csv';
                                        link.click();
                                        showToast('📊 HOD list exported!', 'success');
                                        break;
                                    case 'print_report':
                                        window.print();
                                        break;
                                    case 'send_bulk_email':
                                        let emails = [];
                                        document.querySelectorAll('.hod-table-container tbody tr').forEach(row => {
                                            let phone = row.cells[2]?.innerText;
                                            if (phone) emails.push(phone);
                                        });
                                        alert('Bulk email to HODs: Coming soon!\nWill send to: ' + emails.length + ' HODs');
                                        break;
                                    case 'add_hod':
                                        alert('Add new HOD form - Feature coming soon');
                                        break;
                                }
                            }
                        </script>
                    </div>
                </div>
            </div>
        </div>

        <!-- Branch & User Overview Row -->
        <div class="row">
            <div class="col-md-8">
                <!-- Quick Email Section - Matches the design shown in the image -->
                <section class="connectedSortable">
                    <div class="box box-info" style="display: flex; flex-direction: column; height: 100%;">
                        <div class="box-header">
                            <i class="fa fa-envelope"></i>
                            <h3 class="box-title">Quick Email</h3>
                            <div class="pull-right box-tools">
                                <button type="button" class="btn btn-info btn-sm" data-widget="remove" data-toggle="tooltip" title="Remove">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <?php
                        // Email sending logic
                        $email_status = '';
                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_email'])) {
                            $to = trim($_POST['emailto']);
                            $subject = trim($_POST['subject']);
                            $message = trim($_POST['message']);
                            $from_email = "noreply@tcetmumbai.in"; // Default from email

                            // Basic validation
                            if (empty($to) || empty($subject) || empty($message)) {
                                $email_status = '<div class="alert alert-danger alert-dismissible" style="margin: 10px 15px 0 15px;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <i class="icon fa fa-ban"></i> Please fill all fields (To, Subject, Message)!
                </div>';
                            } elseif (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
                                $email_status = '<div class="alert alert-danger alert-dismissible" style="margin: 10px 15px 0 15px;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <i class="icon fa fa-ban"></i> Please enter a valid email address!
                </div>';
                            } else {
                                // Email headers
                                $headers = "MIME-Version: 1.0" . "\r\n";
                                $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
                                $headers .= "From: " . $from_email . "\r\n";
                                $headers .= "Reply-To: " . $to . "\r\n";
                                $headers .= "X-Mailer: PHP/" . phpversion();

                                // Format message as HTML
                                $html_message = '<html><body>';
                                $html_message .= '<h3>New Message from Dashboard</h3>';
                                $html_message .= '<p><strong>Message:</strong></p>';
                                $html_message .= '<p>' . nl2br(htmlspecialchars($message)) . '</p>';
                                $html_message .= '<hr>';
                                $html_message .= '<p><small>Sent via TCET Dashboard</small></p>';
                                $html_message .= '</body></html>';

                                // Send email
                                if (mail($to, $subject, $html_message, $headers)) {
                                    $email_status = '<div class="alert alert-success alert-dismissible" style="margin: 10px 15px 0 15px;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="icon fa fa-check"></i> Email sent successfully to ' . htmlspecialchars($to) . '!
                    </div>';
                                } else {
                                    $email_status = '<div class="alert alert-warning alert-dismissible" style="margin: 10px 15px 0 15px;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="icon fa fa-warning"></i> Email could not be sent. Please check your server mail configuration.
                    </div>';
                                }
                            }
                        }

                        // Display status message
                        echo $email_status;
                        ?>

                        <div class="box box-info" style="margin-bottom: 0; border-top: none; box-shadow: none;">
                            <div class="box-header with-border" style="background: #f9f9f9;">
                                <i class="fa fa-envelope-o"></i>
                                <h3 class="box-title">Compose New Message</h3>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                </div>
                            </div>

                            <form action="#" method="POST" id="quickEmailForm">
                                <div class="box-body" style="padding: 20px;">
                                    <!-- To Field -->
                                    <div class="form-group">
                                        <label><i class="fa fa-at text-info"></i> To:</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                            <input type="email" class="form-control" name="emailto" id="emailto"
                                                placeholder="recipient@example.com" required>
                                        </div>
                                        <small class="text-muted">Enter the recipient's email address</small>
                                    </div>

                                    <!-- CC Field (Optional) -->
                                    <div class="form-group">
                                        <label><i class="fa fa-copy text-info"></i> CC (Optional):</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-users"></i></span>
                                            <input type="email" class="form-control" name="emailcc" id="emailcc"
                                                placeholder="cc@example.com (optional)">
                                        </div>
                                    </div>

                                    <!-- Subject Field -->
                                    <div class="form-group">
                                        <label><i class="fa fa-tag text-info"></i> Subject:</label>
                                        <input type="text" class="form-control" name="subject" id="subject"
                                            placeholder="Enter email subject" required>
                                    </div>

                                    <!-- Message Field -->
                                    <div class="form-group">
                                        <label><i class="fa fa-file-text text-info"></i> Message:</label>
                                        <textarea class="form-control" name="message" id="message"
                                            placeholder="Type your message here..."
                                            style="width: 100%; height: 180px; resize: vertical;" required></textarea>
                                    </div>

                                    <!-- Quick Templates -->
                                    <div class="form-group">
                                        <label><i class="fa fa-magic text-info"></i> Quick Templates:</label>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-default" onclick="insertTemplate('meeting')">
                                                <i class="fa fa-calendar"></i> Meeting
                                            </button>
                                            <button type="button" class="btn btn-default" onclick="insertTemplate('deadline')">
                                                <i class="fa fa-clock-o"></i> Deadline
                                            </button>
                                            <button type="button" class="btn btn-default" onclick="insertTemplate('approval')">
                                                <i class="fa fa-check-circle"></i> Approval
                                            </button>
                                            <button type="button" class="btn btn-default" onclick="insertTemplate('reminder')">
                                                <i class="fa fa-bell"></i> Reminder
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="box-footer clearfix" style="background: #f9f9f9; border-top: 1px solid #f0f0f0;">
                                    <div class="pull-left">
                                        <button type="reset" class="btn btn-default btn-flat" onclick="resetForm()">
                                            <i class="fa fa-refresh"></i> Reset
                                        </button>
                                    </div>
                                    <button type="submit" name="send_email" class="pull-right btn btn-primary btn-flat">
                                        <i class="fa fa-paper-plane"></i> Send Email
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Recent Email History (Optional) -->
                        <div class="box-footer" style="border-top: 1px solid #f0f0f0; background: white;">
                            <div class="row">
                                <div class="col-xs-12">
                                    <p class="text-muted" style="margin-bottom: 5px;">
                                        <i class="fa fa-history"></i> <strong>Recent Activity</strong>
                                    </p>
                                    <ul class="list-unstyled" style="margin-bottom: 0; font-size: 12px;">
                                        <li><i class="fa fa-check-circle text-success"></i> Support ticket system active</li>
                                        <li><i class="fa fa-envelope text-info"></i> Auto-replies configured for HODs</li>
                                        <li><i class="fa fa-database text-warning"></i> Email logs: Last 7 days</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div class="col-md-4">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-users"></i> User Roles Overview</h3>
                    </div>
                    <div class="box-body no-padding">
                        <table class="table table-striped">
                            <tbody>
                                <?php foreach ($roles_data as $role): ?>
                                    <tr>
                                        <td><?php echo $role['role_name']; ?></td>
                                        <td><span class="badge bg-blue pull-right counter" data-target="<?php echo $role['count']; ?>">0</span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-clock-o"></i> Recent Student Registrations</h3>
                    </div>
                    <div class="box-body">
                        <ul class="products-list product-list-in-box">
                            <?php foreach ($recent_students as $student): ?>
                                <li class="item">
                                    <div class="product-info" style="margin-left: 0;">
                                        <a href="javascript:void(0)" class="product-title"><?php echo $student['fname'] . ' ' . $student['lname']; ?>
                                            <span class="label label-success pull-right"><?php echo $student['registration_no']; ?></span>
                                        </a>
                                        <span class="product-description">
                                            Registered on <?php echo date('d M Y, h:i A', strtotime($student['created_at'])); ?>
                                        </span>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-clock-o"></i> Recent Staff Registrations</h3>
                    </div>
                </div>
            </div>
        </div>


        <style>
            /* Additional styling for Quick Email section */
            .quick-email-section .form-group label {
                font-weight: 600;
                font-size: 13px;
                margin-bottom: 5px;
                color: #333;
            }

            .quick-email-section .input-group-addon {
                background-color: #f4f4f4;
                border: 1px solid #d2d6de;
            }

            .quick-email-section .form-control:focus {
                border-color: #3c8dbc;
                box-shadow: 0 0 0 1px rgba(60, 141, 188, 0.2);
            }

            .btn-group .btn-default {
                background: #f4f4f4;
                border-color: #ddd;
                transition: all 0.2s;
            }

            .btn-group .btn-default:hover {
                background: #3c8dbc;
                color: white;
                border-color: #367fa9;
            }

            #quickEmailForm textarea {
                font-family: inherit;
                line-height: 1.5;
            }
        </style>

        <script>
            // Quick template insertion function
            function insertTemplate(type) {
                var messageField = document.getElementById('message');
                var subjectField = document.getElementById('subject');
                var currentMsg = messageField.value;
                var template = '';
                var subjectTemplate = '';

                switch (type) {
                    case 'meeting':
                        subjectTemplate = 'Meeting Schedule - Department Coordination';
                        template = 'Dear Team,\n\nI hope this message finds you well. I would like to schedule a meeting to discuss the following agenda items:\n\n1. Department updates\n2. Student progress review\n3. Upcoming deadlines\n4. Any other business\n\nPlease let me know your availability for this meeting.\n\nBest regards,\nHOD Office';
                        break;
                    case 'deadline':
                        subjectTemplate = 'Important Deadline Reminder';
                        template = 'Dear All,\n\nThis is a gentle reminder about the upcoming deadline. Please ensure all required documents and submissions are completed by the due date.\n\nIf you have any questions or need clarification, please reach out at your earliest convenience.\n\nThank you for your attention to this matter.\n\nRegards,\nDepartment Administration';
                        break;
                    case 'approval':
                        subjectTemplate = 'Request for Approval';
                        template = 'Dear Sir/Madam,\n\nI am writing to kindly request your approval for the attached proposal/document. The request has been reviewed and is ready for your consideration.\n\nPlease let me know if you require any additional information or have any questions regarding this request.\n\nThank you for your time and consideration.\n\nBest regards,\n[Your Name]';
                        break;
                    case 'reminder':
                        subjectTemplate = 'Friendly Reminder';
                        template = 'Dear All,\n\nThis is a friendly reminder regarding the pending tasks/assignments. Kindly complete the necessary actions at your earliest convenience.\n\nShould you need any assistance, please don\'t hesitate to reach out.\n\nThank you for your cooperation.\n\nBest regards,\nSupport Team';
                        break;
                }

                if (subjectTemplate && subjectField.value === '') {
                    subjectField.value = subjectTemplate;
                }

                if (template) {
                    if (currentMsg === '') {
                        messageField.value = template;
                    } else {
                        messageField.value = currentMsg + '\n\n---\n\n' + template;
                    }
                    messageField.focus();
                }
            }

            // Reset form function
            function resetForm() {
                document.getElementById('quickEmailForm').reset();
                // Clear any alert messages after reset
                setTimeout(function() {
                    var alerts = document.querySelectorAll('.alert');
                    alerts.forEach(function(alert) {
                        if (alert.style.margin !== '10px 15px 0 15px') {
                            alert.style.display = 'none';
                        }
                    });
                }, 500);
            }

            // Form validation before submit
            document.getElementById('quickEmailForm').addEventListener('submit', function(e) {
                var emailTo = document.getElementById('emailto').value;
                var subject = document.getElementById('subject').value;
                var message = document.getElementById('message').value;

                if (!emailTo || !subject || !message) {
                    e.preventDefault();
                    alert('Please fill in all required fields: To, Subject, and Message');
                    return false;
                }

                var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(emailTo)) {
                    e.preventDefault();
                    alert('Please enter a valid email address');
                    return false;
                }

                // Optional CC validation
                var emailCc = document.getElementById('emailcc').value;
                if (emailCc && !emailPattern.test(emailCc)) {
                    e.preventDefault();
                    alert('Please enter a valid CC email address');
                    return false;
                }

                // Show sending indicator
                var submitBtn = e.submitter;
                if (submitBtn && submitBtn.name === 'send_email') {
                    submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Sending...';
                    submitBtn.disabled = true;
                }
            });

            // Initialize tooltips
            $(function() {
                $('[data-toggle="tooltip"]').tooltip();
            });
        </script>

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-bar-chart"></i> Branch-wise Student Distribution</h3>
            </div>
            <div class="box-body">
                <div class="chart-container" style="height: 280px;">
                    <canvas id="branchChart"></canvas>
                </div>
            </div>
        </div>
        <!-- ==================== DEPARTMENT HOD TABLE ==================== -->
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-users"></i> <strong>Department Heads (HOD)</strong>
                        </h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body table-responsive no-padding hod-table-container">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th><i class="fa fa-building"></i> Department</th>
                                    <th><i class="fa fa-user-tie"></i> HOD Name</th>
                                    <th><i class="fa fa-phone"></i> Phone Number</th>
                                    <th><i class="fa fa-circle"></i> Status</th>
                                    <th><i class="fa fa-cog"></i> Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($departments_hod as $id => $hod): ?>
                                    <tr id="row-<?php echo $id; ?>">
                                        <td><strong><?php echo $hod['dept']; ?></strong></td>
                                        <td><?php echo $hod['hod_name']; ?></td>
                                        <td><?php echo $hod['phone']; ?></td>
                                        <td>
                                            <?php
                                            $status_class = '';
                                            $status_text = '';
                                            switch ($hod['status']) {
                                                case 'Available':
                                                    $status_class = 'status-available';
                                                    $status_text = 'Available';
                                                    break;
                                                case 'In Meeting':
                                                    $status_class = 'status-meeting';
                                                    $status_text = 'In Meeting';
                                                    break;
                                                case 'On Leave':
                                                    $status_class = 'status-leave';
                                                    $status_text = 'On Leave';
                                                    break;
                                                default:
                                                    $status_class = 'status-available';
                                                    $status_text = $hod['status'];
                                            }
                                            ?>
                                            <span class="status-badge <?php echo $status_class; ?>">
                                                <i class="fa <?php echo ($status_text == 'Available') ? 'fa-check-circle' : (($status_text == 'In Meeting') ? 'fa-clock-o' : 'fa-calendar-times-o'); ?>"></i>
                                                <?php echo $status_text; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn-action" onclick="openEditModal(<?php echo $id; ?>, '<?php echo htmlspecialchars($hod['hod_name']); ?>', '<?php echo $hod['phone']; ?>', '<?php echo $hod['status']; ?>')">
                                                <i class="fa fa-edit"></i> Edit
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <!-- Calendar and Schedule Row -->
        <div class="row">
            <div class="col-md-8">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-calendar"></i> Academic Calendar</h3>
                    </div>
                    <div class="box-body no-padding">
                        <div id="calendar" style="padding: 10px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box box-solid" style="border-top: 3px solid #605ca8;">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-calendar-check-o"></i> Upcoming Schedule</h3>
                    </div>
                    <div class="box-body no-padding">
                        <ul class="nav nav-stacked event-list">
                            <?php foreach ($academic_events as $event): ?>
                                <?php
                                $event_date = date('d M, Y', strtotime($event['start']));
                                $event_time = (strpos($event['start'], 'T') !== false) ? date('h:i A', strtotime($event['start'])) : 'All Day';
                                ?>
                                <li>
                                    <a href="javascript:void(0)" onclick="alert('Event: <?php echo addslashes($event['title']); ?>\nDate: <?php echo $event_date; ?>\nTime: <?php echo $event_time; ?>')">
                                        <i class="fa <?php echo $event['icon']; ?>" style="color: <?php echo $event['color']; ?>; margin-right: 10px;"></i>
                                        <strong><?php echo $event['title']; ?></strong>
                                        <span class="pull-right text-muted small"><i class="fa fa-clock-o"></i> <?php echo $event_date; ?></span>
                                        <div style="margin-left: 25px; margin-top: 5px; font-size: 12px; color: #777;">
                                            <span class="label" style="background-color: <?php echo $event['color']; ?>"><?php echo $event['type']; ?></span>
                                            <span style="margin-left: 10px;"><i class="fa fa-clock-o"></i> <?php echo $event_time; ?></span>
                                        </div>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- ==================== EDIT MODAL ==================== -->
<div class="modal fade" id="editHodModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><i class="fa fa-pencil-square-o"></i> Edit HOD Details</h4>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" name="dept_id" id="edit_dept_id">
                    <div class="form-group">
                        <label><i class="fa fa-user-tie"></i> HOD Name</label>
                        <input type="text" class="form-control" name="hod_name" id="edit_hod_name" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fa fa-phone"></i> Phone Number</label>
                        <input type="text" class="form-control" name="phone" id="edit_phone" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fa fa-circle"></i> Status</label>
                        <select class="form-control" name="status" id="edit_status">
                            <option value="Available">Available</option>
                            <option value="In Meeting">In Meeting</option>
                            <option value="On Leave">On Leave</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="edit_hod" class="btn btn-primary"><i class="fa fa-save"></i> Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Include Chart.js & FullCalendar -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<script>
    // Open Edit Modal and populate fields
    function openEditModal(id, hodName, phone, status) {
        document.getElementById('edit_dept_id').value = id;
        document.getElementById('edit_hod_name').value = hodName;
        document.getElementById('edit_phone').value = phone;
        document.getElementById('edit_status').value = status;
        $('#editHodModal').modal('show');
    }

    // Initialize FullCalendar
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        if (calendarEl) {
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                height: 450,
                events: <?php echo json_encode($academic_events); ?>
            });
            calendar.render();
        }
    });

    // Animate Counters
    document.addEventListener("DOMContentLoaded", function() {
        const counters = document.querySelectorAll('.counter');
        const duration = 1500;

        counters.forEach(counter => {
            const target = +counter.getAttribute('data-target');
            if (target === 0) {
                counter.innerText = '0';
                return;
            }

            const increment = target / (duration / 16);
            let current = 0;

            const updateCounter = () => {
                current += increment;
                if (current < target) {
                    counter.innerText = Math.ceil(current);
                    requestAnimationFrame(updateCounter);
                } else {
                    counter.innerText = target;
                }
            };

            updateCounter();
        });
    });

    // Specialization Overview Pie Chart
    var ctxSpec = document.getElementById('specializationChart');
    if (ctxSpec) {
        var specChart = new Chart(ctxSpec, {
            type: 'doughnut',
            data: {
                labels: [<?php echo implode(',', $spec_labels); ?>],
                datasets: [{
                    data: [<?php echo implode(',', $spec_totals); ?>],
                    backgroundColor: ['#00c0ef', '#f39c12', '#00a65a'],
                    hoverBackgroundColor: ['#00acd6', '#e08e0b', '#008d4c']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });
    }

    // Branch-wise Distribution Bar Chart
    var ctxBranch = document.getElementById('branchChart');
    if (ctxBranch) {
        var branchChart = new Chart(ctxBranch, {
            type: 'bar',
            data: {
                labels: [<?php echo implode(',', $branch_labels); ?>],
                datasets: [{
                    label: 'Total Students',
                    data: [<?php echo implode(',', $branch_counts); ?>],
                    backgroundColor: 'linear-gradient(135deg, #667eea, #764ba2)',
                    backgroundColor: '#00a65a',
                    borderColor: '#008d4c',
                    borderWidth: 1,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }
</script>

<?php include "header/footer.php"; ?>