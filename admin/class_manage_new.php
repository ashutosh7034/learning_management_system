<?php include "header/header.php"; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
   <section class="content-header">
      
      <ol class="breadcrumb"> 
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Class</li>
      </ol>
    </section>
 <section class="content" style="margin-top: 30px">
  <div class="box" style=" padding: 10px;">
 
  
  <h2><i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i> Class </h2>
  <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#home"><i class="fa fa-bars" aria-hidden="true"></i> Class Lists</a></li>
    <li><a data-toggle="tab" href="#menu1"><i class="fa fa-plus-circle"></i> Allocate Class </a></li>
    
  </ul> 

  <div class="tab-content">
    <div id="home" class="tab-pane fade in active">
     <div class="box-body" style="margin-top: 30px;">
             <table id="all_admin" class="text-center table table-striped table-bordered" width="100%">
              <thead>
               <tr>
                <th>ID</th>
                <th>Class Name</th>
                <th>Sections Name</th> 
                <th>Teacher Name</th>            
                <th>Date</th>
                <th>Edit</th>
                <th>Delete</th>
              </tr>
            </thead>
          </table>


            </div>
     
    </div>


    <div id="menu1" class="tab-pane fade">
    <div class="box-body" style="margin-top: 30px;">
      <form action="" method="POST" class="form-horizontal" role="form" style="align-content: center;">
        <div class="form-group">
                    <label class="col-xs-4 control-label"
                              for="name">Class Name</label>
                    <div class="col-xs-4">



                          <select class="form-control" id="select_class" name="select_class" required>
                        <option value="" disabled="disabled" selected="selected">Select Class</option>


                         <?php
               
                  $result=$db_handle->conn->query("SELECT * FROM class_master");

                  while($row=$result->fetch_assoc()){
                    $class_id = $row['id'];
                    $class_name = $row['class'];
                    ?>

                    <option value="<?php echo $class_id; ?>"><?php echo $class_name; ?></option>


                  <?php } ?>
                          

                        </select>



                      

 





                    </div>
        </div>


         <div class="form-group">
                    <label class="col-xs-4 control-label"
                              for="name">Sections Name</label>
                    <div class="col-xs-4">




                          <select class="form-control" id="select_section" name="select_section" required>
                        <option value="" disabled="disabled" selected="selected">Select Section</option>


                         <?php
               
                  $result=$db_handle->conn->query("SELECT * FROM section_master");

                  while($row=$result->fetch_assoc()){
                    $sec_id = $row['id'];
                    $sec_name = $row['sections'];
                    ?>

                    <option value="<?php echo $sec_id; ?>"><?php echo $sec_name; ?></option>


                  <?php } ?>
                          

                        </select>
                       






                    </div>
        </div>


         <div class="form-group">
                    <label class="col-xs-4 control-label"
                              for="name">Teacher Name</label>
                    <div class="col-xs-4">



                          <select class="form-control" id="select_teacher" name="select_teacher" required>
                        <option value="" disabled="disabled" selected="selected">Select Teacher</option>


                         <?php
               
                  $result=$db_handle->conn->query("SELECT * FROM dsms_employee_master where emp_type='2'");

                  while($row=$result->fetch_assoc()){
                    $teacher_id = $row['emp_id'];
                    $teacher_name = $row['emp_name'];
                    ?>

                    <option value="<?php echo $teacher_id; ?>"><?php echo $teacher_name; ?></option>


                  <?php } ?>
                          

                        </select>



                    </div>
        </div>
                  
                   <div class="form-group">
                    <div class="col-sm-offset-1 col-sm-10" align="center">
                      <button type="submit" class="btn btn-success" name="add">Allocate Class</button>
                    </div>
                  </div>
      </form>
      </div>
    </div>


   </div>
</div>

   </section> 

<!-- start add class php code -->

<?php  


if(isset($_POST['add']))
{
  
$select_class = $_POST['select_class']; 
$select_section = $_POST['select_section']; 
$select_teacher = $_POST['select_teacher']; 



//   $result=$db_handle->numRows("select * from manage_class where teacher_id='$select_teacher' ");

// if ($result>=1) {
//    echo '<script type="text/javascript">swal("Oops...", "You Are Already Allocated Class To This Teacher..!", "error");</script>';
// }




// else{ 
 


  $result=$db_handle->numRows("select * from manage_class where class_id='$select_class' and section_id='$select_section'");

if ($result>=1) {
   echo '<script type="text/javascript">swal("Oops...", "Your Allocated Class Already Exist..!", "error");</script>';
}




else{ 
 


$sql = "INSERT INTO manage_class(class_id,section_id,teacher_id) VALUES ('$select_class','$select_section','$select_teacher')";  // Insert query

if($db_handle->conn->query($sql) === TRUE)
{
  // echo '<script type="text/javascript">swal("Subject Added Successfully..!");</script>';
  // echo "<script>window.open('subject_manage.php','_self')</script>";

echo '<script type="text/javascript">swal({title: "ADDED", text: "Class Allocated Successfully", type: 
"success"}).then(function()
   { 
    window.location.href ="class_manage_new.php";
   }
);</script>';


}
else
{
echo("Error description: " . mysqli_error($db_handle->conn));
}



}



