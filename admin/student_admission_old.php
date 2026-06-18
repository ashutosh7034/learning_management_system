<?php include "header/header.php"; ?>
<script>
  function validateform() {

    var academic = document.myform.academic.value;

    var class1 = document.myform.class.value;

    var registration_no = document.myform.registration_no.value;

    var batch = document.myform.batch.value;

    var fname = document.myform.fname.value;

    var dob = document.myform.dob.value;

    var cat = document.myform.cat.value;

    var gender = document.myform.gender.value;

    var lname = document.myform.lname.value;

    var present = document.myform.present.value;

    var pname = document.myform.pname.value;

    var pmobile = document.myform.pmobile.value;

    var pjob = document.myform.pjob.value;

    var pincode = document.myform.pincode.value;

    var country = document.myform.country.value;

    var state = document.myform.state.value;

    var mobile = document.myform.mobile.value;
    var specializationSelect = document.getElementById("specialization_select");
    var specializationText = "";
    var cgpaInput = document.getElementById("cgpa");
    var cgpaValue = "";

    if (specializationSelect && specializationSelect.selectedIndex >= 0) {
      specializationText = specializationSelect.options[specializationSelect.selectedIndex].text.toLowerCase();
    }

    if (cgpaInput) {
      cgpaValue = cgpaInput.value;
    }


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
      alert("Batch  can't be blank.");
      return false;
    }
    if (fname == null || fname == "") {
      alert("First Name can't be blank.");
      return false;
    }
    if (specializationText.indexOf("minor") !== -1) {
      if ($('#specialization_subject_select').prop('selectedIndex') <= 0) {
        alert("Please select Specialization Subject for Minor Degree.");
        return false;
      }
      // Validate Multidisciplinary field for Minor
      var multidisciplinary = $('input[name="multidisciplinary"]:checked').val();
      if (!multidisciplinary) {
        alert("Please select Yes or No for Multidisciplinary field.");
        return false;
      }
      if (multidisciplinary === "no") {
        var minorCgpa = $('#minor_cgpa').val();
        if (minorCgpa == null || minorCgpa === "") {
          alert("Please enter CGPA for Multidisciplinary Minor.");
          return false;
        }
        if (isNaN(minorCgpa)) {
          alert("Please enter valid numeric CGPA.");
          return false;
        }
        if (parseFloat(minorCgpa) < 6) {
          alert("CGPA must be at least 6 for Multidisciplinary Minor.");
          return false;
        }
      }
    }
    if (specializationText.indexOf("honours") !== -1 || specializationText.indexOf("honors") !== -1) {
      if (cgpaValue == null || cgpaValue === "") {
        alert("Please enter CGPA for Honours.");
        return false;
      }
      if (isNaN(cgpaValue)) {
        alert("Please enter valid numeric CGPA.");
        return false;
      }
      if (parseFloat(cgpaValue) <= 7) {
        alert("Not eligible to register in Honours. CGPA must be above 7.");
        return false;
      }
      if ($('#specialization_subject_select').prop('selectedIndex') <= 0) {
        alert("Please select Specialization Subject for Honours.");
        return false;
      }
    } else if (isNaN(mobile)) {
      alert("Enter only numeric value in mobile field.");
      return false;
    } else {
      return true;
    }
  }
</script>

<script type="text/javascript">
  function display2() {

    var class1 = $('#class4').val();

    $('#class4').on('change', function() {

      // Reset specialization dropdown
      $('#specialization_select').prop('selectedIndex', 0);

      // Reset specialization subject
      $('#specialization_subject_select').prop('selectedIndex', 0);

      // Reset CGPA
      $('#cgpa').val('');

      // Reset Minor fields
      $('#minor_cgpa_section').hide();
      $('#minor_cgpa').val('');
      $('input[name="multidisciplinary"]').prop('checked', false);

      // Hide UI
      $('#specialization_subject_wrapper').hide();
      $('#cgpa_section').hide();
      $('#honours_not_eligible').hide();
      $('#multidisciplinary_section').hide();

      setAdmissionDetailSectionsVisible(false);
    });



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
        success: function(response) {

          console.log(response);

          if (response == 1) {

            $('#registration_no').val("");


            alert("the Register number is already exist!!");
          }

        }
      });

    }

  }
