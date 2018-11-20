<?php
    session_start();
  include('../db_op.php');

?>
   <!DOCTYPE html>
<?php include('../includes/header.php'); ?>
<div class="container text-center">
<!--End fixed menu-->
<!-- </div> -->
<?php 

if (isset($_SESSION["user_id"]) && (!empty($_SESSION["user_id"])) && (isset($_SESSION["role"])) && (!empty($_SESSION["role"])) && ($_SESSION["role"] == 1)) {
 
 $get_active_semester_tbl_row_count = Crud_op::get_active_semester_tbl_row_count1();
if($get_active_semester_tbl_row_count!=null) { 
 $active_sem_id=$get_active_semester_tbl_row_count [0]['auto_inc_id'];
 
 ?>
 
 <!-- Start add btn -->
 
 <div class="row" style="margin-bottom: 12px;">
 <div class="form-group text-center">
   
    <div class="col-sm-8 col-sm-offset-2">
       <h1>ﺇﺿﺎﻓﺔ ﻗﺎﻋﺎﺕ ﻣﻨﺎﻗﺸﺔ ﻣﺸﺎﺭﻳﻊ اﻟﺘﺨﺮﺝ</h1>
    </div> 
</div>
</div> 
<div class="row" style="margin-bottom: 12px;">
 <div class="form-group text-center">
  <div class="col-sm-8 col-sm-offset-2">
        <a class="btn icon-btn btn-success" href="add_halls_into_this_semester.php?action=add" name="add_halls_into_this_semester" style="font-size: 19px;font-weight: bold;"><span class="glyphicon btn-glyphicon glyphicon-plus-sign img-circle text-success" style="color: white;background-color: #83e88b" ></span>ﺇﺿﺎﻓﺔ ﻗﺎﻋﺎﺕ ﻣﻨﺎﻗﺸﺔ ﻣﺸﺎﺭﻳﻊ اﻟﺘﺨﺮﺝ</a>
 
  </div> 
</div>
</div>
<div class="row" style="margin-bottom: 12px;">
 <div class="form-group text-center">
   <div class="col-sm-4"></div>
    <div class="col-sm-4 alert alert-success">
       
  <strong>الفصل الفعّال:<?php
  if ( $get_active_semester_tbl_row_count!=null) {
 echo  $get_active_semester_tbl_row_count[0]['sem_name'].' '.$get_active_semester_tbl_row_count[0]['year_val'];
   }
   else{
    echo  'ﻟﻢ ﻳﺘﻢ ﺗﻔﻌﻴﻞ ﺃﻱ ﻓﺼﻞ ﺑﻌﺪ';
   }

  ?>
 </strong>
    </div>
    <div class="col-sm-4"></div>
</div>
</div>
 <?php
 try{
   
 if(
 isset(
 $_POST['add_this_hall'])
 && isset($_POST['hall_no'])   
 
 ){
  $check_if_this_hall_exist=Crud_op::check_if_this_hall_exist($_POST['hall_no']);
  if($check_if_this_hall_exist){ 
  $inserted_row_count=Crud_op::insert_new_hall_no($_POST['hall_no'],$active_sem_id);
  if($inserted_row_count!=0){
  $add_hall_into_this_semester_success="ﺗﻤﺖ ﻋﻤﻠﻴﺔ اﻹﺿﺎﻓﺔ ﺑﻨﺠﺎﺡ";
 $add_hall_into_this_semester_err=null; ?>
 
 <div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-success"><strong><?php echo $add_hall_into_this_semester_success; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
 <?php 
  }
  else{
   $add_hall_into_this_semester_success=null;
 $add_hall_into_this_semester_err="ﻟﻢ ﺗﺘﻢ ﻋﻤﻠﻴﺔ اﻟﺤﻔﻆ ﺑﻨﺠﺎﺡ"; ?>
 
 <div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger"><strong><?php echo $add_hall_into_this_semester_err; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
 <?php
  }
 }
 else{
 $add_hall_into_this_semester_success=null;
 $add_hall_into_this_semester_err="ﻫﺬﻩ اﻟﻘﺎﻋﺔ ﻣﻮﺟﻮﺩﺓ  ﻳﺮﺟﻰ اﺳﺘﺒﺪاﻟﻬﺎ"; ?>
 
 <div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger"><strong><?php echo $add_hall_into_this_semester_err; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
 <?php 
 }
  $_POST = array();
   //echo "<meta http-equiv=refresh content=\"0; URL=add_halls_into_this_semester.php\">"; 
 }//end insert if statement
  
 elseif(
 isset($_POST['edit_this_hall'])
 && isset($_POST['hall_no'])
   
 && isset($_POST['selected_room_id'])
 
 && isset($_POST['active_sem_id'])
 ){  
  $updated_row_count=Crud_op::update_specific_hall_no($_POST['selected_room_id'],$_POST['hall_no'],$_POST['active_sem_id']);
  $check_if_this_hall_exist1=Crud_op::check_if_this_hall_exist1($_POST['selected_room_id'],$_POST['hall_no']);
  if(($check_if_this_hall_exist1 )){
  if($updated_row_count){
  $add_hall_into_this_semester_success="ﺗﻤﺖ ﻋﻤﻠﻴﺔ اﻟﺘﻌﺪﻳﻞ ﺑﻨﺠﺎﺡ";
 $add_hall_into_this_semester_err=null; ?>
 
 <div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-success"><strong><?php echo $add_hall_into_this_semester_success; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
 <?php 
  }
  else{
   $add_hall_into_this_semester_err="ﻟﻢ ﺗﺘﻢ ﻋﻤﻠﻴﺔ اﻟﺘﻌﺪﻳﻞ ﺑﻨﺠﺎﺡ ﺃﻭ ﺃﻧﻚ ﺃﺑﻘﻴﺖ اﻟﻘﻴﻤﺔ اﻟﺴﺎﺑﻘﺔ ﻛﻤﺎ ﻫﻲ ";
 $add_hall_into_this_semester_success=null; ?>
 
 <div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger"><strong><?php echo $add_hall_into_this_semester_err; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
 <?php
  } 
 }
  else{
 $add_hall_into_this_semester_err="ﻫﺬﻩ اﻟﻘﺎﻋﺔ ﻣﻮﺟﻮﺩﺓ  ﻳﺮﺟﻰ اﺳﺘﺒﺪاﻟﻬﺎ";
 $add_hall_into_this_semester_success=null; ?>
 
 <div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger"><strong><?php echo $add_hall_into_this_semester_err; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
 <?php 
 }
 }//end edit statement
 elseif((isset($_REQUEST['action']) && ($_REQUEST['action'] == "add")) 
  || (isset($_REQUEST['action']) && ($_REQUEST['action'] == "edit") &&
 (isset($_GET['r_id'])&&isset($_GET['s_id']) ) &&(Crud_op::check_if_this_hall_available_in_this_semester($_GET['r_id'],$_GET['s_id'])!=null)))
 {
  //add inout type text into db and check if start word is letter and other is no at first letter and other number_format
  // and add table to make edit and delete
  ?>
  <div class="row" style="margin-bottom: 12px;">

  <div class="col-sm-8 col-sm-offset-2">
  <form method="POST" action="add_halls_into_this_semester.php">
      <div class="form-group text-center">  
   
         <label class="user_name">ﺭﻗﻢ اﻟﻘﺎﻋﺔ</label>
    
   <input type="text" id="hall_no" style="margin-bottom: 12px;direction:ltr !important;" name="hall_no" maxlength="5" class="form-control" placeholder="ﺃﺩﺧﻞ ﺭﻗﻢ اﻟﻘﺎﻋﺔ" 
   autocomplete="off" autofocus required 
   
   <?php 
   
if(isset($_REQUEST['action']) && ($_REQUEST['action'] == "edit")){
$room_id=$_GET['r_id']; 
$semester_id=$_GET['s_id']; 
  
 
 $check_if_this_hall_available_in_this_semester = Crud_op::check_if_this_hall_available_in_this_semester($room_id,$semester_id );
 if( $check_if_this_hall_available_in_this_semester!=null){
  $room_description=$check_if_this_hall_available_in_this_semester[0]['room_description'];
  
 echo'value="'.$room_description.'" >';
}
 
  
  echo '<input type="hidden" value="'. $room_id .'" name="selected_room_id" />';
  echo '<input type="hidden" value="'. $active_sem_id .'" name="active_sem_id" />';
}

elseif(isset($_REQUEST['action']) && $_REQUEST['action'] =="add"){
 echo 'value="" >';
}
  
  echo '
 <input type="submit" class="btn btn-success text-center submit"
style="margin-bottom: 12px;align-items: center;text-align: center;"

';
 
        
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == "add") {
 
 

 echo 'onsubmit="return confirm(\'ﻫﻞ ﺃﻧﺖ ﻣﺘﺄﻛﺪ ﻣﻦ ﺇﺿﺎﻓﺔ ﻫﺬﻩ اﻟﻘﺎﻋﺔ\')"';
 
 

  echo 'value="ﺇﺿﺎﻓﺔ ﻫﺬﻩ اﻟﻘﺎﻋﺔ" '.' name="add_this_hall" >';
 
           
       
        }
        
        elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == "edit" ) {
              echo 'value="ﺗﻌﺪﻳﻞ" '.' name="edit_this_hall" >';
        }
        
 

echo'
     
  </div> 
   
</div>
</div>
   
 '; 
 }
 elseif(isset($_REQUEST['action']) && ($_REQUEST['action'] == "del") &&
 (isset($_GET['r_id']) ) &&(Crud_op::check_if_this_hall_available_in_this_semester($_GET['r_id'],$_GET['s_id'])!=null)){
  try{
    $rowcount_of_del_this_hall_in_this_semester=Crud_op::del_this_hall_available_in_this_semester($_GET['r_id'],$_GET['s_id']);
  if($rowcount_of_del_this_hall_in_this_semester!=0){
  $add_hall_into_this_semester_success="ﺗﻤﺖ ﻋﻤﻠﻴﺔ اﻟﺤﺬﻑ ﺑﻨﺠﺎﺡ";
 $add_hall_into_this_semester_err=null; 
 echo'
 
 <div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-success"><strong>'.$add_hall_into_this_semester_success  .'</strong></div>
<div class="col-sm-4 "></div>
</div>
  '; 
  }
  else{
 $add_hall_into_this_semester_success=null;
 $add_hall_into_this_semester_err="ﻟﻢ ﺗﺘﻢ ﻋﻤﻠﻴﺔ اﻟﺤﺬﻑ ﺑﻨﺠﺎﺡ"; ?>
 
 <div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger"><strong><?php echo $add_hall_into_this_semester_err; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
 <?php
 }
   
  }catch(PDOException $ex){
  $add_hall_into_this_semester_success=null;
 $add_hall_into_this_semester_err="ﻫﺬﻩ اﻟﻘﺎﻋﺔ ﻣﺤﺪﺩ ﻟﻬﺎ ﻣﻮاﻋﻴﺪ ﻟﻤﻨﺎﻗﺸﺎﺕ اﻟﻄﻼﺏ ﻟﺬﻟﻚ ﻻ ﻳﻤﻜﻨﻚ ﺣﺬﻓﻬﺎ ﻣﺎ ﺩاﻡ اﻟﻤﻮﻋﺪ ﻣﻮﺟﻮﺩا"; ?>
 
 <div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger"><strong><?php echo $add_hall_into_this_semester_err; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
 <?php 
  }
 
 
 }
}
catch(Exception $ex){
 echo $ex->getMessage();
  $add_hall_into_this_semester_success="ﺣﺪﺙ ﺧﻄﺄ ﻟﺮﺑﻤﺎ ﻗﻤﺖ ﺑﺈﺩﺧﺎﻝ ﻗﺎﻋﺔ ﻣﻮﺟﻮﺩﺓ ﻫﺬا اﻟﻔﺼﻞ ﺳﻮاء ﺑﻌﺪ ﻃﻠﺐ اﻹﺿﺎﻓﺔ ﺃﻭ اﻟﺘﻌﺪﻳﻞ";
 $add_hall_into_this_semester_err=null; ?>
 
 <div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger"><strong><?php echo $add_hall_into_this_semester_err; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
 <?php
}
 ?>
