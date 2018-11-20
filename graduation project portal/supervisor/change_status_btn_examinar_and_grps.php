

<?php
 include('../db_op.php');
 $myArray=[]; 
  $selected_grp=$_POST['selected_grp1'];
 $selected_examiner=$_POST['selected_examiner1']; 
 if($selected_grp==0 || $selected_examiner==''){
	 $output="يرجى الااختيار";
		 $class_out="alert alert-danger";
		 $output='<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert '.$class_out.' text-center"><strong>'.$output.' </strong></div>
<div class="col-sm-4 "></div>
</div>';
echo $output;
die();
 }
 $maximum_no_of_allowed_examiner=2;
 $class_out="";
 $output='';
  $get_accepted_status_rowCount_of_selected_grp_to_prevent_exceed_maximum_no_of_examinar_for_this_grp=
  Crud_op::get_accepted_status_rowCount_of_selected_grp_to_prevent_exceed_maximum_no_of_examinar_for_this_grp($selected_grp);
 
 $get_status_btn_selected_sup_and_grp = Crud_op::get_request_status_btn_examiner_and_grp($selected_grp, $selected_examiner);
 if( $get_status_btn_selected_sup_and_grp==""){
	 $examination_accept_status="pending";
	 if($get_accepted_status_rowCount_of_selected_grp_to_prevent_exceed_maximum_no_of_examinar_for_this_grp<=$maximum_no_of_allowed_examiner){
		 $inserted_row = Crud_op::insert_new_request_status_btn_examiner_and_grp($selected_grp,$selected_examiner,$examination_accept_status);
	
	if($inserted_row!=0){
		 $output="تمت العملية بنجاح";
		 $class_out="alert alert-success";
	}
else{
	 $output="لم تتم العملية بنجاح";
		 $class_out="alert alert-danger";
}
		 
	 }
	  else{$output="تجاوزت الحد المسموح به للمشرفين و هو ".$maximum_no_of_allowed_examiner;
		 $class_out="alert alert-danger";}
 
		
	 
	 }
	 elseif( $get_status_btn_selected_sup_and_grp=="pending"){
		//  if($get_accepted_status_rowCount_of_selected_grp_to_prevent_exceed_maximum_no_of_examinar_for_this_grp<=$maximum_no_of_allowed_examiner){
		  	$deleted_row_no=Crud_op::delete_request_btn_examiner_and_grp($selected_grp,$selected_examiner);
	 if($deleted_row_no!=0){
	  $output="تمت العملية بنجاح";
		 $class_out="alert alert-success";
  }
  else{
	 $output="لم تتم العملية بنجاح"; 
		 $class_out="alert alert-danger";
  }
  //}
		//  else{$output="تجاوزت الحد المسموح به للمشرفين و هو ".$maximum_no_of_allowed_examiner;}
  
	
  
 }
	 else{
		$new_status=""; 
		
 if( $get_status_btn_selected_sup_and_grp=="reject"){
	 $new_status="accepted";
	 	 if($get_accepted_status_rowCount_of_selected_grp_to_prevent_exceed_maximum_no_of_examinar_for_this_grp<=$maximum_no_of_allowed_examiner){
			  $change_request_status_btn_examiner_and_grp = Crud_op::change_request_status_btn_examiner_and_grp($selected_grp, $selected_examiner,$new_status);
  if($change_request_status_btn_examiner_and_grp!=0){
	  $output="تمت العملية بنجاح";
		 $class_out="alert alert-success";
  }
  else{
	 $output="لم تتم العملية بنجاح";
		 $class_out="alert alert-danger"; 
  }
		 }
		 else{$output="تجاوزت الحد المسموح به للمشرفين و هو ".$maximum_no_of_allowed_examiner;
		 $class_out="alert alert-danger";}
 }
 elseif( $get_status_btn_selected_sup_and_grp=="accepted"){
	 $new_status="reject";
	 $change_request_status_btn_examiner_and_grp = Crud_op::change_request_status_btn_examiner_and_grp($selected_grp, $selected_examiner,$new_status);
  if($change_request_status_btn_examiner_and_grp!=0){
	  $output="تمت العملية بنجاح";
  }
  else{
	 $output="لم تتم العملية بنجاح"; 
		 $class_out="alert alert-danger";
  }
 }
  
	 }
$output='<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert '.$class_out.' text-center"><strong>'.$output.' </strong></div>
<div class="col-sm-4 "></div>
</div>';
echo $output;
  
 
 
?>