<?php
/**
 * Student course detail with enrollment action and curriculum preview.
 */

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../includes/models/enrollment_model.php';
require_once __DIR__ . '/../includes/models/progress_model.php';

require_role(ROLE_STUDENT, ROLE_TEACHER, ROLE_MENTOR, ROLE_ADMIN, ROLE_SUPER_ADMIN);

$studentId = current_user_id();
$courseId = (int) ($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_require();
    if (current_role_id() !== ROLE_STUDENT) {
        flash('danger', 'Only students can enroll in courses.');
        redirect('student/course.php?id=' . (int) ($_POST['course_id'] ?? 0));
    }
    $postedCourseId = (int) ($_POST['course_id'] ?? 0);
    if (lms_student_enroll($studentId, $postedCourseId)) {
        flash('success', 'Enrollment confirmed.');
    } else {
        flash('danger', 'Could not enroll in that course.');
    }
    redirect('student/course.php?id=' . $postedCourseId);
}

$course = lms_published_course_get($courseId, $studentId);
if (!$course) {
    flash('danger', 'Course not found or not available.');
    redirect('student/catalog.php');
}

$sections = lms_course_curriculum($courseId);
$isEnrolled = !empty($course['enrollment_id']) && ($course['enrollment_status'] ?? '') !== 'dropped';
$firstLesson = $isEnrolled ? lms_first_lesson_for_enrollment((int) $course['enrollment_id']) : null;
$typeIcon = ['video' => 'fa-play-circle', 'pdf' => 'fa-file-pdf-o', 'quiz' => 'fa-question-circle'];

lms_layout_header([
    'title'      => $course['title'],
    'heading'    => 'Course Detail',
    'icon'       => 'fa fa-book',
    'breadcrumb' => ['Course Catalog' => url('student/catalog.php'), $course['title'] => null],
]);
?>

<div class="row">
    <div class="col-md-8">
        <div class="box box-primary">
            <div class="box-body">
                <h2 style="margin-top:0;"><?php echo e($course['title']); ?></h2>
                <p class="lead"><?php echo e($course['summary'] ?: 'No summary available.'); ?></p>
                <p>
                    <span class="label label-info"><?php echo e($course['level']); ?></span>
                    <?php if (!empty($course['category_name'])): ?><span class="label label-default"><?php echo e($course['category_name']); ?></span><?php endif; ?>
                </p>
                <hr>
                <div><?php echo nl2br(e($course['description'] ?: $course['summary'] ?: 'Course description will be added soon.')); ?></div>
            </div>
        </div>

        <div class="box box-solid" id="curriculum">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-list"></i> Curriculum</h3>
            </div>
            <div class="box-body">
                <?php if (empty($sections)): ?>
                    <p class="text-muted">No curriculum has been published yet.</p>
                <?php else: foreach ($sections as $section): ?>
                    <h4><i class="fa fa-folder-open"></i> <?php echo e($section['title']); ?></h4>
                    <table class="table table-condensed">
                        <tbody>
                        <?php if (empty($section['lessons'])): ?>
                            <tr><td class="text-muted">No lessons in this section.</td></tr>
                        <?php else: foreach ($section['lessons'] as $lesson): ?>
                            <tr>
                                <td>
                                    <i class="fa <?php echo e($typeIcon[$lesson['type']] ?? 'fa-file-o'); ?>"></i>
                                    <?php echo e($lesson['title']); ?>
                                    <?php if ((int) $lesson['is_free'] === 1): ?><span class="label label-success">Free preview</span><?php endif; ?>
                                </td>
                                <td class="text-right"><span class="label label-default"><?php echo e(ucfirst($lesson['type'])); ?></span></td>
                            </tr>
                        <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="box box-solid">
            <div class="box-body">
                <?php if (!empty($course['intro_video_path'])): ?>
                    <div style="margin-bottom:12px;">
                        <video controls preload="metadata" style="width:100%;height:190px;background:#000;border-radius:4px;">
                            <source src="<?php echo e(LMS_UPLOAD_URL . '/' . $course['intro_video_path']); ?>">
                            Your browser does not support HTML5 video.
                        </video>
                    </div>
                <?php elseif (!empty($course['intro_video_url'])): ?>
                    <div style="margin-bottom:12px;">
                        <?php 
                            $isYoutube = (strpos($course['intro_video_url'], 'youtube.com') !== false || strpos($course['intro_video_url'], 'youtu.be') !== false);
                            if ($isYoutube) {
                                preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $course['intro_video_url'], $match);
                                $youtubeId = $match[1] ?? '';
                            }
                        ?>
                        <?php if ($isYoutube && !empty($youtubeId)): ?>
                            <iframe style="width:100%;height:190px;border-radius:4px;border:none;" src="https://www.youtube.com/embed/<?php echo $youtubeId; ?>" allowfullscreen></iframe>
                        <?php else: ?>
                            <video controls preload="metadata" style="width:100%;height:190px;background:#000;border-radius:4px;">
                                <source src="<?php echo e($course['intro_video_url']); ?>">
                                Your browser does not support HTML5 video.
                            </video>
                        <?php endif; ?>
                    </div>
                <?php elseif (!empty($course['thumbnail'])): ?>
                    <img src="<?php echo e(LMS_UPLOAD_URL . '/' . $course['thumbnail']); ?>" alt="" style="width:100%;height:190px;object-fit:cover;border-radius:4px;margin-bottom:12px;">
                <?php else: ?>
                    <div style="height:190px;background:#eef2f7;border-radius:4px;margin-bottom:12px;display:flex;align-items:center;justify-content:center;color:#8a98a8;">
                        <i class="fa fa-graduation-cap fa-4x"></i>
                    </div>
                <?php endif; ?>
                <p><i class="fa fa-user"></i> <?php echo e($course['teacher_name'] ?: 'Teacher'); ?></p>
                <p><i class="fa fa-folder"></i> <?php echo (int) $course['sections']; ?> sections</p>
                <p><i class="fa fa-list"></i> <?php echo (int) $course['lessons']; ?> lessons</p>
            </div>
            <div class="box-footer">
                <?php if (current_role_id() !== ROLE_STUDENT): ?>
                    <div class="alert alert-info text-center" style="margin-bottom:0; padding:10px;"><i class="fa fa-info-circle"></i> You are viewing this course as a <?php echo e(role_label(current_role_id())); ?>.</div>
                <?php elseif ($isEnrolled): ?>
                    <a href="<?php echo e($firstLesson ? url('student/lesson.php?id=' . (int) $firstLesson['id']) : '#curriculum'); ?>" class="btn btn-success btn-block"><i class="fa fa-play"></i> Continue</a>
                    <a href="<?php echo e(url('student/my_courses.php')); ?>" class="btn btn-default btn-block"><i class="fa fa-bookmark"></i> My Courses</a>
                <?php else: ?>
                    <form method="post">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="course_id" value="<?php echo (int) $course['id']; ?>">
                        <button class="btn btn-primary btn-block"><i class="fa fa-plus"></i> Enroll Now</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php lms_layout_footer(); ?>
