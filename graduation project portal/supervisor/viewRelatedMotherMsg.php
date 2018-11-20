
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
$err="";

$get_all_grps_for_specific_supervisor =Crud_op::get_all_grps_for_specific_supervisor($user_id);
   
if (count($get_all_grps_for_specific_supervisor) ==0) {
 $err="لستَ مسؤولا عن أي مجموعة ";
}
// $selected_grp=0;
// if ($_SERVER['REQUEST_METHOD'] =='POST') {
// 	$selected_grp = $_POST['selected_grp'];
// }
?>
   <!DOCTYPE html>
<?php include('../includes/header.php'); ?>
 
<?php if (isset($_SESSION["user_id"]) && (!empty($_SESSION["user_id"])) && (isset($_SESSION["role"])) && (!empty($_SESSION["role"])) && ($_SESSION["role"] == 2)) {
 $get_active_semester_tbl_row_count = Crud_op::get_active_semester_tbl_row_count();
 if ($get_active_semester_tbl_row_count==0) {
  $err="لم يتم تفعيل الفصل الدراسي بعد";
 }
 /* Start check if it's a time to send thesis and weekly file*/
 
 $evt_name="send_your_weekly_project_work_and_thesis";
 $check_if_it_is_a_time_to_begin_evt = Crud_op::check_if_it_is_a_time_to_begin_evt( $evt_name);
 if (!$check_if_it_is_a_time_to_begin_evt) {
 	$err="غير مسموح إرسال ملفات ثيسز و لا ملفات أسبوعية هذه الفترة";
 }
 /* End check if it's a time to send thesis and weekly file*/
 if ($err!="") {
 	 ?>
<div class="row" style="margin-bottom: 12px;">
  <div class="form-group text-center">
    <!-- <div class="col-sm-4"></div> -->
    <div class="col-sm-4 col-sm-offset-4 alert alert-danger">
      <?php echo $err; include('../includes/footer.php');die();?>
  </div>
    <div class="col-sm-4"></div>
</div>
</div>
 	 <?php
 }
 $sender=0;
 if($_SERVER['REQUEST_METHOD'] == "POST"){

 $to_which_msg_id_reply= $_POST['to_which_msg_id_reply'];
 
 if ( $to_which_msg_id_reply!=0) {//lecturer start chatting
  $messages_id=$_POST['to_which_msg_id_reply'];
 }
 else {//std start chatting
 	$messages_id=$_POST['MotherMsg'];
 }
$get_related_to_mother_msg_for_std = Crud_op::get_related_to_mother_msg_for_std($messages_id);
$get_arrachment_of_related_msg = Crud_op::get_attachment_for_related_msg($messages_id);
?>
<div class="container">
<?php
if ($get_related_to_mother_msg_for_std!=null) {
	 for ($i=0; $i < count($get_related_to_mother_msg_for_std); $i++) { 
 ?>
 <div class="row" style="margin-bottom: 12px;">
 	<div class="col-sm-8 col-sm-offset-2"><center>
 	<a href="sentMsgForSup.php" class="btn btn-primary">الرجوع إلى الصفحة السابقة</a></center>
</div>
 </div>
<div class="row">
	
	<div class="col-sm-8 col-sm-offset-2 alert alert-success">
		اسم المرسل: <?php  echo $get_related_to_mother_msg_for_std[$i]['fname'].' '.$get_related_to_mother_msg_for_std[$i]['lname'].' - '.$get_related_to_mother_msg_for_std[$i]['sender']; ?> <br>
		وقت الإرسال: <?php echo $get_related_to_mother_msg_for_std[$i]['sending_time']; ?> <br>
		نص الرسالة: <?php echo $get_related_to_mother_msg_for_std[$i]['messages_text'] ; ?> <br>
<?php
if ($get_arrachment_of_related_msg!=null) {
	 if ($get_arrachment_of_related_msg[0]['url_str']!="") {
		 ?>الملف المرفق: <a href="<?php echo $get_related_to_mother_msg_for_std[0]['url_str'] ; ?>" class="btn btn-primary">عرض الملف المرفق</a>  <br>    

		حالة الملف المرفق: <?php echo $get_arrachment_of_related_msg[0]['status'] ; ?> <br>

		 <?php
if ( $get_related_to_mother_msg_for_std[$i]['is_this_thesis_file'] ==1) {
	echo 'ملف ثيسز';
}
elseif ( $get_related_to_mother_msg_for_std[$i]['is_this_thesis_file'] ==2) {
		echo 'ملف مشرف';
}
elseif ($get_related_to_mother_msg_for_std[$i]['is_this_thesis_file'] ==0) {
		echo 'ملف أسبوعي';
}
		 }  
}
?>
		 

	</div>
</div>

</div>
 <?php
}
}
else {
?>
 <div class="row" style="margin-bottom: 12px;">
 	<div class="col-sm-8 col-sm-offset-2"><center>
 	<div class="btn btn-danger">لا يتوفر ردود تابعة لهذه الرسالة</div></center>
</div>
 </div>
<?php	 
}


}
}
 include('../includes/footer.php');?>