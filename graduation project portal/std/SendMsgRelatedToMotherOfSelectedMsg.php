 
 <?php
     
  
    session_start();
  include('../db_op.php'); 
  date_default_timezone_set('israel');
 /**/ 
if (isset($_SESSION["user_id"])) {
  $user_id=$_SESSION["user_id"];
}
 $url_str="";
$upload_file_err="";
$upload_file_success="";

if($_SERVER['REQUEST_METHOD'] == 'POST'){ 
/* Start variable */
 
$group_id = $_POST['groupOfUser'];
$sender = $user_id; 
$messages_text = $_POST['txt_msg'];
$to_which_msg_id_reply = $_POST['motherMessageID'];
$is_this_thesis_file = $_POST['thesisFileType'];
$IsThisMsgTheMother =0;
$status="";
$url_str= "";
/* End variable */
?>

   <!DOCTYPE html>
<?php include('../includes/header.php');  

 if (isset($_FILES['weekly_peoject_works'])) {
	 /* start upload file */
        $errors= array();
      $file_name = $_FILES['weekly_peoject_works']['name'];
      $file_size =$_FILES['weekly_peoject_works']['size'];
      $file_tmp =$_FILES['weekly_peoject_works']['tmp_name'];
      $file_type=$_FILES['weekly_peoject_works']['type'];
    $tmp=explode('.',$_FILES['weekly_peoject_works']['name']);
      $file_ext=strtolower(end($tmp));
      $expensions= array("pdf");
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="هذا الامتداد غير مسموح - يرجى اختيار ملف امتداده PDF";
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
      //insert with file
      $row_count=Crud_op::insert_a_new_msg_with_its_attachment($group_id ,$sender,$messages_text ,$to_which_msg_id_reply ,$is_this_thesis_file ,$IsThisMsgTheMother,$dest_path);
      if($row_count){$upload_file_success="تمت العملية بنجاح";
      $upload_file_err="";
        echo $upload_file_success;}
      else{  unlink($dest_path ); 
    $upload_file_success="";
       $upload_file_err="لم تتم العملية بنجاح";
       echo $upload_file_err;
   
     }
      
    }
     else{
       $upload_file_success="";
       $upload_file_err="لم تتم العملية بنجاح";
       foreach($errors as $key=>$value){
         $upload_file_err.=$value;
        // print_r($errors); 
       }
       
    }
}
else{
  $upload_file_err= 'الملف موجود،أعد تسمية ملفك أو أنك قمت بإضافته مسبقا';
  $upload_file_success="";
}   
      }else{
         foreach($errors as $key=>$value){
         $upload_file_err.=$value;
        // print_r($errors); 
       }
     //print_r($errors);
      }
       if(isset($errors)){
        foreach($errors as $key=>$value){
         $upload_file_err.=$value;
        // print_r($errors); 
       }
       
     }
	 /* end upload file*/
$status="pending";
$url_str= $dest_path;
} 
else{
	//insert without file
	$insert_a_new_messages_without_attachment = Crud_op::insert_a_new_messages_without_attachment($group_id ,$sender,$messages_text ,$to_which_msg_id_reply ,$is_this_thesis_file ,$IsThisMsgTheMother);
	
  if ($insert_a_new_messages_without_attachment) {
	$upload_file_success="تمت العملية بنجاح";
	}
	else{
		$upload_file_err="لم تتم العملية بنجاح";
	}
}
?>
<div class="row" style="margin-bottom: 12px;">
  <div class="form-group text-center">
    <!-- <div class="col-sm-4"></div> -->
       <?php 
    if (isset($upload_file_err)) {
      $alert="alert-danger";
      	  
          } 
			elseif (isset($upload_file_success)) {
			   $alert="alert-success";
												} ?> 
                        <div class="alert <?php echo $alert ; ?> text-center">
      <?php if (isset($upload_file_err)) {
      	 echo $upload_file_err;
     									 } 
			elseif (isset($upload_file_success)) {
			   	 echo $upload_file_success;
												}
      ?>

  </div>
    <div class="col-sm-4"></div>
    <div  >
      
 <a href="send_weekly_work_of_group_edit.php" class="btn btn-primary" style="margin-top:12px; ">الرجوع للصفحة الرئيسية</a>
    </div>  
</div>
</div>
<?php
}
?>