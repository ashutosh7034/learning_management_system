<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
include "header/header.php";

$passwordAlertType = '';
$passwordAlertMessage = '';

$currentUserId = intval($userid ?? 0);
$currentRoleId = intval($usertype ?? 0);
$closeRoute = ($currentRoleId === 5) ? 'student_dashboard.php' : 'index.php';

if ($currentUserId <= 0 || $currentRoleId <= 0) {
  $passwordAlertType = 'danger';
  $passwordAlertMessage = 'Unable to load password form. Please login again.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password']) && $currentUserId > 0 && $currentRoleId > 0) {
  $currentPassword = (string) ($_POST['current_password'] ?? '');
  $newPassword = (string) ($_POST['new_password'] ?? '');
  $confirmPassword = (string) ($_POST['confirm_password'] ?? '');

  if ($currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
    $passwordAlertType = 'warning';
    $passwordAlertMessage = 'All password fields are required.';
  } elseif ($newPassword !== $confirmPassword) {
    $passwordAlertType = 'warning';
    $passwordAlertMessage = 'New password and confirm password must match.';
  } else {
      $loginSql = "SELECT password FROM lms_login WHERE user_id = ? LIMIT 1";
    $loginStmt = mysqli_prepare($db_handle->conn, $loginSql);

    if ($loginStmt) {
        mysqli_stmt_bind_param($loginStmt, 'i', $currentUserId);
      mysqli_stmt_execute($loginStmt);
      $loginResult = mysqli_stmt_get_result($loginStmt);
      $loginRow = $loginResult ? mysqli_fetch_assoc($loginResult) : null;
      mysqli_stmt_close($loginStmt);

      $storedPassword = (string) ($loginRow['password'] ?? '');
      if ($storedPassword !== $currentPassword) {
        $passwordAlertType = 'danger';
        $passwordAlertMessage = 'Current password is incorrect.';
      } else {
          $updateSql = "UPDATE lms_login SET password = ? WHERE user_id = ?";
        $updateStmt = mysqli_prepare($db_handle->conn, $updateSql);
        if ($updateStmt) {
            mysqli_stmt_bind_param($updateStmt, 'si', $newPassword, $currentUserId);
          if (mysqli_stmt_execute($updateStmt)) {
            $passwordAlertType = 'success';
            $passwordAlertMessage = 'Password updated successfully.';
          } else {
            $passwordAlertType = 'danger';
            $passwordAlertMessage = 'Unable to update password right now.';
          }
          mysqli_stmt_close($updateStmt);
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

$passwordData = array(
  'username' => (string) ($username ?? ''),
  'role_name' => (string) ($role_name ?? '')
);

if ($currentUserId > 0 && $currentRoleId > 0) {
    $profileSql = "SELECT l.username, r.role_name
                   FROM lms_login l
                   LEFT JOIN lms_user_master u ON u.user_id = l.user_id
                   LEFT JOIN lms_role_master r ON r.role_id = u.role_id
                   WHERE l.user_id = ?
                   LIMIT 1";
  $profileStmt = mysqli_prepare($db_handle->conn, $profileSql);

  if ($profileStmt) {
      mysqli_stmt_bind_param($profileStmt, 'i', $currentUserId);
    mysqli_stmt_execute($profileStmt);
    $profileResult = mysqli_stmt_get_result($profileStmt);
    if ($profileResult && ($row = mysqli_fetch_assoc($profileResult))) {
      $passwordData['username'] = (string) ($row['username'] ?? $passwordData['username']);
      $passwordData['role_name'] = (string) ($row['role_name'] ?? $passwordData['role_name']);
    }
    mysqli_stmt_close($profileStmt);
  }
}
?>

<style>
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
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .profile-form-card .box-title {
    color: #fff;
    font-weight: 700;
    letter-spacing: 0.2px;
  }

  .profile-form-card .profile-close-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    color: #b4232f;
    background: #fdecef;
    border: 1px solid #f3b7bf;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    position: absolute;
    right: 6px;
    top: 6px;
    text-decoration: none;
    transition: background 0.2s ease, color 0.2s ease;
  }

  .profile-form-card .profile-close-icon:hover,
  .profile-form-card .profile-close-icon:focus {
    color: #8f1a24;
    background: #f9d8dd;
    text-decoration: none;
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

  .profile-input-group .input-group-btn .btn {
    height: 42px;
    border: 1px solid #dbe3ec;
    border-left: 0;
    border-radius: 0 8px 8px 0;
    background: #f8fafc;
    color: #4b5563;
  }

  .profile-input-group .input-group-btn .btn:focus {
    outline: none;
    box-shadow: none;
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
    position: relative;
    text-align: center;
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

  .profile-form-card .profile-close-btn {
    display: inline-block;
    border: 1px solid #f3b7bf;
    border-radius: 8px;
    padding: 9px 16px;
    background: #fdecef;
    color: #b4232f;
    font-weight: 700;
    text-decoration: none;
    position: absolute;
    right: 18px;
    top: 50%;
    transform: translateY(-50%);
  }

  .profile-form-card .profile-close-btn:hover,
  .profile-form-card .profile-close-btn:focus {
    background: #f9d8dd;
    color: #8f1a24;
    text-decoration: none;
  }
</style>

<div class="content-wrapper">
  <section class="content-header">
    <ol class="breadcrumb">
      <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Update Password</li>
    </ol>
  </section>

  <section class="content" style="margin-top: 20px;">
    <div class="row">
      <div class="col-md-3"></div>
      <div class="col-md-6">
        <div class="box profile-form-card">
          <div class="box-header with-border">
            <h3 class="box-title">Update Password</h3>
            <a href="<?php echo htmlspecialchars($closeRoute); ?>" class="profile-close-icon" aria-label="Close update password">
              <i class="fa fa-times"></i>
            </a>
          </div>

          <div class="box-body" style="padding-bottom: 0;">
            <p style="margin-bottom: 10px; color: #374151; font-weight: 600;">User: <?php echo htmlspecialchars($passwordData['username']); ?></p>
            <p style="margin-bottom: 0; color: #6b7280;">Role: <?php echo htmlspecialchars($passwordData['role_name']); ?></p>
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
                <label class="col-sm-4 control-label">Current Password</label>
                <div class="col-sm-8">
                  <div class="input-group profile-input-group">
                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                    <input type="password" class="form-control password-toggle-field" id="current_password" name="current_password" placeholder="Enter current password" required>
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-default toggle-password" data-target="current_password" aria-label="Show password">
                        <i class="fa fa-eye"></i>
                      </button>
                    </span>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-4 control-label">New Password</label>
                <div class="col-sm-8">
                  <div class="input-group profile-input-group">
                    <span class="input-group-addon"><i class="fa fa-key"></i></span>
                    <input type="password" class="form-control password-toggle-field" id="new_password" name="new_password" placeholder="Enter new password" required>
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-default toggle-password" data-target="new_password" aria-label="Show password">
                        <i class="fa fa-eye"></i>
                      </button>
                    </span>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-4 control-label">Confirm Password</label>
                <div class="col-sm-8">
                  <div class="input-group profile-input-group">
                    <span class="input-group-addon"><i class="fa fa-check"></i></span>
                    <input type="password" class="form-control password-toggle-field" id="confirm_password" name="confirm_password" placeholder="Re-enter new password" required>
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-default toggle-password" data-target="confirm_password" aria-label="Show password">
                        <i class="fa fa-eye"></i>
                      </button>
                    </span>
                  </div>
                </div>
              </div>
            </div>
            <div class="box-footer">
              <input type="hidden" name="update_password" value="1">
              <a href="<?php echo htmlspecialchars($closeRoute); ?>" class="profile-close-btn"><i class="fa fa-times"></i> Close</a>
              <button type="submit" class="profile-save-btn"><i class="fa fa-refresh"></i> Update Password</button>
            </div>
          </form>
        </div>
      </div>
      <div class="col-md-3"></div>
    </div>
  </section>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    var toggleButtons = document.querySelectorAll('.toggle-password');
    for (var i = 0; i < toggleButtons.length; i++) {
      toggleButtons[i].addEventListener('click', function () {
        var targetId = this.getAttribute('data-target');
        var input = document.getElementById(targetId);
        if (!input) {
          return;
        }

        var icon = this.querySelector('i');
        var isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';

        if (icon) {
          icon.className = isPassword ? 'fa fa-eye-slash' : 'fa fa-eye';
        }
        this.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
      });
    }
  });
</script>

<?php include "header/footer.php"; ?>
