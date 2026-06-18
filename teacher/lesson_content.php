<?php
/**
 * Lesson content manager (Teacher / Admin).
 * Attaches a video (file upload or external URL) or a PDF to a lesson,
 * depending on the lesson type. Quiz lessons link to the quiz builder.
 */

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../includes/uploads.php';
require_once __DIR__ . '/../includes/models/course_model.php';
require_once __DIR__ . '/../includes/models/content_model.php';

require_teacher();

$isAdmin   = is_admin_role(current_role_id());
$teacherId = current_user_id();

$lessonId = (int) ($_GET['lesson'] ?? 0);
$lesson   = lms_lesson_full($lessonId);
if (!$lesson) {
    flash('danger', 'Lesson not found.');
    redirect('teacher/courses.php');
}
if (!$isAdmin && (int) $lesson['teacher_id'] !== $teacherId) {
    http_response_code(403);
    flash('danger', 'You can only manage your own course content.');
    redirect('teacher/courses.php');
}

$builderUrl = url('teacher/course_builder.php?id=' . (int) $lesson['course_id']);
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_require();
    $action = $_POST['action'] ?? '';

    if ($action === 'save_video' && $lesson['type'] === 'video') {
        $title    = trim($_POST['title'] ?? '');
        $duration = (int) ($_POST['duration_seconds'] ?? 0);
        $extUrl   = trim($_POST['external_url'] ?? '');
        $existing = lms_video_for_lesson($lessonId);
        $filePath = $existing['file_path'] ?? null;

        if (!empty($_FILES['video']['name'])) {
            $up = lms_store_upload($_FILES['video'], 'video');
            if ($up['ok']) {
                $filePath = $up['path'];
                $extUrl = ''; // an uploaded file takes precedence over a URL
            } else {
                $errors[] = 'Video: ' . $up['error'];
            }
        }

        if (empty($filePath) && $extUrl === '') {
            $errors[] = 'Provide a video file or an external video URL.';
        }

        if (empty($errors)) {
            lms_video_save($lessonId, [
                'title'            => $title ?: $lesson['title'],
                'file_path'        => $filePath ?: null,
                'external_url'     => $extUrl ?: null,
                'duration_seconds' => $duration,
            ]);
            flash('success', 'Video saved.');
            redirect('teacher/lesson_content.php?lesson=' . $lessonId);
        }
    } elseif ($action === 'save_pdf' && $lesson['type'] === 'pdf') {
        $title    = trim($_POST['title'] ?? '');
        $pages    = (int) ($_POST['page_count'] ?? 0);
        $existing = lms_pdf_for_lesson($lessonId);
        $filePath = $existing['file_path'] ?? '';

        if (!empty($_FILES['pdf']['name'])) {
            $up = lms_store_upload($_FILES['pdf'], 'pdf');
            if ($up['ok']) {
                $filePath = $up['path'];
            } else {
                $errors[] = 'PDF: ' . $up['error'];
            }
        }

        if (empty($filePath)) {
            $errors[] = 'Please upload a PDF file.';
        }

        if (empty($errors)) {
            lms_pdf_save($lessonId, [
                'title'      => $title ?: $lesson['title'],
                'file_path'  => $filePath,
                'page_count' => $pages,
            ]);
            flash('success', 'PDF saved.');
            redirect('teacher/lesson_content.php?lesson=' . $lessonId);
        }
    }
}

$video = lms_video_for_lesson($lessonId);
$pdf   = lms_pdf_for_lesson($lessonId);

lms_layout_header([
    'title'      => 'Lesson Content',
    'heading'    => 'Lesson Content',
    'icon'       => 'fa fa-upload',
    'breadcrumb' => [
        'Course Management' => url('teacher/courses.php'),
        $lesson['course_title'] => $builderUrl,
        $lesson['title'] => null,
    ],
]);
?>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger"><ul style="margin:0;padding-left:18px;">
        <?php foreach ($errors as $err): ?><li><?php echo e($err); ?></li><?php endforeach; ?>
    </ul></div>
<?php endif; ?>

