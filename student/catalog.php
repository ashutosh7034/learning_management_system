<?php
/**
 * Student catalog: browse, search, filter and enroll in published courses.
 */

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../includes/models/course_model.php';
require_once __DIR__ . '/../includes/models/enrollment_model.php';

require_role(ROLE_STUDENT, ROLE_TEACHER, ROLE_MENTOR, ROLE_ADMIN, ROLE_SUPER_ADMIN);

$studentId = current_user_id();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_require();
    if (current_role_id() !== ROLE_STUDENT) {
        flash('danger', 'Only students can enroll in courses.');
        redirect('student/catalog.php');
    }
    $courseId = (int) ($_POST['course_id'] ?? 0);
    if (lms_student_enroll($studentId, $courseId)) {
        flash('success', 'You are enrolled. The course is ready in My Courses.');
        redirect('student/course.php?id=' . $courseId);
    }
    flash('danger', 'Could not enroll in that course.');
    redirect('student/catalog.php');
}

$filters = lms_catalog_filters();
$courses = lms_catalog_courses($filters, $studentId);
$categories = lms_categories_all();

lms_layout_header([
    'title'      => 'Course Catalog',
    'heading'    => 'Course Catalog',
    'icon'       => 'fa fa-th-list',
    'breadcrumb' => ['Course Catalog' => null],
]);
?>

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-search"></i> Find Courses</h3>
    </div>
    <form method="get">
        <div class="box-body">
            <div class="row">
                <div class="col-md-5">
                    <input type="text" name="q" class="form-control" value="<?php echo e($filters['q']); ?>" placeholder="Search title, summary or teacher">
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-control">
                        <option value="0">All categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo (int) $cat['id']; ?>" <?php echo ((int) $filters['category'] === (int) $cat['id']) ? 'selected' : ''; ?>>
                                <?php echo e($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="level" class="form-control">
                        <option value="">All levels</option>
                        <?php foreach (['Beginner', 'Intermediate', 'Advanced'] as $level): ?>
                            <option value="<?php echo e($level); ?>" <?php echo ($filters['level'] === $level) ? 'selected' : ''; ?>><?php echo e($level); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary btn-block"><i class="fa fa-filter"></i> Filter</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="row">
    <?php if (empty($courses)): ?>
        <div class="col-xs-12">
            <div class="callout callout-info">No published courses match your filters.</div>
        </div>
    <?php else: foreach ($courses as $course): ?>
        <div class="col-md-4 col-sm-6">
            <div class="box box-solid">
                <div class="box-body">
                    <?php if (!empty($course['thumbnail'])): ?>
                        <img src="<?php echo e(LMS_UPLOAD_URL . '/' . $course['thumbnail']); ?>" alt="" style="width:100%;height:150px;object-fit:cover;border-radius:4px;margin-bottom:10px;">
                    <?php else: ?>
                        <div style="height:150px;background:#eef2f7;border-radius:4px;margin-bottom:10px;display:flex;align-items:center;justify-content:center;color:#8a98a8;">
                            <i class="fa fa-book fa-3x"></i>
                        </div>
                    <?php endif; ?>
                    <h4 style="margin-top:0;"><a href="<?php echo e(url('student/course.php?id=' . (int) $course['id'])); ?>"><?php echo e($course['title']); ?></a></h4>
                    <p class="text-muted" style="min-height:40px;"><?php echo e($course['summary'] ?: 'No summary available.'); ?></p>
                    <p>
                        <span class="label label-info"><?php echo e($course['level']); ?></span>
                        <?php if (!empty($course['category_name'])): ?><span class="label label-default"><?php echo e($course['category_name']); ?></span><?php endif; ?>
                    </p>
                    <p class="text-muted">
                        <i class="fa fa-user"></i> <?php echo e($course['teacher_name'] ?: 'Teacher'); ?>
                        &nbsp; <i class="fa fa-list"></i> <?php echo (int) $course['lessons']; ?> lessons
                    </p>
                </div>
                <div class="box-footer">
                    <?php if (current_role_id() !== ROLE_STUDENT): ?>
                        <a href="<?php echo e(url('student/course.php?id=' . (int) $course['id'])); ?>" class="btn btn-primary btn-block"><i class="fa fa-search"></i> View Course</a>
                    <?php elseif (!empty($course['enrollment_id'])): ?>
                        <a href="<?php echo e(url('student/course.php?id=' . (int) $course['id'])); ?>" class="btn btn-success btn-block"><i class="fa fa-play"></i> Continue</a>
                    <?php else: ?>
                        <form method="post">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="course_id" value="<?php echo (int) $course['id']; ?>">
                            <button class="btn btn-primary btn-block"><i class="fa fa-plus"></i> Enroll</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; endif; ?>
</div>

<?php lms_layout_footer(); ?>
