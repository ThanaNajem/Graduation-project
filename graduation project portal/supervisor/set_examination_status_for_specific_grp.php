<?php
    session_start();
  include('../db_op.php');

$status=false;
?>
   <!DOCTYPE html>
<?php include('../includes/header.php'); ?>
<!--End fixed menu-->
<!-- </div> -->
<div class="container">
<div class="row">
<div class="col-sm-9">
<h1 class="text-center" style="align-items: center;">تحديد قابلية المجموعات للمناقشة</h1>
</div>
</div>
<?php if (isset($_SESSION["user_id"]) && (!empty($_SESSION["user_id"])) && (isset($_SESSION["role"])) && (!empty($_SESSION["role"])) && ($_SESSION["role"] == 2)) {
	 
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
		
	$get_thesis_and_prject_idea_and_supervisor_for_accepted_grp_examiner = 
	Crud_op::get_thesis_and_prject_idea_and_supervisor_for_accepted_grp_examiner($user_id);
	if($get_thesis_and_prject_idea_and_supervisor_for_accepted_grp_examiner!=null){
		if(
		isset($_POST['change_grp_examination_status'])
		&& isset($_POST['grp_id'])
		&& isset($_POST['examiner_id'])
		&& isset($_POST['examination_accept_status'])
		){
		$examination_accept_status = $_POST['examination_accept_status'];
			$new_status="";
		if($examination_accept_status=="accepted"){
			$new_status="reject";
		}	
		elseif($examination_accept_status=="reject"){
			$new_status="accepted";
		}
		$grp_id=$_POST['grp_id'];
		$examiner_id=$_POST['examiner_id'];
		 
		$updated_row_no=Crud_op::set_examination_status_for_specific_grp($grp_id,$new_status);
		 echo "<meta http-equiv=refresh content=\"0; URL=set_examination_status_for_specific_grp.php\">";
	 
		$_POST = array();
		
		}
		?>
					 <div class="row" style="margin-bottom: 12px;">
    <div class="col-xs-12">
    	
  <div class="form-group text-center">
<div class="table-responsive text-center " style="direction: rtl;margin-bottom:12px;">          
  <table class="table  table-hover table-striped table-bordered text-center" id="tbl1" style="align-items: center;">
  <thead>
   <tr>
   <th>اسم المجموعة و أعضاؤها</th>
   <th>فكرة المشروع و وصفه </th>
   <th>مشرفي المجموعة</th>
   <th>الإجراء</th>
   </tr>
  
  
  </thead>
  
   
  
  <tbody>
		<?php
		for($t1=0;$t1<count($get_thesis_and_prject_idea_and_supervisor_for_accepted_grp_examiner);$t1++){
			$grp_id=$get_thesis_and_prject_idea_and_supervisor_for_accepted_grp_examiner[$t1]['grp_id'];
			$grp_name=$get_thesis_and_prject_idea_and_supervisor_for_accepted_grp_examiner[$t1]['grp_name'];
				 
	$grp_member=Crud_op::get_grp_member_for_specific_grp($grp_id); 
	$grp_name_and_member='';
	$grp_name_and_member=' اسم المجموعة: '.$grp_name.' / '.' أعضاء المجموعة: ' ;
			?>
			<tr>
			<td><?php
			$l='-'; 
 for($t=0;$t<count($grp_member);$t++){
	 if($t==count($grp_member)-1){
		$grp_name_and_member .=($t+1).'. '.$grp_member[$t]['name'];
	}
	else{
	$grp_name_and_member .=($t+1).'. '.$grp_member[$t]['name'].$l;
	}
 }
  
 echo $grp_name_and_member;
			?></td>
			<td><?php
			$idea_name_and_description="فكرة المشروع - ".
			$get_thesis_and_prject_idea_and_supervisor_for_accepted_grp_examiner[$t1]['idea_name']." وصف المشروع : ".
			$get_thesis_and_prject_idea_and_supervisor_for_accepted_grp_examiner[$t1]['description'];
			 echo $idea_name_and_description;
			
			?></td>
			
			<td>
			<?php
			$get_supervisor_of_this_grp = Crud_op::get_supervisor_of_this_grp($grp_id);
			$sup_of_this_grp="";
			for($e=0;$e<count($get_supervisor_of_this_grp);$e++){
			$sup_of_this_grp.=$get_supervisor_of_this_grp[$e]['teacher_id']." - ".$get_supervisor_of_this_grp[$e]['name']." ";	
				
			}
			echo $sup_of_this_grp; 
			?>
			</td>
			<td><a href="<?php
			if($get_thesis_and_prject_idea_and_supervisor_for_accepted_grp_examiner[$t1]['thesis']==''){echo '#';}
			else{echo $get_thesis_and_prject_idea_and_supervisor_for_accepted_grp_examiner[$t1]['thesis'];}
			
			?>" class="btn btn-primary btn-sm" target="_blank" style="float: right;">عرض ملف المشروع</a>
			<form method="POST" action="set_examination_status_for_specific_grp.php">
			 <?php
				 $examination_accept_status=
				 $get_thesis_and_prject_idea_and_supervisor_for_accepted_grp_examiner[$t1]['examination_status'];
				 if($examination_accept_status=="accepted"){
					 ?>
					 
				  <input type="submit" class="btn btn-danger btn-sm" value="رفض" name="change_grp_examination_status"  >
					 <?php
				 }
				 elseif($examination_accept_status=="reject"){
					 
					 ?>
					 
				  <input type="submit" class="btn btn-success btn-sm" value="قبول" name="change_grp_examination_status" > 
				 
					 <?php
				 }
				 ?>
			</td>
			<td style="display:none;"> <input type="hidden" value="<?php echo $grp_id; ?>" name="grp_id" />
				 
				 <input type="hidden" value="<?php echo $user_id ; ?>" name="examiner_id" /> 
				
				 <input type="hidden" value="<?php echo $examination_accept_status; ?>" name="examination_accept_status" /></td>
				 </form>
			</tr>
			<?php
		}
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
		$set_examination_status_for_specific_grp_err=
		"إما أنك لا تملك مجموعات لتناقشها أو أنك رفضت كافة طلبات انضمام المجموعات 
		أو أن طلباتك قيد الانتظار أو ان المجموعة لا ملف ثيسز او فكرة لها او مشرفا أو ليس لديها ممتحن و على الأغلب الأخيرة";
		$set_examination_status_for_specific_grp_success=null;
		?>
		<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo $set_examination_status_for_specific_grp_err; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
		<?php 
	}
	}
	else{
		$set_examination_status_for_specific_grp_err="غير مسموح تحديد قابلية هذه المجموعة للمناقشة هذه الفترة";
		$set_examination_status_for_specific_grp_success=null;
		?>
		<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo $set_examination_status_for_specific_grp_err; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
		<?php 
		
	}
 }
 else{
$set_examination_status_for_specific_grp_err="لم يتم تحديد قابلية هذه المجموعة للمقابلة بعد من قبل مسؤول الموقع";
		$set_examination_status_for_specific_grp_success=null;
		?>
		<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo $set_examination_status_for_specific_grp_err; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
		<?php 	 
 }
}
else{
$set_examination_status_for_specific_grp_err="لم يتم تحديد الفصل الدراسي بعد من قبل مسؤول الموقع";
		$set_examination_status_for_specific_grp_success=null;
		?>
		<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo $set_examination_status_for_specific_grp_err; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
		<?php 	
}
	} 
 
?>
    <!--script src="../js/jquery-1.12.4.min.js"></script-->
     <?php include('../includes/footer.php');?>