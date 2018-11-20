<?php
require('../db_op.php');
$group_id = $_POST['selected_grp1']; 
$get_grp_member_for_specific_grp = Crud_op::get_grp_member_for_specific_grp($group_id);
  $output ='<option class="text-center">يرجى الاختيار</option>';
for($t=0;$t<count($get_grp_member_for_specific_grp);$t++){ 
 $output.= '<option 
class="text-center" 
value="'.$get_grp_member_for_specific_grp[$t]['student_id'].'">'.$get_grp_member_for_specific_grp[$t]['name'].'</option>';	
	
}

echo $output;
?>

