<?php
/**
 * Shared LMS layout (AdminLTE shell) for /teacher, /student and new /admin pages.
 *
 * Reuses the existing AdminLTE theme assets (under /admin) via absolute URLs and
 * the DB-driven sidebar (admin/header/side_menu.php), so every role area renders
 * the same navbar, sidebar and footer regardless of its folder.
 *
 *   require_once __DIR__ . '/../includes/bootstrap.php';
 *   require_once __DIR__ . '/../includes/layout.php';
 *   require_login();
 *   lms_layout_header(['title' => 'Courses', 'heading' => 'Course Management',
 *                      'breadcrumb' => ['Courses' => null]]);
 *   // ... page body ...
 *   lms_layout_footer();
 */

require_once __DIR__ . '/bootstrap.php';

if (!function_exists('lms_asset')) {
    /** Absolute URL to a theme asset stored under /admin. */
    function lms_asset($path)
    {
        return LMS_BASE_URL . '/admin/' . ltrim($path, '/');
    }
}

if (!function_exists('lms_current_user_row')) {
    /** Resolve display name + role name for the logged-in user (cached per request). */
    function lms_current_user_row()
    {
        static $cached = null;
        if ($cached !== null) {
            return $cached;
        }
        $loginId = current_login_id();
        $row = db_one(
            "SELECT l.login_id, l.username, l.user_id, u.user_name, u.role_id, r.role_name
               FROM lms_login l
               LEFT JOIN lms_user_master u ON u.user_id = l.user_id
               LEFT JOIN lms_role_master r ON r.role_id = u.role_id
              WHERE l.login_id = ? LIMIT 1",
            'i',
            [$loginId]
        );
        if (!$row) {
            $row = [
                'username'  => 'User',
                'user_id'   => current_user_id(),
                'user_name' => 'User',
                'role_id'   => current_role_id(),
                'role_name' => role_label(current_role_id()),
            ];
        }
        $row['display_name'] = trim((string) ($row['user_name'] ?? '')) !== ''
            ? $row['user_name']
            : ($row['username'] ?? 'User');
        $row['role_name'] = $row['role_name'] ?: role_label($row['role_id'] ?? current_role_id());
        $cached = $row;
        return $cached;
    }
}

