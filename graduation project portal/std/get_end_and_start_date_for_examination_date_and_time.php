<?php
require('../db_op.php');
 $myArray=[];
$evt_name = "period_allowed_for_discussions";
$period_allowed_for_discussions_time = Crud_op::get_first_and_end_date_for_evt($evt_name);
if($period_allowed_for_discussions_time!=null){
$from_date = $period_allowed_for_discussions_time[0]['from_date'];
$to_date = $period_allowed_for_discussions_time[0]['to_date'];
	$from_date= date("Y-m-d",$from_date);
	$to_date= date("Y-m-d",$to_date);
	 
 
 $myArray = array("start" =>  $from_date,"end"=>$to_date );
 echo json_encode($myArray); 
}
else{
	 $myArray = array("start" =>  date("Y-m-d"),"end"=>date("Y-m-d") );
 
 echo json_encode($myArray);
}

?>