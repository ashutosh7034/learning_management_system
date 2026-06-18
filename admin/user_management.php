<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../includes/models/report_model.php';

require_admin();

$users = lms_users_for_management();

lms_layout_header([
    'title' => 'User Management',
    'heading' => 'User Management',
    'icon' => 'fa fa-users',
    'breadcrumb' => ['User Management' => null],
]);
?>

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Users</h3>
        <div class="box-tools pull-right"><a class="btn btn-success btn-sm" href="<?php echo e(url('admin/user_register.php')); ?>"><i class="fa fa-plus"></i> New User</a></div>
    </div>
    <div class="box-body table-responsive">
        <table id="usersTable" class="table table-bordered table-hover">
            <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Username</th><th>Role</th></tr></thead>
            <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo (int) $user['user_id']; ?></td>
                    <td><?php echo e($user['user_name']); ?></td>
                    <td><?php echo e($user['email_id']); ?></td>
                    <td><?php echo e($user['username'] ?: '-'); ?></td>
                    <td><?php echo e(role_label($user['role_id'])); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php lms_layout_footer(['scripts' => '<script>$(function(){ $("#usersTable").DataTable({ "order": [[0, "desc"]] }); });</script>']); ?>
