<?php
    session_start();
  include('../db_op.php');
$maximum_no_of_official_discussion_committee_to_discuss_graduation_projects=2;
$status=false;
$user_id=null;
if(isset($_SESSION["user_id"])){
	$user_id=$_SESSION["user_id"];
}
?>
   <!DOCTYPE html>
<?php include('../includes/header.php'); ?>
<div class="container">
<!--End fixed menu-->
<!-- </div> -->
<?php 
if (isset($_SESSION["user_id"]) && (!empty($_SESSION["user_id"])) && (isset($_SESSION["role"])) && (!empty($_SESSION["role"])) 
	&& ($_SESSION["role"] == 2)) {
	
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
		/////////// I'm reach to here 
		//I must check if grp has thesis and grp and supervisor and idea .. we will return grp has status with accepted..
		//then if we click on select option group and its member then all users has role=2 will appear then submit will has different name fetch with its demand 
		//if this grp has accepted with this supervisor then submit will change into reject and vise versa but if it is pending then it will be accept and befor
		//do previous status it must be less then maximum_no_of_official_discussion_committee_to_discuss_graduation_projects and this condition will be befor 
		//make crud operation of status in db
		/* start get all groups for supervisor */
		?>
		  <h3 style="direction:rtl;text-align:center">إرسال طلب انضمام لجنة مناقشة لمجموعة معينة</h3>
  
  <div class="row" style="margin-bottom: 12px;">
    <div class="col-md-8 col-md-offset-2">
  <div class="form-group text-center">
   
    <div class="col-md-8 col-md-offset-2">
        <a class="btn icon-btn btn-success" href="edit_request_btn_examiner_and_grps.php?action=add" name="add-old-year" 
    style="font-size: 19px;font-weight: bold;direction:rtl;"><span class="glyphicon btn-glyphicon glyphicon-plus-sign img-circle text-success" 
    style="color: white;background-color: #83e88b" ></span>إرسال طلب انضمام لجنة مناقشة لمجموعة معينة</a>
  </div>
    <!--div class="col-sm-4"></div-->
</div>
</div>
</div>
 <div class="row" style="margin-bottom: 12px;">
    <div class="col-md-8 col-md-offset-2">
  <div class="form-group text-center" id="form_request_status_result">
   
     
    <!--div class="col-sm-4"></div-->
</div>
</div>
</div>
		<?php
		if(isset($_REQUEST['action']) && $_REQUEST['action']=="add"){
		$get_all_grps_for_specific_supervisor = Crud_op::get_all_grps_for_specific_supervisor($user_id);
		//$get_all_regular_teachers_status = Crud_op::get_all_regular_teachers_status_except_sup_of_this_grp($user_id);
		if($get_all_grps_for_specific_supervisor!=null //&& $get_all_regular_teachers_status!=null
		){
			
			?>
			<div class="row" style="margin-top:12px;">
<div class="col-md-8 col-md-offset-2">
<!--action="follow_up_its_grp_weekly_project_work.php"-->
	<form method="POST"  id="request_btn_examinar_and_grps"  action="edit_request_btn_examiner_and_grps.php" >
	<input type="hidden" name="supervisor_login_id" value="<?php echo $user_id ; ?>" />  
	<div class="form-group"> 
	
  <label style="float:right;">اختر المجموعة المراد إرسال طلب انضمام عضو لجنة مناقشة لها</label>
    <select name="groups_for_specific_sup" id="groups_for_specific_sup" class="form-control" style="direction:rtl ;margin-bottom:12px;">
<option class="defult" value="0">يرجى الاختيار</option> 
<?php
for($k=0;$k<count($get_all_grps_for_specific_supervisor);$k++){
	$group_id = $get_all_grps_for_specific_supervisor[$k]['grp_id'];
	$grp_member=Crud_op::get_grp_member_for_specific_grp($group_id);
	$grp_name=$get_all_grps_for_specific_supervisor[$k]['grp_name'] ;
	$grp_name_and_member='';
	$grp_name_and_member=' اسم المجموعة: '.$grp_name.' / '.' أعضاء المجموعة: ' ;
?> 
<option value="<?php echo $group_id ;

 ?>"><?php
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
 ?></option>  
<?php
}
?>
  </select> 
   </div>
   <!-- start get all supervisor -->
   	<div class="form-group">
  <label style="float:right;">اختر عضو لجنة المناقشة المراد إرسال أو إلغاء طلب الانضمام له</label>
    <select name="all_sup_in_this_semester1" id="all_sup_in_this_semester1" class="form-control" style="direction:rtl ;margin-bottom:12px;" >
<option class="defult" value="0">يرجى الاختيار</option> 
 
  </select> 
   </div>
   
   <!-- end get all supervisor -->
<div id="get_input_type_submit_depend_on_comm_disc_and_grp_status"></div>
			 <?php
		}
		else{
		$err_request_committees_to_discuss_graduation_projects = 'ليس لديك أي مجموعة إلى الآن أو لا يتوفر مشرفين إلى الآن بهذا الفصل';
		$success_request_committees_to_discuss_graduation_projects = null;
		?>
			 
		<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo $err_request_committees_to_discuss_graduation_projects; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
		<?php
	}
	}	
		/* end get all groups for supervisor */
	}
	else{
		$err_request_committees_to_discuss_graduation_projects = 'غير مسموح إرسال طلبات انضمام للجان المناقشة هذه الفترة';
		?>
		<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo $err_request_committees_to_discuss_graduation_projects; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
		<?php
	}
	 
	 /**/
 
 }else{
	$err_request_committees_to_discuss_graduation_projects = 'لم يتم تحديد فترة إرسال طلبات انضمام للجان المناقشة';
	 ?>
	 <div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo $err_request_committees_to_discuss_graduation_projects; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
	 <?php
 }
}else{
	$err_request_committees_to_discuss_graduation_projects = 'لم يقم مسؤول الموقع بتفعيل الفصل الدراسي - يرجى مراجعته';
	?>
	<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo $err_request_committees_to_discuss_graduation_projects; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
	<?php
}
	} 
 
