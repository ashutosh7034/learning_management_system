<?php include "header/header.php"; ?>
<?php
$admissionForm = $_SESSION['student_admission_form'] ?? [];
$admissionSuccess = $_SESSION['student_admission_success'] ?? '';
unset($_SESSION['student_admission_success']);

$isEditMode = isset($_GET['edit']) && $_GET['edit'] === '1';
$linkedStudentId = 0;
if ((int) ($usertype ?? 0) === 5 && !empty($userid)) {
  $linkedRes = $db_handle->query("SELECT student_id FROM lms_user_master WHERE user_id = " . intval($userid) . " AND student_id > 0 LIMIT 1");
  if ($linkedRes && ($linkedRow = mysqli_fetch_assoc($linkedRes))) {
    $linkedStudentId = intval($linkedRow['student_id']);
  }
}

if ($linkedStudentId > 0 && !$isEditMode) {
  echo "<script>window.location.replace('student_admission_view.php?id=" . $linkedStudentId . "');</script>";
  exit;
}

// If admission form not in session, attempt to prefill from linked student record
if (($isEditMode || empty($admissionForm)) && !empty($userid)) {
  $mapRes = $db_handle->query("SELECT student_id FROM lms_user_master WHERE user_id = " . intval($userid) . " LIMIT 1");
  if ($mapRes && ($mapRow = mysqli_fetch_assoc($mapRes))) {
    $sid = intval($mapRow['student_id']);
    if ($sid > 0) {
      $sres = $db_handle->query("SELECT * FROM lms_student_master WHERE student_id = " . $sid . " LIMIT 1");
      if ($sres && ($srow = mysqli_fetch_assoc($sres))) {
        $admissionForm['registration_no'] = $srow['registration_no'] ?? '';
        $admissionForm['roll_no'] = $srow['roll_no'] ?? '';
        $admissionForm['class'] = $srow['class_id'] ?? '';
        $admissionForm['current_semester_id'] = $srow['current_semester_id'] ?? '';
        $admissionForm['batch'] = $srow['academic_year_id'] ?? '';
        $admissionForm['graduation_year'] = $srow['grad_year'] ?? '';
        $admissionForm['department_id'] = $srow['department_id'] ?? '';
        $admissionForm['specialization_id'] = $srow['specialization_id'] ?? '';
        $admissionForm['cgpa'] = $srow['cgpa'] ?? '';
        $admissionForm['minor_course_id'] = $srow['minor_course_id'] ?? '';
        $admissionForm['minor_subject_id'] = $srow['minor_subject_id'] ?? '';
        $admissionForm['minor_cgpa'] = '';
        $admissionForm['unaided_subject'] = $srow['specialization_subject_id'] ?? '';
        $admissionForm['fname'] = $srow['fname'] ?? '';
        $admissionForm['email'] = $srow['email'] ?? '';
        $admissionForm['mobile'] = $srow['mobile'] ?? '';
      }
    }
  }
}
?>

<script>
  function validateform() {
    var academic = document.myform.academic.value;
    var class1 = document.myform.class.value;
    var registration_no = document.myform.registration_no.value;
    var batch = document.myform.batch.value;
    var fname = document.myform.fname.value;

    if (academic == null || academic == "") {
      alert("Academic Year can't be blank.");
      return false;
    }
    if (class1 == null || class1 == "") {
      alert("Class can't be blank.");
      return false;
    }
    if (registration_no == null || registration_no == "") {
      alert("Registration Number can't be blank.");
      return false;
    }
    if (batch == null || batch == "") {
      alert("Batch can't be blank.");
      return false;
    }
    if (fname == null || fname == "") {
      alert("First Name can't be blank.");
      return false;
    }

    var mobile = document.myform.mobile.value;
    if (mobile && isNaN(mobile)) {
      alert("Enter only numeric value in mobile field.");
      return false;
    }

    return true;
  }
</script>

<script type="text/javascript">
  function display2() {
    var class1 = $('#class4').val();
    var section = $('#batch4').val();

    $.ajax({
      type: 'POST',
      url: 'getroll_no.php',
      data: {
        "class": class1,
        "section": section
      },
      success: function(response) {
        $("#data421").val(response);
        console.log(response);
      },
    });
  }
</script>

<script>
  function ckeck_reg() {
    var register = document.getElementById("registration_no").value;
    if (register) {
      $.ajax({
        type: 'post',
        url: 'reg_no.php',
        data: {
          "register1": register,
        },
        dataType: 'json',
        success: function(response) {
          console.log(response);
          if (response && response.exists) {
            var fullNameField = document.getElementById('full_name');
            var emailField = document.getElementById('college_email');
            var mobileField = document.getElementById('mobile');

            if (fullNameField && response.fname) {
              fullNameField.value = response.fname;
            }

            if (emailField && response.email) {
              emailField.value = response.email;
            }

            if (mobileField && response.mobile) {
              mobileField.value = response.mobile;
            }
          }
        }
      });
    }
  }
