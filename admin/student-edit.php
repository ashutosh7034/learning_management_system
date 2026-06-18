<?php
session_start();
require "../database/db_connect.php";
$db_handle = new DBController();

if (isset($_REQUEST['id'])) {
  $student_id = intval($_REQUEST['id']);
  $table = 'lms_student_master';
  
  $sql = "SELECT 
    sm.*,
    IFNULL(cl.class_name, '') AS class_name,
    IFNULL(sec.sections, '') AS section_name,
    IFNULL(dep.department_name, '') AS department_name,
    IFNULL(sp.specialization_name, '') AS specialization_name,
    IFNULL(ssb.subject_name, '') AS specialization_subject_name,
    IFNULL(sess.session_name, '') AS academic_year_name,
    IFNULL(sem.semester_name, '') AS semester_name,
    IFNULL(mc.course_name, '') AS minor_course_name,
    IFNULL(ms.subject_name, '') AS minor_subject_name
  FROM $table sm
  LEFT JOIN lms_class_master cl ON cl.class_id = sm.class_id
  LEFT JOIN lms_section_master sec ON sec.id = sm.division_id
  LEFT JOIN lms_department_master dep ON dep.department_id = sm.department_id
  LEFT JOIN lms_specialization_master sp ON sp.specialization_id = sm.specialization_id
  LEFT JOIN lms_specialization_subject_master ssb ON ssb.subject_id = sm.specialization_subject_id
  LEFT JOIN lms_minorcourse mc ON mc.course_id = sm.minor_course_id
  LEFT JOIN lms_minorsubject ms ON ms.subject_id = sm.minor_subject_id
  LEFT JOIN lms_session_master sess ON sess.session_id = sm.academic_year_id
  LEFT JOIN lms_semester_master sem ON sem.semester_id = sm.current_semester_id
  WHERE sm.student_id = $student_id";
  
  $result = $db_handle->query($sql);
  $row = $result->fetch_assoc();

  if (!$row) {
    echo "<div class='alert alert-danger'>Student record not found.</div>";
    exit;
  }
  
  // Determine specialization type
  $specialization_name = strtolower($row['specialization_name'] ?? '');
  $is_minor_multidisciplinary = strpos($specialization_name, 'minor multidisciplinary') !== false;
  $is_minor = strpos($specialization_name, 'minor') !== false && !$is_minor_multidisciplinary;
  $is_honours = strpos($specialization_name, 'honour') !== false || strpos($specialization_name, 'honor') !== false;
?>
<!DOCTYPE html>
<html>
<head>
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
    </style>
</head>
<body>
<form action="edit_process.php" name="editform" method="POST" onsubmit="return validateform()" enctype="multipart/form-data">
    <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
    
    <div class="box box-default" style="padding: 10px;">
        <div class="box-header with-border" style="border-bottom: 2px solid #9C27B0;">
            <h3 class="box-title">OFFICIAL DETAILS:- </h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>

        <div class="box-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Academic Year <span style="color: red;">*</span></label>
                        <select class="form-control select" name="academic_year_id" id="academic_year_id" style="width: 100%;" required>
                            <option value="">Select Academic Year</option>
                            <?php
                            $sessionResult = $db_handle->query("SELECT session_id, session_name FROM lms_session_master ORDER BY session_id DESC");
                            while ($sessionRow = $sessionResult->fetch_assoc()) {
                                $selected = ($row['academic_year_id'] == $sessionRow['session_id']) ? 'selected' : '';
                                echo "<option value='{$sessionRow['session_id']}' $selected>{$sessionRow['session_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Current Semester <span style="color: red;">*</span></label>
                        <select class="form-control select" name="current_semester_id" id="current_semester_id" style="width: 100%;" required>
                            <option value="">Select Semester</option>
                            <?php
                            $semesterResult = $db_handle->query("SELECT semester_id, semester_name FROM lms_semester_master ORDER BY semester_id");
                            while ($semesterRow = $semesterResult->fetch_assoc()) {
                                $selected = ($row['current_semester_id'] == $semesterRow['semester_id']) ? 'selected' : '';
                                echo "<option value='{$semesterRow['semester_id']}' $selected>{$semesterRow['semester_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Registration Number <span style="color: red;">*</span></label>
                        <input type="text" name="registration_no" value="<?php echo htmlspecialchars($row['registration_no'] ?? ''); ?>" id="registration_no" class="form-control" style="width: 100%;" readonly>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Class <span style="color: red;">*</span></label>
                        <select class="form-control select" name="class_id" id="class_id" style="width: 100%;" required>
                            <option value="">Select Class</option>
                            <?php
                            $classResult = $db_handle->query("SELECT class_id, class_name FROM lms_class_master");
                            while ($classRow = $classResult->fetch_assoc()) {
                                $selected = ($row['class_id'] == $classRow['class_id']) ? 'selected' : '';
                                echo "<option value='{$classRow['class_id']}' $selected>{$classRow['class_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Division <span style="color: red;">*</span></label>
                        <select class="form-control select" name="division_id" id="division_id" style="width: 100%;" required>
                            <option value="">Select Division</option>
                            <?php
                            $sectionResult = $db_handle->query("SELECT id, sections FROM lms_section_master");
                            while ($sectionRow = $sectionResult->fetch_assoc()) {
                                $selected = ($row['division_id'] == $sectionRow['id']) ? 'selected' : '';
                                echo "<option value='{$sectionRow['id']}' $selected>{$sectionRow['sections']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Roll No.</label>
                        <input type="text" name="roll_no" class="form-control" value="<?php echo htmlspecialchars($row['roll_no'] ?? ''); ?>" style="width: 100%;">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Department <span style="color: red;">*</span></label>
                        <select class="form-control select" name="department_id" id="department_select" style="width: 100%;" required>
                            <option value="">Select Department</option>
                            <?php
                            $deptResult = $db_handle->query("SELECT department_id, department_name FROM lms_department_master");
                            while ($deptRow = $deptResult->fetch_assoc()) {
                                $selected = ($row['department_id'] == $deptRow['department_id']) ? 'selected' : '';
                                echo "<option value='{$deptRow['department_id']}' $selected>{$deptRow['department_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>


            </div>





            <!-- FIXED: Graduation Year Field -->
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Graduation Year</label>
                        <input type="number" name="grad_year" class="form-control" value="<?php echo htmlspecialchars($row['grad_year'] ?? ''); ?>" placeholder="Enter Graduation Year" style="width: 100%;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PERSONAL DETAILS -->
    <div class="box box-default" style="padding: 10px;">
        <div class="box-header with-border" style="border-bottom: 2px solid #9C27B0;">
            <h3 class="box-title">PERSONAL DETAILS:- </h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>

        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Full Name <span style="color: red;">*</span></label>
                        <input type="text" name="fname" class="form-control" value="<?php echo htmlspecialchars($row['fname'] ?? ''); ?>" style="width: 100%;" required>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Mobile Number <span style="color: red;">*</span></label>
                        <input type="text" pattern="^\d{10}$" class="form-control" name="mobile" value="<?php echo htmlspecialchars($row['mobile'] ?? ''); ?>" minlength="10" maxlength="10" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($row['email'] ?? ''); ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- STATUS SECTION -->
    <div class="box box-default" style="padding: 10px;">
        <div class="box-header with-border" style="border-bottom: 2px solid #9C27B0;">
            <h3 class="box-title">STATUS:- </h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>

        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control select" name="status" style="width: 100%;">
                            <option value="1" <?php echo ($row['status'] == '1') ? 'selected' : ''; ?>>Active</option>
                            <option value="0" <?php echo ($row['status'] == '0') ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 text-center">
            <input type="submit" name="save" value="UPDATE" class="btn btn-primary" />
            <input type="reset" name="reset" value="RESET" class="btn btn-default" />
        </div>
    </div>

</form>

<script>
function validateform() {
    var academic_year_id = document.editform.academic_year_id.value;
    var current_semester_id = document.editform.current_semester_id.value;
    var class_id = document.editform.class_id.value;
    var division_id = document.editform.division_id.value;
    var fname = document.editform.fname.value;
    var mobile = document.editform.mobile.value;
    
    if (academic_year_id == null || academic_year_id == "") {
        alert("Academic Year can't be blank.");
        return false;
    }
    if (current_semester_id == null || current_semester_id == "") {
        alert("Current Semester can't be blank.");
        return false;
    }
    if (class_id == null || class_id == "") {
        alert("Class can't be blank.");
        return false;
    }
    if (division_id == null || division_id == "") {
        alert("Division can't be blank.");
        return false;
    }
    if (fname == null || fname == "") {
        alert("Student Name can't be blank.");
        return false;
    }
    if (mobile == null || mobile == "") {
        alert("Mobile Number can't be blank.");
        return false;
    }
    if (mobile.length != 10) {
        alert("Mobile Number must be 10 digits.");
        return false;
    }
    
    return true;
}
</script>
</body>
</html>
<?php
} else {
  echo "<div class='alert alert-danger'>No student ID provided.</div>";
}
?>