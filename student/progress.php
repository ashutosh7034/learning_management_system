<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../includes/models/progress_model.php';

require_student();

$rows = lms_student_progress_rows(current_user_id());

lms_layout_header([
    'title' => 'Course Progress',
    'heading' => 'Course Progress',
    'icon' => 'fa fa-tasks',
    'breadcrumb' => ['Course Progress' => null],
]);
?>

<div class="box box-primary">
    <div class="box-body table-responsive">
        <table class="table table-hover">
            <thead><tr><th>Course</th><th>Video</th><th>PDF</th><th>Quiz</th><th>Overall</th><th>Certificate</th></tr></thead>
            <tbody>
            <?php if (empty($rows)): ?>
                <tr><td colspan="6" class="text-center text-muted">No progress yet.</td></tr>
            <?php else: foreach ($rows as $row): ?>
                <tr>
                    <td><a href="<?php echo e(url('student/course.php?id=' . (int) $row['course_id'])); ?>"><?php echo e($row['title']); ?></a></td>
                    <?php foreach (['video_pct', 'pdf_pct', 'quiz_pct', 'overall_pct'] as $field): $pct = max(0, min(100, (float) $row[$field])); ?>
                        <td style="min-width:130px;"><div class="progress progress-xs"><div class="progress-bar progress-bar-info" style="width: <?php echo $pct; ?>%"></div></div><small><?php echo number_format($pct, 0); ?>%</small></td>
                    <?php endforeach; ?>
                    <td>
                        <?php if (!empty($row['certificate_id'])): ?>
                            <a href="<?php echo e(url('student/certificate.php?id=' . (int) $row['certificate_id'])); ?>" class="btn btn-xs btn-success">View</a>
                        <?php else: ?>
                            <span class="text-muted">Pending</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php lms_layout_footer(); ?>
