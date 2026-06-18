<?php

 include "../database/db_connect.php";
 $db_handel = new DBController();

if(isset($_POST['update'])){

$update_id = $_POST['std_id'];
$medium = $_POST['medium'];	
$academic  = $_POST['academic'];
$class1  = $_POST['class'];
$registration_no  = $_POST['registration_no'];
$saral_id  = $_POST['saral_id'];
$gr_no  = $_POST['gr_no'];
$aadhar_no  = $_POST['aadhar_no'];
$pen = $_POST['pen'];
$apar  = $_POST['apar'];
$batch   = $_POST['batch'];
$join_date  = $_POST['join_date'];
$roll_no  = $_POST['roll_no'];
$fname  = $_POST['fname'];
$dob  = $_POST['dob'];
$birth_place  = $_POST['birth_place'];
$dsms_student_master  = $_POST['table_name'];
$dsms_fees_master  = $_POST['table_name1'];
$dsms_cancelled_fees_master  = $_POST['table_name2'];
$cat  = $_POST['cat'];
$mname  = $_POST['mname'];
$gender  = $_POST['gender'];
$nation  = $_POST['nation'];
$religion  = $_POST['religion'];
$lname  = $_POST['lname'];
$blood  = $_POST['blood'];
$mother_tongue  = $_POST['mother_tongue'];
$caste  = $_POST['caste'];
$permanent  = $_POST['permanent'];
$present  = $_POST['present'];
$city  = $_POST['city'];
$pincode  = $_POST['pincode'];
$country  = $_POST['country'];
$state  = $_POST['state'];
$phone  = $_POST['phone'];
$mobile  = $_POST['mobile'];
$email  = $_POST['email'];
$pname  = $_POST['pname'];
$pmobile  = $_POST['pmobile'];
$pemail  = $_POST['pemail'];
$pjob  = $_POST['pjob'];
$m_name  = $_POST['m_name'];
$m_mobile  = $_POST['m_mobile'];
$memail  = $_POST['memail'];
$mjob  = $_POST['mjob'];
$qualification  = $_POST['education'];
$school  = $_POST['school'];
$saddress  = $_POST['saddress'];
$unaided_subject  = $_POST['unaided_subject'];
$folder="../manage/pdf/";
$photo = $_FILES['photo']['name'];
$file_loc = $_FILES['photo']['tmp_name'];
$mark = $_FILES['mark-list']['name'];
$file_loc1 = $_FILES['mark-list']['tmp_name'];
$bc	 = $_FILES['bc']['name'];
$file_loc2 = $_FILES['bc']['tmp_name'];
$tc = $_FILES['tc']['name'];
$file_loc3 = $_FILES['tc']['tmp_name'];
$cc = $_FILES['cc']['name'];
$file_loc4 = $_FILES['cc']['tmp_name'];
$migration = $_FILES['migration']['name'];
$file_loc5 = $_FILES['migration']['tmp_name'];
$affidavit = $_FILES['affidavit']['name'];
$file_loc6 = $_FILES['affidavit']['tmp_name'];

if($photo!="")
    {
    $photo = $_FILES['photo']['name'];
    move_uploaded_file($file_loc,$folder.$photo);
	}
	else{
		$result=mysqli_query($db_handel->conn,"SELECT * FROM $dsms_student_master WHERE std_id='$update_id'");
	
	while($row=$result->fetch_assoc()){
			$new_file = $row['photo'];
	}

		$photo = $new_file;
	}

if($mark!="")
    {
    	$mark = $_FILES['mark-list']['name'];
    move_uploaded_file($file_loc1,$folder.$mark);
	}
	else{
		$result=mysqli_query($db_handel->conn,"SELECT * FROM $dsms_student_master WHERE std_id='$update_id'");
	
	while($row=$result->fetch_assoc()){
			$new_file = $row['marklist'];
	}

		$mark = $new_file;
	}

if($bc!="")
    {
    	$bc	 = $_FILES['bc']['name'];
    move_uploaded_file($file_loc2,$folder.$bc);
	}
	else{
		$result=mysqli_query($db_handel->conn,"SELECT * FROM $dsms_student_master WHERE std_id='$update_id'");
	
	while($row=$result->fetch_assoc()){
			$new_file = $row['birth_certificate'];
	}

		$bc = $new_file;
	}

if($tc!="")
    {
    	$tc = $_FILES['tc']['name'];
    move_uploaded_file($file_loc3,$folder.$tc);
	}
	else{
		$result=mysqli_query($db_handel->conn,"SELECT * FROM $dsms_student_master WHERE std_id='$update_id'");
	
	while($row=$result->fetch_assoc()){
			$new_file = $row['transfer_certificate'];
	}

		$tc = $new_file;
	}

if($cc!="")
    {
    	$cc = $_FILES['cc']['name'];

   move_uploaded_file($file_loc4,$folder.$cc);

	}
	else{
		$result=mysqli_query($db_handel->conn,"SELECT * FROM $dsms_student_master WHERE std_id='$update_id'");
	
	while($row=$result->fetch_assoc()){
			$new_file = $row['caste_certificate'];
	}

		$cc = $new_file;
	}

if($migration!="")
    {
    	$migration = $_FILES['migration']['name'];

   move_uploaded_file($file_loc5,$folder.$migration);

	}
	else{
		$result=mysqli_query($db_handel->conn,"SELECT * FROM $dsms_student_master WHERE std_id='$update_id'");
	
	while($row=$result->fetch_assoc()){
			$new_file = $row['migration_certificate'];
	}

		$migration = $new_file;
	}	

if($affidavit!="")
    {
    	$affidavit = $_FILES['affidavit']['name'];

   move_uploaded_file($file_loc6,$folder.$affidavit);

	}
	else{
		$result=mysqli_query($db_handel->conn,"SELECT * FROM $dsms_student_master WHERE std_id='$update_id'");
	
	while($row=$result->fetch_assoc()){
			$new_file = $row['affidavit'];
	}

		$affidavit = $new_file;
	}	




$sql = "UPDATE $dsms_student_master SET std_id_sch='$saral_id',medium='$medium',academic_year='$academic',  register_number='$registration_no',gr_no='$gr_no', aadhar_no='$aadhar_no', joining_date='$join_date', class='$class1', batch='$batch', roll_no='$roll_no', fname='$fname', mname='$mname', lname='$lname', dob='$dob', gender='$gender', blood_group='$blood', birth_place='$birth_place', nationality='$nation', mother_tongue='$mother_tongue', category='$cat', religion='$religion', caste='$caste', permanent_address='$permanent', present_address='$present', city='$city', pincode='$pincode', country='$country', state='$state', phone='$phone', mobile='$mobile', email_id='$email', photo='$photo', parent_name='$pname', parent_mobile='$pmobile', parent_email='$pemail', parent_job='$pjob',mother_name='$m_name', mother_mobile='$m_mobile', mother_email='$memail', mother_job='$mjob', qualification='$qualification',pen='$pen',apar='$apar', school_name='$school', school_address='$saddress', marklist='$mark', birth_certificate='$bc', transfer_certificate='$tc', caste_certificate='$cc', migration_certificate='$migration', affidavit='$affidavit',unaided_sub='$unaided_subject'  where std_id='$update_id'";

$sql1 = "UPDATE $dsms_fees_master SET `registration_no`='$registration_no' WHERE std_id='$update_id'";
$sql2 = "UPDATE $dsms_cancelled_fees_master SET `registration_no`='$registration_no' WHERE std_id='$update_id'";

$result=mysqli_query($db_handel->conn,$sql);
$result1=mysqli_query($db_handel->conn,$sql1);
$result2=mysqli_query($db_handel->conn,$sql2);

 if($result === TRUE){  


echo '<script type="text/javascript">alert("Student Details have been Updated Successfuly.");</script>';
echo "<script>window.open('student-edit.php?id=$update_id&dsms_student_master=$dsms_student_master&dsms_fees_master=$dsms_fees_master&dsms_cancelled_fees_master=$dsms_cancelled_fees_master','_self')</script>";
}


else
{
echo("Error description: " . mysqli_error($db_handel->conn));
}

 

}

?>