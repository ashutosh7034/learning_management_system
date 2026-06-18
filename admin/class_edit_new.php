<?php
  session_start();
  include "../database/db_connect.php";

  $db_handle = new DBcontroller();
 
	if (isset($_REQUEST['id'])) {

		$id = intval($_REQUEST['id']);
		$result=$db_handle->conn->query("SELECT * FROM lms_class_master WHERE class_id='$id'");

	while($row=$result->fetch_assoc()){
                    $b_id = $row['class_id'];
                    $class_name = $row['class_name'];
                    $date = $row['date']; 
                  }
                }
 ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />

		<div class="modal-body">

                <form id="class_edit_new" method="POST" class="form-horizontal"  style="padding: 10px; align-content: center;">
      
                  <div class="form-group">
                    <label  class="col-sm-4 control-label"
                              for="Book Name">Class Name</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control"
                        id="class_name" name="class_name" value="<?php echo $class_name; ?>" />


                        <input type="hidden" class="form-control"
                        id="class_id" name="class_id" value="<?php echo $b_id; ?>" />
                    </div>
                  </div>
                 
            <div class="form-group" id="response"></div>

                 <hr>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10" align="center">
                      <input type="submit" id="submit" class="btn btn-success" name="update" value="Update Now"/>
                    </div>
                  </div>
                </form>

            </div>
<script type="text/javascript">
  
 $("#class_edit_new").submit(function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    
    $.ajax({  
        url: 'class_edit_new_ajax.php',
        type: 'POST', 
        data: formData, 
        success: function (response) {
           console.log(response);
            

               if(response == 1)
                   {
           
                    document.getElementById("response").innerHTML = "<div class='alert alert-success col-md-12'> <div class='container col-md-12'> <button type='button' class='close' data-dismiss='alert' aria-label='Close'> <span aria-hidden='true'><i class='fa fa-times'></i></span> </button> <b> Class Updated Successfully . </b></div></div>";

                     location.reload();
                   }

                      else
                   {
                     document.getElementById("response").innerHTML = "<div class='alert alert-warning col-md-12'> <div class='container col-md-12'> <button type='button' class='close' data-dismiss='alert' aria-label='Close'> <span aria-hidden='true'><i class='fa fa-times'></i></span> </button> <b> Their is Problem in Updating. </b></div></div>";
                    
                   }
                   
        },
        cache: false,
        contentType: false,
        processData: false
      });
    });



</script>