<div class="row">
    <div class="col-md-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-file-o"></i> <?php echo e($lesson['title']); ?>
                    <small class="label label-default"><?php echo e(ucfirst($lesson['type'])); ?></small>
                </h3>
                <div class="box-tools pull-right">
                    <a href="<?php echo e($builderUrl); ?>" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> Back to Curriculum</a>
                </div>
            </div>

            <?php if ($lesson['type'] === 'video'): ?>
                <form method="post" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="action" value="save_video">
                    <div class="box-body">
                        <div class="form-group">
                            <label>Display title</label>
                            <input type="text" name="title" class="form-control" value="<?php echo e($video['title'] ?? $lesson['title']); ?>">
                        </div>
                        <div class="form-group">
                            <label>Upload video file</label>
                            <input type="file" name="video" accept="video/*">
                            <p class="help-block">MP4, WebM, OGG or MOV, up to 512 MB. Leave empty to keep the current file or use a URL below.</p>
                        </div>
                        <div class="form-group">
                            <label>…or external video URL</label>
                            <input type="url" name="external_url" class="form-control" value="<?php echo e($video['external_url'] ?? ''); ?>" placeholder="https://…/video.mp4">
                        </div>
                        <div class="form-group">
                            <label>Duration (seconds)</label>
                            <input type="number" name="duration_seconds" min="0" class="form-control" style="max-width:200px;" value="<?php echo (int) ($video['duration_seconds'] ?? 0); ?>">
                            <p class="help-block">Used to compute watch percentage. Optional.</p>
                        </div>
                    </div>
                    <div class="box-footer"><button class="btn btn-primary"><i class="fa fa-save"></i> Save Video</button></div>
                </form>

            <?php elseif ($lesson['type'] === 'pdf'): ?>
                <form method="post" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="action" value="save_pdf">
                    <div class="box-body">
                        <div class="form-group">
                            <label>Display title</label>
                            <input type="text" name="title" class="form-control" value="<?php echo e($pdf['title'] ?? $lesson['title']); ?>">
                        </div>
                        <div class="form-group">
                            <label>Upload PDF</label>
                            <input type="file" name="pdf" accept="application/pdf">
                            <p class="help-block">PDF only, up to 64 MB.<?php echo !empty($pdf['file_path']) ? ' Leave empty to keep the current file.' : ''; ?></p>
                        </div>
                        <div class="form-group">
                            <label>Page count</label>
                            <input type="number" name="page_count" min="0" class="form-control" style="max-width:200px;" value="<?php echo (int) ($pdf['page_count'] ?? 0); ?>">
                            <p class="help-block">Used to compute read percentage. Optional.</p>
                        </div>
                    </div>
                    <div class="box-footer"><button class="btn btn-primary"><i class="fa fa-save"></i> Save PDF</button></div>
                </form>

            <?php else: /* quiz */ ?>
                <div class="box-body">
                    <p>This is a <strong>quiz</strong> lesson. Quiz authoring is managed in the Quiz Builder.</p>
                    <a href="<?php echo e(url('teacher/quiz_builder.php?lesson=' . $lessonId)); ?>" class="btn btn-info"><i class="fa fa-question-circle"></i> Open Quiz Builder</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-md-4">
        <div class="box box-info">
            <div class="box-header with-border"><h3 class="box-title">Preview</h3></div>
            <div class="box-body">
                <?php if ($lesson['type'] === 'video'): ?>
                    <?php
                        $src = !empty($video['file_path']) ? LMS_UPLOAD_URL . '/' . $video['file_path'] : ($video['external_url'] ?? '');
                    ?>
                    <?php if ($src): ?>
                        <video controls preload="metadata" style="width:100%;border-radius:4px;background:#000;">
                            <source src="<?php echo e($src); ?>">
                            Your browser does not support HTML5 video.
                        </video>
                        <?php if (!empty($video['duration_seconds'])): ?>
                            <p class="text-muted" style="margin-top:8px;">Duration: <?php echo (int) $video['duration_seconds']; ?>s</p>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="text-muted">No video yet.</p>
                    <?php endif; ?>
                <?php elseif ($lesson['type'] === 'pdf'): ?>
                    <?php if (!empty($pdf['file_path'])): ?>
                        <a href="<?php echo e(LMS_UPLOAD_URL . '/' . $pdf['file_path']); ?>" target="_blank" class="btn btn-default btn-block"><i class="fa fa-file-pdf-o"></i> Open PDF</a>
                        <?php if (!empty($pdf['page_count'])): ?><p class="text-muted" style="margin-top:8px;"><?php echo (int) $pdf['page_count']; ?> pages</p><?php endif; ?>
                    <?php else: ?>
                        <p class="text-muted">No PDF yet.</p>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="text-muted">No preview for quiz lessons.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php lms_layout_footer(); ?>
