<?php
 /* 
 $upload_file_status='';
 

*/
  
 /* */
 /* start my-needed */
 
 $upload_file_status='لم تتم العملية بتاتا أعد المحاولة';
 
 
 /* end my-needed */
  
 
  
 
 
include('../db_op.php');
 
  
		$grp_id=$_POST['groups_for_specific_sup'];
	 
		$status_weekly_file_satus=$_POST['status'];
		$txt_msg=$_POST['txt_msg'];
		$supervisor_login_id=$_POST['supervisor_login_id'];
		$to_which_msg_reply_id=$_POST['to_which_msg_reply_id'];
		$attachments_id=$_POST['attachments_id'];
		  $is_this_thesis_file = $_POST['is_this_thesis_file'];
		     $errors= array();
      $file_name = $_FILES['weekly_peoject_works_for_specific_grp']['name'];
      $file_size =$_FILES['weekly_peoject_works_for_specific_grp']['size'];
      $file_tmp =$_FILES['weekly_peoject_works_for_specific_grp']['tmp_name'];
      $file_type=$_FILES['weekly_peoject_works_for_specific_grp']['type'];
    $tmp=explode('.',$_FILES['weekly_peoject_works_for_specific_grp']['name']);
      $file_ext=strtolower(end($tmp));
      $expensions= array("pdf");
      if(in_array($file_ext,$expensions)=== false){
       $errors[]="هذا الامتداد غير مسموح به يجب أن يكون pdf";
		$upload_file_status= "هذا الامتداد غير مسموح به يجب أن يكون pdf";
      }
        if($file_size == 0 || $file_size > 2097152 ){
      
      $errors[]="الملف فارغ! أو تجاوز الحد المسموح به";
		 $upload_file_status= "الملف فارغ! أو تجاوز الحد المسموح به";
      }
        $dest_path = "../testupload/".$file_name; 

		 
		$check_if_this_file_accepted_in_this_week=Crud_op::check_if_this_file_accepted_in_this_week($grp_id);
		 
		if($check_if_this_file_accepted_in_this_week=="pending"){
			    if(empty($errors)==true){
 if(!file_exists ($dest_path)){ 
 
 
       
    if( move_uploaded_file($file_tmp,$dest_path) ) {
      $row_count=0;
	  
      $row_count=Crud_op::insert_a_new_msg_with_its_attachment_and_specify_to_which_msg_reply($status_weekly_file_satus,$grp_id,
	  $supervisor_login_id,$txt_msg,$dest_path,$to_which_msg_reply_id,$attachments_id,$is_this_thesis_file);
				$upload_file_status=$row_count;	 
	  
      if($row_count==3){
		  $upload_file_status="تمت العملية بنجاح";
      $upload_file_err=null; 
      }
      else{
		  $upload_file_success=null;
       $upload_file_status="لم تتم العملية بنجاح";
      
     }
    }
     else{
       $upload_file_success=null;
       $upload_file_status="لم تتم العملية بنجاح";
       foreach($errors as $key=>$value){
        // print_r($value); 
       }
        }
}else{
	$upload_file_status="الملف موجود";
}
      }else{
		  $upload_file_success="هناك خطأ";
       //  print_r($errors);
      }
		}
		elseif($check_if_this_file_accepted_in_this_week==null && $grp_id!=0){
			$upload_file_status ='<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger"><strong>لآ يوجد رسائل من هذه المجموعة</strong></div>
<div class="col-sm-4 "></div>
</div>';
		}
		elseif($check_if_this_file_accepted_in_this_week==null && $grp_id==0){
			$upload_file_status ='<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger"><strong>يرجى الاختيار</strong></div>
<div class="col-sm-4 "></div>
</div>';
		}
		elseif($check_if_this_file_accepted_in_this_week=="reject"){
		/*start reject act*/
		
			    if(empty($errors)==true){
 if(!file_exists ($dest_path)){ 
       
    if( move_uploaded_file($file_tmp,$dest_path) ) {
      $row_count=0;
	  
      $row_count=Crud_op::update_status_into_reject_and_update_check_if_this_file_thesis_into_zero($status_weekly_file_satus,$grp_id,
	  $supervisor_login_id,$txt_msg,$dest_path,$to_which_msg_reply_id,$attachments_id,$is_this_thesis_file);
				$upload_file_status=$row_count;	 
	  
      if($row_count>0){
		  $upload_file_status="تمت العملية بنجاح";
      $upload_file_err=null; 
      }
      else{
		  $upload_file_success=null;
       $upload_file_status="لم تتم العملية بنجاح";
      
     }
    }
     else{
       $upload_file_success=null;
       $upload_file_status="لم تتم العملية بنجاح";
       foreach($errors as $key=>$value){
        // print_r($value); 
       }
        }
}else{
	$upload_file_status="الملف موجود";
}
      }else{
		  $upload_file_success="هناك خطأ";
       //  print_r($errors);
      }
		/*end reject act*/	 
			 
		}
		elseif($check_if_this_file_accepted_in_this_week=="accepted"){
		 /*start accepted act*/
		
			    if(empty($errors)==true){
 if(!file_exists ($dest_path)){ 
       
    if( move_uploaded_file($file_tmp,$dest_path) ) {
      $row_count=0;
	  
      $row_count=Crud_op::update_status_into_accepted_and_update_check_if_this_file_thesis_into_zero($status_weekly_file_satus,$grp_id,
	  $supervisor_login_id,$txt_msg,$dest_path,$to_which_msg_reply_id,$attachments_id,$is_this_thesis_file);
				$upload_file_status=$row_count;	 
	  
      if($row_count>0){
		  $upload_file_status="تمت العملية بنجاح";
      $upload_file_err=null; 
      }
      else{
		  $upload_file_success=null;
       $upload_file_status="لم تتم العملية بنجاح";
      
     }
    }
     else{
       $upload_file_success=null;
       $upload_file_status="لم تتم العملية بنجاح";
       foreach($errors as $key=>$value){
        // print_r($value); 
       }
        }
}else{
	$upload_file_status="الملف موجود";
}
      }else{
		  $upload_file_success="هناك خطأ";
       //  print_r($errors);
      }
		/*end accepted act*/	 
		
		}
	 
	  
 

	 
 /*} */
 
 $myArray=[];
 //var_dump($upload_file_status);
 if(isset($upload_file_status)){

if( $upload_file_status=="تمت العملية بنجاح"){
$upload_file_status='<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-success"><strong>'.$upload_file_status.'</strong></div>
<div class="col-sm-4 "></div>
</div>';
}
else{
$upload_file_status='<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger"><strong>'.$upload_file_status.'</strong></div>
<div class="col-sm-4 "></div>
</div>';
}   $myArray = array("upload_file_status" =>  $upload_file_status);
echo json_encode($myArray); 
 }



if(isset($errors)){
foreach($errors as $key=>$value){
$errors1='<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger"><strong>'.$value.'</strong></div>
<div class="col-sm-4 "></div>
</div>';
$myArray = array("errors" =>  $errors1);

echo json_encode($errors1);
}   
 } 
?>