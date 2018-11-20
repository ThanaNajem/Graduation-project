<?php
    session_start();
   require_once("../db_op.php");
 
$group_admin_id=null;
date_default_timezone_set('israel');
$grp_status=null;
 $status=false;
if (isset($_SESSION["user_id"])) {
  # code...
  $student_id=$_SESSION["user_id"];

   $group_admin_id=Crud_op::check_if_he_is_an_admin_of_any_group($student_id) ;
//$group_admin_id =  $group_admin_id;
}
  
  
   if($group_admin_id!=null){
   $group_admin_id=$group_admin_id[0]['grp_id'] ;
   }  
$maximum_no_of_grp_mem=5;
$k=0;
?>
   <!DOCTYPE html>
<?php include('../includes/header.php'); ?>
<!--End fixed menu-->
<!-- </div> -->
<?php if (isset($_SESSION["user_id"]) && (!empty($_SESSION["user_id"])) && (isset($_SESSION["role"])) && (!empty($_SESSION["role"])) && ($_SESSION["role"] == 4)) {
  
if(
 isset(
  $_POST['update_this_grp_status_into_accepted_and_delete_grp_which_he_was_owner_and_add_him_as_grp_chat_member_and_delete_other_requests']) 
 || 
 isset($_POST['update_this_grp_request_status_into_accepted_and_change_grp_owner_into_oldest_member_and_remove_orginal_owner_from_previous_own_grp_and_delete_other_reuest']) 
 || 
 isset($_POST['change_request_status_into_reject_and_remove_std_from_chat_member_by_set_status_into_left']) 
 || 
 isset($_POST['update_request_status_for_this_grp_into_accepted_and_delete_other_requests_and_add_std_as_member_into_chat']) 
  
&&
(isset($_POST['id']))
){
  $owner_id=$student_id;
  $std_id=$_POST['id'];
  if(isset($_POST['grp_id']) && isset($_POST['id']) ){
    $prev_requests_member_grp_id=$_POST['grp_id'];
      $std_id=$_POST['id'];
      }
      else{ $prev_requests_member_grp_id="";   $std_id="";}
      
try{
  //start

  if(Crud_op::check_if_admin_grp_in_member_no_bounded($group_admin_id,$maximum_no_of_grp_mem)){
    /////////////
    if ( isset($_POST['update_this_grp_status_into_accepted_and_delete_grp_which_he_was_owner_and_add_him_as_grp_chat_member_and_delete_other_requests']) ) {
      # code...
      $confirm_accept_this_owner_grp_send_grp_request=Crud_op::del_its_own_grp_and_all_request_and_accept_in_this_owner_grp($std_id,$owner_id,$group_admin_id);
if ($confirm_accept_this_owner_grp_send_grp_request) {
  # code...
 $err_send_request=null;
$success_send_request="تمت اﻟﻌﻤﻠﻴﺔ ﺑﻨﺠﺎﺡ";
}else{
$err_send_request="لم تتم العملية بنجاح";
$success_send_request=null;
}
    }
    elseif ( isset($_POST['update_this_grp_request_status_into_accepted_and_change_grp_owner_into_oldest_member_and_remove_orginal_owner_from_previous_own_grp_and_delete_other_reuest']) ) {
      # code...

$row2= Crud_op::del_all_request_and_change_its_grp_owner_into_oldest_member_and_accept_in_this_grp($std_id,$group_admin_id,$owner_id,$prev_requests_member_grp_id);
if($row2){
$success_send_request="ﺗﻢ اﻧﻀﻤﺎﻣﻪ ﻟﻤﺠﻤﻮﻋﺘﻚ ﺑﻨﺠﺎﺡ";
$err_send_request=null;
}
else{
$success_send_request=null;
$err_send_request="ﻟﻢ ﻳﺘﻢ اﻧﻀﻤﺎﻣﻪ ﻟﻤﺠﻤﻮﻋﺘﻚ ﺑﻨﺠﺎﺡ"; 
}

    }
    elseif ( isset($_POST['change_request_status_into_reject_and_remove_std_from_chat_member_by_set_status_into_left']) ) {
      # code...
$row2= Crud_op::change_status_to_reject($std_id,$group_admin_id);
if($row2){
$success_send_request="ﺗﻢ اﻧﻀﻤﺎﻣﻪ ﻟﻤﺠﻤﻮﻋﺘﻚ ﺑﻨﺠﺎﺡ";
$err_send_request=null;
}
else{
$success_send_request=null;
$err_send_request="ﻟﻢ ﻳﺘﻢ اﻧﻀﻤﺎﻣﻪ ﻟﻤﺠﻤﻮﻋﺘﻚ ﺑﻨﺠﺎﺡ"; 
}
    }
    elseif (isset($_POST['update_request_status_for_this_grp_into_accepted_and_delete_other_requests_and_add_std_as_member_into_chat'])) {
      # code...
      $change_status_to_accept=Crud_op::change_status_to_accept($std_id,$group_admin_id);
     if($change_status_to_accept){
$success_send_request="ﺗﻢ ﻗﺒﻮﻝ اﻧﻀﻤﺎﻣﻪ ﻟﻤﺠﻤﻮﻋﺘﻚ ﺑﻨﺠﺎﺡ";
$err_send_request=null;
}
else{
$success_send_request=null;
$err_send_request="ﻟﻢ ﻳﺘﻢ ﻗﺒﻮﻝ اﻧﻀﻤﺎﻣﻪ ﻟﻤﺠﻤﻮﻋﺘﻚ ﺑﻨﺠﺎﺡ"; 
}
    }
    /////////// 
//end

  }
  else{
    $err_send_request="ﻋﺪﺩ ﺃﻋﻀﺎء ﻫﺬﻩ اﻟﻤﺠﻤﻮﻋﺔ ﺑﻠﻎ ﺣﺪﻩ اﻷﻗﺼﻰ ﻭ ﺣﺘﻰ ﻳﺘﺴﻨﻰ ﻟﻚ ﺇﺿﺎﻓﺔ ﺃﻱ ﻋﻀﻮ ﺟﺪﻳﺪ ﻋﻠﻴﻚ ﺣﺬﻑ ﻋﻀﻮ ﻣﻘﺒﻮﻝ ﻭاﺣﺪ ﻋﻠﻰ اﻷﻗﻞ";
    $success_send_request=null;
  }
 if (isset($_POST['change_status_to_reject'])) {
  $change_status_to_reject=Crud_op::change_status_to_reject($std_id,$group_admin_id);
  if($change_status_to_reject){
$success_send_request="ﺗﻢ ﺭﻓﺾ اﻧﻀﻤﺎﻣﻪ ﻟﻤﺠﻤﻮﻋﺘﻚ ﺑﻨﺠﺎﺡ";
$err_send_request=null;
}
else{
$success_send_request=null;
$err_send_request="ﻟﻢ ﻳﺘﻢ ﺭﻓﺾ اﻧﻀﻤﺎﻣﻪ ﻟﻤﺠﻤﻮﻋﺘﻚ ﺑﻨﺠﺎﺡ"; 
}
}
}
catch(PDOException $ex){
echo $ex->getMessage();
$success_send_request=null;
$err_send_request="ﻟﻢ ﻳﺘﻢ ﻗﺒﻮﻝ اﻻﻧﻀﻤﺎﻡ ﺑﻨﺠﺎﺡ ﺃﻭ ﻟﺮﺑﻤﺎ ﺃﻋﺪﺕ ﺗﺤﻤﻴﻞ اﻟﺼﻔﺤﺔ اﻥ ﻇﻬﺮ ﺑﻌﺪ ﻧﺠﺎﺡ اﻟﻌﻤﻠﻴﺔ ﺃﺛﻨﺎء ﺗﺤﻤﻴﻠﻚ ﻟﻠﺼﻔﺤﺔ "; 
}
$_POST = array();
	//echo "<meta http-equiv=refresh content=\"0; URL=follow_up_grp_request.php\">";
}

 
 $get_active_semester_tbl_row_count = Crud_op::get_active_semester_tbl_row_count1();

if($get_active_semester_tbl_row_count!=null) {
//   $hour=date('H');
//  $min=date('i');
//  $sec=date('s');
//  $month=date('m');
//  $day=date('d');
//  $year=date('Y');
//  $from_date = mktime($hour, $min, $sec, $month, $day, $year);
//  $to_date = mktime($hour, $min, $sec, $month, $day, $year);

// $from_date= date("Y-m-d H:i:s", $from_date);
// $to_date= date("Y-m-d H:i:s", $to_date);
if($group_admin_id!=null){

 $evt_name="create_grps";
 $arr_from_and_to_evt_date=Crud_op::get_first_and_end_date_for_evt($evt_name);
 
  if($arr_from_and_to_evt_date!=null){
 
 
 $from_date=$arr_from_and_to_evt_date[0]['from_date'];
 $to_date=$arr_from_and_to_evt_date[0]['to_date'];
 //$current_Date=date("Y-m-d H:i:s");
  $hour=date('H');
 $min=date('i');
 $sec=date('s');
 $month=date('m');
 $day=date('d');
 $year=date('Y');
 $current_Date = mktime($hour, $min, $sec, $month, $day, $year);

//$from_date = strtotime($from_date);

$from_date= date("Y-m-d H:i:s", $from_date);

//$to_date = strtotime($to_date);
$to_date= date("Y-m-d H:i:s", $to_date);

//$current_Date = strtotime($current_Date);
 
$current_Date= date("Y-m-d H:i:s", $current_Date); 
  

 
 
 if($current_Date>=$from_date && $current_Date<=$to_date){
   
$status=true;}
}

 if($status){
  

  ?>
      <div class="row" align="Center">
       <h1>ﻣﺮاﺟﻌﺔ ﻃﻠﺒﺎﺕ اﻻﻧﻀﻤﺎﻡ ﻟﻤﺠﻤﻮﻋﺘﻲ ﻟﻬﺬا اﻟﻔﺼﻞ</h1>
       <div class="row" style="margin-bottom: 12px;">
  <div class="form-group text-center">
    <div class="col-sm-4"></div>
    <div class="col-sm-4 alert alert-success">
       
  <strong>الفصل الفعّال: <?php
  if ( $get_active_semester_tbl_row_count!=null) {
  //  echo $_SESSION['active'];
 echo  $get_active_semester_tbl_row_count[0]['sem_name'].' '.$get_active_semester_tbl_row_count[0]['year_val'];
   }
   else{
    echo  'لم يتم تفعيل أي فصل بعد';
   }

  ?>
 </strong>
    </div>
    <div class="col-sm-4"></div>
</div>
</div>

                <?php
     
     $rec_count =Crud_op::get_all_grp_has_a_pending_or_accepted_request_status($student_id);
     $rec_limit=8;
     $page_count=($rec_count/$rec_limit);
     
     if( isset($_GET{'page'} ) )
     {
      $page = $_GET{'page'}-1;
      $start = ($rec_limit * $page) ;

      }else {
      $page=0;
      $start = 0;
      }

     echo '<ul class="pagination pagination-sm" style="direction:rtl !important;">';
     for($index = 0; $index<$page_count;$index=$index+1)
     {
         $index2=$index+1;
      if($index2==($page+1))
      $active="active";
      else
      $active="";
         echo "<li class='$active' style='float:right;'><a style='border-top-right-radius: 0px !important;border-bottom-right-radius: 0px !important;margin-left:2px; ' href='follow_up_grp_request.php?page=$index2'> ﺻﻔﺤﺔ$index2 </a></li>";

     }
     echo '</ul>';

   ?>
                </div> 
      
   <?php if(isset($success_send_request)){

?><div class="row" style="margin-top: 12px;" >
  <div class="col-sm-4 "></div>
<div class="col-sm-4 alert alert-success text-center"><strong><?php echo $success_send_request; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
<?php
  } 
   elseif(isset($err_send_request)){
?>
<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo $err_send_request; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
<?php
  } ?>
                      <div class="form-group">              
                <?php 
  
$row=Crud_op::get_group_requests_for_specific_group_owner($student_id,$start,$rec_limit);
         if($row!=null){
           for ($i=0;$i<count($row);$i++) {

//
            ?>
 
<div class="accordion f-group">
 <div class="header">
  <div class="title">
   <h3  > <?php echo $row[$i]['id'] ." - ".$row[$i]['fname'].' '.$row[$i]['lname']; ?></h3>
  </div>
  <form action="follow_up_grp_request.php" method="post">
<?php
//accepted grp
 $grp_id=$row[$i]['grp_id'];
$pender_std_id=$row[$i]['id']; 
/***//**/
$value_of_submit_input_depend_on_grp_row_status = null;
  $grp_status=null;

$grp_id=$row[$i]['grp_id'];
  $get_status_of_row_grp = Crud_op::get_status_of_row_grp($grp_id,$pender_std_id);
  if ($get_status_of_row_grp=="accepted") {
    # code... 
$grp_status = "مقبول";
  }
  elseif ($get_status_of_row_grp=="reject") {
    # code...
  $grp_status="مرفوض";
   }
  elseif ($get_status_of_row_grp=="pending") {
    # code...
$grp_status = "قيد الانتظار";
   }
  elseif ($get_status_of_row_grp==null) {
    # code...
$grp_status="ليس هناك طلب مُرسل لهذه المجموعة";
 
  }
   
//accepted grp
// $return_grp_send_request_prev=Crud_op::return_grp_send_request_prev($student_id,$grp_id,$maximum_no_of_grp_mem);
//  $grp_id1=$row[$i]['grp_id'];
//  $array=$return_grp_send_request_prev;
//  $key='grp_id';
//  $key_value=$grp_id1; 
//has grp that is it's own grp and don't send request to this grp row

$grp_id=$row[$i]['grp_id']; 
$check_if_he_has_a_grp = 0;
$check_if_he_has_a_grp1 = Crud_op::check_if_he_has_a_grp1($pender_std_id);
if ($check_if_he_has_a_grp1!=null) {
 
$check_if_he_has_a_grp1 = $check_if_he_has_a_grp1[0]['group_id'];
} 



  $check_if_he_has_his_own_grp = Crud_op::check_if_he_has_his_own_grp($pender_std_id); 
  $check_if_he_is_a_last_one_in_his_own_grp = Crud_op::check_if_he_is_a_last_one_in_his_own_grp($pender_std_id,$check_if_he_has_a_grp1);
 /* $check_if_this_std_send_a_request_into_another_grp = Crud_op::check_if_this_std_send_a_request_into_another_grp($grp_id,$pender_std_id);
*/  
  $get_status_of_row_grp = Crud_op::get_status_of_row_grp($grp_id,$pender_std_id);
  $value_of_submit_input_depend_on_grp_row_status = null;
  
    $on_click_val=null;
     $class_val=null;
  $name = null;
   /* start coding */
if ($check_if_he_has_a_grp1!=null) {
  # code...
if ( $check_if_he_has_his_own_grp!=0) {
  # code...
if ($check_if_he_is_a_last_one_in_his_own_grp==0) {
//has it's own group and std is a last one
/*
//std won't accepted, because he will be accepted just at one group and in this
//just for it's own group
  if ($get_status_of_row_grp=="accepted") {
    # code...
$value_of_submit_input_depend_on_grp_row_status = "حذف هذا الطلب المقبول";
$name = "delete_request_row";
 $on_click_val = "هل أنتَ متأكد من إلغاء طلب انضمامك لمجموعة " .$row[$i]['grp_name'] ;
// $grp_status = "مقبول";
  }
  */

 if ($get_status_of_row_grp=="reject") {
    # code...
  // $grp_status="مرفوض";

$value_of_submit_input_depend_on_grp_row_status = "قبول";
  $name = "update_this_grp_status_into_accepted_and_delete_grp_which_he_was_owner_and_add_him_as_grp_chat_member_and_delete_other_requests";
  $on_click_val = "هل أنتَ متأكد من رغبتك بقبول الطالب " .$row[$i]['fname'].' '.$row[$i]['lname'].' - '.$row[$i]['id'] ;
  $class_val = "btn btn-success btn-lg";
  }
  elseif ($get_status_of_row_grp=="pending") {
    # code...
// $grp_status = "قيد الانتظار";
$value_of_submit_input_depend_on_grp_row_status = "قبول";
$name = "update_this_grp_status_into_accepted_and_delete_grp_which_he_was_owner_and_add_him_as_grp_chat_member_and_delete_other_requests";
   $class_val = "btn btn-success btn-lg";
   $on_click_val = "هل أنتَ متأكد من رغبتك بقبول الطالب " .$row[$i]['fname'].' '.$row[$i]['lname'].' - '.$row[$i]['id'] ;
  }

}
else{
/* std is owner of grp, but std isnot a last one*/
 
if ($get_status_of_row_grp=="accepted") {
    # code...
$value_of_submit_input_depend_on_grp_row_status = "حذف هذا الطلب المقبول";
$name = "delete_request_row_and_give_oldest_member_owner_responsibilities";
 $on_click_val = "هل أنتَ متأكد من إلغاء طلب انضمامك لمجموعة " .$row[$i]['grp_name']."علماً بأنه بعد قبولك في المجموعة ستخويل أقدم عضو مسؤولية مالك المجموعة " ;
// $grp_status = "مقبول";
  }
   
  if ($get_status_of_row_grp=="reject") {
    # code...
  // $grp_status="مرفوض";
$value_of_submit_input_depend_on_grp_row_status = "قبول";
  $name = "update_this_grp_request_status_into_accepted_and_change_grp_owner_into_oldest_member_and_remove_orginal_owner_from_previous_own_grp_and_delete_other_reuest";
    $class_val = "btn btn-success btn-lg";
    $on_click_val = "هل أنتَ متأكد من رغبتك بقبول الطالب " .$row[$i]['fname'].' '.$row[$i]['lname'].' - '.$row[$i]['id'] ;
  }
  elseif ($get_status_of_row_grp=="pending") {
    # code...
// $grp_status = "قيد الانتظار";
$value_of_submit_input_depend_on_grp_row_status = "قبول";
  $class_val = "btn btn-success btn-lg";
  $name = "update_this_grp_request_status_into_accepted_and_change_grp_owner_into_oldest_member_and_remove_orginal_owner_from_previous_own_grp_and_delete_other_reuest"; 
   $on_click_val = "هل أنتَ متأكد من رغبتك بقبول الطالب " .$row[$i]['fname'].' '.$row[$i]['lname'].' - '.$row[$i]['id'] ;
  }
 
/**/

}

}
else{
 
/* don't have other grps, but may have request to this grp */
 
if ($get_status_of_row_grp=="accepted") {
    # code...
$value_of_submit_input_depend_on_grp_row_status = "رفض";
$name = "change_request_status_into_reject_and_remove_std_from_chat_member_by_set_status_into_left";
  $class_val = "btn btn-danger btn-lg";
 $on_click_val = "هل أنتَ متأكد من رغبتك برفض الطالب " .$row[$i]['fname'].' '.$row[$i]['lname'].' - '.$row[$i]['id'] ;
// $grp_status = "مقبول";
  }
 
  elseif ($get_status_of_row_grp=="reject") {
    # code...
  // $grp_status="مرفوض";
$value_of_submit_input_depend_on_grp_row_status = "قبول";
  $name = "update_request_status_for_this_grp_into_accepted_and_delete_other_requests_and_add_std_as_member_into_chat";
    $class_val = "btn btn-success btn-lg";
   $on_click_val = "هل أنتَ متأكد من رغبتك بقبول الطالب " .$row[$i]['fname'].' '.$row[$i]['lname'].' - '.$row[$i]['id'] ;
  }
  elseif ($get_status_of_row_grp=="pending") {
    # code...
// $grp_status = "قيد الانتظار";
$value_of_submit_input_depend_on_grp_row_status = "قبول";
  $class_val = "btn btn-success btn-lg";
$name = "update_request_status_for_this_grp_into_accepted_and_delete_other_requests_and_add_std_as_member_into_chat"; 
 $on_click_val = "هل أنتَ متأكد من رغبتك بقبول الطالب " .$row[$i]['fname'].' '.$row[$i]['lname'].' - '.$row[$i]['id'] ;
  }
  

/**/
}

}
else {
  //no grp accepted and has other requests with other grp
  /**/
/*
if ($get_status_of_row_grp=="accepted") {
    # code...
$value_of_submit_input_depend_on_grp_row_status = "حذف هذا الطلب المقبول";
$name = "delete_request_row_and_give_oldest_member_owner_responsibilities";
 $on_click_val = "هل أنتَ متأكد من إلغاء طلب انضمامك لمجموعة " .$row[$i]['grp_name']."علماً بأنه بعد قبولك في المجموعة ستخويل أقدم عضو مسؤولية مالك المجموعة " ;
// $grp_status = "مقبول";
  }
  */
  if ($get_status_of_row_grp=="reject") {
    # code...
  // $grp_status="مرفوض";
$value_of_submit_input_depend_on_grp_row_status = "قبول";
  $name = "update_request_status_for_this_grp_into_accepted_and_delete_other_requests_and_add_std_as_member_into_chat";
    $class_val = "btn btn-success btn-lg";
  $on_click_val = "هل أنتَ متأكد من رغبتك بقبول الطالب " .$row[$i]['fname'].' '.$row[$i]['lname'].' - '.$row[$i]['id'] ;
  }
  elseif ($get_status_of_row_grp=="pending") {
    # code...
// $grp_status = "قيد الانتظار";
$value_of_submit_input_depend_on_grp_row_status = "قبول";
  $class_val = "btn btn-success btn-lg";
$name = "update_request_status_for_this_grp_into_accepted_and_delete_other_requests_and_add_std_as_member_into_chat"; 
  $on_click_val = "هل أنتَ متأكد من رغبتك بقبول الطالب " .$row[$i]['fname'].' '.$row[$i]['lname'].' - '.$row[$i]['id'] ;
  }
 

  /**/
  # code...
  //if student doesn't have a grp this doesn't mean that he doesn't send request into another grps

}
  /* end coding */
 ?>
<input type="submit" 
style="min-width: 100px;" 
class="<?php echo  $class_val; ?>" 
value="<?php echo $value_of_submit_input_depend_on_grp_row_status; ?>"
 
onclick="alert(<?php echo "'".$on_click_val."'"; ?>)"
 
name="<?php echo $name; ?>"
 >
 
 
<input type="hidden" name="grp_id"<?php echo ' value="'.$row[$i]['grp_id'].'"'  ?> /> 
<input type="hidden" name="grp_name"<?php echo ' value="'.$row[$i]['grp_name'].'"'  ?> />
<input type="hidden" name="id"<?php echo ' value="'.$row[$i]['id'].'"'  ?> />
<input type="hidden" name="usr_name"<?php echo ' value="'.$row[$i]['fname'].' '.$row[$i]['lname'].'"'  ?> />


  </form>
  </div>
    <div class="content hidden">
<?php

 echo '<h2 style="margin-right:5px;">حالته مع هذه المجموعة: </h2>
    <h4 style="margin-right:8px;"> الحالة: '. $grp_status  .'</h4>';
?>
    </div>
 </div>
  
</div>
</div>
            <?php

  
            ///



 
        } 
         }
         else{

          ?>
<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php  echo 'ﻟﻴﺲ ﻟﺪﻳﻚ ﺃﻳﺎ ﻣﻦ ﻃﻠﺒﺎﺕ اﻻﻧﻀﻤﺎﻡ'; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
          <?php
           
         }
        } 
else{
  $err_send_request="غير مسموح تشكيل المجموعات هذه الفترة";
  ?>
<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo $err_send_request; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
  <?php
}

    
}
else{
 ?>
<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo "لستَ مسؤولا بأي مجموعة فلن تتمكن من عرض الطلبات"; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
 <?php
}
}
else{
 ?>
<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo "لم يتم تفعيل الفصل الدراسي بعد"; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
 <?php
}
} 
 
?> 
     <?php include('../includes/footer.php');?>