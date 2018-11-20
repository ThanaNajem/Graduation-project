<?php
    session_start();
  require_once("../db_op.php");
  date_default_timezone_set('israel');
  
$status=false;
?>
   <!DOCTYPE html>
<?php include('../includes/header.php'); ?>
<!--End fixed menu-->
<!-- </div> -->
<?php 
if (isset($_SESSION["user_id"]) && (!empty($_SESSION["user_id"])) && (isset($_SESSION["role"])) && (!empty($_SESSION["role"]))
	&& ($_SESSION["role"] == 4)) { 

 $get_active_semester_tbl_row_count = Crud_op::get_active_semester_tbl_row_count1();
if($get_active_semester_tbl_row_count!=null) {
// 	$hour=date('H');
//  $min=date('i');
//  $sec=date('s');
//  $month=date('m');
//  $day=date('d');
//  $year=date('Y');
//  $from_date = mktime($hour, $min, $sec, $month, $day, $year);
//  $to_date = mktime($hour, $min, $sec, $month, $day, $year);

// $from_date= date("Y-m-d H:i:s", $from_date);
// $to_date= date("Y-m-d H:i:s", $to_date);
  $evt_name="create_ideas";
 $arr_from_and_to_evt_date=Crud_op::get_first_and_end_date_for_evt($evt_name);
if($arr_from_and_to_evt_date!=null){
 
 
 $from_date=$arr_from_and_to_evt_date[0]['from_date'];
 $to_date=$arr_from_and_to_evt_date[0]['to_date'];
 //$current_Date=date("Y-m-d H:i:s");
  $hour=date('H');
 $min=date('i');
 $sec=date('s');
 $month=date('m');
 $day=date('d');
 $year=date('Y');
 $current_Date = mktime($hour, $min, $sec, $month, $day, $year);

//$from_date = strtotime($from_date);
$from_date= date("Y-m-d H:i:s", $from_date);

//$to_date = strtotime($to_date);
$to_date= date("Y-m-d H:i:s", $to_date);

//$current_Date = strtotime($current_Date);
$current_Date= date("Y-m-d H:i:s", $current_Date); 
  
 
 if($current_Date>=$from_date && $current_Date<=$to_date){
 	 
$status=true;}

}
else{
$send_idea_success=null;
 	$send_idea_err="لم يتم تحديد الوقت المسموح به لتكوين الفكرة بعد";

}
if ($status) {
$student_id=$_SESSION['user_id'];
 
$grp_id=Crud_op::check_if_this_usr_has_grp($student_id);
 
	if ($grp_id!=null) {
	 	# code...
			$get_no_of_supervisor = 0;
			$get_no_of_supervisor = Crud_op::get_no_of_supervisor_of_these_grp($grp_id);
			if ($get_no_of_supervisor!=0) {
				# code...
				?>
	<div class="row" style="margin-bottom: 12px;">
	<div class="form-group text-center">
	  <div class="col-sm-4"></div>
    <div class="col-sm-4">
        <a class="btn icon-btn btn-success" href="send_project_idea_into_my_sup.php?action=add" name="send_project_idea_into_my_sup" style="font-size: 19px;font-weight: bold;"><span class="glyphicon btn-glyphicon glyphicon-plus-sign img-circle text-success" style="color: white;background-color: #83e88b" ></span>إضافة فكرة مشروعي</a>
 
    </div>
    <div class="col-sm-4"></div>
	</div>
	</div>


	<div class="row" style="margin-bottom: 12px;">
  <div class="form-group text-center">
    <div class="col-sm-4"></div>
    <div class="col-sm-4 alert alert-success">
       
  <strong>الفصل الفعّال: <?php
  if ( $get_active_semester_tbl_row_count!=null) {
  //  echo $_SESSION['active'];
 echo  $get_active_semester_tbl_row_count[0]['sem_name'].' '.$get_active_semester_tbl_row_count[0]['year_val'];
   }
   else{
    echo  'لم يتم تفعيل أي فصل بعد';
   }

  ?>
 </strong>
    </div>
    <div class="col-sm-4"></div>
</div>
</div>
<div class="row" style="margin-bottom: 12px;">
	<div class="form-group text-center">
	  <div class="col-sm-4"></div>
    <div class="col-sm-4 alert-success">
         <?php
		$get_idea_for_grp=Crud_op::get_idea_for_grp($grp_id);
		$accepted_idea_name="اسم الفكرة المقبولة: ";
		$alert_accepted_idea="";
		if($get_idea_for_grp!=null){
		$accepted_idea_name.=$get_idea_for_grp;	 
		
		}
		else{
			$accepted_idea_name.="لا فكرة مقبولة لمجموعتك";

		}
		echo $accepted_idea_name; 
		 		 ?>
    </div>
    <div class="col-sm-4"></div>
	</div>
	</div>
	<div class="row" style="margin-bottom: 12px;">
	<div class="form-group text-center">
	  <div class="col-sm-4"></div>
    <div class="col-sm-4 alert-danger">
         <?php
		$get_idea_for_grp=Crud_op::get_idea_for_grp($grp_id);
		$accepted_idea_name="اسم الفكرة المقبولة: ";
		$alert_accepted_idea="";
		if($get_idea_for_grp!=null){ 
	$alert_accepted_idea="تم قبول فكرتك فلن تتمكن من إرسال أفكار";
	echo $alert_accepted_idea;	include('../includes/footer.php');
		die( );
		}
		 
		 
		 ?>
    </div>
    <div class="col-sm-4"></div>
	</div>
	</div>
<?php
if (
(
	isset($_POST['resend_project_idea_into_my_sup'])
	||
    isset($_POST['send_project_idea_into_my_sup'])
) && ( isset($_POST['idea_name']) && isset($_POST['idea_desc'])) 
    ) 
{
	# code...
	

	# code...
	$idea_name = $_POST['idea_name'];
	$idea_desc = $_POST['idea_desc'];
 
	 
	 $grp_id = $grp_id;
	 $usr_login_id = $student_id;
	 
	if (isset($_POST['send_project_idea_into_my_sup'])) {
		# code...

		$add_new_idea_into_sup = Crud_op::add_new_idea_into_sup($idea_name,$idea_desc,$grp_id,$usr_login_id); 
		 
		if ($add_new_idea_into_sup) {
			# code...
			$send_idea_success="تمت إرسال فكرتك لمشرفك بنجاح";
			$send_idea_err="";
		}
		else{
			$send_idea_success="";
			$send_idea_err="لم يمت إرسال فكرتك لمشرفك بنجاح";
		}
	}
	elseif (isset($_POST['resend_project_idea_into_my_sup'])) {
		$idea_id='';
	 
		if (
			isset($_POST['idea_id']) 

		) { 
			$idea_id = $_POST['idea_id']; 
			 
		}

			$resend_project_idea_into_my_sup = Crud_op::resend_project_idea_into_my_sup($idea_name,$idea_desc,$idea_id);
			if ($resend_project_idea_into_my_sup) {
				$send_idea_success="تم إرسال فكرتك المعدلة لمشرفك بنجاح";
				$send_idea_err=""; 
			}
			else{
			$send_idea_err="لم يتم إرسال فكرتك المعدلة لمشرفك بنجاح";
			$send_idea_success="";
			}

	}
	$_POST = array();
	//	echo "<meta http-equiv=refresh content=\"0; URL=send_project_idea_into_my_sup.php\">";
}
elseif(
isset($_GET['action']) && ($_GET['action']=='del')
&& isset($_GET['idea_id'])
 ){
	$idea_id = $_GET['idea_id'];
	 
$del_status = Crud_op::del_idea_from_sup($idea_id,$grp_id);
if($del_status ){
	?>
	<div class="row" style="margin-top: 12px;" >
  <div class="col-sm-4 "></div>
<div class="col-sm-4 alert alert-success text-center"><strong><?php echo 'تمت عملية الحذف بنجاح'; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
	<?php
}
else{
	?>
	<div class="row" style="margin-top: 12px;" >
  <div class="col-sm-4 "></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo 'لم تتم عملية الحذف بنجاح'; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
	<?php
	}
}
 
   if(isset($send_idea_success)){
 echo '<div class="row" style="margin-top: 12px;" >
  <div class="col-sm-4 "></div>
<div class="col-sm-4 alert alert-success text-center"><strong>'. $send_idea_success.'</strong></div>
<div class="col-sm-4 "></div>
</div>';
 
  }  elseif(isset($send_idea_err)){
?>
<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo $send_idea_err; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
<?php
  }
  ?>
	
		<div class="container"><!-- Start of container-->
<?php
if (
	((isset($_REQUEST['action']) && $_REQUEST['action'] == "edit")  && !empty($_GET['idea_id']))
	||
(isset($_REQUEST['action']) && $_REQUEST['action'] == "add")
 ) {
	  $hidden_selected_idea = '';
 	$idea_id=0;
 	if (isset($_GET['idea_id'])) {
 		# code...
 		$idea_id=$_GET['idea_id'];
		 $hidden_selected_idea = '<input type="hidden" name="idea_id"  value="'.$_GET["idea_id"].' "/>';
 	}
		
	$get_specific_info_about_specific_idea_id = Crud_op::get_specific_info_about_specific_idea_id($idea_id);
	 if ($get_specific_info_about_specific_idea_id!=null || (isset($_REQUEST['action']) && $_REQUEST['action'] == "add")) {
	 	 	
if(
 (
 	(isset($_REQUEST['action']) && $_REQUEST['action'] == "edit")  && !empty($_GET['idea_id'])  ) 
 || 
 ((isset($_REQUEST['action']) && $_REQUEST['action'] == "add")  )
) {
	try {
		
			
	
?><form method="post" action="send_project_idea_into_my_sup.php">
<div class="form-group">

<?php
echo $hidden_selected_idea;
?>
	<div class="row">
<div class="col-xs-4 col-xs-offset-4">
	<label for="idea_name" style="margin-bottom: 12px;">اسم فكرتك</label>
	 
  <input type="text" class="form-control" id="idea_name" name="idea_name" placeholder="أَدْخِل اسم فكرتك" style="margin-bottom: 12px;" required="required" autofocus="autofocus"
<?php 
if(isset($_REQUEST['action']) && $_REQUEST['action'] == "add"){
	  
echo 'value=""> ';
	 
}
elseif ((isset($_REQUEST['action']) && $_REQUEST['action'] == "edit")  && !empty($_GET['idea_id'])) {
	# code...
	
	 
	echo 'value="'.$get_specific_info_about_specific_idea_id[0]['idea_name'].'">';
	 
}
?>
  
 
<label for="idea_desc" style="margin-bottom: 12px;">وصف فكرتك</label>
<textarea class="form-control" rows="5" id="desc_of_idea" name="idea_desc" placeholder="أَدْخِل وصف فكرتك" maxlength="500" style="margin-bottom: 12px;" required="required"><?php
if(isset($_REQUEST['action']) && $_REQUEST['action'] == "add"){
  echo '';  
}
elseif((isset($_REQUEST['action']) && $_REQUEST['action'] == "edit")  && !empty($_GET['idea_id'])){
echo $get_specific_info_about_specific_idea_id[0]['description'];   
}
echo '</textarea>
 

<input type="submit" class="btn btn-success"  style="margin-bottom: 12px; align-items: center;text-align: center;"

  required="required"
 ';
 
 
if(isset($_REQUEST['action']) && $_REQUEST['action'] == "add"){
	 
 echo 'value="إرسال فكرتي إلى مشرفي مشروعي"

onClick="return confirm(\'هل أنت متأكد من رغبتك بإرسال فكرتك و وصفها إلى مشرفيك\')"
 name="send_project_idea_into_my_sup">';
	 
}
elseif ((isset($_REQUEST['action']) && $_REQUEST['action'] == "edit")  && !empty($_GET['idea_id'])) {
	# code...
	 
	echo 'value="تعديل الفكرة و وصفها و إعادة إرسالها لمشرفي مشروعي"
	
onClick="return confirm(\'هل أنت متأكد من رغبتك بإرسال فكرتك و وصفها إلى مشرفيك بعد التعديل\')" 
	  name="resend_project_idea_into_my_sup">';
	 
}
 echo'


</div>
 </div>


	</div>
	</form>
	';
 
} catch (Exception $e) {
	/* To prevent url from change query parameter to data not found in database because someone write invalid parameter */ 
		echo $e->getMessage();
		$send_idea_err="يرجى عدم العبث بالعنوان";
		$send_idea_success=null;

   if(isset($send_idea_success)){
 ?><div class="row" style="margin-top: 12px;" >
  <div class="col-sm-4 "></div>
<div class="col-sm-4 alert alert-success text-center"><strong><?php echo $send_idea_success; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
<?php
  }  if(isset($send_idea_err)){
?>
<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo $send_idea_err; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
<?php
  }
  ?>
  <?php
	} 	
														  }
	 else{
	 	$send_idea_err="يرجى عدم العبث بالعنوان";

	 	?>
<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo $send_idea_err; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
	 	<?php
 
		 }
}

}
?>

 </div><!-- End of container-->
<div class="row">
	<!-- get data from idea table-->

	<div class="table-responsive text-center " style="direction: rtl;margin-top: 12px;">          
  <table class="table  table-hover table-striped table-bordered text-center" id="tbl1" style="align-items: center;">
<thead>
   
  	<th>أفكارك</th>
  	<th>وصف لأفكارك</th>
  	<th>حالة الفكرة</th> 
  	 
  	<th>الإجراء</th>
   
</thead>
<tbody>
	<?php 

$get_proj_idea_for_specific_std_for_specific_grp = Crud_op::get_proj_idea_for_specific_std_for_specific_grp($grp_id,$student_id);
if ($get_proj_idea_for_specific_std_for_specific_grp!=null) {
	for($l=0;$l<count($get_proj_idea_for_specific_std_for_specific_grp);$l++) {
		# code...
	
	?>
	<tr>
<td><?php echo $get_proj_idea_for_specific_std_for_specific_grp[$l]['idea_name'] ; ?></td>
<td><?php echo $get_proj_idea_for_specific_std_for_specific_grp[$l]['description'] ; ?></td>
<td><?php 
$idea_status="";
if ($get_proj_idea_for_specific_std_for_specific_grp[$l]['idea_status']=="pending") {
	# code...
	$idea_status="قيد الانتظار";
}
elseif ($get_proj_idea_for_specific_std_for_specific_grp[$l]['idea_status']=="accepted") {
	# code...
	$idea_status="مقبولة";
}
elseif ($get_proj_idea_for_specific_std_for_specific_grp[$l]['idea_status']=="reject") {
	# code...
	$idea_status="مرفوضة";
}

echo  $idea_status ;


 ?></td>
	 <td> <a href="send_project_idea_into_my_sup.php?action=edit&idea_id=<?php echo $get_proj_idea_for_specific_std_for_specific_grp[$l]['id'] ; ?>"
  class="btn btn-primary btn-sm a-btn-slide-text" id="edit_idea"  style="margin-top: 3px;"">
        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
        <span><strong>تعديل</strong></span>            
    </a>
 
	<!-- data-toggle="modal" data-target="#confirm-delete" -->
<a href="send_project_idea_into_my_sup.php?action=del&idea_id=<?php echo $get_proj_idea_for_specific_std_for_specific_grp[$l]['id'] ; ?>"
  class="btn btn-danger btn-sm a-btn-slide-text" onClick="return confirm('هل أنت متأكد من حذف هذه الفكرة')" id="del_user" style="margin-top: 3px;">
        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
        <span><strong>حذف</strong></span>            
    </a>

  
</td>

	<?php
	}
	?>
</tr>
	<?php
}
	else{
?>
<td colspan="4">لا يوجد بيانات</td>
 

<?php
	}
	  ?>

</tbody>
</table>
</div>

</div>
<!-- </form> -->


				<?php
			}
			else{
			$send_idea_success=null;
 	$send_idea_err="لستَ منضما لأي مشرف يرجى الانضمام إلى مشرف و بينما تنقبل سيُتاح لك الإرسال فكرتك";	 //

 
   if(isset($send_idea_success)){
 ?><div class="row" style="margin-top: 12px;" >
  <div class="col-sm-4 "></div>
<div class="col-sm-4 alert alert-success text-center"><strong><?php echo $send_idea_success; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
<?php
  }  if(isset($send_idea_err)){
?>
<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo $send_idea_err; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
<?php
  }
   
 	//
			}
	 } 
	 else{
$send_idea_success=null;
 	$send_idea_err="لستَ منضما لأي مجموعة يرجى الانضمام إلى مجموعة أولا ثم الانضمام إلى مشرف ليتسنى لك إرسال أفكار لمشرفك";

   if(isset($send_idea_success)){
 ?><div class="row" style="margin-top: 12px;" >
  <div class="col-sm-4 "></div>
<div class="col-sm-4 alert alert-success text-center"><strong><?php echo $send_idea_success; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
<?php
  }  if(isset($send_idea_err)){
?>
<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo $send_idea_err; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
<?php
  }
  

	 }
	//here can add any idea we need
	//الجروب الواحد بقترح أكثر من فكرة
	//ليش اسمهم جروب إذا و لا واحد مسموح له يقترح فكرة
	?>

	<?php
  } //end of if status
  else{

 
 	$send_idea_err="غير مسموح اقتراح فكرة مشروعك هذه الفترة";
 	$send_idea_success=null;
 
   if(isset($send_idea_success)){
 ?><div class="row" style="margin-top: 12px;" >
  <div class="col-sm-4 "></div>
<div class="col-sm-4 alert alert-success text-center"><strong><?php echo $send_idea_success; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
<?php
  }  if(isset($send_idea_err)){
?>
<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo $send_idea_err; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
<?php
  }
  ?>
 
<!-- <div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php //echo $idea_err; ?></strong></div>
<div class="col-sm-4 "></div>
</div> -->
 <?php
}
 }
 else{
 	$idea_success=null;
 	$idea_err="يرجى مراجعة مسؤول الموقع لتفعيل الفصل الدراسي";
?>
<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo $idea_err; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
<?php
 }

} 

?>
    <!--script src="../js/jquery-1.12.4.min.js"></script-->
     <?php include('../includes/footer.php');?>