//}


$db_handle->conn->close();
}

?>






<!-- end add class php code -->


 

<!--Start Edit Modal -->

<div id="edit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
             <div class="modal-dialog"> 
                  <div class="modal-content"> 
                  
                       <div class="modal-header"> 
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
                            <h4 class="modal-title">
                              <i class="glyphicon glyphicon-user"></i> Edit Section Details
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
  
  $(document).on('click', '#allocated_class_edit', function(e){
    
    e.preventDefault();
    
    var uid = $(this).data('id');   // it will get id of clicked row
    
    $('#dynamic-content').html(''); // leave it blank before ajax call
    $('#modal-loader').show();      // load ajax loader
    
    $.ajax({
      url: 'allocated_class_edit_new.php', 
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

  



<script type="text/javascript" language="javascript">

    function delete_allocated_class(id)
    {
      var form_data = new FormData();
     
     form_data.append("id", id)
    swal({
    title: "Are you sure?",
    text: "Once deleted, you will not be able to recover this Allocated Class details!",
    icon: "warning",
    buttons: true,
    dangerMode: true,
    })
    .then((willDelete) => {
    if (willDelete) {
    $.ajax({
        url: 'delete_allocated_class.php',
        type: 'post',
        dataType: "json",
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        success: function (data)
        {


            swal('Allocated Class has been deleted!')
           .then((value) => {
            window.location.href=window.location.href;
            });
        }
    });
  }
  else
  {
    swal("Delete Operation has been cancel!");
  }
});

}



      $(document).ready(function() {
        var dataTable = $('#all_admin').DataTable( {
         "dom": 'Bfrtip',
         "paging": true,
         "searching": true,
         "select": true,

         "aaSorting" : [[0, 'asc']],
         "lengthMenu": [
            [ 10, 25, 50, 100, 500],
            [ '10', '25', '50', '100', '500']
        ],
        "columnDefs": [
                         {
                          "orderable": false, "targets": 4
                         }
                        ],
        "buttons": [
           {
            extend: 'colvis',
            text: "Columns"
           },
           {
            extend: 'pageLength',
            text: 'Show'
           },
             {
                extend: 'excel',
                text: "Excel",
                title: "",
                exportOptions:
                {
                    columns: [ 0, 1, 2, 3, 4, 5 ]
                },
            },
             {
                extend: 'csv',
                text: "CSV",
                title: "",
                exportOptions: {
                   columns: [ 0, 1, 2, 3, 4, 5 ]
                }
            },
             {
                extend: 'pdf',
                text: "PDF",
                title: "",
                orientation: 'landscape',
                pageSize: 'LEGAL',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5 ]
                }
            },
             {
                extend: 'print',
                title: "",
                text: "print",
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5 ]
                }
            },
        ], 
          "processing": true,
          "serverSide": true,
          "language": {
                        "processing": "<span style='color:#8b0000;font-size:20px;back'> Processing data.. <i class='fa fa-spinner fa-spin'></i> </span>",
                        "search": '',
                        "searchPlaceholder": "search",
                        "paginate": {
                        "previous": '<i class="fa fa-angle-double-left"></i> Previous',
                        "next": 'Next <i class="fa fa-angle-double-right"></i>'
            }
                        }, 
          "ajax":{ 
            url :"all_allocated_class_ajax.php",
            type: "post",
            error: function(response){
              console.log(response);
              $(".all_admin_ajax-error").html("");
              $("#all_admin_ajax").append('<tbody class="all_admin_ajax-error"><tr><th colspan="3">No data found in the server </th></tr></tbody>');
              $("#all_admin_ajax_processing").css("display","none");
            }
          }
        });
        $('input[type=search]').addClass('form-control');
        $('#all_admin_length').addClass('hidden');
        $('.sidebar-mini').addClass('sidebar-collapse');
      });
 

    function check_subject()
    {


      var typ_subject = $("#subject_name").val();      
      
        processData: false, 

    $.ajax({
        type: 'POST',
        url: 'check_subject.php',
        data: { "subject" : typ_subject },
        success: function (data)
        {

          if (data==1) {
              swal('Subject has been deleted!');
          }


          
           
        }


    });


    }

    </script>

 

  <?php include "header/footer.php"; ?>