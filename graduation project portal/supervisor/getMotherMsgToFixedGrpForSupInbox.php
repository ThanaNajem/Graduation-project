<?php

include('../db_op.php');
$selected_grp=0;
$loginSupervID=0;
if(
isset($_POST['selected_grp'])
&&
isset($_POST['loginSupervID'])
)
{
$selected_grp=$_POST['selected_grp'];
$loginSupervID=$_POST['loginSupervID'];	
}
$_SESSION['selected_grp']=$selected_grp;

$getMotherMsgOfGrp = Crud_op::getMotherMsgToFixedGrpForSupInbox($selected_grp) ;

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
	$MotherMsgID="<input type='hidden' value='". $getMotherMsgOfGrp[$rowCount]['messages_id']."' name='messageID' id='messageID' />"."<input type='hidden' value='". $selected_grp."' name='selected_grp' id='selected_grp' /> 
	"."<input type='hidden' value='". $loginSupervID."' name='loginStdID' id='loginStdID' />";
	
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
	 
	if($sender_id!=$loginSupervID){
		  
		$sender_info='المرسل: '.$sender_id .' - '. $sender_name.'<br>' ;
		
		$output.='<div class="alert alert-success" style="margin-bottom:12px;position:relative;  border-radius: 0px; ">'.
		$sending_time.$sender_info.stripslashes(htmlspecialchars($msg_text)).'<form method="POST" id="send_msg_to_selected_group_from_sup">'.$MotherMsgID.'<br>'.$fileTypeText.'<br>'.$fileStatusText.'<br>'.
		'<input type="submit" class="btn btn-primary" name="send" style="position:absolute;left:0;top:12px;" value="الرد على هذه الرسالة" >'.'</form>';
		if ($fileType!=-1){
$output.="عرض الملف المرفق مع هذه الرسالة: ".$file_link;
	}
		$output.= '
		</div> ';
		
	}
	else{
		$sender_info='المرسل: '.$sender_id .' - '. $sender_name.' _ أنت _ '.'<br>' ;
		$output.='<div class="alert alert-success" style="margin-bottom:12px;border-radius: 0px;position:relative;">
		'.$sending_time.$sender_info.'<form method="POST" id="send_msg_to_selected_group_from_sup">'.$MotherMsgID.$repltBtn.stripslashes(htmlspecialchars($msg_text)).'<br>'.$fileTypeText.'<br>'.$fileStatusText.
		'<input type="submit" class="btn btn-primary" name="send" style="position:absolute;left:0;top:12px;" value="الرد على هذه الرسالة" >'.'</form><br>';
		if ($fileType!=-1){
$output.="عرض الملف المرفق مع هذه الرسالة: ".$file_link;
	}
		$output.= '
		</div> ';
	}
		
	

}	
 

}
else{
$output.='<div class="alert alert-danger" style="border-radius: 0px;"><center><strong>لا يوجد رسائل واردة</strong></center></div>';

}
 
$output.='</div>';
echo $output;
?>