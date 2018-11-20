 <?php
     
  
    session_start();
  include('../db_op.php');
  $status=false;
  $send_thesis_file_status=false;
  date_default_timezone_set('israel');
 /**/
 $user_id=null; 
 $errors=null;
if (isset($_SESSION["user_id"])) {
  $user_id=$_SESSION["user_id"];
}
$motherMessageID=0; 
  
 ?>
   <!DOCTYPE html>
<?php include('../includes/header.php'); ?>
 
<?php if (isset($_SESSION["user_id"]) && (!empty($_SESSION["user_id"])) && (isset($_SESSION["role"])) && (!empty($_SESSION["role"])) && ($_SESSION["role"] == 2) && ($_GET['msg_id']!=0)) {
 
	?>
<!-- Start of container -->
<div class="container">
   <!-- Start Form tag -->
  <form method="POST"   action="SendMsgRelatedToMotherOfSelectedMsg.php" enctype="multipart/form-data">


<!-- Start reciever -->
<div class="form-group">
  <!-- Start New Msg Alert -->
  
  <div class="row"  style="margin-bottom: 12px;">
   <strong><a href="follow_up_weekly_work_of_group_edit.php" class="btn btn-primary">الرجوع إلى الصفحة الرئيسية</a></strong> 
</div>
<div class="row">
  <div class="col-sm-1 col-sm-offset-11 alert alert-success"><strong>رسالة جديدة</strong></div>
</div>
<!-- End New Msg Alert -->
   <label class="form-control" style="height:75px;direction:rtl;"  ><?php 
$get_member_of_selected_grp = Crud_op::get_grp_member_for_specific_grp($_GET['selected_group1']);
 
echo 'المرسل إليهم: '; 
for($k=0;$k<count($get_member_of_selected_grp);$k++){
  echo $get_member_of_selected_grp[$k]['student_id'].' - '.$get_member_of_selected_grp[$k]['name'].' ';
}
  ?> </label>
<!-- Start msg text --> 
  <textarea class="form-control" name="txt_msg" placeholder="يُرجى كتابة رسالة نصية لا تتجاوز أحرفها 500 كلمة" maxlength="500" style="height:150px;resize: none;direction:rtl ;
  " autofocus="autofocus" required="required" id="txt_msg"  ></textarea>

<!-- End msg text -->
   </div>
<!-- End reciever -->

<!-- Start file type -->
<div class="form-group">
<div class="radio">
<input type="radio" required="required" name="FileStatus" id="optradio1" value="accepted"><label for="optradio1"  style="margin-right:20px;">مقبول</label>
</div> 
<div class="radio">
<input type="radio" required="required" name="FileStatus" id="optradio2" value="reject"><label for="optradio2"  style="margin-right:20px;">مرفوض</label>
</div>  
</div>
<!-- End file type -->
<!-- Start file input -->
<div class="form-group">
      
        <input type="file" id="fileInput" name="weekly_peoject_works" style="direction:rtl; float:right;clear:right !important;" /><!--  required="required"  --> 
      <br> 
    </div><!-- End file input -->
<!-- Start hidden group number -->

    <input type="hidden" name="groupOfUser"  value="<?php echo  $_GET['selected_group1'];?>" />
<input type="hidden" name="sender"   value="<?php echo  $_GET['sender'];?>" />

<input type="hidden" name="motherMessageIDUseInWhichMsgReply" value="<?php echo $_GET['msg_id']; ?>" />

  
<!-- End hidden group number -->
    <!-- Start submit -->
    <div class="form-group">
    <input type="submit" class="btn btn-primary pull-right" name="sending_weekly_work"  value="إرسال" style="display: block;" >
     
  </div>
  <br><br><br>
    <!-- End submit -->
    <div class="form-group">
      <div id="stdInboxMessages" style="border: 5px solid #0000FF;">
  <div class="alert alert-danger"><strong><center>الرسائلة الأم و المرتبط بها من الردود مرتبة بحسب تاريخ الإرسال</strong></center></div>

  <?php

$getRelatedMsgOfMotherMsg = Crud_op::getRelatedMsgOfMotherMsg($_GET['msg_id'],$_GET['selected_group1']);
if ($getRelatedMsgOfMotherMsg!=null) 
{
for ($i=0; $i < count($getRelatedMsgOfMotherMsg) ; $i++) { 
  
  ?>
<div class="alert alert-success">
  المرسل: <?php echo $getRelatedMsgOfMotherMsg[$i]['sender'].' - '.$getRelatedMsgOfMotherMsg[$i]['name'].'<br>'; ?>
  وقت الإرسال: <?php echo $getRelatedMsgOfMotherMsg[$i]['sending_time'].'<br>'; ?> 
  <?php


 

if ($getRelatedMsgOfMotherMsg[$i]['is_this_thesis_file']==1) {
   ?>

نوع الملف: ملف ثيسز <br>
   <?php
    
}
elseif ($getRelatedMsgOfMotherMsg[$i]['is_this_thesis_file']==0) {
   ?>
نوع الملف: ملف أعمال أسبوعية <br>
   <?php
    
}
 

  ?>
  نص الرسالة: <?php echo $getRelatedMsgOfMotherMsg[$i]['messages_text'].'<br>'; ?>


<?php

if ($getRelatedMsgOfMotherMsg[$i]['url_str']!="") {
   ?>

<a href="<?php echo $getRelatedMsgOfMotherMsg[$i]['url_str']; ?>" target="_blank" class="btn btn-small btn-primary">عرض الملف المرفق</a> <br>
   <?php
    
}
?>

</div>
 <?php

}
 
}
 
 ?>

</div>
  </div>
    
</div>
<!-- End of container -->
  <!-- End Form tag -->
  </form>
<?php } 
 
?> 
     <?php include('../includes/footer.php');?>























 