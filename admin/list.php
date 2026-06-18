<?php
require "header/header.php";

// ========================
// GET TYPE PARAMETER
// ========================
$type = isset($_GET['type']) ? $_GET['type'] : '';
$page_title = '';
$data = [];
$columns = [];

// ========================
// DATABASE CONNECTION
// ========================
$db_handle = new DBController();

// ========================
// DATA FETCHING FUNCTIONS
// ========================

// Function to fetch students data
function fetchStudents($db) {
    $query = "SELECT 
        s.std_id as id,
        CONCAT(s.fname, ' ', COALESCE(s.lname, '')) as name,
        s.email_id as email,
        d.department_name as department,
        s.class as year,
        CASE 
            WHEN s.mobile IS NOT NULL AND s.mobile != '' THEN s.mobile
            ELSE 'N/A'
        END as mobile
    FROM lms_student_master s
    LEFT JOIN lms_department_master d ON s.department_id = d.department_id
    WHERE s.status = 1
    ORDER BY s.std_id";
    
    $result = mysqli_query($db->conn, $query);
    $students = [];
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $students[] = $row;
        }
    } else {
        // Log error if needed
        error_log("Students query failed: " . mysqli_error($db->conn));
    }
    
    return $students;
}

// Function to fetch users data
function fetchUsers($db) {
    $query = "SELECT 
        l.login_id as id,
        COALESCE(NULLIF(TRIM(u.user_name), ''), l.username) as name,
        l.username as email,
        r.role_name as role,
        d.department_name as department,
        CASE 
            WHEN s.mobile IS NOT NULL AND s.mobile != '' THEN s.mobile
            ELSE 'N/A'
        END as mobile
    FROM lms_login l
    LEFT JOIN lms_user_master u ON u.user_id = l.user_id
    LEFT JOIN lms_role_master r ON u.role_id = r.role_id
    LEFT JOIN lms_department_master d ON u.department_id = d.department_id
    LEFT JOIN lms_student_master s ON s.std_id = l.user_id
    ORDER BY l.login_id";
    
    $result = mysqli_query($db->conn, $query);
    $users = [];
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $users[] = $row;
        }
    } else {
        error_log("Users query failed: " . mysqli_error($db->conn));
    }
    
    return $users;
}

// Function to fetch branches data
function fetchBranches($db) {
    $query = "SELECT 
        d.department_id as id,
        d.department_name as name,
        UPPER(SUBSTRING(d.department_name, 1, LOCATE(' ', d.department_name) - 1)) as code,
        'HOD' as hod,
        (SELECT COUNT(*) FROM lms_student_master WHERE department_id = d.department_id AND status = 1) as students_count
    FROM lms_department_master d
    ORDER BY d.department_id";
    
    $result = mysqli_query($db->conn, $query);
    $branches = [];
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $branches[] = $row;
        }
    } else {
        error_log("Branches query failed: " . mysqli_error($db->conn));
    }
    
    return $branches;
}

// Function to fetch mentors data
function fetchMentors($db) {
    $query = "SELECT 
        l.login_id as id,
        COALESCE(NULLIF(TRIM(u.user_name), ''), l.username) as name,
        l.username as email,
        d.department_name as department,
        (SELECT COUNT(*) FROM lms_mentor_student_mapping WHERE mentor_id = l.user_id) as students_assigned,
        CASE 
            WHEN s.mobile IS NOT NULL AND s.mobile != '' THEN s.mobile
            ELSE 'N/A'
        END as mobile
    FROM lms_login l
    LEFT JOIN lms_user_master u ON u.user_id = l.user_id
    LEFT JOIN lms_department_master d ON u.department_id = d.department_id
    LEFT JOIN lms_student_master s ON s.std_id = l.user_id
    WHERE u.role_id IN (3, 4)
    ORDER BY l.login_id";
    
    $result = mysqli_query($db->conn, $query);
    $mentors = [];
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $mentors[] = $row;
        }
    } else {
        error_log("Mentors query failed: " . mysqli_error($db->conn));
    }
    
    return $mentors;
}

