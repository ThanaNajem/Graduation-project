<?php
    session_start();
   // ob_start();
  include('../db_op.php');  
  date_default_timezone_set('israel');
  $std_login_id='';
  $status=false;
  if (isset($_SESSION["user_id"])) {
  	 $std_login_id= $_SESSION["user_id"];
  }
 
 

?>
  <!DOCTYPE html>
<?php include('../includes/header.php'); 
?>
<div class="container">
<?php
$err_login=
	'<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong>لم تقم بتسجيل الدخول</strong></div>
<div class="col-sm-4 "></div>
</div>';
 if(!(isset($_SESSION["user_id"])  &&  isset($_SESSION["role"]) && 
 !empty($_SESSION["role"]) && !empty($_SESSION["user_id"]) && (  $_SESSION["role"]==4 ) )){die($err_login);}
 
  $chk_no_of_semester_tbl_rows=Crud_op::get_active_semester_tbl_row_count1();
 $semeter_err='<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong>لم يتم تفعيل الفصل الدراسي</strong></div>
<div class="col-sm-4 "></div>
</div>';

	if($chk_no_of_semester_tbl_rows==null) {
		include('../includes/footer.php');
		die($semeter_err);
		}
		 $room_usr_err='<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong>ينبغي على الأقل تواجد طالبين أو طالب و مشرف</strong></div>
<div class="col-sm-4 "></div>
</div>';
$std_grp_name = Crud_op::check_if_this_usr_has_grp1($std_login_id);
		$check_if_file_exist_in_chat_room_just_one_student_and_his_supervisors_or_two_std_at_least=Crud_op::check_if_file_exist_in_chat_room_just_one_student_and_his_supervisors_or_two_std_at_least($std_grp_name);
		if ($check_if_file_exist_in_chat_room_just_one_student_and_his_supervisors_or_two_std_at_least==0) {
			include('../includes/footer.php');
		die($room_usr_err);
		}
		$std_grp = Crud_op::check_if_this_usr_has_grp($std_login_id);
	
	if(count($std_grp)==0){
		include('../includes/footer.php');
		die('
		<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong>لستَ منضما لأي مجموعة فلن تتمكن من رؤية محادثات</strong></div>
<div class="col-sm-4 "></div>
</div>');
		
	}
	$output='';
	  
	?>
	<div class="container">
	
	<div class="row">
	<div class="col-sm-8 col-sm-offset-2">
	<label class="defult text-center" style="font-size: 38px;background-color:blue; color:white;    padding: 15px;
    border-radius: 20px;"><span >مجموعتك:</span><span > <?php echo $std_grp_name; ?></span></label>
	 
	  
	<form method="POST"   id="std_chatForm" action="#" onsubmit="return false;">
	
			 <input type="hidden" id="std_chatting_grp_id" name="chatting_grp_id" value="<?php echo $std_grp; ?>" />
	</div>
	</div>
	<div class="row">
	<div class="col-sm-8 col-sm-offset-2"> 
<div id="ajax_res"></div>	
<div class="chatContainer">

<div class="chatHeader">

<h3 id="hd_name" class="text-center" style="border-bottom: 6px solid #3c39f0;margin-top: 2px;margin-left: 2px;
 "><?php echo $std_grp_name; ?></h3>
</div>
<div class="std_chatMessages">
 

</div>
<div class="std_chatBottom"  >
 
<input type="text" name="std_chat_msg" id="std_chat_msg" class="form-control" value="" placeholder="اكتب الرسالة" required autocomplete="off" 
style="      width: 86%;
    padding: 3px;
    padding-right: 10px;
    border: 2px solid #808080;
    border-radius: 5px;
    margin: 5px; 
	margin-top: 2px;
	display: inline-block;
	"  />
	<input type="hidden" id="std_login_id" name="supervisor_login_id1" value="<?php  echo $std_login_id; ?>" />
<input type="submit" id="send_chat_msg" class="btn btn-primary text-center submit"
style="margin-right: 2px;padding: 6px;
    
    border: 2px solid #808080;
	
	
	    margin-top: 2px;
    " value="إرسال"/>

</form>

</div>
</div>
	</div>
	</div>
	
	</div>
	
	
	<?php
	include('../includes/footer.php');?>
