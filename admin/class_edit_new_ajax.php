<?php

require "../database/db_connect.php";
$db_handle = new DBController();
  
$id=$_POST["class_id"];
$class_name = ucwords(rtrim(ltrim($_POST["class_name"])));

    $sql = "UPDATE `lms_class_master` SET `class_name`='$class_name' WHERE class_id='$id'";

    $result = mysqli_query($db_handel->conn, $sql);

	if ($result==true) {
		             echo"1";
	               }
	else{
	     echo "2";

	    }

?>