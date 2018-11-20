<?php
include('../db_op.php');

$selected_grp = $_POST['selected_grp1'];
 
$selected_grp_name = Crud_op::get_grp_name_for_specific_grp($selected_grp);
echo $selected_grp_name;
 
?>