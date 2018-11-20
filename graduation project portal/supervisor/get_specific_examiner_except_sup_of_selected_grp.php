<?php
include('../db_op.php');
 $myArray=[]; 
 $selected_grp=$_POST['selected_grp_id']; 
 $maximum_no_of_allowed_examiner=2;
 $output='';
 $output .= '<option value="0">يرجى الاختيار</option>';
 $get_all_teachers_except_supervisor_of_this_grp = Crud_op::get_all_teachers_except_supervisor_of_this_grp($selected_grp);
 if( $get_all_teachers_except_supervisor_of_this_grp!=null){
for($w=0;$w<count($get_all_teachers_except_supervisor_of_this_grp);$w++){
	$sup_name=$get_all_teachers_except_supervisor_of_this_grp[$w]['name'];
	$teacher_id=$get_all_teachers_except_supervisor_of_this_grp[$w]['usr_id'];
	$teacher_id_and_name=$teacher_id.' - '.$sup_name;
	$output .= '<option value="'.$teacher_id.'">'.$teacher_id_and_name.'</option>';
	
}
	 
 }
 else{
	 
	 $output='<div>لا يوجد محاضرين غير المشرفين على هذه المجموعة - يرجى مراجعة مسؤول الموقع</div>';
	 }
 echo $output; 

?>