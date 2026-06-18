<?php
require_once __DIR__ . '/../includes/bootstrap.php';

// Check if CSV export is requested
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    require_once __DIR__ . '/../includes/models/report_model.php';
    $rows = lms_report_course_rows();
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=course_report_' . date('Ymd_His') . '.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Course', 'Level', 'Teacher', 'Category', 'Status', 'Enrollments', 'Avg Progress %', 'Certificates']);
    foreach ($rows as $row) {
        fputcsv($output, [
            $row['title'],
            $row['level'],
            $row['teacher_name'] ?: '-',
            $row['category_name'] ?: '-',
            $row['status'],
            (int) $row['enrollments'],
            number_format((float) $row['avg_progress'], 1),
            (int) $row['certificates']
        ]);
    }
    fclose($output);
    exit;
}

require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../includes/models/course_model.php';
require_once __DIR__ . '/../includes/models/report_model.php';

require_role(ROLE_TEACHER, ROLE_MENTOR, ROLE_ADMIN, ROLE_SUPER_ADMIN);

$rows = lms_report_course_rows();

lms_layout_header([
    'title' => 'Reports',
    'heading' => 'Reports',
    'icon' => 'fa fa-file-text',
    'breadcrumb' => ['Reports' => null],
]);
?>

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Course Report</h3>
        <a href="?export=csv" class="btn btn-success btn-sm pull-right"><i class="fa fa-download"></i> Export CSV</a>
    </div>
    <div class="box-body table-responsive">
        <table id="reportTable" class="table table-bordered table-hover">
            <thead><tr><th>Course</th><th>Teacher</th><th>Category</th><th>Status</th><th>Enrollments</th><th>Avg Progress</th><th>Certificates</th></tr></thead>
            <tbody>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td><?php echo e($row['title']); ?><br><small><?php echo e($row['level']); ?></small></td>
                    <td><?php echo e($row['teacher_name'] ?: '-'); ?></td>
                    <td><?php echo e($row['category_name'] ?: '-'); ?></td>
                    <td><?php echo lms_status_badge($row['status']); ?></td>
                    <td><?php echo (int) $row['enrollments']; ?></td>
                    <td><?php echo number_format((float) $row['avg_progress'], 1); ?>%</td>
                    <td><?php echo (int) $row['certificates']; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php lms_layout_footer(['scripts' => '<script>$(function(){ $("#reportTable").DataTable({ "order": [] }); });</script>']); ?>
