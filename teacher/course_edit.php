<?php
/**
 * Course create / edit form (Teacher / Admin).
 */

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../includes/uploads.php';
require_once __DIR__ . '/../includes/models/course_model.php';

require_teacher();

$roleId    = current_role_id();
$isAdmin   = is_admin_role($roleId);
$teacherId = current_user_id();

$courseId = (int) ($_GET['id'] ?? 0);
$editing  = $courseId > 0;
$course   = null;

if ($editing) {
    $course = lms_course_get($courseId);
    if (!$course) {
        flash('danger', 'Course not found.');
        redirect('teacher/courses.php');
    }
    if (!$isAdmin && (int) $course['teacher_id'] !== $teacherId) {
        http_response_code(403);
        flash('danger', 'You can only edit your own courses.');
        redirect('teacher/courses.php');
    }
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_require();

    $data = [
        'title'            => trim($_POST['title'] ?? ''),
        'category_id'      => $_POST['category_id'] ?? '',
        'level'            => $_POST['level'] ?? 'Beginner',
        'status'           => $_POST['status'] ?? 'draft',
        'summary'          => trim($_POST['summary'] ?? ''),
        'description'      => trim($_POST['description'] ?? ''),
        'teacher_id'       => $isAdmin ? (int) ($_POST['teacher_id'] ?? $teacherId) : ($editing ? (int) $course['teacher_id'] : $teacherId),
        'intro_video_url'  => trim($_POST['intro_video_url'] ?? ''),
        'intro_video_path' => $editing ? ($course['intro_video_path'] ?? null) : null,
    ];

    if ($data['title'] === '') { $errors[] = 'Title is required.'; }
    if (!in_array($data['level'], ['Beginner', 'Intermediate', 'Advanced'], true)) { $data['level'] = 'Beginner'; }
    if (!in_array($data['status'], ['draft', 'published', 'archived'], true)) { $data['status'] = 'draft'; }

    // Optional thumbnail upload.
    $thumbPath = $editing ? ($course['thumbnail'] ?? null) : null;
    if (!empty($_FILES['thumbnail']['name'])) {
        $up = lms_store_upload($_FILES['thumbnail'], 'thumbnail');
        if ($up['ok']) {
            $thumbPath = $up['path'];
        } else {
            $errors[] = 'Thumbnail: ' . $up['error'];
        }
    }
    $data['thumbnail'] = $thumbPath;

    // Optional intro video upload.
    if (!empty($_FILES['intro_video']['name'])) {
        $up = lms_store_upload($_FILES['intro_video'], 'video');
        if ($up['ok']) {
            $data['intro_video_path'] = $up['path'];
            $data['intro_video_url'] = ''; // file takes precedence over url
        } else {
            $errors[] = 'Intro Video: ' . $up['error'];
        }
    }

    if (empty($errors)) {
        if ($editing) {
            lms_course_update($courseId, $data);
            if ($thumbPath !== ($course['thumbnail'] ?? null)) {
                lms_course_set_thumbnail($courseId, $thumbPath);
            }
            flash('success', 'Course updated.');
            redirect('teacher/course_builder.php?id=' . $courseId);
        } else {
            $newId = lms_course_create($data);
            if ($newId) {
                flash('success', 'Course created. Now build the curriculum.');
                redirect('teacher/course_builder.php?id=' . (int) $newId);
            }
            $errors[] = 'Could not create the course.';
        }
    }
    // On error, repopulate the form from the submitted values.
    $course = array_merge((array) $course, $data);
}

$categories = lms_categories_all();
$teachers = [];
if ($isAdmin) {
    $tRes = mysqli_query($db_handle->conn, "SELECT user_id, user_name FROM lms_user_master WHERE role_id IN (3, 4) AND status = 1 ORDER BY user_name");
    if ($tRes) {
        while ($tRow = mysqli_fetch_assoc($tRes)) {
            $teachers[] = $tRow;
        }
    }
}