</script>

<script type="text/javascript">
  function setAdmissionDetailSectionsVisible(isVisible) {
    if (isVisible) {
      $('#below_eligibility_sections').show();
      $('#personal_details_section').show();
      $('#contact_details_section').show();
      $('#upload_documents_section').show();
    } else {
      $('#below_eligibility_sections').hide();
      $('#personal_details_section').hide();
      $('#contact_details_section').hide();
      $('#upload_documents_section').hide();
    }
  }

  function resetSpecializationConditionalUI() {
    $('#cgpa').val('');
    $('#specialization_subject_select').prop('selectedIndex', 0);
    $('#specialization_subject_wrapper').hide();
    $('#cgpa_section').hide();
    $('#honours_not_eligible').hide();
    $('#minor_cgpa_section').hide();
    $('#minor_cgpa').val('');
    $('input[name="multidisciplinary"]').prop('checked', false);
    setAdmissionDetailSectionsVisible(false);
  }

  function updateHonoursEligibility() {
    var specializationText = $('#specialization_select option:selected').text().toLowerCase();
    var cgpaRaw = $('#cgpa').val();
    var cgpa = parseFloat(cgpaRaw);
    var isHonours = specializationText.indexOf('honours') !== -1 || specializationText.indexOf('honors') !== -1;

    if (!isHonours) {
      $('#specialization_subject_wrapper').hide();
      $('#honours_not_eligible').hide();
      setAdmissionDetailSectionsVisible(false);
      return;
    }

    if (cgpaRaw !== '' && !isNaN(cgpa) && cgpa > 7) {
      $('#specialization_subject_wrapper').show();
      $('#honours_not_eligible').hide();
      setAdmissionDetailSectionsVisible(true);
    } else if (cgpaRaw !== '') {
      $('#specialization_subject_wrapper').hide();
      $('#specialization_subject_select').prop('selectedIndex', 0);
      $('#honours_not_eligible').show();
      setAdmissionDetailSectionsVisible(false);
    } else {
      $('#specialization_subject_wrapper').hide();
      $('#honours_not_eligible').hide();
      setAdmissionDetailSectionsVisible(false);
    }
  }

  function handleMultidisciplinaryChange() {
    var isMinorSelected = $('#specialization_select option:selected').text().toLowerCase().indexOf('minor') !== -1;
    if (isMinorSelected) {
      var multidisciplinaryValue = $('input[name="multidisciplinary"]:checked').val();
      if (multidisciplinaryValue === 'no') {
        $('#minor_cgpa_section').show();
        $('#specialization_subject_wrapper').show();
        setAdmissionDetailSectionsVisible(true);
      } else if (multidisciplinaryValue === 'yes') {
        $('#minor_cgpa_section').hide();
        $('#minor_cgpa').val('');
        $('#specialization_subject_wrapper').show();
        setAdmissionDetailSectionsVisible(true);
      } else {
        $('#minor_cgpa_section').hide();
        $('#specialization_subject_wrapper').hide();
        setAdmissionDetailSectionsVisible(false);
      }
    }
  }

  function handleSpecializationSelection() {
    var specializationText = $('#specialization_select option:selected').text().toLowerCase();
    var isMinor = specializationText.indexOf('minor') !== -1;
    var isHonours = specializationText.indexOf('honours') !== -1 || specializationText.indexOf('honors') !== -1;

    resetSpecializationConditionalUI();

    if (isMinor) {
      // Show Multidisciplinary question for Minor
      $('#multidisciplinary_section').show();
      // Do not show subject wrapper yet - wait for multidisciplinary selection
      $('#specialization_subject_wrapper').hide();
      setAdmissionDetailSectionsVisible(false);
      return;
    } else {
      $('#multidisciplinary_section').hide();
    }

    if (isHonours) {
      $('#cgpa_section').show();
    }
  }

  $(document).ready(function() {
    handleSpecializationSelection();

    $('#specialization_select').on('change', function() {
      handleSpecializationSelection();
    });

    $('#cgpa').on('input keyup change blur', function() {
      updateHonoursEligibility();
    });

    $('input[name="multidisciplinary"]').on('change', function() {
      handleMultidisciplinaryChange();
    });
  });
</script>