// Function to fetch rejected students data
function fetchRejectedStudents($db) {
    $query = "SELECT 
        s.std_id as id,
        CONCAT(s.fname, ' ', COALESCE(s.lname, '')) as name,
        s.email_id as email,
        'Rejected' as status,
        CASE 
            WHEN s.cgpa < 2.0 THEN 'Low Score'
            WHEN s.email_id IS NULL OR s.email_id = '' THEN 'Invalid Details'
            ELSE 'Incomplete Documents'
        END as reason,
        CASE 
            WHEN s.mobile IS NOT NULL AND s.mobile != '' THEN s.mobile
            ELSE 'N/A'
        END as mobile
    FROM lms_student_master s
    WHERE s.status = 0
    ORDER BY s.std_id";
    
    $result = mysqli_query($db->conn, $query);
    $rejected_students = [];
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rejected_students[] = $row;
        }
    } else {
        error_log("Rejected students query failed: " . mysqli_error($db->conn));
    }
    
    return $rejected_students;
}

// Fetch data based on type
$students = fetchStudents($db_handle);
$users = fetchUsers($db_handle);
$branches = fetchBranches($db_handle);
$mentors = fetchMentors($db_handle);
$rejected_students = fetchRejectedStudents($db_handle);

// ========================
// SWITCH BASED ON TYPE
// ========================
switch ($type) {
    case 'students':
        $page_title = 'All Students';
        $data = $students;
        $columns = ['ID', 'Name', 'Email', 'Department', 'Year', 'Mobile'];
        break;
        
    case 'users':
        $page_title = 'All Users';
        $data = $users;
        $columns = ['ID', 'Name', 'Email', 'Role', 'Department', 'Mobile'];
        break;
        
    case 'branches':
        $page_title = 'All Branches';
        $data = $branches;
        $columns = ['ID', 'Name', 'Code', 'HOD', 'Students Count'];
        break;
        
    case 'mentors':
        $page_title = 'All Mentors';
        $data = $mentors;
        $columns = ['ID', 'Name', 'Email', 'Department', 'Students Assigned', 'Mobile'];
        break;
        
    case 'rejected':
        $page_title = 'Rejected Students';
        $data = $rejected_students;
        $columns = ['ID', 'Name', 'Email', 'Status', 'Rejection Reason', 'Mobile'];
        break;
        
    default:
        $page_title = 'Data List';
        $data = [];
        $columns = [];
        break;
}

?>

