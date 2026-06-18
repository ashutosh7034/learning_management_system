<?php
/**
 * Course Curriculum builder — manage sections and lessons for a course.
 * (Content uploads for video/pdf lessons and quiz authoring arrive in later modules;
 *  this screen defines the curriculum structure and lesson shells.)
 */

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../includes/models/course_model.php';

require_teacher();

$isAdmin   = is_admin_role(current_role_id());
$teacherId = current_user_id();

$courseId = (int) ($_GET['id'] ?? 0);
$course   = lms_course_get($courseId);
if (!$course) {
    flash('danger', 'Course not found.');
    redirect('teacher/courses.php');
}
if (!$isAdmin && (int) $course['teacher_id'] !== $teacherId) {
    http_response_code(403);
    flash('danger', 'You can only manage your own courses.');
    redirect('teacher/courses.php');
}

/* ---------------- POST actions ---------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_require();
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'add_section':
            $title = trim($_POST['title'] ?? '');
            if ($title !== '') {
                lms_section_create($courseId, $title);
                flash('success', 'Section added.');
            }
            break;

        case 'rename_section':
            $sid = (int) ($_POST['section_id'] ?? 0);
            $title = trim($_POST['title'] ?? '');
            if ($sid && $title !== '') {
                lms_section_update($sid, $title);
                flash('success', 'Section renamed.');
            }
            break;

        case 'delete_section':
            $sid = (int) ($_POST['section_id'] ?? 0);
            if ($sid) {
                lms_section_delete($sid);
                flash('success', 'Section deleted.');
            }
            break;

        case 'add_lesson':
            $sid   = (int) ($_POST['section_id'] ?? 0);
            $title = trim($_POST['title'] ?? '');
            $type  = $_POST['type'] ?? 'video';
            if (!in_array($type, ['video', 'pdf', 'quiz'], true)) { $type = 'video'; }
            $isFree = isset($_POST['is_free']) ? 1 : 0;
            if ($sid && $title !== '') {
                lms_lesson_create($sid, $courseId, $title, $type, $isFree);
                flash('success', 'Lesson added.');
            }
            break;

        case 'update_lesson':
            $lid   = (int) ($_POST['lesson_id'] ?? 0);
            $title = trim($_POST['title'] ?? '');
            $type  = $_POST['type'] ?? 'video';
            if (!in_array($type, ['video', 'pdf', 'quiz'], true)) { $type = 'video'; }
            $isFree = isset($_POST['is_free']) ? 1 : 0;
            if ($lid && $title !== '') {
                lms_lesson_update($lid, $title, $type, $isFree);
                flash('success', 'Lesson updated.');
            }
            break;

        case 'delete_lesson':
            $lid = (int) ($_POST['lesson_id'] ?? 0);
            if ($lid) {
                lms_lesson_delete($lid);
                flash('success', 'Lesson deleted.');
            }
            break;
    }
    redirect('teacher/course_builder.php?id=' . $courseId);
}

$sections = lms_sections_for_course($courseId);

$typeIcon = ['video' => 'fa-play-circle', 'pdf' => 'fa-file-pdf-o', 'quiz' => 'fa-question-circle'];

lms_layout_header([
    'title'      => 'Curriculum — ' . $course['title'],
    'heading'    => 'Curriculum',
    'icon'       => 'fa fa-list',
    'breadcrumb' => ['Course Management' => url('teacher/courses.php'), $course['title'] => null],
]);
?>

<div class="row">
    <div class="col-md-12">
        <div class="box box-solid">
            <div class="box-body">
                <strong><i class="fa fa-book"></i> <?php echo e($course['title']); ?></strong>
                <?php echo lms_status_badge($course['status']); ?>
                <a href="<?php echo e(url('teacher/course_edit.php?id=' . $courseId)); ?>" class="btn btn-xs btn-default pull-right"><i class="fa fa-pencil"></i> Edit Details</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="box box-success">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-plus"></i> Add Section</h3></div>
            <form method="post">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="action" value="add_section">
                <div class="box-body">
                    <div class="form-group">
                        <label>Section title</label>
                        <input type="text" name="title" class="form-control" required placeholder="e.g. Getting Started">
                    </div>
                </div>
                <div class="box-footer"><button class="btn btn-success btn-block"><i class="fa fa-plus"></i> Add Section</button></div>
            </form>
        </div>
    </div>

    <div class="col-md-8">
        <?php if (empty($sections)): ?>
            <div class="callout callout-info"><p>No sections yet. Add your first section to start building the curriculum.</p></div>
        <?php else: foreach ($sections as $s): $lessons = lms_lessons_for_section($s['id']); ?>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-folder"></i> <?php echo e($s['title']); ?></h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-xs btn-default" data-toggle="collapse" data-target="#sec-edit-<?php echo (int) $s['id']; ?>"><i class="fa fa-pencil"></i></button>
                        <form method="post" style="display:inline;" onsubmit="return confirm('Delete this section and all its lessons?');">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="action" value="delete_section">
                            <input type="hidden" name="section_id" value="<?php echo (int) $s['id']; ?>">
                            <button class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>
                        </form>
                    </div>
                </div>
                <div class="box-body">
                    <!-- rename -->
                    <div id="sec-edit-<?php echo (int) $s['id']; ?>" class="collapse" style="margin-bottom:12px;">
                        <form method="post" class="form-inline">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="action" value="rename_section">
                            <input type="hidden" name="section_id" value="<?php echo (int) $s['id']; ?>">
                            <input type="text" name="title" class="form-control input-sm" value="<?php echo e($s['title']); ?>" required>
                            <button class="btn btn-sm btn-primary">Rename</button>
                        </form>
                    </div>

                    <!-- lessons -->
                    <table class="table table-condensed">
                        <tbody>
                        <?php if (empty($lessons)): ?>
                            <tr><td class="text-muted">No lessons in this section yet.</td></tr>
                        <?php else: foreach ($lessons as $l): ?>
                            <tr>
                                <td>
                                    <i class="fa <?php echo $typeIcon[$l['type']] ?? 'fa-file'; ?>"></i>
                                    <?php echo e($l['title']); ?>
                                    <small class="label label-default"><?php echo e(ucfirst($l['type'])); ?></small>
                                    <?php if ((int) $l['is_free'] === 1): ?><small class="label label-success">Free preview</small><?php endif; ?>
                                </td>
                                <td class="text-right" style="white-space:nowrap;">
                                    <a href="<?php echo e(url('teacher/lesson_content.php?lesson=' . (int) $l['id'])); ?>" class="btn btn-xs btn-info" title="Manage content"><i class="fa fa-upload"></i></a>
                                    <button class="btn btn-xs btn-default" data-toggle="collapse" data-target="#les-edit-<?php echo (int) $l['id']; ?>"><i class="fa fa-pencil"></i></button>
                                    <form method="post" style="display:inline;" onsubmit="return confirm('Delete this lesson?');">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="action" value="delete_lesson">
                                        <input type="hidden" name="lesson_id" value="<?php echo (int) $l['id']; ?>">
                                        <button class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            <tr id="les-edit-<?php echo (int) $l['id']; ?>" class="collapse">
                                <td colspan="2">
                                    <form method="post" class="form-inline">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="action" value="update_lesson">
                                        <input type="hidden" name="lesson_id" value="<?php echo (int) $l['id']; ?>">
                                        <input type="text" name="title" class="form-control input-sm" value="<?php echo e($l['title']); ?>" required>
                                        <select name="type" class="form-control input-sm">
                                            <?php foreach (['video' => 'Video', 'pdf' => 'PDF', 'quiz' => 'Quiz'] as $tv => $tl): ?>
                                                <option value="<?php echo $tv; ?>" <?php echo ($l['type'] === $tv) ? 'selected' : ''; ?>><?php echo $tl; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <label class="checkbox-inline"><input type="checkbox" name="is_free" <?php echo ((int) $l['is_free'] === 1) ? 'checked' : ''; ?>> Free</label>
                                        <button class="btn btn-sm btn-primary">Save</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; endif; ?>
                        </tbody>
                    </table>

                    <!-- add lesson -->
                    <form method="post" class="form-inline" style="margin-top:8px;">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="action" value="add_lesson">
                        <input type="hidden" name="section_id" value="<?php echo (int) $s['id']; ?>">
                        <input type="text" name="title" class="form-control input-sm" placeholder="New lesson title" required>
                        <select name="type" class="form-control input-sm">
                            <option value="video">Video</option>
                            <option value="pdf">PDF</option>
                            <option value="quiz">Quiz</option>
                        </select>
                        <label class="checkbox-inline"><input type="checkbox" name="is_free"> Free</label>
                        <button class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Add Lesson</button>
                    </form>
                </div>
            </div>
        <?php endforeach; endif; ?>
    </div>
</div>

<?php lms_layout_footer(); ?>