</script>

<script type="text/javascript">

</script>
<style>
  .form-group .required-field {
    border-color: #ef4444;
  }

  .form-group .error-message {
    color: #ef4444;
    font-size: 11px;
    margin-top: 4px;
    display: block;
  }

  input:invalid {
    border-color: #ef4444;
  }
</style>

<!-- Content Wrapper -->
<div class="content-wrapper">
  <section class="content-header">
    <h1>Student Registration</h1>
    <ol class="breadcrumb">
      <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">student</a></li>
      <li class="active">Admission</li>
    </ol>
  </section>

  <section class="content">
    <?php if (!empty($admissionSuccess)) { ?>
      <div class="alert alert-success" style="margin-bottom: 15px;">
        <?php echo htmlspecialchars($admissionSuccess); ?>
      </div>
    <?php } ?>
    <form action="student_process.php" name="myform" method="POST" onsubmit="return validateform()" enctype="multipart/form-data">
      <div class="box box-default" style="padding: 10px;">
        <div class="box-header with-border" style="border-bottom: 2px solid #9C27B0;">
          <h3 class="box-title">OFFICIAL DETAILS:- </h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Academic Year</label>
                  <select class="form-control select" name="academic" id="academic" style="width: 100%;" required>
                    <option value="">Select Academic Year</option>
                    <?php
                    // Fetch all batches from the database
                    $batch_result = $db_handle->query("SELECT session_id, session_name FROM `lms_session_master` ORDER BY session_id DESC");

                    while ($row = $batch_result->fetch_assoc()) {
                      $id = $row['session_id'];
                      $name = $row['session_name'];

                      // This makes the latest batch selected by default
                      $selected = (!empty($admissionForm['academic']) && (string)$admissionForm['academic'] === (string)$id) ? 'selected' : (($id == 1 && empty($admissionForm['academic'])) ? 'selected' : '');

                      echo "<option value='{$id}' {$selected}>{$name}</option>";
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>ERP ID <span style="color: red;">*</span></label>
                  <input type="text" name="registration_no" value="<?php echo htmlspecialchars($admissionForm['registration_no'] ?? ''); ?>" id="registration_no" onblur="ckeck_reg()" class="form-control" style="width: 100%;" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Roll No.</label>
                  <input type="text" name="roll_no" value="<?php echo htmlspecialchars($admissionForm['roll_no'] ?? ''); ?>" class="form-control" id="data421" style="width: 100%;" required>
                </div>
              </div>
            </div>

            <div class="col-md-12">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Class <span style="color: red;">*</span></label>
                  <select class="form-control select" name="class" id="class4" class="class" style="width: 100%;" required>
                    <option value="">Select Class</option>
                    <?php
                    $result = $db_handle->conn->query("select * from lms_class_master");
                    while ($row = $result->fetch_assoc()) {
                      $class_name = $row['class_name'];
                      $class_id = $row['class_id'];
                      $selected = (!empty($admissionForm['class']) && (string)$admissionForm['class'] === (string)$class_id) ? 'selected' : '';
                    ?>
                      <option value="<?php echo $class_id; ?>" <?php echo $selected; ?>><?php echo $class_name; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label>Semester <span style="color: red;">*</span></label>
                  <select class="form-control" name="current_semester_id" id="semester_select" required>
                    <option value="">Select Semester</option>
                    <?php
                    // Make sure to use the correct table name: lms_semester_master (not lms_semester)
                    $result = $db_handle->conn->query("SELECT semester_id, semester_name FROM lms_semester_master ORDER BY semester_id ASC");
                    if ($result && $result->num_rows > 0) {
                      while ($row = $result->fetch_assoc()) {
                        $selected = (!empty($admissionForm['current_semester_id']) && (string)$admissionForm['current_semester_id'] === (string)$row['semester_id']) ? 'selected' : '';
                        echo "<option value='{$row['semester_id']}' {$selected}>{$row['semester_name']}</option>";
                      }
                    } else {
                      // Fallback if table name is different
                      $result = $db_handle->conn->query("SELECT semester_id, semester_name FROM lms_semester ORDER BY semester_id ASC");
                      while ($row = $result->fetch_assoc()) {
                        $selected = (!empty($admissionForm['current_semester_id']) && (string)$admissionForm['current_semester_id'] === (string)$row['semester_id']) ? 'selected' : '';
                        echo "<option value='{$row['semester_id']}' {$selected}>{$row['semester_name']}</option>";
                      }
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label>Division<span style="color: red;">*</span></label>
                  <select class="form-control select" name="batch" id="batch4" class="batch" onchange="display2()" style="width: 100%;" required>
                    <option value="">Select Division</option>
                    <?php
                    $result = $db_handle->conn->query("SELECT * from lms_section_master");
                    while ($row = $result->fetch_assoc()) {
                      $section_name = $row['sections'];
                      $s_id = $row['id'];
                      $selected = (!empty($admissionForm['batch']) && (string)$admissionForm['batch'] === (string)$s_id) ? 'selected' : '';
                    ?>
                      <option value="<?php echo $s_id;  ?>" <?php echo $selected; ?>><?php echo $section_name;  ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
             <div class="col-md-4">
    <div class="form-group">
        <label>Graduating Year</label>
        <select class="form-control select" name="graduation_year" id="graduation_year" class="graduation_year" style="width: 100%;">
            <option value="">Select Year</option>
            <?php
            // Assuming batch_name contains the year (like "2024", "2025")
            $result = $db_handle->conn->query("SELECT * from lms_batch_master ORDER BY batch_name");
            while ($row = $result->fetch_assoc()) {
                $batch_name = $row['batch_name'];
                $batch_id = $row['batch_id']; // or academic_year_id
                
                // Extract year from batch_name if it contains the year
                // Or use academic_year_id if it represents the year
              $selected = (!empty($admissionForm['graduation_year']) && (string)$admissionForm['graduation_year'] === (string)$batch_name) ? 'selected' : '';
              echo "<option value='{$batch_name}' {$selected}>{$batch_name}</option>";
            }
            ?>
        </select>
    </div>
</div>
            </div>

            <div class="col-md-12">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Department<span style="color: red;">*</span></label>
                  <select class="form-control select" name="department_id" id="department_select" class="batch" style="width: 100%;" required>
                    <option value="">Select Department</option>
                    <?php
                    $result = $db_handle->conn->query("SELECT * from lms_department_master");
                    while ($row = $result->fetch_assoc()) {
                      $department_name = $row['department_name'];
                      $department_id = $row['department_id'];
                      $selected = (!empty($admissionForm['department_id']) && (string)$admissionForm['department_id'] === (string)$department_id) ? 'selected' : '';
                    ?>
                      <option value="<?php echo $department_id;  ?>" <?php echo $selected; ?>><?php echo $department_name;  ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>


            </div>
          </div>
        </div>
      </div>

      <div id="below_eligibility_sections">
        <div class="box box-default" id="personal_details_section" style="padding: 10px;">
          <div class="box-header with-border" style="border-bottom: 2px solid #9C27B0;">
            <h3 class="box-title">PERSONAL DETAILS:- </h3>
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="fname" id="full_name" value="<?php echo htmlspecialchars($admissionForm['fname'] ?? ''); ?>" class="form-control" autocomplete="name" style="width: 100%;" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>College Email <span style="color: red;">*</span></label>
                    <input type="email" name="email" id="college_email" class="form-control"
                      value="<?php echo htmlspecialchars($admissionForm['email'] ?? ''); ?>"
                      autocomplete="email"
                      placeholder="example@tcetmumbai.in" style="width: 100%;"
                      pattern="[a-zA-Z0-9._%+\-]+@tcetmumbai\.in"
                      title="Email must end with @tcetmumbai.in" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Mobile</label>
                    <input type="text" pattern="^\d{10}$" class="form-control"
                      id="mobile" name="mobile" value="<?php echo htmlspecialchars($admissionForm['mobile'] ?? ''); ?>" autocomplete="tel" minlength="10" maxlength="10"
                      oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                      placeholder="Mobile No." style="width: 100%;" />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="box box-default" style="padding: 10px;">
          <div class="box-body">
            <div class="row" style="margin: 10px 0;">
              <div style="text-align: center;">
                <input type="submit" name="save" value="Save Changes" class="btn-submit">
                <input type="reset" name="reset" value="Reset" class="btn-reset">
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </section>
</div>

<style>
  .semester-upload-card {
    background: white;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 14px;
    transition: all 0.3s ease;
  }

  .semester-upload-card:hover {
    border-color: #2563eb;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
  }

  .semester-upload-card.completed {
    border-color: #10b981;
    background: #f0fdf4;
  }

  .semester-badge {
    display: inline-block;
    background: linear-gradient(135deg, #2563eb, #0ea5e9);
    color: white;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    margin-right: 10px;
    min-width: 80px;
    text-align: center;
  }

  .semester-badge.completed {
    background: linear-gradient(135deg, #10b981, #059669);
  }

  .semester-label {
    font-weight: 600;
    color: #2f3b45;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .file-input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .file-input-wrapper input[type="file"] {
    flex: 1;
    padding: 10px 12px;
    border: 2px dashed #cbd5e1;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s ease;
  }

  .file-input-wrapper input[type="file"]:hover {
    border-color: #2563eb;
    background: #f0f9ff;
  }

  .file-input-label {
    color: #64748b;
    font-size: 12px;
    margin-top: 6px;
  }

  .upload-icon {
    color: #2563eb;
    font-size: 18px;
  }
</style>



</div>
<?php include "header/footer.php"; ?>
