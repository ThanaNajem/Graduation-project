<?php
/**/
$check_if_this_file_thesis=$_POST['check_if_this_file_thesis'];
$messages_text=$_POST['txt_msg'];
              $errors= array();
      $file_name = $_FILES['weekly_peoject_works']['name'];
      $file_size =$_FILES['weekly_peoject_works']['size'];
      $file_tmp =$_FILES['weekly_peoject_works']['tmp_name'];
      $file_type=$_FILES['weekly_peoject_works']['type'];
    $tmp=explode('.',$_FILES['weekly_peoject_works']['name']);
      $file_ext=strtolower(end($tmp));
      $expensions= array("pdf");
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, please choose a PDF file.";
      }
      
    //  if($file_size > 2097152){
      if($file_size == 0 || $file_size > 104857600){
      
         $errors[]='الملف فارغ! أو تجاوز الحد المسموح به';
      }
        $dest_path = "../testupload/".$file_name;
     
      if(empty($errors)==true){
if(!file_exists ($dest_path)){      
    if( move_uploaded_file($file_tmp,$dest_path) ) {
      $row_count=0; 
      $row_count=Crud_op::insert_a_new_msg_with_its_attachment($get_usr_grp,$user_id,$messages_text,$dest_path);
      if($row_count==2){$upload_file_success="تمت العملية بنجاح";
      $upload_file_err=null;
        echo $upload_file_success;}
      else{  unlink($dest_path ); 
	  $upload_file_success=null;
       $upload_file_err="لم تتم العملية بنجاح";
       echo $upload_file_err;
	 
	   }
      
    }
     else{
       $upload_file_success=null;
       $upload_file_err="لم تتم العملية بنجاح";
       foreach($errors as $key=>$value){
         
         print_r($value); 
       }
       
    }
}
else{
  $upload_file_err= 'الملف موجود،أعد تسمية ملفك أو أنك قمت بإضافته مسبقا';
  $upload_file_success=null;
}   
      }else{
         print_r($errors);
      }
              
              /**/
			  ?>