<?php
require('../db_op.php');
$selected_examiner1 = $_POST['selected_examiner1'];
$selected_grp1=$_POST['selected_grp1']; 
 //get_status btn them to specify if input type submit has pending status or reject or accepted or null
 //befor this check if grp has thesis ,if yes ->apppear suitavle submit , if no appear err msg 
 //and after appear submit in custom.js submit then check befor insert or update if no not exceeded maximum no of examinar
$get_request_status_btn_examiner_and_grp = Crud_op::get_request_status_btn_examiner_and_grp($selected_grp1,$selected_examiner1);

$output='<input type="submit" ';
$submit_value="";
if($get_request_status_btn_examiner_and_grp=="pending"){
$submit_value="إلغاء إرسال الطلب لهذا الممتحن";	
$cofirm_alert_msg=' return confirm(\'هل أنتَ متأكد من إلغاء إرسال هذا الطلب\');';
$submit_class= "btn btn-danger"  ;
}
elseif($get_request_status_btn_examiner_and_grp=="reject"){
	$submit_value="إعادة إرسال الطلب لهذا الممتحن";	
$cofirm_alert_msg=' return confirm(\'هل أنتَ متأكد من إعادة إرسال هذا الطلب\');';
$submit_class= "btn btn-success"  ;
}
elseif($get_request_status_btn_examiner_and_grp=="accepted"){
	$submit_value="إلغاء هذا الطلب المقبول";

$cofirm_alert_msg=' return confirm(\'هل أنتَ متأكد من حذف هذا الطلب المقبول\');';	
$submit_class= "btn btn-danger"  ;
}
elseif($get_request_status_btn_examiner_and_grp==""){
	$submit_value="إرسال طلب انضمام لهذه المجموعة";
	
$cofirm_alert_msg=' return confirm(\'هل أنتَ متأكد من إرسال طلب انضمام لهذه المجموعة\');';
$submit_class="btn btn-success";
}
$output.='value="'.$submit_value.'" class="submit '.$submit_class.'" onClick="'.$cofirm_alert_msg.'"  >';
echo $output;
?>