lms_layout_header([
    'title'      => $editing ? 'Edit Course' : 'New Course',
    'heading'    => $editing ? 'Edit Course' : 'New Course',
    'icon'       => 'fa fa-pencil-square-o',
    'breadcrumb' => ['Course Management' => url('teacher/courses.php'), ($editing ? 'Edit' : 'New') => null],
]);
?>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul style="margin:0;padding-left:18px;"><?php foreach ($errors as $err): ?><li><?php echo e($err); ?></li><?php endforeach; ?></ul>
    </div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <div class="row">
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header with-border"><h3 class="box-title">Course Details</h3></div>
                <div class="box-body">
                    <div class="form-group">
                        <label>Title <span class="text-red">*</span></label>
                        <input type="text" name="title" class="form-control" required
                               value="<?php echo e($course['title'] ?? ''); ?>" placeholder="e.g. Introduction to PHP">
                    </div>
                    <div class="form-group">
                        <label>Short Summary</label>
                        <input type="text" name="summary" class="form-control" maxlength="500"
                               value="<?php echo e($course['summary'] ?? ''); ?>" placeholder="One-line description shown in the catalog">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="6" placeholder="Full course description"><?php echo e($course['description'] ?? ''); ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box box-info">
                <div class="box-header with-border"><h3 class="box-title">Settings</h3></div>
                <div class="box-body">
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category_id" class="form-control">
                            <option value="">— Select —</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo (int) $cat['id']; ?>"
                                    <?php echo ((string) ($course['category_id'] ?? '') === (string) $cat['id']) ? 'selected' : ''; ?>>
                                    <?php echo e($cat['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php if ($isAdmin): ?>
                    <div class="form-group">
                        <label>Assigned Teacher <span class="text-red">*</span></label>
                        <select name="teacher_id" class="form-control" required>
                            <option value="">— Select —</option>
                            <?php foreach ($teachers as $t): ?>
                                <option value="<?php echo (int) $t['user_id']; ?>"
                                    <?php echo ((int) ($course['teacher_id'] ?? 0) === (int) $t['user_id']) ? 'selected' : ''; ?>>
                                    <?php echo e($t['user_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <label>Level</label>
                        <select name="level" class="form-control">
                            <?php foreach (['Beginner', 'Intermediate', 'Advanced'] as $lvl): ?>
                                <option value="<?php echo $lvl; ?>" <?php echo (($course['level'] ?? 'Beginner') === $lvl) ? 'selected' : ''; ?>><?php echo $lvl; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <?php foreach (['draft' => 'Draft', 'published' => 'Published', 'archived' => 'Archived'] as $val => $lbl): ?>
                                <option value="<?php echo $val; ?>" <?php echo (($course['status'] ?? 'draft') === $val) ? 'selected' : ''; ?>><?php echo $lbl; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Thumbnail</label>
                        <?php if (!empty($course['thumbnail'])): ?>
                            <div style="margin-bottom:8px;"><img src="<?php echo e(LMS_UPLOAD_URL . '/' . $course['thumbnail']); ?>" style="max-width:100%;border-radius:4px;"></div>
                        <?php endif; ?>
                        <input type="file" name="thumbnail" accept="image/*">
                        <p class="help-block">JPG, PNG or WebP, up to 5 MB.</p>
                    </div>
                    <div class="form-group">
                        <label>Introductory Video File</label>
                        <?php if (!empty($course['intro_video_path'])): ?>
                            <div style="margin-bottom:8px;">
                                <video controls preload="metadata" style="max-width:100%;border-radius:4px;background:#000;">
                                    <source src="<?php echo e(LMS_UPLOAD_URL . '/' . $course['intro_video_path']); ?>">
                                    Your browser does not support HTML5 video.
                                </video>
                            </div>
                        <?php endif; ?>
                        <input type="file" name="intro_video" accept="video/*">
                        <p class="help-block">MP4, WebM, OGG or MOV, up to 512 MB. Leave empty to keep existing.</p>
                    </div>
                    <div class="form-group">
                        <label>…or External Intro Video URL</label>
                        <input type="url" name="intro_video_url" class="form-control" value="<?php echo e($course['intro_video_url'] ?? ''); ?>" placeholder="https://…/video.mp4 or YouTube link">
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $editing ? 'Save Changes' : 'Create Course'; ?></button>
                    <a href="<?php echo e(url('teacher/courses.php')); ?>" class="btn btn-default">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</form>

<?php lms_layout_footer(); ?>
