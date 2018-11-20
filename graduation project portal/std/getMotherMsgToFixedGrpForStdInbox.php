<?php
include('../db_op.php');
$groupID = 0; 
$loginStd = 0;
if (isset($_POST['usr_grp']) && isset($_POST['loginStd'])) {
	 
$groupID = $_POST['usr_grp']; 
$loginStd = $_POST['loginStd'];
}

$getMotherMsgOfGrp = Crud_op::getMotherMsgToFixedGrpForStdInbox($groupID);

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
	$MotherMsgID="<input type='hidden' value='". $getMotherMsgOfGrp[$rowCount]['messages_id']."' name='messageID' id='messageID'"."<input type='hidden' value='". $loginStd."' name='loginStdID' id='loginStdID'";
	
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
	$repltBtn="<input type='submit' class='btn btn-primary' style='position:absolute;left:0;top:12px;' value='الرد على هذه الرسالة'";
	if($sender_id!=$loginStd){
		echo '<form method="POST" id="replyOnToMotherMsgForm" action="replyOnMotherMsgAndViewRelated.php">';
		$sender_info=$MotherMsgID.'المرسل: '.$sender_id .' - '. $sender_name.'<br>' ;
		
		$output.='<div class="alert alert-success" style="margin-bottom:12px;position:relative;  border-radius: 0px; ">'.$repltBtn.
		$sending_time.$sender_info.'<br>'.stripslashes(htmlspecialchars($msg_text)).'<br>'.$fileTypeText.'<br>'.$fileStatusText.'<br></form>';
		if ($fileType!=-1){
$output.="عرض الملف المرفق مع هذه الرسالة: ".$file_link;
	}
		echo '
		</div> ';
		
	}
	else{
		$sender_info='المرسل: '.$sender_id .' - '. $sender_name.' _ أنت _ '.'<br>' ;
		$output.='<form method="POST"><div class="alert alert-success" style="margin-bottom:12px;border-radius: 0px;position:relative;">
		'.$sending_time.$sender_info.'<br>'.stripslashes(htmlspecialchars($msg_text)).'<br>'.$fileTypeText.'<br>'.$fileStatusText.'<br>';
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
echo $output;
?>