?>
    <!--script src="../js/jquery-1.12.4.min.js"></script-->
    <!-- start tbl content -->

<div class="form-group text-center">
<div class="table-responsive text-center " style="direction: rtl;margin-top: 12px;">          
  <table class="table  table-hover table-striped table-bordered text-center" id="tbl1" style="align-items: center;">
<thead>
   
  	<th>الفصل الدراسي</th>
  	<th>اسم المجموعة</th>
  	<th>اسم ممتحن</th> 
  	<th>حالة هذه الجموعة مع هذا الممتحن</th>  
</thead>
<tbody>
<?php
       $get_all_examiner_status_for_specific_suoervisors_grp = Crud_op::get_all_examiner_status_for_specific_grp_for_specific_supervisor($user_id);
        
 /*
        SELECT users.usr_id,users.usr_status,users.role,user_type.type  from users,user_type where user_type.id=users.role
        */
        $get_active_semester = Crud_op::get_active_semester();
        $year_val            = $get_active_semester[0]['year_val'];
        $sem_name            = $get_active_semester[0]['sem_name'];
        /*
        SELECT semester.year_val ,semester_names.sem_name  FROM semester,semester_names WHERE semester_names.id=semester.semester_id and semester.active=1;
        */
        
        if ($get_all_examiner_status_for_specific_suoervisors_grp != null ) {
            
            /*
            SELECT  `usr_id`, `role`, `fname`, `lname`, `status`,users.role,user_type.type  from users,user_type where user_type.id=users.role
            */
            for ($i = 0; $i < count($get_all_examiner_status_for_specific_suoervisors_grp); $i++) {
?>
	<tr>
		  

		<td><?php
                echo $sem_name . ' ' . $year_val;
?></td>
		 <td><?php
                echo $get_all_examiner_status_for_specific_suoervisors_grp[$i]['grp_id'].' - '
.$get_all_examiner_status_for_specific_suoervisors_grp[$i]['grp_name']
                ;
?></td>
		 <td><?php
                echo $get_all_examiner_status_for_specific_suoervisors_grp[$i]['fname'].' - '.$get_all_examiner_status_for_specific_suoervisors_grp[$i]['lname'];
?></td> 
		 <td><?php
		 $examination_status="";
 if ($get_all_examiner_status_for_specific_suoervisors_grp[$i]['examination_accept_status']=='accepted') {
 	$examination_status="مقبول";
 }
 elseif ($get_all_examiner_status_for_specific_suoervisors_grp[$i]['examination_accept_status']=='pending') {
 	 $examination_status="قيد الانتظار";
 }
   elseif ($get_all_examiner_status_for_specific_suoervisors_grp[$i]['examination_accept_status']=='reject') {
 	 $examination_status="مرفوض";
 }
                echo $examination_status ;
?></td> 

</tr>
    <?php
            }
        } else {
?>
<td colspan="4">لآ توجد بيانات</td>
<?php
            
        }
?>
 
</tbody>
  </table>

</div>
			


</div><!-- End tbl info -->
    <!-- end tbl content -->

	</div>
     <?php include('../includes/footer.php');?>