<style>
  /* Modern form styling */
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


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Student Registration
    </h1>
    <ol class="breadcrumb">
      <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">student</a></li>
      <li class="active">Admission</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">

    <!-- new formset-->
    <form action="student.php" name="myform" method="POST" onsubmit="return validateform()" enctype="multipart/form-data">
      <div class="box box-default" style="padding: 10px;">
        <div class="box-header with-border" style="border-bottom: 2px solid #9C27B0;">
          <h3 class="box-title">OFFICIAL DETAILS:- </h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>

          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Academic Year</label>
                  <select class="form-control select" name="academic" id="academic" style="width: 100%;" required>
                    <option value="">Select Academic Year</option>
                    <option value="2024 - 2025">2024 - 2025</option>
                    <option value="2025 - 2026">2025 - 2026</option>
                    <option value="2026 - 2027" selected>2026 - 2027</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>ERP ID <span style="color: red;">*</span></label>
                  <input type="text" name="registration_no" value="" id="registration_no" onblur="ckeck_reg()" class="form-control" style="width: 100%;" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Roll No.</label>
                  <input type="text" name="roll_no" class="form-control" id="data421" data-placeholder="" style="width: 100%;" required>
                </div>
              </div><!--1 Section End->
             /.col -->
            </div>

            <div class="col-md-12">
              <!-- /.form-group -->
              <div class="col-md-4">
                <div class="form-group">
                  <label>Class <span style="color: red;">*</span></label>
                  <select class="form-control select" name="class" id="class4" class="class" style="width: 100%;" required>
                    <option>Select Class</option>
                    <?php
                    $result = $db_handle->conn->query("select * from lms_class_master");

                    while ($row = $result->fetch_assoc()) {
                      $class_name = $row['class_name'];
                      $class_id = $row['class_id'];

                    ?>
                      <option value="<?php echo $class_id; ?>"><?php echo $class_name; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <!-- /.form-group -->
              <div class="col-md-4">
                <div class="form-group">
                  <label>Division<span style="color: red;">*</span></label>
                  <select class="form-control select" name="batch" id="batch4" class="batch" onchange="display2()" style="width: 100%;" required>
                    <option>Select Division</option>
                    <?php
                    $result = $db_handle->conn->query("SELECT * from lms_section_master");

                    while ($row = $result->fetch_assoc()) {
                      $section_name = $row['sections'];
                      $s_id = $row['id'];

                    ?>
                      <option value="<?php echo $s_id;  ?>"><?php echo $section_name;  ?></option>
                    <?php } ?>

                  </select>
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label>Graduating Year</label>
                  <select class="form-control select" name="batch_id" id="batch_id" class="batch_id" style="width: 100%;">
                    <option>Select Year</option>
                    <?php
                    $result = $db_handle->conn->query("SELECT * from lms_batch_master");

                    while ($row = $result->fetch_assoc()) {
                      $batch_name = $row['batch_name'];
                      $batch_id = $row['batch_id'];
                    ?>
                      <option value="<?php echo $batch_id;  ?>"><?php echo $batch_name;  ?></option>
                    <?php } ?>

                  </select>
                </div>
              </div>

            </div> <!-- /.col 12 -->
            <!-- /.col -->

            <div class="col-md-12">

              <div class="col-md-4">
                <div class="form-group">
                  <label>Department<span style="color: red;">*</span></label>
                  <select class="form-control select" name="department_id" id="department_select" class="batch" style="width: 100%;" required>
                    <option>Select Department</option>
                    <?php
                    $result = $db_handle->conn->query("SELECT * from lms_department_master");
                    while ($row = $result->fetch_assoc()) {
                      $department_name = $row['department_name'];
                      $department_id = $row['department_id'];
                    ?>
                      <option value="<?php echo $department_id;  ?>"><?php echo $department_name;  ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label>Specialization<span style="color: red;">*</span></label>
                  <select class="form-control select" name="specialization_id" id="specialization_select" class="batch" style="width: 100%;" required>
                    <option>Select Specialization</option>
                    <?php
                    $result = $db_handle->conn->query("SELECT * from lms_specialization_master");

                    while ($row = $result->fetch_assoc()) {
                      $specialization_name = $row['specialization_name'];
                      $specialization_id = $row['specialization_id'];
                    ?>
                      <option value="<?php echo $specialization_id;  ?>"><?php echo $specialization_name;  ?></option>
                    <?php } ?>

                  </select>
                </div>
              </div>

              <!-- Multidisciplinary Section - Only for Minor -->
              <div class="col-md-4" id="multidisciplinary_section" style="display: none;">
                <div class="form-group">
                  <label>Multidisciplinary <span style="color: red;">*</span></label>
                  <div>
                    <label style="margin-right: 14px; padding-top: 4px; font-weight: normal;">
                      <input type="radio" name="multidisciplinary" value="yes"> Yes
                    </label>
                    <label style="font-weight: normal;">
                      <input type="radio" name="multidisciplinary" value="no"> No
                    </label>
                  </div>
                </div>
              </div>

            </div>

            <div class="col-md-12">
              <div class="col-md-4" id="specialization_subject_wrapper" style="display: none;">
                <div class="form-group">
                  <label>Specialization Subject<span style="color: red;">*</span></label>
                  <select class="form-control select" name="unaided_subject" id="specialization_subject_select" class="batch" style="width: 100%;">
                    <option>Select Specialization Subject</option>
                    <?php
                    $result = $db_handle->conn->query("SELECT * from lms_specialization_subject_master");

                    while ($row = $result->fetch_assoc()) {
                      $subject_name = $row['subject_name'];
                      $subject_id = $row['subject_id'];
                    ?>
                      <option value="<?php echo $subject_id;  ?>"><?php echo $subject_name;  ?></option>
                    <?php } ?>

                  </select>
                </div>
              </div>

              <div class="col-md-4" id="minor_cgpa_section" style="display: none;">
                <div class="form-group">
                  <label>Enter Your CGPA (for Multidisciplinary Minor)<span style="color: red;">*</span></label>
                  <input type="text" name="minor_cgpa" id="minor_cgpa" class="form-control" data-placeholder="" style="width: 100%;">
                  <small class="text-muted">Minimum CGPA required: 6.0</small>
                </div>
              </div>
            </div>

            <div class="col-md-12" id="cgpa_section" style="display: none;">

              <div class="col-md-4">
                <div class="form-group">
                  <label>Enter Your CGPA(Aggregate)<span style="color: red;">*</span></label>
                  <input type="text" name="cgpa" id="cgpa" class="form-control" data-placeholder="" style="width: 100%;">
                </div>
              </div>

              <div class="col-md-8" id="honours_not_eligible" style="display: none; margin-top: 30px; color: #d9534f; font-weight: 600;">
                Not eligible to register in Honours. CGPA must be above 7.
              </div>

            </div>


          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->

      </div>


      <!--end formset-->

      <div id="below_eligibility_sections">

        <!-- new formset2-->

        <div class="box box-default" id="personal_details_section" style="padding: 10px; display: none;">
          <div class="box-header with-border" style="border-bottom: 2px solid #9C27B0;">
            <h3 class="box-title">PERSONAL DETAILS:- </h3>

            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>

            </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">

                <div class="col-md-4">
                  <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="fname" class="form-control" data-placeholder="" style="width: 100%;" required>
                  </div>
                </div>


                <div class="col-md-4">
                  <div class="form-group">
                    <label>College Email <span style="color: red;">*</span></label>
                    <input type="email" name="email" id="college_email" class="form-control"
                      placeholder="example@tcetmumbai.in" style="width: 100%;"
                      pattern="[a-zA-Z0-9._%+\-]+@tcetmumbai\.in"
                      title="Email must end with @tcetmumbai.in" required>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label>Mobile</label>
                    <input type="text" pattern="^\d{10}$" class="form-control"
                      id="mobile" name="mobile" minlength="10" maxlength="10" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Mobile No." style="width: 100%;" />
                    <!-- <input type="text" class="form-control" name="mobile"  data-placeholder="" style="width: 100%;" required="required"> -->
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
            </div>


            <!--end formset2-->

          </div>

          <!-- new formset3-->


          <div class="box box-default" id="upload_documents_section" style="padding: 10px; display: none;">
            <div class="box-header with-border" style="border-bottom: 2px solid #9C27B0;">
              <h3 class="box-title">Upload Documents:- </h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="row doc-row">
                <div class="col-md-3 col-sm-4">
                  <label class="doc-label" for="checkbox1">
                    <input type="checkbox" id="checkbox1"> Mark List
                  </label>
                </div>
                <div class="col-md-9 col-sm-8 doc-upload-field" id="autoUpdate1" style="display:none;">
                  <input type="file" class="form-control" name="mark-list1" accept=".pdf,.jpg,.jpeg,.png">
                </div>
              </div>

              <div class="row doc-row">
                <div class="col-md-3 col-sm-4">
                  <label class="doc-label" for="checkbox2">
                    <input type="checkbox" id="checkbox2"> MarkSheet of Semester 1
                  </label>
                </div>
                <div class="col-md-9 col-sm-8 doc-upload-field" id="autoUpdate2" style="display:none;">
                  <input type="file" class="form-control" name="mark-list2" accept=".pdf,.jpg,.jpeg,.png">
                </div>
              </div>

              <div class="row doc-row">
                <div class="col-md-3 col-sm-4">
                  <label class="doc-label" for="checkbox6">
                    <input type="checkbox" id="checkbox6"> MarkSheet of Semester 2
                  </label>
                </div>
                <div class="col-md-9 col-sm-8 doc-upload-field" id="autoUpdate6" style="display:none;">
                  <input type="file" class="form-control" name="mark-list6" accept=".pdf,.jpg,.jpeg,.png">
                </div>
              </div>

              <div class="row doc-row">
                <div class="col-md-3 col-sm-4">
                  <label class="doc-label" for="checkbox4">
                    <input type="checkbox" id="checkbox4"> MarkSheet of Semester 3
                  </label>
                </div>
                <div class="col-md-9 col-sm-8 doc-upload-field" id="autoUpdate4" style="display:none;">
                  <input type="file" class="form-control" name="mark-list4" accept=".pdf,.jpg,.jpeg,.png">
                </div>
              </div>

              <div class="row" style="margin: 20px 0 0 0;">
                <div style="margin-top: 20px; text-align: center;">
                  <input type="submit" name="save" value="Save Changes" class="btn-submit">
                  <input type="reset" name="reset" value="Reset" class="btn-reset">
                </div>
              </div>
            </div>
          </div>

    </form>
    <!--end formset3-->


  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script type="text/javascript">
  function show(input) {

    var validExtensions = ['jpg', 'png', 'jpeg']; //array of valid extensions
    var fileName = input.files[0].name;
    var fileNameExt = fileName.substr(fileName.lastIndexOf('.') + 1);
    if ($.inArray(fileNameExt, validExtensions) == -1) {
      input.type = ''
      input.type = 'file'
      $('#user_img').attr('src', "");
      alert("Only these file types are accepted : " + validExtensions.join(', '));
    } else {
      if (input.files && input.files[0]) {
        var filerdr = new FileReader();
        filerdr.onload = function(e) {
          $('#user_img').attr('src', e.target.result);
        }
        filerdr.readAsDataURL(input.files[0]);
      }
    }
  }
</script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#checkbox1').on('change', function() {
      if (this.checked) {
        $('#autoUpdate1').stop(true, true).slideDown('fast');
      } else {
        $('#autoUpdate1').stop(true, true).slideUp('fast');
      }
    });

    $('#checkbox2').on('change', function() {
      if (this.checked) {
        $('#autoUpdate2').stop(true, true).slideDown('fast');
      } else {
        $('#autoUpdate2').stop(true, true).slideUp('fast');
      }
    });

    $('#checkbox3').on('change', function() {
      if (this.checked) {
        $('#autoUpdate3').stop(true, true).slideDown('fast');
      } else {
        $('#autoUpdate3').stop(true, true).slideUp('fast');
      }
    });

    $('#checkbox4').on('change', function() {
      if (this.checked) {
        $('#autoUpdate4').stop(true, true).slideDown('fast');
      } else {
        $('#autoUpdate4').stop(true, true).slideUp('fast');
      }
    });

    $('#checkbox6').on('change', function() {
      if (this.checked) {
        $('#autoUpdate6').stop(true, true).slideDown('fast');
      } else {
        $('#autoUpdate6').stop(true, true).slideUp('fast');
      }
    });
  });
</script>
<?php include "header/footer.php" ?>