<?php
     
  
    session_start();
  include('../db_op.php');
  $status=false;
  $send_thesis_file_status=false;
  date_default_timezone_set('israel');
 /**/
 $user_id=null;
 $get_usr_grp=null;
 $errors=null;
if (isset($_SESSION["user_id"])) {
  $user_id=$_SESSION["user_id"];
}
$upload_file_err="";
$upload_file_success="";


  // $get_usr_grp = Crud_op::check_if_this_usr_has_grp($user_id);

?>
   <!DOCTYPE html>
<?php include('../includes/header.php'); ?>
 
<?php if (isset($_SESSION["user_id"]) && (!empty($_SESSION["user_id"])) && (isset($_SESSION["role"])) && (!empty($_SESSION["role"])) && (($_SESSION["role"] == 4)||($_SESSION["role"] == 2)) ) {
 if($_SERVER['REQUEST_METHOD'] == 'POST'){
  if ($_SESSION["role"] == 2) {
     $is_this_thesis_file=-1;
  }
  elseif ($_SESSION["role"] == 4 ) {
   
$is_this_thesis_file=$_POST['thesisFileType']; 
  }
 $to_which_msg_id_reply=0;
$messages_text = $_POST['txt_msg'];
$sender = $user_id;
$groupID = $_POST['groupOfUser'];


$IsThisMsgTheMother=1;
$attachmentPath="";$status="";
/* start file info */ 
if (isset($_FILES['weekly_peoject_works']) &&!empty($_FILES['weekly_peoject_works']['name'])) {
  $errors= array();
      $file_name = $_FILES['weekly_peoject_works']['name'];
      $file_size =$_FILES['weekly_peoject_works']['size'];
      $file_tmp =$_FILES['weekly_peoject_works']['tmp_name'];
      $file_type=$_FILES['weekly_peoject_works']['type'];
    $tmp=explode('.',$_FILES['weekly_peoject_works']['name']);
      $file_ext=strtolower(end($tmp));
      $expensions= array("pdf");
      if((in_array($file_ext,$expensions)=== false)&&( $file_ext!="")){
         $errors[]="هذا الامتداد غير مسموح - يرجى اختيار ملف امتداده PDF";
      }
      
    //  if($file_size > 2097152){
      if($file_size == 0 || $file_size > 104857600){
      
         $errors[]='الملف فارغ! أو تجاوز الحد المسموح به';
     
      }
        $dest_path = "../testupload/".$file_name;

/* end file info */

$status="pending";
$attachmentPath=$dest_path;

/* start check ext and size of file and other error */
if(empty($errors)==true){
if(!file_exists ($dest_path)){      
    if( move_uploaded_file($file_tmp,$dest_path) ) {
      $row_count=0; 
      $row_count= Crud_op::insertNewMotherMeg($groupID,$sender,$messages_text,$to_which_msg_id_reply,$is_this_thesis_file,$IsThisMsgTheMother,$attachmentPath,$status);
     
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
       $upload_file_err="لم تتم العملية بنجاح ";
       foreach($errors as $key=>$value){
         $upload_file_err.=$value;
        // print_r($errors); 
       }
       
    }
}
else{
  $upload_file_err= 'الملف موجود،أعد تسمية ملفك أو أنك قمت بإضافته مسبقا' ;
  $upload_file_success="";
}   
      }else{
         foreach($errors as $key=>$value){
         $upload_file_err.=$value;
        // print_r($errors); 
       }
     //print_r($errors);
      }
      

}
 else{
   $row_count= Crud_op::insertNewMotherMeg($groupID,$sender,$messages_text,$to_which_msg_id_reply,$is_this_thesis_file,$IsThisMsgTheMother,$attachmentPath,$status);
  
      if($row_count){$upload_file_success="تمت العملية بنجاح";
      $upload_file_err="";
        //echo $upload_file_success;
      }
      else{  
    $upload_file_success="";
       $upload_file_err="لم تتم العملية بنجاح";
      // echo $upload_file_err;
   
     }

 }    
/* End check ext and size of file and other error */
?>
 <div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
 <?php

if ($upload_file_err!="") {
  echo '<div class="col-sm-4 alert alert-danger text-center" >'.$upload_file_err.'</div>';
  
}
elseif ($upload_file_success!="") {

  echo '<div class="col-sm-4 alert alert-success text-center" >'.$upload_file_success.'</div>';
   
}
?>
<div class="col-sm-4 "></div>
</div>
<hr style="display: none;">
 <div class="form-group text-center">
    <!-- <div class="col-sm-4"></div> -->
    <div class="col-md-8 col-md-offset-2">
        <a class="btn icon-btn btn-primary" href="send_weekly_work_of_group_edit.php"  
    style="font-size: 16px;font-weight: bold;direction:rtl;"  >انتقل إلى صفحة إرسال الرسائل الرئيسية</a>
  </div>
    <div class="col-sm-4"></div>
</div>
<hr style="display: none;">
 <div class="form-group text-center">
    <!-- <div class="col-sm-4"></div> -->
    <div class="col-md-8 col-md-offset-2">
        <a class="btn icon-btn btn-primary" href="newMsg.php?action=add"  
    style="font-size: 16px;font-weight: bold;direction:rtl;"  >انتقل إلى الصفحة السابقة</a>
  </div>
    <div class="col-sm-4"></div>
</div>
<?php


/**/

// header('Location: send_weekly_work_of_group_edit.php');
 }
 
?>

 <?php } 
 
?> 
     <?php include('../includes/footer.php');?>