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
  $get_usr_grp = Crud_op::check_if_this_usr_has_grp($user_id);
if ( $get_usr_grp==0) {
 $err="لستَ منضما لأي مجموعة ";
}
$get_sup_grp = Crud_op::check_if_this_grp_has_a_supervisor($get_usr_grp);
if ($get_sup_grp==0) {
	$err="ليس لدى هذه المجموعة مشرفا ";
}
?>
   <!DOCTYPE html>
<?php include('../includes/header.php'); ?>
 
<?php if (isset($_SESSION["user_id"]) && (!empty($_SESSION["user_id"])) && (isset($_SESSION["role"])) && (!empty($_SESSION["role"])) && ($_SESSION["role"] == 4)) {
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
	?>
<!-- Start of container -->
<div class="container">
	<!--start new msg -->
<div class="row" style="margin-bottom: 12px;">
  <div class="form-group text-center">
    <!-- <div class="col-sm-4"></div> -->
    <div class="col-md-10 col-md-offset-1">
        <a class="btn icon-btn btn-success" href="newMsg.php?action=add" name="add-old-year" 
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

/**/

$getMotherMsgOfGrp = Crud_op::getMotherMsgToFixedGrpForStdInbox($get_usr_grp);

 $output="";
$output='<div>';
if($getMotherMsgOfGrp!=null){
for($rowCount=0;$rowCount<count($getMotherMsgOfGrp);$rowCount++){
	$sender_id = $getMotherMsgOfGrp[$rowCount]['sender'];
	$sender_name=  $getMotherMsgOfGrp[$rowCount]['fname'].' - '.$getMotherMsgOfGrp[$rowCount]['lname'];
	$msg_text= 'نص الرسالة: '.$getMotherMsgOfGrp[$rowCount]['messages_text'] ;
	$sending_time = 'وقت الإرسال: '.$getMotherMsgOfGrp[$rowCount]['sending_time'].'<br>' ; 
	$fileType=$getMotherMsgOfGrp[$rowCount]['is_this_thesis_file'];
	$fileTypeText= "نوع الملف المُرسَل: ";
	$file_link="";
	$MotherMsgID="<input type='hidden' value='". $getMotherMsgOfGrp[$rowCount]['messages_id']."' name='messageID' /> 
	"."<input type='hidden' value='". $user_id."' name='loginStdID' id='loginStdID' />";
	
	if ($fileType==0) {
	$fileType=" ملف أعمال أسبوعية ";	

		$file_link='<a href="'.$getMotherMsgOfGrp[$rowCount]['url_str'].'"  target="_blank">عرض الملف المرفق</a>'; 
	}
	elseif ($fileType==1) {
		$fileType=" ملف ثيسز "; 
		$file_link='<a href="'.$getMotherMsgOfGrp[$rowCount]['url_str'].'"  target="_blank">عرض الملف المرفق</a>';
	}
	elseif ($fileType==-1) {
		$fileType=" ليس هناك ملف مرفق "; 
		$file_link="";
	}
$fileTypeText.=$fileType;
	$fileStatus=$getMotherMsgOfGrp[$rowCount]['attach_status'];
	$fileStatusText="حالة الملف المُرسَل: ";
	if ($fileStatus=="pending") {
		$fileStatus="قيد الانتظار"; 
	}
	elseif ($fileStatus=="accepted") {
	 $fileStatus="مقبول"; 
	}
	elseif ($fileStatus=="reject") {
	 $fileStatus="مرفوض"; 
	}
$fileStatusText.= $fileStatus;
	 
	if($sender_id!=$user_id){
		  
		$sender_info='<form method="POST" action="replyOnMotherMsgAndViewRelated.php" target="_self">'.$MotherMsgID.'المرسل: '.$sender_id .' - '. $sender_name.'<br>' ;
		
		$output.='<div class="alert alert-success" style="margin-bottom:12px;position:relative;  border-radius: 0px; ">'.
		$sending_time.$sender_info.'<br>'.stripslashes(htmlspecialchars($msg_text)).'<br>'.$fileTypeText.'<br>'.$fileStatusText.'<br>'.'<input type="submit" class="btn btn-primary" name="send" style="position:absolute;left:0;top:12px;" value="الرد على هذه الرسالة" >'.'</form>';
		if ($fileType!=-1){
$output.="عرض الملف المرفق مع هذه الرسالة: ".$file_link;
	}
		echo '
		</div> ';
		
	}
	else{
		$sender_info='المرسل: '.$sender_id .' - '. $sender_name.' _ أنت _ '.'<br>' ;
		$output.='<form method="POST" action="replyOnMotherMsgAndViewRelated.php" target="_self"><div class="alert alert-success" style="margin-bottom:12px;border-radius: 0px;position:relative;">
		'.$sending_time.$sender_info.$MotherMsgID.$repltBtn.'<br>'.stripslashes(htmlspecialchars($msg_text)).'<br>'.$fileTypeText.'<br>'.$fileStatusText.'<input type="submit" class="btn btn-primary" name="send" style="position:absolute;left:0;top:12px;" value="الرد على هذه الرسالة" >'.'</form><br>';
		if ($fileType!=-1){
$output.="عرض الملف المرفق مع هذه الرسالة: ".$file_link;
	}
		echo '
		</div> ';
	}
		
	

}	
 

}
else{
$output.='<div class="alert alert-danger" style="border-radius: 0px;"><center><strong>لا يوجد رسائل واردة</strong></center></div>';

}
 
$output.='</div>';

/**/
 ?>
<div id="stdInboxMessages" style="border: 5px solid #0000FF;">
	<?php
echo $output;
	?>
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