</form>
<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-6 col-sm-offset-3">
<div class="table-responsive text-center " style="direction: rtl;margin-top: 12px;">          
  <table class="table  table-hover table-striped table-bordered text-center" style="align-items: center;">
<thead>
 <tr>
   <th>ﺭﻗﻢ اﻟﻘﺎﻋﺔ</th>
  
 
   <th>اﻹﺟﺮاء</th> 
 </tr>
</thead>

<tbody> 
<?php
$get_all_hall_for_active_semester = Crud_op::get_all_hall_for_active_semester();
if($get_all_hall_for_active_semester!=null){
 for($u=0;$u<count($get_all_hall_for_active_semester);$u++){
 ?> 
 
 <tr> 
 <td><?php echo $get_all_hall_for_active_semester[$u]['room_description']; ?></td>
 
 <!-- start edit&del -->
  <td> <a href="add_halls_into_this_semester.php?action=edit&r_id=<?php echo  $get_all_hall_for_active_semester[$u]['room_id']; ?>&s_id=<?php echo  $get_all_hall_for_active_semester[$u]['semesters_id_ref']; ?>"
  class="btn btn-primary btn-sm a-btn-slide-text" id="edit_idea"  style="margin-top: 3px;"">
        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
        <span><strong>ﺗﻌﺪﻳﻞ</strong></span>            
    </a>
 
 <!-- data-toggle="modal" data-target="#confirm-delete" -->
<a href="add_halls_into_this_semester.php?action=del&r_id=<?php echo  $get_all_hall_for_active_semester[$u]['room_id'];?>&s_id=<?php echo  $get_all_hall_for_active_semester[$u]['semesters_id_ref']; ?>"
  class="btn btn-danger btn-sm a-btn-slide-text" onClick="return confirm('ﻫﻞ ﺃﻧﺖَ ﻣﺘﺄﻛﺪ ﻣﻦ ﺣﺬﻑ ﻫﺬﻩ اﻟﻘﺎﻋﺔ ﻟﻬﺬا اﻟﻔﺼﻞ')" id="del_user" style="margin-top: 3px;">
        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
        <span><strong>ﺣﺬﻑ</strong></span>            
    </a>

  
 
 <!-- end edit&del -->
 </td>
 
</tr>
 <?php
}
}
else{
?>
 <tr>
  
 <!-- start edit&del -->
  <td colspan="3">ﻻ ﻳﻮﺟﺪ ﺑﻴﺎﻧﺎﺕ</td>
 <!-- end edit&del -->
 
 
</tr>
<?php 
}

?>

 
</tbody>
</table>
</div>
</div>
</div>
<?php
/**/

}
else{
 $add_hall_into_this_semester_success=null;
 $add_hall_into_this_semester_err="ﻟﻢ ﻳﺘﻢ ﺗﻔﻌﻴﻞ اﻟﻔﺼﻞ اﻟﺪﺭاﺳﻲ اﻟﺤﺎﻟﻲ";
 ?>
 
 <div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger"><strong><?php echo $add_hall_into_this_semester_err; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
 <?php
}
 
} 
 
?>
 </div>
    <!--script src="../js/jquery-1.12.4.min.js"></script-->
     <?php include('../includes/footer.php');?>