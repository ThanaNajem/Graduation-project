<?php
include('../db_op.php');
$selected_grp = $_POST['selected_grp1'];
//$msg_chat = $_POST['msg_chat1'];
$supervisor_login_id = $_POST['supervisor_login_id1'];
$get_msg_for_specsific_grp = Crud_op::get_msg_for_specsific_grp($selected_grp);
 $output="";
$output='<div>';
if($get_msg_for_specsific_grp!=null){
for($rowCount=0;$rowCount<count($get_msg_for_specsific_grp);$rowCount++){
	$sender_id = $get_msg_for_specsific_grp[$rowCount]['sender'];
	$sender_name=  $get_msg_for_specsific_grp[$rowCount]['fname'].' - '.$get_msg_for_specsific_grp[$rowCount]['lname'];
	$msg_text= 'نص الرسالة: '.$get_msg_for_specsific_grp[$rowCount]['msg_text'] ;
	$sending_time = 'وقت الإرسال: '.$get_msg_for_specsific_grp[$rowCount]['sending_time'] .'<br>' ; 
	
	
	if($sender_id!=$supervisor_login_id){
		$sender_info='المرسل: '.$sender_id .' - '. $sender_name .'<br>' ;
		
		$output.='<div class="alert alert-success" style="margin-bottom:12px;">
		'.$sending_time.''.$sender_info.''.stripslashes(htmlspecialchars($msg_text)).'<br>'.'
		</div>';
		
	}
	else{
		$sender_info='المرسل: '.$sender_id .' - '. $sender_name.' _ أنت _ '.'<br>'  ;
		$output.='<div class="alert alert-primary" style="margin-bottom:12px;">
		'.$sending_time.''.$sender_info.''.stripslashes(htmlspecialchars($msg_text)).'<br>'.'
		</div>';
	}
		
	
}	
}

 
$output.='</div>';
echo $output;
?>