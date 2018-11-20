<?php
     
  
    session_start();
  include('../db_op.php');
  $status=false;
  $send_thesis_file_status=false;
  date_default_timezone_set('israel');
 /**/
 $user_id=null;
 $get_usr_grp=null;
 $errors=null;
if (isset($_SESSION["user_id"])) {
  $user_id=$_SESSION["user_id"];
}


  //$get_usr_grp = Crud_op::check_if_this_usr_has_grp($user_id);

?>
   <!DOCTYPE html>
<?php include('../includes/header.php'); ?>
 
<?php if (isset($_SESSION["user_id"]) && (!empty($_SESSION["user_id"])) && (isset($_SESSION["role"])) && (!empty($_SESSION["role"])) && ($_SESSION["role"] == 2)) {
 $get_active_semester_tbl_row_count = Crud_op::get_active_semester_tbl_row_count();
 if ($get_active_semester_tbl_row_count==0) {
  $err="لم يتم تفعيل الفصل الدراسي بعد";
 }
$get_grps_of_sup = Crud_op::check_if_this_supervisor_has_a_group($user_id);
if ($get_grps_of_sup==null) {
  $err="ليس لديك أي مجموعة لذا لن تتمكن من متابعة الأعمال الأسبوعية";
 }
 /* Start check if it's a time to send thesis and weekly file*/
 
 $evt_name="send_your_weekly_project_work_and_thesis";
 $check_if_it_is_a_time_to_begin_evt = Crud_op::check_if_it_is_a_time_to_begin_evt( $evt_name);
 if (!$check_if_it_is_a_time_to_begin_evt) {
 	$err="غير مسموح إرسال ملفات ثيسز و لا ملفات أسبوعية هذه الفترة";
 }
 /* End check if it's a time to send thesis and weekly file*/
 if (isset($err)) {
 	 ?>
<div class="row" style="margin-bottom: 12px;">
  <div class="form-group text-center">
    <!-- <div class="col-sm-4"></div> -->
    <div class="col-sm-4 col-sm-offset-4">
      <?php echo $err; include('../includes/footer.php');die();?>
  </div>
    <div class="col-sm-4"></div>
</div>
</div>
 	 <?php
 }
	?>
<!-- Start of container -->
<div class="container">
	<!--start new msg -->
<div class="row" style="margin-bottom: 12px;">
  <div class="form-group text-center">
    <!-- <div class="col-sm-4"></div> -->
    <div class="col-md-10 col-md-offset-1">
        <a class="btn icon-btn btn-success" href="NewMsgFromSupervisor.php?action=add" name="add-old-year" 
    style="font-size: 19px;font-weight: bold;direction:rtl;"  ><span class="glyphicon btn-glyphicon glyphicon-plus-sign img-circle text-success" 
    style="color: white;background-color: #83e88b" ></span>رسالة جديدة</a>
  </div>
    <div class="col-sm-4"></div>
</div>
</div>
<!-- end new msg -->
<!-- start tbl of mother msg -->
<div class="col-sm-8 col-sm-offset-2">
 
 <?php
 echo '<form method="POST" id="send_msg_to_selected_group">';
/* start supervisor group */
echo '<select class="form-control" name="groupOfUser" id="group_and_related_member_for_group_of_supervisor_msg" style="background-color: #eee;margin-bottom: 12px;" required="required"> ';
echo '<option class="defult text-center">يرجى الاختيار</option>';
  for($k=0;$k<count($get_grps_of_sup);$k++){

  $output="";
  $grp_id = $get_grps_of_sup[$k]['group_id'];
  $grp_name = $get_grps_of_sup[$k]['grp_name'];
  $get_grp_member_for_specific_grp = Crud_op::get_grp_member_for_specific_grp($grp_id);
  $output.='مجموعة '.$get_grps_of_sup[$k]['grp_name'].' و أعضاؤها: ';
  for ($i=0; $i < count($get_grp_member_for_specific_grp) ; $i++) 
  { 
  
   
  $stdID= $get_grp_member_for_specific_grp[$i]['student_id'];
 $stdName=  $get_grp_member_for_specific_grp[$i]['name'];
 
 $output.= '- '.$stdName.' - '.$stdID;

  }
  
   echo '<option class="text-center" value="' . $grp_id . '"
       >' . $output . '</option>';
}
  echo '</select>';
/* end supervisor grouup */
/* start login supervisor(sender) id */
echo '<input type="hidden" name="loginSupervID" id="loginSupervID"  value="'.$user_id.'"  />';
/* end login supervisor(sender) id */

echo '</form>';

/**/
 ?>
<div id="supInboxMessages" style="border: 5px solid #0000FF;">
	 
</div>
</div>
<!-- 
<input type="hidden" name="get_group_of_mother_msg_inbox"  value="<?php echo $get_usr_grp; ?>" />
<input type="hidden" name="loginStd"  value="<?php echo $user_id; ?>"  /> -->

<!-- start tbl of mother msg -->
 </div>
<?php } 
 
?> 
     <?php include('../includes/footer.php');?>