<?php
    session_start();
  include('../db_op.php');
   $maximum_no_of_supervisor_for_each_grp = 2;
   date_default_timezone_set('israel');
   
$status=false;
?>
   <!DOCTYPE html>
<?php include('../includes/header.php'); ?>
<!--End fixed menu-->
<!-- </div> -->
<div class="row" align="Center">
      
<?php if (isset($_SESSION["user_id"]) && (!empty($_SESSION["user_id"])) && 
(isset($_SESSION["role"])) && (!empty($_SESSION["role"])) && ($_SESSION["role"] == 2)) {
  ?>
 <h1 style="color: #1e527f;">متابعة طلبات الانضمام إليّ</h1>

  <?php
	$supervisor_id=$_SESSION["user_id"];
	$get_active_semester_tbl_row_count = Crud_op::get_active_semester_tbl_row_count1();
  ?>
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
  <?php
if($get_active_semester_tbl_row_count!=null){
   $evt_name="join_into_supervisor";
 
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
   
$status=true;
}
}
if ($status) {
	try {
	if (
		isset($_POST['delete_all_request_if_this_request_last_request_allowed_then_add_accepted_row_else_just_change_status_to_accepted']) ||
		isset($_POST['change_this_grp_request_into_reject']) ||
		isset($_POST['delete_all_request_if_this_request_last_request_allowed_then_add_accepted_row_else_just_change_status_to_reject']) &&
		(isset($_POST['group_id']) && isset($_POST['supervisor_id']))
	)

	{
		$group_id = $_POST['group_id'];
		$supervisor_id = $_POST['supervisor_id'];
	
		# code...
		if (isset($_POST['delete_all_request_if_this_request_last_request_allowed_then_add_accepted_row_else_just_change_status_to_accepted'])) {
			# code...
	$get_no_of_supervisor_of_these_grp = Crud_op::get_no_of_supervisor_of_these_grp($group_id);
		if ($get_no_of_supervisor_of_these_grp!=null) {
			# code...
if ($get_no_of_supervisor_of_these_grp>0 && $get_no_of_supervisor_of_these_grp<$maximum_no_of_supervisor_for_each_grp) {
	# code...
$delete_all_request_if_this_request_last_request_allowed_then_add_accepted_row_res = Crud_op::delete_all_request_if_this_request_last_request_allowed_then_add_accepted_row($group_id,$supervisor_id);
if ($delete_all_request_if_this_request_last_request_allowed_then_add_accepted_row_res) {
  # code...
  $err_send_into_supervisor_request = null;
  $sucess_send_into_supervisor_request = "تم قبول الطلب بنجاح";
}
else{
  $err_send_into_supervisor_request = "لم يتم قبول الطلب بنجاح";
  $sucess_send_into_supervisor_request = null;
 
}
}
else{
$update_grp_sup_request_status_to_accept = Crud_op::update_grp_sup_request_status_to_accept($group_id,$supervisor_id);
if ($update_grp_sup_request_status_to_accept==0) {
	# code...

  $err_send_into_supervisor_request = "لم يتم قبول الطلب بنجاح";
  $sucess_send_into_supervisor_request = null;
}
else{

  $err_send_into_supervisor_request = null;
  $sucess_send_into_supervisor_request = "تم قبول الطلب بنجاح";
}

}
		}
		}
		elseif (isset($_POST['change_this_grp_request_into_reject'])) {
			# code...
	$update_grp_sup_request_status_to_accept = Crud_op::update_grp_sup_request_status_to_reject($group_id,$supervisor_id);
if ($update_grp_sup_request_status_to_accept==0) {
	# code...

  $err_send_into_supervisor_request = "لم يتم رفض طلب الانضمام بنجاح";
  $sucess_send_into_supervisor_request = null;
}
else{

  $err_send_into_supervisor_request = null;
  $sucess_send_into_supervisor_request = "تم رفض طلب الانضمام بنجاح";
}
		}
		elseif (isset($_POST['delete_all_request_if_this_request_last_request_allowed_then_add_accepted_row_else_just_change_status_to_reject'])) {
			# code...
	//start

			# code...
	$get_no_of_supervisor_of_these_grp = Crud_op::get_no_of_supervisor_of_these_grp($group_id);
		if ($get_no_of_supervisor_of_these_grp!=null) {
			# code...
if ($get_no_of_supervisor_of_these_grp>0 && $get_no_of_supervisor_of_these_grp<$maximum_no_of_supervisor_for_each_grp) {
	# code...
$delete_all_request_if_this_request_last_request_allowed_then_add_accepted_row_res = Crud_op::delete_all_request_if_this_request_last_request_allowed_then_add_accepted_row($group_id,$supervisor_id);
if ($delete_all_request_if_this_request_last_request_allowed_then_add_accepted_row_res[1]>$delete_all_request_if_this_request_last_request_allowed_then_add_accepted_row_res[0]+1) {
  # code...
  $err_send_into_supervisor_request = null;
  $sucess_send_into_supervisor_request = "تم قبول الطلب بنجاح";
}
else{
  $err_send_into_supervisor_request = "لم يتم قبول الطلب بنجاح";
  $sucess_send_into_supervisor_request = null;
 
}
}
else{
$update_grp_sup_request_status_to_reject = Crud_op::update_grp_sup_request_status_to_reject($group_id,$supervisor_id);
if ($update_grp_sup_request_status_to_reject==0) {
	# code...

  $err_send_into_supervisor_request = "لم يتم قبول الطلب بنجاح";
  $sucess_send_into_supervisor_request = null;
}
else{

  $err_send_into_supervisor_request = null;
  $sucess_send_into_supervisor_request = "تم قبول الطلب بنجاح";
}

}
		}
			//end
		}
		//echo "<meta http-equiv=refresh content=\"0; URL=follow_up_grp_request.php\">";
	$_POST = array();
	}	
	} catch (Exception $e) {
	echo $e->getMessage();	
	}
	 
     ///
                 if(isset($sucess_send_into_supervisor_request)){
?><div class="row" style="margin-top: 12px;" >
  <div class="col-sm-4 "></div>
<div class="col-sm-4 alert alert-success text-center"><strong><?php echo $sucess_send_into_supervisor_request; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
<?php
  }   elseif(isset($err_send_into_supervisor_request)){
?>
<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo $err_send_into_supervisor_request; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
<?php
  }
	 
	# code...
	 $rec_count=Crud_op::get_count_member_send_request_to_this_supervisor($supervisor_id);
     $rec_limit=8;
     $page_count=floor($rec_count/$rec_limit);
     
     if( isset($_GET{'page'} ) )
     {
      $page = $_GET{'page'}-1;
      $start = ($rec_limit * $page) ;

      }else {
      $page=0;
      $start = 0;
      }

     echo '<ul class="pagination pagination-sm" style="direction:rtl !important;">';
     for($index = 0; $index<$page_count;$index=$index+1)
     {
         $index2=$index+1;
      if($index2==($page+1))
      $active="active";
      else
      $active="";
         echo "<li class='$active' style='float:right;'><a style='border-top-right-radius: 0px !important;border-bottom-right-radius: 0px !important;margin-left:2px; ' href='follow_up_grp_request.php?page=$index2'> ﺻﻔﺤﺔ$index2 </a></li>";

     }
     echo '</ul>';
$get_all_member_send_request=Crud_op::get_sub_member_send_request_to_this_supervisor($supervisor_id,$start,$rec_limit);
if ($get_all_member_send_request!=null) {
	# code...
for ($j=0; $j <count($get_all_member_send_request) ; $j++) { 
	
	$group_id=$get_all_member_send_request[$j]['group_id'];
	$grp_member=Crud_op::get_grp_member_for_specific_grp($group_id);
	?>
</div>
<div class="row" align="center">
<form method="post" action="follow_up_grp_request.php">
	<input type="hidden" name="group_id" value="<?php echo $group_id ; ?>" />
	<input type="hidden" name="supervisor_id" value="<?php echo $supervisor_id; ?>" />
 <div class="accordion f-group">
  <div class="header">
    <div class="title">
      <h3 class="close"><?php echo $get_all_member_send_request[$j]['grp_name']; ?></h3>
    </div>

    <input type="submit" 
<?php $sup_status=$get_all_member_send_request[$j]['sup_status']; 
if ($sup_status=="pending") {
	# code...
 ?>  
	name="delete_all_request_if_this_request_last_request_allowed_then_add_accepted_row_else_just_change_status_to_accepted" 
 class="btn btn-success"
  onClick="return confirm('هل أنت متأكد من قبولك لهذه المجموعة')" 
	<?php 
	/* 
	by compare $maximum_no_of_supervisor_for_each_grp variable at line 4 with accutal number of row for group's supervisor has sup_status == accepted
	if it's >0 &&  <$maximum_no_of_supervisor_for_each_grp then can add else no 
	*/
	   ?>
    value="قبول"

    <?php
}
elseif ($sup_status=="reject") {
	# code...
?>
	
	name="delete_all_request_if_this_request_last_request_allowed_then_add_accepted_row_else_just_change_status_to_accepted"
    value="قبول"
 class="btn btn-success"
  onClick="return confirm('هل أنت متأكد من قبولك لهذه المجموعة')" 
<?php
}
elseif ($sup_status=="accepted") {
	# code...
?>
	name="change_this_grp_request_into_reject" 
    value="رفض"
 class="btn btn-danger"
  onClick="return confirm('هل أنت متأكد من رفضك لهذه المجموعة')" 
<?php
}
?>
	



    >
  </div>
  <div class="content hidden">
    
    <h2>أعضاء المجموعة</h2>
    <ul>
    	<?php for ($n=0; $n <count($grp_member) ; $n++) { 
    		# code...
    		?>
 <li><?php echo 'رقم الطالب '. $grp_member[$n]['student_id'] .' اسمه : '.$grp_member[$n]['name'] ; ?> </li>
    		<?php
    	} ?> 
     
       
    </ul>
  </div>
</div>
</form>
</div><!-- End 2nd row -->
<?php
}
 

}
else{
$sup_grp_request_err="لا يتوفر لديك أي طلبات انضمام";
	$sup_grp_request_success=null;	
  ?>
<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo $sup_grp_request_err; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
  <?php
}
}
else{
$sup_grp_request_err="غير مسموح طلبات انضمام للمشرفين هذه الفترة";
	$sup_grp_request_success=null;
  ?>
<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo $sup_grp_request_err; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
  <?php
}
} 
else {
	$sup_grp_request_err="لا يتوفر فصل فعّال إلى الآن يرجى مراجعة مسؤول الموقع";
	$sup_grp_request_success=null;
?>
<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo $sup_grp_request_err; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
<?php
}
	
	 } 
 
 
?>
    <!--script src="../js/jquery-1.12.4.min.js"></script-->
     <?php include('../includes/footer.php');?>