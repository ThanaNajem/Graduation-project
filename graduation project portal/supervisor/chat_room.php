<?php
    session_start();
     ob_start();
  include('../db_op.php');  
  date_default_timezone_set('israel');
  $supervisor_login_id='';
  $status=false;
  if (isset($_SESSION["user_id"])) {
  	 $supervisor_login_id= $_SESSION["user_id"];
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
 !empty($_SESSION["role"]) && !empty($_SESSION["user_id"]) && (  $_SESSION["role"]==2 ) )){die($err_login);}
 
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
		$supervisor_grp = Crud_op::check_if_this_supervisor_has_a_group($supervisor_login_id);
		 
	if($supervisor_grp==null){
		include('../includes/footer.php');
		die('
		<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong>ليس لديك مجموعات فلن تتمكن من عملية المراسلة</strong></div>
<div class="col-sm-4 "></div>
</div>');
		
	}
	/* start */
	 $room_usr_err='<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong>ينبغي على الأقل تواجد طالبين أو طالب و مشرف</strong></div>
<div class="col-sm-4 "></div>
</div>';
$supervisor_grp1=$supervisor_grp[0]['group_id']; 
		$check_if_file_exist_in_chat_room_just_one_student_and_his_supervisors_or_two_std_at_least=Crud_op::check_if_file_exist_in_chat_room_just_one_student_and_his_supervisors_or_two_std_at_least($supervisor_grp1);
		if ($check_if_file_exist_in_chat_room_just_one_student_and_his_supervisors_or_two_std_at_least==0) {
			include('../includes/footer.php');
		die($room_usr_err);
		}
	/* end */
	$output='';
	/*
	if($_SERVER['REQUEST_METHOD']=='POST'){
		$chatting_grp = $_POST['chatting_grp'];
		$supervisor_login_id1= $_POST['supervisor_login_id1'];
		$chat_msg = $_POST['chat_msg'];
		
		$get_msg_for_specsific_grp = Crud_op::insert_msg_into_specific_user_for_specific_grp($chatting_grp ,$chat_msg ,$supervisor_login_id1);
		 
	}
	*/
	?>
	<div class="container">
	
	<div class="row">
	<div class="col-sm-8 col-sm-offset-2">
	<label class="defult text-center">اختر المجموعة</label>
	<form method="POST"   id="chatForm" action="#" onsubmit="return false;">
			<select class="form-control" name="chatting_grp" id="chatting_grp" style="background-color: #eee;margin-bottom: 12px;" required="required"> 
  
<option class="defult text-center" value="0">يرجى الاختيار</option>

			<?php
			var_dump(sizeof($supervisor_grp));
		for($k=0;$k<sizeof($supervisor_grp);$k++){ 
		echo '<option class="text-center" value="'.$supervisor_grp[$k]["group_id"].'"
       >'.$supervisor_grp[$k]["grp_name"].'</option>'; 
	   }
	  ?>
	  </select>
	</div>
	</div>
	<div class="row">
	<div class="col-sm-8 col-sm-offset-2"> 
<div id="ajax_res"></div>	
<div class="chatContainer" style="display:none;">

<div class="chatHeader" style="display:none;">

<h3 id="hd_name" class="text-center" style="border-bottom: 6px solid #3c39f0;margin-top: 2px;margin-left: 2px;
 "> </h3>
</div>
<div class="chatMessages">
 

</div>
<div class="chatBottom"  style="display:none;" >

<input type="text" name="chat_msg" id="chat_msg" class="form-control" value="" placeholder="اكتب الرسالة" required autocomplete="off" 
style="      width: 71%;
    padding: 3px;
    padding-right: 10px;
    border: 2px solid #808080;
    border-radius: 5px;
    margin: 5px; 
	margin-top: 2px;
	"  />
	<input type="hidden" id="supervisor_login_id" name="supervisor_login_id1" value="<?php  echo $supervisor_login_id; ?>" />
<input type="submit" id="send_chat_msg" class="btn btn-primary text-center submit" disabled="disabled" 
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
