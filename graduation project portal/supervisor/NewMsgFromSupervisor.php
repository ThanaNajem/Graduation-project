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


  $get_grps_of_sup = Crud_op::check_if_this_supervisor_has_a_group($user_id);

?>
   <!DOCTYPE html>
<?php include('../includes/header.php'); ?>
 
<?php if (isset($_SESSION["user_id"]) && (!empty($_SESSION["user_id"])) && (isset($_SESSION["role"])) && (!empty($_SESSION["role"])) && ($_SESSION["role"] == 2)) {?>
<!-- Start of container -->
<div class="container">
	 <!-- Start Form tag -->
	<form method="POST"   action="SendNewMsgCalledMotherMsg.php"  enctype="multipart/form-data">


<!-- Start reciever -->
<div class="form-group">
	<!-- Start New Msg Alert -->
	
	<div class="row"  style="margin-bottom: 12px;">
	 <strong><a href="follow_up_weekly_work_of_group_edit.php" class="btn btn-primary">الرجوع إلى الصفحة السابقة</a></strong> 
</div>
<div class="row">
	<div class="col-sm-1 col-sm-offset-11 alert alert-success"><strong>رسالة جديدة</strong></div>
</div>
<!-- End New Msg Alert -->
   <label  style="direction:rtl;" for="group_and_related_member" ><?php echo 'المرسل إليهم: <br>';?></label><?php  


echo '    <select class="form-control" name="groupOfUser" id="group_and_related_member" style="background-color: #eee;margin-bottom: 12px;" required="required"> ';
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
  ?>  
<!-- Start msg text --> 
  <textarea class="form-control" name="txt_msg" placeholder="يُرجى كتابة رسالة نصية لا تتجاوز أحرفها 500 كلمة" maxlength="500" style="height:150px;resize: none;direction:rtl ;
  " autofocus="autofocus" required="required" id="txt_msg"  ></textarea>

<!-- End msg text -->
   </div>
<!-- End reciever -->

<!-- Start file type -->
 
<!-- End file type -->
<!-- Start file input -->
<div class="form-group">
      
        <input type="file" id="fileInput" name="weekly_peoject_works" style="direction:rtl; float:right;clear:right !important;" /><!--  required="required"  --> 
      <br> 
    </div><!-- End file input -->
<!-- Start hidden group number -->
     
  
<!-- End hidden group number -->
    <!-- Start submit -->
    <div class="form-group">
    <input type="submit" class="btn btn-success text-center submit" disabled="disabled" name="sending_weekly_work"  value="إرسال" >
     
  </div>
    <!-- End submit -->
</div>
<!-- End of container -->
	<!-- End Form tag -->
	</form>
<?php } 
 
?> 
     <?php include('../includes/footer.php');?>