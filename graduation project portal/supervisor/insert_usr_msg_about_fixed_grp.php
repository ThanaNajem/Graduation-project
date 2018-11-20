<?php

include('../db_op.php');
//chatting_grp1:chatting_grp,chat_msg1=chat_msg,supervisor_login_id1=supervisor_login_id 
$chatting_grp1 = $_POST['chatting_grp1'];
$chat_msg1 = $_POST['chat_msg1'];
$supervisor_login_id1 = $_POST['supervisor_login_id1'];
Crud_op::insert_msg_into_specific_user_for_specific_grp($chatting_grp1 ,$chat_msg1 ,$supervisor_login_id1);
		 
?>