<style>
    .content-wrapper {
        background: #f8f9fc;
    }
    
    .box {
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border: none;
    }
    
    .box-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 8px 8px 0 0;
        padding: 15px 20px;
    }
    
    .box-title {
        font-size: 18px;
        font-weight: 600;
        margin: 0;
    }
    
    .table {
        background: white;
        border-radius: 8px;
        overflow: hidden;
    }
    
    .table thead tr th {
        background: #f8f9fa;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 0.5px;
        padding: 15px;
        border: none;
    }
    
    .table tbody tr {
        transition: background-color 0.2s ease;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fc;
    }
    
    .table td {
        padding: 15px;
        vertical-align: middle;
        border-top: 1px solid #e9ecef;
    }
    
    .btn-back {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border: none;
        padding: 8px 20px;
        border-radius: 25px;
        color: white;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-back:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        color: white;
        text-decoration: none;
    }
    
    .no-data {
        text-align: center;
        padding: 40px;
        color: #6c757d;
    }
    
    .no-data i {
        font-size: 48px;
        margin-bottom: 15px;
        color: #dee2e6;
    }
    
    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 500;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    
    .badge-student { background: #007bff; }
    .badge-admin { background: #28a745; }
    .badge-coordinator { background: #ffc107; color: #212529; }
    .badge-mentor { background: #17a2b8; }
    .badge-super-admin { background: #dc3545; }
    .badge-danger { background: #dc3545; }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-list"></i> <span><strong><?php echo htmlspecialchars($page_title); ?></strong></span>
        </h1>
        <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><?php echo htmlspecialchars($page_title); ?></li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">
                            <i class="fa fa-users"></i> 
                            <?php echo htmlspecialchars($page_title); ?>
                            <small class="pull-right">
                                Total: <span class="badge"><?php echo count($data); ?></span>
                            </small>
                        </h3>
                        <div class="box-tools pull-right">
                            <a href="index.php" class="btn-back">
                                <i class="fa fa-arrow-left"></i> Back to Dashboard
                            </a>
                        </div>
                    </div>
                    
                    <div class="box-body table-responsive no-padding">
                        <?php if (!empty($data)): ?>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <?php foreach ($columns as $column): ?>
                                            <th><?php echo htmlspecialchars($column); ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data as $item): ?>
                                        <tr>
                                            <?php 
                                            // Display data based on type
                                            switch ($type) {
                                                case 'students':
                                                    echo '<td>' . $item['id'] . '</td>';
                                                    echo '<td><strong>' . htmlspecialchars($item['name']) . '</strong></td>';
                                                    echo '<td>' . htmlspecialchars($item['email']) . '</td>';
                                                    echo '<td><span class="badge badge-student">' . htmlspecialchars($item['department']) . '</span></td>';
                                                    echo '<td>' . htmlspecialchars($item['year']) . '</td>';
                                                    echo '<td>' . htmlspecialchars($item['mobile']) . '</td>';
                                                    break;
                                                    
                                                case 'users':
                                                    echo '<td>' . $item['id'] . '</td>';
                                                    echo '<td><strong>' . htmlspecialchars($item['name']) . '</strong></td>';
                                                    echo '<td>' . htmlspecialchars($item['email']) . '</td>';
                                                    $role_class = 'badge-' . str_replace(' ', '-', strtolower($item['role']));
                                                    echo '<td><span class="badge ' . $role_class . '">' . htmlspecialchars($item['role']) . '</span></td>';
                                                    echo '<td>' . htmlspecialchars($item['department']) . '</td>';
                                                    echo '<td>' . htmlspecialchars($item['mobile']) . '</td>';
                                                    break;
                                                    
                                                case 'branches':
                                                    echo '<td>' . $item['id'] . '</td>';
                                                    echo '<td><strong>' . htmlspecialchars($item['name']) . '</strong></td>';
                                                    echo '<td><code>' . htmlspecialchars($item['code']) . '</code></td>';
                                                    echo '<td>' . htmlspecialchars($item['hod']) . '</td>';
                                                    echo '<td><span class="badge badge-primary">' . $item['students_count'] . '</span></td>';
                                                    break;
                                                    
                                                case 'mentors':
                                                    echo '<td>' . $item['id'] . '</td>';
                                                    echo '<td><strong>' . htmlspecialchars($item['name']) . '</strong></td>';
                                                    echo '<td>' . htmlspecialchars($item['email']) . '</td>';
                                                    echo '<td><span class="badge badge-mentor">' . htmlspecialchars($item['department']) . '</span></td>';
                                                    echo '<td><span class="badge badge-info">' . $item['students_assigned'] . '</span></td>';
                                                    echo '<td>' . htmlspecialchars($item['mobile']) . '</td>';
                                                    break;
                                                    
                                                case 'rejected':
                                                    echo '<td>' . $item['id'] . '</td>';
                                                    echo '<td><strong>' . htmlspecialchars($item['name']) . '</strong></td>';
                                                    echo '<td>' . htmlspecialchars($item['email']) . '</td>';
                                                    echo '<td><span class="badge badge-danger">' . htmlspecialchars($item['status']) . '</span></td>';
                                                    echo '<td><em>' . htmlspecialchars($item['reason']) . '</em></td>';
                                                    echo '<td>' . htmlspecialchars($item['mobile']) . '</td>';
                                                    break;
                                                    
                                                default:
                                                    foreach ($item as $value) {
                                                        echo '<td>' . htmlspecialchars($value) . '</td>';
                                                    }
                                                    break;
                                            }
                                            ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="no-data">
                                <i class="fa fa-inbox"></i>
                                <h4>No data available</h4>
                                <p>There are no <?php echo htmlspecialchars($page_title); ?> to display.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include "header/footer.php"; ?>