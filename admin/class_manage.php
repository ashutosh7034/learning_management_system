<?php include "header/header.php"; ?>
<?php
if(isset($_POST['add']))
{
  $c_name=$_POST['c_name'];
  $cn_name=$_POST['cn_name'];
  $c_teacher=$_POST['c_teacher'];
  $period=$_POST['period'];

  $sql="INSERT INTO `dsms_class_master`(`class_name`, `numeric_name`, `teacher`, `Period`) VALUES ('".$c_name."','".$cn_name."','".$c_teacher."','".$period."')";
    if($db_handle->conn->query($sql) === TRUE){
      echo '<script type="text/javascript">alert("Class Added Successfully..!");</script>';
      echo "<meta http-equiv='refresh' content='0'>";
    }else{
      echo("Error description: " . mysqli_error($db_handle->conn));
    }
}

?>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  
  <script>
  function validateaadhar(period){
      var len = document.myform.period.value;
 
      if(len<=10){
        document.getElementById('period').style.background ='#ccffcc';
        document.getElementById('numberError').style.display = "none";
        return true;
      }else{
        document.getElementById('period').style.background ='#e35152';
     document.getElementById('numberError').style.display = "block";
        return false;
        }
      }
	  
	  
	  function validateForm(){
		  var error =0;
		  
		  if(document.myform.c_name.value == null || document.myform.c_name.value == ""){
          document.getElementById('ClassError').style.display = "block";
          error++;
			}
			
			if(document.myform.cn_name.value == null || document.myform.cn_name.value == ""){
          document.getElementById('romanError').style.display = "block";
          error++;
			}
			
			if(document.myform.period.value == null || document.myform.period.value == ""){
          document.getElementById('numberError1').style.display = "block";
          error++;
			}
			
			if(document.myform.c_teacher.value == null || document.myform.c_teacher.value == ""){
          document.getElementById('teacherError').style.display = "block";
          error++;
			}
		  
		  if(error > 0){
                return false;
              }
	  }
  </script>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
   <section class="content-header">

      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"> Class</a></li>
       <li class="active">Manage Classes</li>
      </ol>
    </section>
  <section class="content" style="margin-top: 30px">
    <div class="box" style=" padding: 10px;">
      <h2><i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i> Manage Classes</h2>
    <ul class="nav nav-tabs">
      <li class="active"><a data-toggle="tab" href="#home"><i class="fa fa-bars" aria-hidden="true"></i> Class List</a></li>
      <li><a data-toggle="tab" href="#menu1"><i class="fa fa-plus-circle"></i> Add Class</a></li>

    </ul>
    <div class="tab-content">
      <div id="home" class="tab-pane fade in active">
       <div class="box-body" style="margin-top: 30px;">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>ID</th>
                    <th>Class Name</th>
                    <th>Numeric Name</th>
                    <th>Period</th>
                    <th>Teacher</th>
                    <th>Option</th>

                  </tr>
                  </thead>
                  <tbody>
                  <?php
                    $result=$db_handle->conn->query("SELECT * FROM dsms_class_master");

                    while($row=$result->fetch_assoc()){
                      $c_id = $row['c_id'];
                      $class_name = $row['class_name'];
                      $numeric_name = $row['numeric_name'];
                      $teacher = $row['teacher'];
                      $Period = $row['Period'];




                  ?>
                  <tr>
                    <td><?php echo $c_id;   ?></td>
                    <td><?php echo $class_name;   ?> </td>
                    <td><?php echo $numeric_name;   ?></td>
                    <td><?php echo $Period;   ?></td>
                    <td><?php if(!empty($teacher)) {
                      ?><span class="label label-success"><?php
                         echo $teacher; ?></span><?php
                        }else{?>
                          <span class="label label-danger">No Teacher</span><?php }?> </td>
                    <td><div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">Action
                    <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                      <li><a data-toggle="modal" data-target="#edit" data-id="<?php echo $c_id; ?>" id="class_edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a></li>
                      <li><a href="class-delete.php?del=<?php echo $c_id; ?>"><i class="fa fa-trash-o" aria-hidden="true"></i><span onclick="return confirm('Do you really want to delete?')"> Delete</span></a></li>

                    </ul>
                  </div>
                  </td>
                  </tr>
                   <?php } ?>
                  </tbody>
                  <!--tfoot>
                  <tr>
                    <th>Rendering engine</th>
                    <th>Browser</th>
                    <th>Platform(s)</th>
                    <th>Engine version</th>
                    <th>CSS grade</th>
                  </tr>
                  </tfoot-->
                </table>
              </div>

      </div>
      <div id="menu1" class="tab-pane fade" width="500px";>
        <div class="box-body" style="margin-top: 30px;">
          <form action="" method="POST" name="myform" class="form-horizontal" role="form" style="align-content: center;" onsubmit="return validateForm()">
            <div class="form-group">
                        <label class="col-xs-4 control-label"
                                  for="name">Class Name</label>
                        <div class="col-xs-4">
                            <input type="text" class="form-control"
                            id="c_name" name="c_name" placeholder="" autocomplete="off" onBlur="checkUserAvailability()" />
							<span id="ClassError" style="display: none; color:red;" >Class Name cannot be blank.</span>
                            <span id="username-availability-status" class="alert-danger"></span>
                        </div>
                      </div>
                      <div class="form-group">
                        <label  class="col-xs-4 control-label"
                                  for="name">Name Numeric</label>
                        <div class="col-xs-4">
                            <input type="text" class="form-control"
                            id="cn_name" name="cn_name" placeholder="" autocomplete="off"/>
							<span id="romanError" style="display: none; color:red;" >Class Roman cannot be blank.</span>
                        </div>
                      </div>

                      <div class="form-group">
                        <label  class="col-xs-4 control-label"
                                  for="name">Period</label>
                        <div class="col-xs-4">
                            <input type="text" class="form-control"
                            id="period" name="period" placeholder="" autocomplete="off" onblur="validateaadhar(value)"/>
							<span id="numberError" style="display: none; color:red;" >Period is Allow Of Less Than 10. </span>
							<span id="numberError1" style="display: none; color:red;" >Period cannot blank. </span>
                        </div>
                      </div>

                      <div class="form-group">
                        <label  class="col-xs-4 control-label"
                                  for="gender">Teacher</label>
                        <div class="col-xs-4">
                            <select class="form-control select2" style="width: 100%;" id="c_teacher" name="c_teacher">
                            <option value="">Select Teacher</option>
                              <?php
                                  $result=$db_handle->conn->query("SELECT * FROM dsms_employee_master WHERE emp_type='teacher'");

                                  while($row=$result->fetch_assoc()){
                                   $emp_name = $row['emp_name'];

                               ?>
                              <option value="<?php echo $emp_name;  ?>"><?php echo $emp_name;  ?></option>
                              <?php } ?>

                            </select>
							<span id="teacherError" style="display: none; color:red;" >Please Select The Teacher</span>
                        </div>
                      </div>
                       <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10" align="center">
                          <button type="submit" class="btn btn-success" name="add">Add Class</button>
                        </div>
                      </div>

      </form>
      </div>
     </div>
    </div>



  </section>
