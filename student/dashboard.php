<?php
/**
 * LMS-native student dashboard.
 */

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../includes/models/enrollment_model.php';
require_once __DIR__ . '/../includes/models/progress_model.php';
require_once __DIR__ . '/../includes/models/quiz_model.php';

require_student();

$studentId = current_user_id();
$courses = lms_student_courses($studentId);
$progressRows = lms_student_progress_rows($studentId);
$quizzes = lms_student_quizzes($studentId);
$certificates = lms_student_certificates($studentId);

$overallTotal = 0;
foreach ($progressRows as $row) {
    $overallTotal += (float) $row['overall_pct'];
}
$avgProgress = count($progressRows) > 0 ? $overallTotal / count($progressRows) : 0;

// 1. Course Progress data extraction
$courseLabels = [];
$courseProgressData = [];
foreach ($progressRows as $row) {
    $courseLabels[] = $row['title'];
    $courseProgressData[] = (float) $row['overall_pct'];
}

// 2. Learning Activity (updates to progress last 7 days)
$activityLabels = [];
$activityCounts = [];
$activity_res = mysqli_query($conn, "
    SELECT DATE(updated_at) AS date, COUNT(*) AS count
    FROM (
        SELECT updated_at FROM lms_video_progress WHERE enrollment_id IN (
            SELECT id FROM lms_enrollments WHERE student_id = $studentId AND deleted_at IS NULL
        )
        UNION ALL
        SELECT updated_at FROM lms_pdf_progress WHERE enrollment_id IN (
            SELECT id FROM lms_enrollments WHERE student_id = $studentId AND deleted_at IS NULL
        )
    ) AS combined
    GROUP BY DATE(updated_at)
    ORDER BY date DESC
    LIMIT 7
");
if ($activity_res) {
    while ($row = mysqli_fetch_assoc($activity_res)) {
        $activityLabels[] = date('d M', strtotime($row['date']));
        $activityCounts[] = (int) $row['count'];
    }
}
$activityLabels = array_reverse($activityLabels);
$activityCounts = array_reverse($activityCounts);

lms_layout_header([
    'title' => 'Student Dashboard',
    'heading' => 'Student Dashboard',
    'icon' => 'fa fa-dashboard',
    'breadcrumb' => ['Student Dashboard' => null],
]);
?>

<div class="row">
    <?php foreach ([
        ['bg-aqua', 'Enrolled Courses', count($courses), 'fa-book'],
        ['bg-green', 'Average Progress', number_format($avgProgress, 0) . '%', 'fa-line-chart'],
        ['bg-yellow', 'Available Quizzes', count($quizzes), 'fa-question-circle'],
        ['bg-red', 'Certificates', count($certificates), 'fa-certificate'],
    ] as $card): ?>
        <div class="col-md-3 col-sm-6">
            <div class="small-box <?php echo $card[0]; ?>">
                <div class="inner"><h3><?php echo e($card[2]); ?></h3><p><?php echo e($card[1]); ?></p></div>
                <div class="icon"><i class="fa <?php echo e($card[3]); ?>"></i></div>
                <a href="<?php echo e(url($card[1] === 'Enrolled Courses' ? 'student/my_courses.php' : ($card[1] === 'Certificates' ? 'student/certificates.php' : 'student/progress.php'))); ?>" class="small-box-footer">Open <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Charts Row -->
<div class="row">
    <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-tasks"></i> Course Progress</h3>
            </div>
            <div class="box-body">
                <div style="position:relative; height:220px; width:100%;">
                    <canvas id="courseProgressChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-line-chart"></i> Learning Activity</h3>
            </div>
            <div class="box-body">
                <div style="position:relative; height:220px; width:100%;">
                    <canvas id="learningActivityChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Continue Learning</h3>
                <div class="box-tools pull-right"><a class="btn btn-success btn-sm" href="<?php echo e(url('student/catalog.php')); ?>"><i class="fa fa-plus"></i> Browse Catalog</a></div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover">
                    <thead><tr><th>Course</th><th>Progress</th><th>Action</th></tr></thead>
                    <tbody>
                    <?php if (empty($courses)): ?>
                        <tr><td colspan="3" class="text-center text-muted">No enrolled courses yet.</td></tr>
                    <?php else: foreach (array_slice($courses, 0, 6) as $course): ?>
                        <?php $first = lms_first_lesson_for_enrollment((int) $course['id']); $pct = max(0, min(100, (float) $course['overall_pct'])); ?>
                        <tr>
                            <td><strong><?php echo e($course['title']); ?></strong><br><small><?php echo e($course['teacher_name'] ?: 'Teacher'); ?></small></td>
                            <td style="min-width:180px;"><div class="progress progress-xs"><div class="progress-bar progress-bar-success" style="width: <?php echo $pct; ?>%"></div></div><small><?php echo number_format($pct, 0); ?>%</small></td>
                            <td><a class="btn btn-xs btn-primary" href="<?php echo e($first ? url('student/lesson.php?id=' . (int) $first['id']) : url('student/course.php?id=' . (int) $course['course_id'])); ?>">Resume</a></td>
                        </tr>
                    <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="box box-solid">
            <div class="box-header with-border"><h3 class="box-title">Quick Links</h3></div>
            <div class="box-body">
                <a class="btn btn-default btn-block" href="<?php echo e(url('student/learning.php')); ?>"><i class="fa fa-graduation-cap"></i> My Learning</a>
                <a class="btn btn-default btn-block" href="<?php echo e(url('student/quizzes.php')); ?>"><i class="fa fa-question-circle"></i> Quizzes</a>
                <a class="btn btn-default btn-block" href="<?php echo e(url('student/progress.php')); ?>"><i class="fa fa-tasks"></i> Progress</a>
                <a class="btn btn-default btn-block" href="<?php echo e(url('student/certificates.php')); ?>"><i class="fa fa-certificate"></i> Certificates</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Course Progress Chart
    var ctxProg = document.getElementById('courseProgressChart');
    if (ctxProg) {
        new Chart(ctxProg, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($courseLabels); ?>,
                datasets: [{
                    label: 'Progress %',
                    data: <?php echo json_encode($courseProgressData); ?>,
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
                        max: 100
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }

    // 2. Learning Activity Chart
    var ctxAct = document.getElementById('learningActivityChart');
    if (ctxAct) {
        new Chart(ctxAct, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($activityLabels); ?>,
                datasets: [{
                    label: 'Activities Completed',
                    data: <?php echo json_encode($activityCounts); ?>,
                    borderColor: 'rgba(46, 204, 113, 1)',
                    backgroundColor: 'rgba(46, 204, 113, 0.1)',
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

<?php lms_layout_footer(); ?>
