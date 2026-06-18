<?php header("Location: profile.php"); exit; ?>
<?php
$profileAlertType = '';
$profileAlertMessage = '';
$passwordAlertType = '';
$passwordAlertMessage = '';

$currentUserId = intval($userid ?? 0);
$currentRoleId = intval($usertype ?? 0);

if ($currentUserId <= 0 || $currentRoleId <= 0) {
  $profileAlertType = 'danger';
  $profileAlertMessage = 'Unable to load profile. Please login again.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile']) && $currentUserId > 0 && $currentRoleId > 0) {
  $profileName = trim((string) ($_POST['profile_name'] ?? ''));
  $profileEmail = trim((string) ($_POST['profile_email'] ?? ''));
  $profilePhone = trim((string) ($_POST['profile_phone'] ?? ''));

  if ($profileName === '') {
    $profileAlertType = 'warning';
    $profileAlertMessage = 'Name is required.';
  } elseif ($profileEmail !== '' && !filter_var($profileEmail, FILTER_VALIDATE_EMAIL)) {
    $profileAlertType = 'warning';
    $profileAlertMessage = 'Please enter a valid email address.';
  } else {
    mysqli_begin_transaction($db_handle->conn);
    $ok = true;

      $loginUpdateSql = "UPDATE lms_login SET username = ? WHERE user_id = ?";
    $loginStmt = mysqli_prepare($db_handle->conn, $loginUpdateSql);
    if ($loginStmt) {
        mysqli_stmt_bind_param($loginStmt, 'si', $profileName, $currentUserId);
      $ok = $ok && mysqli_stmt_execute($loginStmt);
      mysqli_stmt_close($loginStmt);
    } else {
      $ok = false;
    }

    if ($ok) {
        $profileCheckSql = "SELECT user_id FROM lms_user_master WHERE user_id = ? LIMIT 1";
      $profileCheckStmt = mysqli_prepare($db_handle->conn, $profileCheckSql);
      if ($profileCheckStmt) {
          mysqli_stmt_bind_param($profileCheckStmt, 'i', $currentUserId);
        mysqli_stmt_execute($profileCheckStmt);
        $profileCheckResult = mysqli_stmt_get_result($profileCheckStmt);
        $profileExists = ($profileCheckResult && mysqli_num_rows($profileCheckResult) > 0);
        mysqli_stmt_close($profileCheckStmt);

        if ($profileExists) {
            $profileUpdateSql = "UPDATE lms_user_master SET user_name = ?, email_id = ?, phone_number = ? WHERE user_id = ?";
          $profileUpdateStmt = mysqli_prepare($db_handle->conn, $profileUpdateSql);
          if ($profileUpdateStmt) {
              mysqli_stmt_bind_param($profileUpdateStmt, 'sssi', $profileName, $profileEmail, $profilePhone, $currentUserId);
            $ok = $ok && mysqli_stmt_execute($profileUpdateStmt);
            mysqli_stmt_close($profileUpdateStmt);
          } else {
            $ok = false;
          }
        } else {
          $profileInsertSql = "INSERT INTO lms_user_master (user_id, user_name, email_id, phone_number, department_id, role_id, student_id) VALUES (?, ?, ?, ?, 0, ?, 0)";
          $profileInsertStmt = mysqli_prepare($db_handle->conn, $profileInsertSql);
          if ($profileInsertStmt) {
            mysqli_stmt_bind_param($profileInsertStmt, 'isssi', $currentUserId, $profileName, $profileEmail, $profilePhone, $currentRoleId);
            $ok = $ok && mysqli_stmt_execute($profileInsertStmt);
            mysqli_stmt_close($profileInsertStmt);
          } else {
            $ok = false;
          }
        }
      } else {
        $ok = false;
      }
    }

    if ($ok) {
      mysqli_commit($db_handle->conn);
      $profileAlertType = 'success';
      $profileAlertMessage = 'Profile updated successfully.';
      $username = $profileName;
      $name = $profileName;
    } else {
      mysqli_rollback($db_handle->conn);
      $profileAlertType = 'danger';
      $profileAlertMessage = 'Unable to update profile right now.';
    }
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password']) && $currentUserId > 0 && $currentRoleId > 0) {
  $currentPassword = trim((string) ($_POST['current_password'] ?? ''));
  $newPassword = trim((string) ($_POST['new_password'] ?? ''));
  $confirmPassword = trim((string) ($_POST['confirm_password'] ?? ''));

  if ($currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
    $passwordAlertType = 'warning';
    $passwordAlertMessage = 'All password fields are required.';
  } elseif ($newPassword !== $confirmPassword) {
    $passwordAlertType = 'warning';
    $passwordAlertMessage = 'New password and confirm password must match.';
  } else {
      $currentPasswordSql = "SELECT password FROM lms_login WHERE user_id = ? LIMIT 1";
    $currentPasswordStmt = mysqli_prepare($db_handle->conn, $currentPasswordSql);
    if ($currentPasswordStmt) {
        mysqli_stmt_bind_param($currentPasswordStmt, 'i', $currentUserId);
      mysqli_stmt_execute($currentPasswordStmt);
      $currentPasswordResult = mysqli_stmt_get_result($currentPasswordStmt);
      $currentPasswordRow = $currentPasswordResult ? mysqli_fetch_assoc($currentPasswordResult) : null;
      mysqli_stmt_close($currentPasswordStmt);

      if (!$currentPasswordRow || (string) ($currentPasswordRow['password'] ?? '') !== $currentPassword) {
        $passwordAlertType = 'danger';
        $passwordAlertMessage = 'Current password is incorrect.';
      } else {
          $updatePasswordSql = "UPDATE lms_login SET password = ? WHERE user_id = ?";
        $updatePasswordStmt = mysqli_prepare($db_handle->conn, $updatePasswordSql);
        if ($updatePasswordStmt) {
            mysqli_stmt_bind_param($updatePasswordStmt, 'si', $newPassword, $currentUserId);
          if (mysqli_stmt_execute($updatePasswordStmt)) {
            $passwordAlertType = 'success';
            $passwordAlertMessage = 'Password updated successfully.';
          } else {
            $passwordAlertType = 'danger';
            $passwordAlertMessage = 'Unable to update password right now.';
          }
          mysqli_stmt_close($updatePasswordStmt);
        } else {
          $passwordAlertType = 'danger';
          $passwordAlertMessage = 'Unable to prepare password update.';
        }
      }
    } else {
      $passwordAlertType = 'danger';
      $passwordAlertMessage = 'Unable to verify current password.';
    }
  }
}

$profileData = array(
  'username' => (string) ($username ?? ''),
  'role_name' => (string) ($role_name ?? ''),
  'email_id' => '',
  'phone_number' => '',
  'department_name' => ''
);

if ($currentUserId > 0 && $currentRoleId > 0) {
    $profileSql = "SELECT l.username, r.role_name, u.user_name, u.email_id, u.phone_number, d.department_name
                   FROM lms_login l
                   LEFT JOIN lms_user_master u ON u.user_id = l.user_id
                   LEFT JOIN lms_role_master r ON r.role_id = u.role_id
                   LEFT JOIN lms_department_master d ON d.department_id = u.department_id
                   WHERE l.user_id = ?
                   LIMIT 1";
  $profileStmt = mysqli_prepare($db_handle->conn, $profileSql);

  if ($profileStmt) {
      mysqli_stmt_bind_param($profileStmt, 'i', $currentUserId);
    mysqli_stmt_execute($profileStmt);
    $profileResult = mysqli_stmt_get_result($profileStmt);
    if ($profileResult && ($row = mysqli_fetch_assoc($profileResult))) {
      $displayName = trim((string) ($row['user_name'] ?? ''));
      if ($displayName === '') {
        $displayName = (string) ($row['username'] ?? '');
      }
      $profileData['username'] = $displayName;
      $profileData['role_name'] = (string) ($row['role_name'] ?? $profileData['role_name']);
      $profileData['email_id'] = (string) ($row['email_id'] ?? '');
      $profileData['phone_number'] = (string) ($row['phone_number'] ?? '');
      $profileData['department_name'] = (string) ($row['department_name'] ?? '');
    }
    mysqli_stmt_close($profileStmt);
  }
}
?>

<style>
  .profile-summary-card {
    border: 0;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 12px 28px rgba(18, 36, 66, 0.14);
  }

  .profile-summary-head {
    height: 74px;
    background: linear-gradient(120deg, #273c8e 0%, #1aa7cf 100%);
  }

  .profile-summary-body {
    margin-top: -44px;
    padding: 0 18px 18px;
  }

  .profile-avatar {
    width: 108px;
    height: 108px;
    border-radius: 50%;
    border: 4px solid #fff;
    box-shadow: 0 8px 18px rgba(18, 36, 66, 0.22);
  }

  .profile-display-name {
    margin: 12px 0 6px;
    font-size: 34px;
    font-weight: 700;
    color: #1f2937;
    letter-spacing: 0.2px;
  }

  .profile-role-pill {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 999px;
    background: #eaf4ff;
    color: #1f5ea8;
    font-weight: 700;
    font-size: 13px;
    margin-bottom: 14px;
  }

  .profile-meta-box {
    border: 1px solid #e7ebf2;
    border-radius: 10px;
    overflow: hidden;
  }

  .profile-meta-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 14px;
    border-bottom: 1px solid #edf1f6;
    background: #fff;
  }

  .profile-meta-row:last-child {
    border-bottom: 0;
  }

  .profile-meta-label {
    color: #374151;
    font-weight: 700;
  }

  .profile-meta-value {
    color: #111827;
    font-weight: 600;
    text-align: right;
  }

  .profile-online-pill {
    display: inline-block;
    padding: 2px 10px;
    border-radius: 999px;
    background: #e8f8ee;
    color: #18794e;
    font-weight: 700;
    font-size: 12px;
  }

  .profile-form-card {
    border: 0;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 12px 28px rgba(18, 36, 66, 0.1);
  }

  .profile-form-card .box-header {
    background: linear-gradient(120deg, #273c8e 0%, #1aa7cf 100%);
    color: #fff;
    border-bottom: 0;
    padding: 14px 18px;
  }

  .profile-form-card .box-title {
    color: #fff;
    font-weight: 700;
    letter-spacing: 0.2px;
  }

  .profile-form-card .box-body {
    padding: 18px 18px 8px;
    background: #fff;
  }

  .profile-form-card .control-label {
    color: #1f2937;
    font-weight: 700;
    padding-top: 10px;
  }

  .profile-input-group .input-group-addon {
    border-radius: 8px 0 0 8px;
    border: 1px solid #dbe3ec;
    border-right: 0;
    background: #f8fafc;
    color: #4b5563;
    min-width: 40px;
    text-align: center;
  }

  .profile-input-group .form-control {
    height: 42px;
    border-radius: 0 8px 8px 0;
    border: 1px solid #dbe3ec;
    box-shadow: none;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
  }

  .profile-input-group .form-control:focus {
    border-color: #1aa7cf;
    box-shadow: 0 0 0 3px rgba(26, 167, 207, 0.16);
  }

  .profile-form-card .box-footer {
    border-top: 1px solid #edf1f6;
    background: #fbfdff;
    padding: 12px 18px;
  }

  .profile-form-card .profile-save-btn {
    display: inline-block;
    border: 0;
    border-radius: 8px;
    padding: 10px 18px;
    background: linear-gradient(120deg, #1aa7cf 0%, #44c4e8 100%) !important;
    color: #fff !important;
    font-weight: 700;
    letter-spacing: 0.2px;
    box-shadow: 0 8px 18px rgba(30, 167, 207, 0.28);
    cursor: pointer;
  }

  .profile-form-card .profile-save-btn:hover,
  .profile-form-card .profile-save-btn:focus,
  .profile-form-card .profile-save-btn:active {
    color: #fff !important;
    background: linear-gradient(120deg, #1596ba 0%, #38b6d8 100%) !important;
  }
</style>

<div class="content-wrapper">
  <section class="content-header">
    <ol class="breadcrumb">
      <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Profile</li>
    </ol>
  </section>

  <section class="content" style="margin-top: 20px;">
    <div id="profile"></div>
    <div class="row">
      <div class="col-md-4">
        <div class="box profile-summary-card">
          <div class="profile-summary-head"></div>
          <div class="box-body box-profile text-center profile-summary-body">
            <img class="profile-avatar" src="dist/img/user2-160x160.jpg" alt="User profile picture">
            <h3 class="profile-display-name text-center"><?php echo htmlspecialchars($profileData['username']); ?></h3>
            <div class="profile-role-pill"><?php echo htmlspecialchars($profileData['role_name']); ?></div>

            <div class="profile-meta-box">
              <div class="profile-meta-row">
                <span class="profile-meta-label">User ID</span>
                <span class="profile-meta-value"><?php echo intval($currentUserId); ?></span>
              </div>
              <div class="profile-meta-row">
                <span class="profile-meta-label">Department</span>
                <span class="profile-meta-value"><?php echo htmlspecialchars($profileData['department_name'] !== '' ? $profileData['department_name'] : 'N/A'); ?></span>
              </div>
              <div class="profile-meta-row">
                <span class="profile-meta-label">Status</span>
                <span class="profile-meta-value"><span class="profile-online-pill">Online</span></span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-8">
        <div class="box profile-form-card">
          <div class="box-header with-border">
            <h3 class="box-title">Profile Details</h3>
          </div>

          <?php if ($profileAlertMessage !== '') { ?>
            <div class="box-body" style="padding-bottom:0;">
              <div class="alert alert-<?php echo htmlspecialchars($profileAlertType); ?> alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo htmlspecialchars($profileAlertMessage); ?>
              </div>
            </div>
          <?php } ?>

          <form class="form-horizontal" method="POST">
            <div class="box-body">
              <div class="form-group">
                <label class="col-sm-3 control-label">Name</label>
                <div class="col-sm-9">
                  <div class="input-group profile-input-group">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <input type="text" class="form-control" name="profile_name" value="<?php echo htmlspecialchars($profileData['username']); ?>" required>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Role</label>
                <div class="col-sm-9">
                  <div class="input-group profile-input-group">
                    <span class="input-group-addon"><i class="fa fa-shield"></i></span>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($profileData['role_name']); ?>" readonly>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Email</label>
                <div class="col-sm-9">
                  <div class="input-group profile-input-group">
                    <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                    <input type="email" class="form-control" name="profile_email" value="<?php echo htmlspecialchars($profileData['email_id']); ?>" placeholder="Enter email">
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Phone</label>
                <div class="col-sm-9">
                  <div class="input-group profile-input-group">
                    <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                    <input type="text" class="form-control" name="profile_phone" value="<?php echo htmlspecialchars($profileData['phone_number']); ?>" placeholder="Enter phone number">
                  </div>
                </div>
              </div>
            </div>
            <div class="box-footer">
              <input type="hidden" name="update_profile" value="1">
              <button type="submit" class="pull-right profile-save-btn"><i class="fa fa-check"></i> Update Profile</button>
            </div>
          </form>
        </div>

        <div id="password" style="margin-top: 20px;"></div>
        <div class="box profile-form-card">
          <div class="box-header with-border">
            <h3 class="box-title">Update Password</h3>
          </div>

          <?php if ($passwordAlertMessage !== '') { ?>
            <div class="box-body" style="padding-bottom:0;">
              <div class="alert alert-<?php echo htmlspecialchars($passwordAlertType); ?> alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo htmlspecialchars($passwordAlertMessage); ?>
              </div>
            </div>
          <?php } ?>

          <form class="form-horizontal" method="POST">
            <div class="box-body">
              <div class="form-group">
                <label class="col-sm-3 control-label">Current Password</label>
                <div class="col-sm-9">
                  <div class="input-group profile-input-group">
                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                    <input type="password" class="form-control" name="current_password" placeholder="Enter current password" required>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">New Password</label>
                <div class="col-sm-9">
                  <div class="input-group profile-input-group">
                    <span class="input-group-addon"><i class="fa fa-key"></i></span>
                    <input type="password" class="form-control" name="new_password" placeholder="Enter new password" required>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Confirm Password</label>
                <div class="col-sm-9">
                  <div class="input-group profile-input-group">
                    <span class="input-group-addon"><i class="fa fa-check"></i></span>
                    <input type="password" class="form-control" name="confirm_password" placeholder="Re-enter new password" required>
                  </div>
                </div>
              </div>
            </div>
            <div class="box-footer">
              <input type="hidden" name="update_password" value="1">
              <button type="submit" class="pull-right profile-save-btn"><i class="fa fa-refresh"></i> Update Password</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</div>

<?php include "header/footer.php"; ?>
