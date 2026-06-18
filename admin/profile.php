<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
include "header/header.php";

$profileAlertType = '';
$profileAlertMessage = '';

$currentUserId = intval($userid ?? 0);
$currentRoleId = intval($usertype ?? 0);

$defaultProfilePhoto = 'dist/img/user2-160x160.jpg';
$profilePhotoStorageDir = __DIR__ . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'profile_photos';
$profilePhotoWebDir = 'uploads/profile_photos';

function getUserProfilePhotoWebPath($userId, $storageDir, $webDir, $defaultPath)
{
  $userId = intval($userId);
  if ($userId <= 0 || !is_dir($storageDir)) {
    return $defaultPath;
  }

  $matches = glob($storageDir . DIRECTORY_SEPARATOR . 'user_' . $userId . '.*');
  if (!$matches || count($matches) === 0) {
    return $defaultPath;
  }

  return $webDir . '/' . basename($matches[0]);
}

$profilePhotoWebPath = getUserProfilePhotoWebPath($currentUserId, $profilePhotoStorageDir, $profilePhotoWebDir, $defaultProfilePhoto);

if ($currentUserId <= 0 || $currentRoleId <= 0) {
  $profileAlertType = 'danger';
  $profileAlertMessage = 'Unable to load profile. Please login again.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile']) && $currentUserId > 0 && $currentRoleId > 0) {
  $profileName = trim((string) ($_POST['profile_name'] ?? ''));
  $profileEmail = trim((string) ($_POST['profile_email'] ?? ''));
  $profilePhone = trim((string) ($_POST['profile_phone'] ?? ''));
  $hasPhotoUpload = isset($_FILES['profile_photo']) && is_array($_FILES['profile_photo']) && intval($_FILES['profile_photo']['error'] ?? 4) !== 4;
  $uploadedPhotoTmpPath = '';
  $uploadedPhotoExt = '';

  if ($profileName === '') {
    $profileAlertType = 'warning';
    $profileAlertMessage = 'Name is required.';
  } elseif ($profileEmail !== '' && !filter_var($profileEmail, FILTER_VALIDATE_EMAIL)) {
    $profileAlertType = 'warning';
    $profileAlertMessage = 'Please enter a valid email address.';
  } elseif ($hasPhotoUpload) {
    $photoErrorCode = intval($_FILES['profile_photo']['error'] ?? 1);
    if ($photoErrorCode !== 0) {
      $profileAlertType = 'warning';
      $profileAlertMessage = 'Unable to upload photo. Please try again.';
    } else {
      $uploadedPhotoTmpPath = (string) ($_FILES['profile_photo']['tmp_name'] ?? '');
      $uploadedPhotoSize = intval($_FILES['profile_photo']['size'] ?? 0);
      $uploadedPhotoName = (string) ($_FILES['profile_photo']['name'] ?? '');
      $uploadedPhotoExt = strtolower(pathinfo($uploadedPhotoName, PATHINFO_EXTENSION));
      $allowedPhotoExt = array('jpg', 'jpeg', 'png', 'webp');

      if ($uploadedPhotoTmpPath === '' || !is_uploaded_file($uploadedPhotoTmpPath)) {
        $profileAlertType = 'warning';
        $profileAlertMessage = 'Invalid photo upload request.';
      } elseif (!in_array($uploadedPhotoExt, $allowedPhotoExt, true)) {
        $profileAlertType = 'warning';
        $profileAlertMessage = 'Profile photo must be JPG, PNG, or WEBP format.';
      } elseif ($uploadedPhotoSize <= 0 || $uploadedPhotoSize > 2 * 1024 * 1024) {
        $profileAlertType = 'warning';
        $profileAlertMessage = 'Profile photo size must be less than 2 MB.';
      }
    }
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

      if ($hasPhotoUpload && $uploadedPhotoTmpPath !== '' && $uploadedPhotoExt !== '') {
        if (!is_dir($profilePhotoStorageDir)) {
          @mkdir($profilePhotoStorageDir, 0777, true);
        }

        if (is_dir($profilePhotoStorageDir)) {
          $existingPhotos = glob($profilePhotoStorageDir . DIRECTORY_SEPARATOR . 'user_' . intval($currentUserId) . '.*');
          if ($existingPhotos) {
            foreach ($existingPhotos as $existingPhotoPath) {
              @unlink($existingPhotoPath);
            }
          }

          $targetPhotoFilename = 'user_' . intval($currentUserId) . '.' . $uploadedPhotoExt;
          $targetPhotoPath = $profilePhotoStorageDir . DIRECTORY_SEPARATOR . $targetPhotoFilename;
          if (move_uploaded_file($uploadedPhotoTmpPath, $targetPhotoPath)) {
            $profilePhotoWebPath = $profilePhotoWebDir . '/' . $targetPhotoFilename;
          } else {
            $profileAlertType = 'warning';
            $profileAlertMessage = 'Profile updated, but photo upload failed.';
          }
        } else {
          $profileAlertType = 'warning';
          $profileAlertMessage = 'Profile updated, but photo storage is not available.';
        }
      }
    } else {
      mysqli_rollback($db_handle->conn);
      $profileAlertType = 'danger';
      $profileAlertMessage = 'Unable to update profile right now.';
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
    object-fit: cover;
  }

  .profile-avatar-wrap {
    width: 120px;
    margin: 0 auto;
    position: relative;
  }

  .profile-avatar-edit-tag {
    position: absolute;
    right: 6px;
    bottom: 4px;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(120deg, #1aa7cf 0%, #44c4e8 100%);
    color: #fff;
    box-shadow: 0 6px 14px rgba(30, 167, 207, 0.35);
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

  .profile-photo-upload-card {
    border: 1px dashed #a6d6eb;
    border-radius: 12px;
    padding: 14px;
    background: linear-gradient(180deg, #f8fdff 0%, #f2f9ff 100%);
  }

  .profile-photo-preview {
    width: 78px;
    height: 78px;
    border-radius: 12px;
    object-fit: cover;
    border: 2px solid #d8ecf7;
    background: #fff;
  }

  .profile-photo-upload-card .help-block {
    margin: 6px 0 0;
    color: #556372;
    font-size: 12px;
  }

  .profile-file-input {
    border-radius: 8px;
    border: 1px solid #dbe3ec;
    background: #fff;
    padding: 7px 10px;
    width: 100%;
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
            <div class="profile-avatar-wrap">
              <img class="profile-avatar" src="<?php echo htmlspecialchars($profilePhotoWebPath); ?>" alt="User profile picture">
              <span class="profile-avatar-edit-tag"><i class="fa fa-camera"></i></span>
            </div>
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

          <form class="form-horizontal" method="POST" enctype="multipart/form-data">
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

              <div class="form-group">
                <label class="col-sm-3 control-label">Profile Photo</label>
                <div class="col-sm-9">
                  <div class="profile-photo-upload-card">
                    <div class="row">
                      <div class="col-xs-3" style="text-align:center;">
                        <img src="<?php echo htmlspecialchars($profilePhotoWebPath); ?>" id="profilePhotoPreview" class="profile-photo-preview" alt="Profile photo preview">
                      </div>
                      <div class="col-xs-9">
                        <input type="file" class="profile-file-input" id="profile_photo" name="profile_photo" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                        <p class="help-block">Upload JPG, PNG, or WEBP image (max 2MB).</p>
                      </div>
                    </div>
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
      </div>
    </div>
  </section>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    var fileInput = document.getElementById('profile_photo');
    var preview = document.getElementById('profilePhotoPreview');

    if (!fileInput || !preview) {
      return;
    }

    fileInput.addEventListener('change', function () {
      var selectedFile = this.files && this.files.length > 0 ? this.files[0] : null;
      if (!selectedFile) {
        return;
      }

      var reader = new FileReader();
      reader.onload = function (event) {
        if (event && event.target && event.target.result) {
          preview.src = event.target.result;
        }
      };
      reader.readAsDataURL(selectedFile);
    });
  });
</script>

<?php include "header/footer.php"; ?>
