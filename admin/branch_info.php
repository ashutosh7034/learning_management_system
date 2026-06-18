<?php
require "header/header.php";

// Database connection
$db_handle = new DBController();

$selectedDepartmentId = intval($_GET['department_id'] ?? 0);

// Fetch all branches from database
$branches_query = "SELECT 
    d.department_id as id,
    d.department_name as name,
    CASE 
        WHEN LOCATE(' ', d.department_name) > 0 
        THEN UPPER(SUBSTRING(d.department_name, 1, LOCATE(' ', d.department_name) - 1))
        ELSE UPPER(d.department_name)
    END as code,
    (SELECT COUNT(*) FROM lms_student_master WHERE department_id = d.department_id AND status = 0
    ) as total_students,
    (SELECT COUNT(*) FROM lms_user_master WHERE department_id = d.department_id AND role_id = 2) as total_hods,
    (SELECT COUNT(*) FROM lms_user_master WHERE department_id = d.department_id AND role_id IN (3,4)) as total_staff
FROM lms_department_master d
" . ($selectedDepartmentId > 0 ? "WHERE d.department_id = $selectedDepartmentId " : "") . "
ORDER BY d.department_id";

$branches_result = mysqli_query($db_handle->conn, $branches_query);
$branches = [];

if ($branches_result) {
    while ($row = mysqli_fetch_assoc($branches_result)) {
        $branches[] = $row;
    }
} else {
    error_log("Branches query failed: " . mysqli_error($db_handle->conn));
}

// Get total branches count
$total_branches = count($branches);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Branch Information</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <style>
        :root {
            --primary: #667eea;
            --primary-dark: #764ba2;
            --success: #4caf50;
            --warning: #ff9800;
            --danger: #f44336;
            --info: #00bcd4;
            --bg-light: #f8f9fc;
            --card-shadow: 0 10px 20px rgba(0,0,0,0.08), 0 6px 6px rgba(0,0,0,0.1);
            --hover-shadow: 0 20px 25px -12px rgba(0,0,0,0.15);
        }

        .content-wrapper { background: var(--bg-light); min-height: 100vh; padding: 20px; }

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--card-shadow);
        }

        .page-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }

        .page-header p {
            margin: 5px 0 0 0;
            opacity: 0.9;
        }

        .back-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: rgba(255,255,255,0.3);
            color: white;
            text-decoration: none;
        }

        /* Stats Bar */
        .stats-bar {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .stat-item {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: var(--card-shadow);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .stat-item:hover { transform: translateY(-3px); }

        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 5px;
        }

        .stat-label {
            color: #666;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Branch Cards Grid */
        .branches-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
        }

        .branch-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
        }

        .branch-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
        }

        .branch-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 25px;
            position: relative;
        }

        .branch-header .branch-code {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255,255,255,0.2);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }

        .branch-header h3 {
            margin: 0 0 5px 0;
            font-size: 20px;
            font-weight: 600;
        }

        .branch-header .branch-id {
            font-size: 12px;
            opacity: 0.8;
        }

        .branch-body {
            padding: 25px;
        }

        .metric-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .metric-row:last-child { border-bottom: none; }

        .metric-label {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #555;
            font-size: 14px;
        }

        .metric-label i {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .metric-value {
            font-weight: 700;
            font-size: 16px;
        }

        .metric-value.students { color: var(--success); }
        .metric-value.hods { color: var(--warning); }
        .metric-value.staff { color: var(--info); }

        /* Status Badge */
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-active { background: #e8f5e9; color: #2e7d32; }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .empty-state i {
            font-size: 60px;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header { flex-direction: column; text-align: center; gap: 15px; }
            .branches-grid { grid-template-columns: 1fr; }
            .stats-bar { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="content-wrapper">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1><i class="fa fa-building"></i> Branch Information</h1>
                <p><?php echo $selectedDepartmentId > 0 ? 'Viewing your branch only' : 'View all departments and their details'; ?></p>
            </div>
            <a href="index.php" class="back-btn">
                <i class="fa fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <!-- Stats Bar -->
        <div class="stats-bar">
            <div class="stat-item">
                <div class="stat-number"><?php echo $total_branches; ?></div>
                <div class="stat-label">Total Branches</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?php echo array_sum(array_column($branches, 'total_students')); ?></div>
                <div class="stat-label">Total Students</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?php echo array_sum(array_column($branches, 'total_hods')); ?></div>
                <div class="stat-label">Total HODs</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?php echo array_sum(array_column($branches, 'total_staff')); ?></div>
                <div class="stat-label">Total Staff</div>
            </div>
        </div>

        <!-- Branches Grid -->
        <?php if (empty($branches)): ?>
            <div class="empty-state">
                <i class="fa fa-building"></i>
                <h3>No Branches Found</h3>
                <p>No branch data available in the database.</p>
            </div>
        <?php else: ?>
            <div class="branches-grid">
                <?php foreach ($branches as $branch): ?>
                    <div class="branch-card">
                        <div class="branch-header">
                            <span class="branch-code"><?php echo htmlspecialchars($branch['code']); ?></span>
                            <h3><?php echo htmlspecialchars($branch['name']); ?></h3>
                            <span class="branch-id">ID: <?php echo htmlspecialchars($branch['id']); ?></span>
                        </div>
                        <div class="branch-body">
                            <div class="metric-row">
                                <div class="metric-label">
                                    <i class="fa fa-graduation-cap" style="background: #e8f5e9; color: #2e7d32;"></i>
                                    Total Students
                                </div>
                                <div class="metric-value students">
                                    <?php echo (int)$branch['total_students']; ?>
                                </div>
                            </div>
                            <div class="metric-row">
                                <div class="metric-label">
                                    <i class="fa fa-user" style="background: #fff3e0; color: #ef6c00;"></i>
                                    HODs
                                </div>
                                <div class="metric-value hods">
                                    <?php echo (int)$branch['total_hods']; ?>
                                </div>
                            </div>
                            <div class="metric-row">
                                <div class="metric-label">
                                    <i class="fa fa-users" style="background: #e3f2fd; color: #1565c0;"></i>
                                    Staff (Mentors/Coordinators)
                                </div>
                                <div class="metric-value staff">
                                    <?php echo (int)$branch['total_staff']; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php include "header/footer.php"; ?>
</body>
</html>