if (!function_exists('lms_layout_header')) {
    function lms_layout_header(array $opts = [])
    {
        $title     = $opts['title'] ?? 'LMS';
        $heading   = $opts['heading'] ?? $title;
        $icon      = $opts['icon'] ?? 'fa fa-dashboard';
        $breadcrumb = $opts['breadcrumb'] ?? []; // ['Label' => url|null]

        $user      = lms_current_user_row();
        $name      = $user['display_name'];
        $username  = $user['username'];
        $role_name = $user['role_name'];

        // Variables consumed by side_menu.php.
        $GLOBALS['usertype'] = (int) ($user['role_id'] ?? current_role_id());
        $GLOBALS['userid']   = (int) ($user['user_id'] ?? current_user_id());
        $usertype = $GLOBALS['usertype'];
        $userid   = $GLOBALS['userid'];
        $db_handle = $GLOBALS['db_handle'];

        $dashboardRoute = url(role_home($usertype));
        ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>LMS | <?php echo e($title); ?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="<?php echo e(lms_asset('bootstrap/css/bootstrap.min.css')); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="<?php echo e(lms_asset('dist/css/AdminLTE.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(lms_asset('dist/css/skins/_all-skins.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(lms_asset('plugins/datatables/dataTables.bootstrap.css')); ?>">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <style>
        .lms-content .box-title { font-weight: 600; }
        .lms-thumb { width: 70px; height: 44px; object-fit: cover; border-radius: 4px; background:#eef2f7; }
        .lms-badge-draft { background:#95a5a6; }
        .lms-badge-published { background:#00a65a; }
        .lms-badge-archived { background:#dd4b39; }
    </style>
    <?php if (!empty($opts['head'])) { echo $opts['head']; } ?>
</head>
<body class="hold-transition skin-blue sidebar-mini main-content-responsive">
<div class="wrapper">
    <header class="main-header">
        <a href="<?php echo e($dashboardRoute); ?>" class="logo">
            <span class="logo-mini"><img src="<?php echo e(lms_asset('images/booklogo.webp')); ?>" height="40px" /></span>
            <span class="logo-lg"><img src="<?php echo e(lms_asset('images/booklogo.webp')); ?>" height="40px" /> <small>LMS</small></span>
        </a>
        <nav class="navbar navbar-static-top">
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button"><span class="sr-only">Toggle navigation</span></a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="<?php echo e(lms_asset('dist/img/user2-160x160.jpg')); ?>" class="user-image" alt="User Image">
                            <span class="hidden-xs">&nbsp;Welcome, <?php echo e($name); ?>&nbsp;</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header text-center" style="padding:14px; background-color:#423cbc;">
                                <img src="<?php echo e(lms_asset('dist/img/user2-160x160.jpg')); ?>" class="img-circle" alt="User Image" style="width:90px;height:90px;border:2px solid rgba(255,255,255,0.2);">
                                <p class="text-white" style="margin-top:10px;color:#fff;font-size:17px;">
                                    <?php echo e($username); ?><br>
                                    <small>Role: <span class="badge"><?php echo e($role_name); ?></span></small>
                                </p>
                            </li>
                            <li class="user-footer" style="background-color:#f9f9f9;padding:10px;">
                                <div class="pull-left"><a href="<?php echo e(lms_asset('profile.php')); ?>" class="btn btn-default btn-flat">Profile</a></div>
                                <div class="pull-right"><a href="<?php echo e(url('login/logout.php')); ?>" class="btn btn-danger btn-flat">Sign out</a></div>
                                <div class="clearfix"></div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <aside class="main-sidebar">
        <section class="sidebar">
            <div class="user-panel">
                <div class="pull-left image"><img src="<?php echo e(lms_asset('dist/img/user2-160x160.jpg')); ?>" class="img-circle" alt="User Image"></div>
                <div class="pull-left info">
                    <p><span><?php echo e($role_name); ?></span></p>
                    <a class="badge" href="#" style="background:white;color:green;"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>
            <br />
            <?php
            // Reuse the DB-driven sidebar. It reads $db_handle, $usertype, $userid.
            $studentAdmissionRoute = 'student_admission.php';
            include LMS_ROOT . '/admin/header/side_menu.php';
            ?>
        </section>
    </aside>

    <div class="content-wrapper lms-content">
        <section class="content-header">
            <h1><i class="<?php echo e($icon); ?>"></i> <span><strong><?php echo e($heading); ?></strong></span></h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo e($dashboardRoute); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <?php foreach ($breadcrumb as $label => $href): ?>
                    <?php if ($href): ?>
                        <li><a href="<?php echo e($href); ?>"><?php echo e($label); ?></a></li>
                    <?php else: ?>
                        <li class="active"><?php echo e($label); ?></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ol>
        </section>
        <section class="content">
            <?php echo render_flashes(); ?>
        <?php
    }
}

if (!function_exists('lms_layout_footer')) {
    function lms_layout_footer(array $opts = [])
    {
        ?>
        </section>
    </div><!-- /.content-wrapper -->

    <footer class="main-footer">
        <div class="pull-right hidden-xs"><b>Version</b> 2026.1</div>
        <strong>&copy; <script>document.write(new Date().getFullYear())</script> Learning Management System</strong>
    </footer>
</div><!-- /.wrapper -->

<script src="<?php echo e(lms_asset('bootstrap/js/bootstrap.min.js')); ?>"></script>
<script src="<?php echo e(lms_asset('plugins/datatables/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(lms_asset('plugins/datatables/dataTables.bootstrap.min.js')); ?>"></script>
<script src="<?php echo e(lms_asset('plugins/slimScroll/jquery.slimscroll.min.js')); ?>"></script>
<script src="<?php echo e(lms_asset('plugins/fastclick/fastclick.js')); ?>"></script>
<script src="<?php echo e(lms_asset('dist/js/app.min.js')); ?>"></script>
<?php if (!empty($opts['scripts'])) { echo $opts['scripts']; } ?>
</body>
</html>
        <?php
    }
}
?>
