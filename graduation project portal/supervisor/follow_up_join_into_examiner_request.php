<?php
    session_start();
  include('../db_op.php');
$status=false;
?>
   <!DOCTYPE html>
<?php include('../includes/header.php'); ?>
<div class="container">
<!--End fixed menu-->
<!-- </div> -->
<?php if (isset($_SESSION["user_id"]) && (!empty($_SESSION["user_id"])) && (isset($_SESSION["role"])) && (!empty($_SESSION["role"])) && ($_SESSION["role"] == 2)) {
	
/* start op */
 $get_active_semester_tbl_row_count = Crud_op::get_active_semester_tbl_row_count();
if($get_active_semester_tbl_row_count!=0){
	/**/
	$user_id=$_SESSION["user_id"];
	
	 $evt_name="request_for_discussion_committees";
 $arr_from_and_to_evt_date=Crud_op::get_first_and_end_date_for_evt($evt_name);
 if($arr_from_and_to_evt_date!=null){
	 /**/
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
$current_Date= date("Y-m-d H:i:s", $current_Date); 

$from_date= date("Y-m-d H:i:s", $from_date); 
$to_date= date("Y-m-d H:i:s", $to_date); 

if($current_Date>=$from_date && $current_Date<=$to_date){
  
$status=true;
}
 
	if($status){
		$check_if_this_examiner_has_a_request = Crud_op::check_if_this_examiner_has_a_request($user_id);
		if($check_if_this_examiner_has_a_request!=null){
		 
			
			
			if(isset($_POST['change_status_into_accepted']) 
				&& !empty($_POST['examiner_id'])
			&& !empty($_POST['grp_id'])
			){
			 $grp_id=$_POST['grp_id'];
			 $examinar_id=$_POST['examiner_id'];
				$updated_row_count=Crud_op::change_request_status_btn_examiner_and_grp($grp_id,$examinar_id,"accepted");
				if($updated_row_count!=0){
					$change_request_status_btn_examiner_and_grp_err=null;
					$change_request_status_btn_examiner_and_grp_success="تمت العملية بنجاح";
				}
				else{
					$change_request_status_btn_examiner_and_grp_err="لم تتم العملية بنجاح";
					$change_request_status_btn_examiner_and_grp_success=null;
				}
				echo "<meta http-equiv=refresh content=\"0; URL=follow_up_join_into_examiner_request.php\">";
			}
			elseif(
			isset($_POST['change_accepted_status_into_reject'])
			&& !empty($_POST['examiner_id'])
			&& !empty($_POST['grp_id'])
			){
			 $grp_id=$_POST['grp_id'];
			 $examinar_id=$_POST['examiner_id'];
			$updated_row_count=Crud_op::change_request_status_btn_examiner_and_grp($grp_id,$examinar_id,"reject");
				if($updated_row_count!=0){
					$change_request_status_btn_examiner_and_grp_err=null;
					$change_request_status_btn_examiner_and_grp_success="تمت العملية بنجاح";
				}
				else{
					$change_request_status_btn_examiner_and_grp_err="لم تتم العملية بنجاح";
					$change_request_status_btn_examiner_and_grp_success=null;
				}
				$_POST = array();
 echo "<meta http-equiv=refresh content=\"0; URL=follow_up_join_into_examiner_request.php\">";				
			}
		?>
		<div class="row" style="margin-bottom: 12px;">
    <div class="col-md-8 col-md-offset-2">
    	<h3>متابعة طلبات انضمام المجموعات المقترحات من المشرفين</h3>
  </div>
  </div>
		<div class="row" style="margin-bottom: 12px;">
    <div class="col-md-8 col-md-offset-2">
  <div class="form-group text-center">
<div class="table-responsive text-center " style="direction: rtl;margin-bottom:12px;">          
  <table class="table  table-hover table-striped table-bordered text-center" id="tbl1" style="align-items: center;">
  <thead>
  <tr>
  <th>اسم المجموعة مع أعضائها</th>
  <th>الإجراء</th>
  
  </tr> 
  </thead>
  
  
  <tbody>
 
  <?php
  
  for($k=0;$k<count($check_if_this_examiner_has_a_request);$k++){
	?>
	
	<form method="POST"    action="follow_up_join_into_examiner_request.php" >
	<?php  
  	$group_id  =$check_if_this_examiner_has_a_request[$k]['groups_id'];
  $grp_name=$check_if_this_examiner_has_a_request[$k]['grp_name'];
  $grp_member=Crud_op::get_grp_member_for_specific_grp($group_id);
  $grp_name_and_member='';
	$grp_name_and_member=' اسم المجموعة: '.$grp_name.' / '.' أعضاء المجموعة: ' ;
	  $l='-'; 
 for($t=0;$t<count($grp_member);$t++){
	 if($t==count($grp_member)-1){
		$grp_name_and_member .=($t+1).'. '.$grp_member[$t]['name'];
	}
	else{
	$grp_name_and_member .=($t+1).'. '.$grp_member[$t]['name'].$l;
	}
 }
  
 
	  ?>
	   <tr>
  <td><?php
  echo $grp_name_and_member;
  ?></td>
  <td>
  <a  href="<?php echo $check_if_this_examiner_has_a_request[$k]['thesis'];?>" class="btn btn-primary" target="_blank">عرض ملفات المشروع</a>
  <input type="hidden" value="<?php echo $group_id;  ?>" name="grp_id"  /> 
  <input type="hidden" value="<?php echo $user_id;  ?>" name="examiner_id"  /> <!--onClick="return confirm(\'هل أنت متأكد من قبولك لهذه المجموعة\');"-->
  
  <?php
  $examination_accept_status = $check_if_this_examiner_has_a_request[$k]['examination_accept_status'];
   
  //$submit_text_for_pending_and_reject_status='هل أنت متأكد من قبولك لهذه المجموعة'.$grp_name_and_member;
  $input_submit_for_pending_and_reject_status='<input type="submit" name="change_status_into_accepted" class="btn btn-success" value ="قبول" onClick="return confirm(\'هل أنت متأكد من قبولك لهذه المجموعة\');" >';
   // $submit_text_for_accepted_status='هل أنتَ تأكد من رفضك لهذه المجموعة'.$grp_name_and_member;
  $input_submit_for_accepted_status='<input type="submit" class="btn btn-danger" name="change_accepted_status_into_reject" value ="رفض" onClick="return confirm(\'هل أنت متأكد من رفضك لهذه المجموعة\');" >';
  
  if($examination_accept_status=="pending"){
	   echo  $input_submit_for_pending_and_reject_status;
  }
  elseif($examination_accept_status=="reject"){
	  
	  echo  $input_submit_for_pending_and_reject_status;
  }
  elseif($examination_accept_status=="accepted"){
	  
	 echo $input_submit_for_accepted_status; 
  }
  
  ?>
  
   
  </td> 
  </tr>
  </form>
	  <?php
  }
  ?>
 
  <?php
  
  ?>
  </tbody>
  </table>
  </div>
  </div>
  </div>
  </div>
		<?php
		}
		else{
			 
			$follow_up_request_btn_examiner_and_grp_err='ليس لديك أيا من طلبات الانضمام';
		$follow_up_request_btn_examiner_and_grp_success="";
	?>
	<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo $follow_up_request_btn_examiner_and_grp_err; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
 <div class="row" style="margin-bottom: 12px;">
    <div class="col-md-8 col-md-offset-2">
  <div class="form-group text-center">
<div class="table-responsive text-center " style="direction: rtl;margin-bottom:12px;">          
  <table class="table  table-hover table-striped table-bordered text-center" id="tbl1" style="align-items: center;">
  <thead>
   <tr><th>اسم المجموعة مع أعضائها</th><th>الإجراء</th></tr>
  
  
  </thead>
  
   
  
  <tbody><tr><td colspan="2">لآ يوجد بيانات</td></tr></tbody>
  </table>
  </div>
  </div>
  </div>
  </div>
  
	<?php
		}
	}
	else{
		$follow_up_request_btn_examiner_and_grp_err="غير مسموح متابعة طلبات انضمام المجموعات لك كممتحن";
		$follow_up_request_btn_examiner_and_grp_success="";
	?>
	<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo $follow_up_request_btn_examiner_and_grp_err; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
	<?php	
	}
 }
 else{
		$follow_up_request_btn_examiner_and_grp_err="لم يتم تحديد موعد متابعة طلبات انضمام المجموعات لك كممتحن إلى الآن";
		$follow_up_request_btn_examiner_and_grp_success="";
	?>
	<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo $follow_up_request_btn_examiner_and_grp_err; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
	<?php	
	}
/* end op */
	} 
  else{
		$follow_up_request_btn_examiner_and_grp_err="لم يتم تفعيل الفصل الدراسي إلى الآن";
		$follow_up_request_btn_examiner_and_grp_success="";
	?>
	<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo $follow_up_request_btn_examiner_and_grp_err; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
	<?php	
	}
}
?>
    <!--script src="../js/jquery-1.12.4.min.js"></script-->
	</div>
     <?php include('../includes/footer.php');?>