</div>


<div id="edit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
             <div class="modal-dialog">
                  <div class="modal-content">

                       <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title">
                              <i class="glyphicon glyphicon-user"></i> Edit Class
                            </h4>
                       </div>
                       <div class="modal-body">

                           <div id="modal-loader" style="display: none; text-align: center;">
                            <img src="ajax-loader.gif">
                           </div>

                           <!-- content will be load here -->
                           <div id="dynamic-content"></div>

                        </div>
                        <div class="modal-footer">
                              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>

                 </div>
              </div>
       </div><!-- /.modal -->

    </div>


<!--script src="../assets/jquery-1.12.4.min.js"></script>
<script src="../assets/js/bootstrap.min.js"></script-->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>

<script>
$(document).ready(function(){

  $(document).on('click', '#class_edit', function(e){

    e.preventDefault();

    var uid = $(this).data('id');   // it will get id of clicked row

    $('#dynamic-content').html(''); // leave it blank before ajax call
    $('#modal-loader').show();      // load ajax loader

    $.ajax({
      url: 'class-edit.php',
      type: 'POST',
      data: 'id='+uid,
      dataType: 'html'
    })
    .done(function(data){
      console.log(data);
      $('#dynamic-content').html('');
      $('#dynamic-content').html(data); // load response
      $('#modal-loader').hide();      // hide ajax loader
    })
    .fail(function(){
      $('#dynamic-content').html('<i class="glyphicon glyphicon-info-sign"></i> Something went wrong, Please try again...');
      $('#modal-loader').hide();
    });

  });

});

</script>


<script>
$(document).ready(function(){

 $('#c_name').typeahead({
  source: function(query, result)
  {
   $.ajax({
    url:"search.php",
    method:"POST",
    data:{query:query},
    dataType:"json",
    success:function(data)
    {
     result($.map(data, function(item){
      return item;
     }));
    }
   })
  }
 });

});
</script>

<script>
$(document).ready(function(){

 $('#cn_name').typeahead({
  source: function(query, result)
  {
   $.ajax({
    url:"search1.php",
    method:"POST",
    data:{query:query},
    dataType:"json",
    success:function(data)
    {
     result($.map(data, function(item){
      return item;
     }));
    }
   })
  }
 });

});
</script>

<script>
function checkUserAvailability() {
	$("#loader").show();
	jQuery.ajax({
	url: "checkdata.php",
	data:'class_name='+$("#c_name").val(),
	type: "POST",
	success:function(data){
		if(data == 1) {
			$("#username-availability-status").html("Class Name is Already Register.");
			$("#username-availability-status").removeClass('available');
			$("#username-availability-status").addClass('not-available');
		} else {
			$("#username-availability-status").html("");
			$("#username-availability-status").removeClass('not-available');
			$("#username-availability-status").addClass('available');
		}
		$("#loader").hide();
	},
	error:function (){}
	});
}
</script>

<?php include "header/footer.php"; ?>
