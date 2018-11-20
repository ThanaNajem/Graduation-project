<?php
    session_start();
   require_once("../db_op.php");
   $student_id=null;
   $status=false;
   date_default_timezone_set('israel');
     $grp_status=null;
if (isset($_SESSION['user_id'])) {
  # code...
   $student_id=$_SESSION['user_id'];
}

$maximum_no_of_grp_mem=5;
$k=0;
?>
   <!DOCTYPE html>
<?php include('../includes/header.php'); ?>
<!--End fixed menu-->
<!-- </div> -->
<?php if (isset($_SESSION["user_id"]) && (!empty($_SESSION["user_id"])) && (isset($_SESSION["role"])) && (!empty($_SESSION["role"])) && ($_SESSION["role"] == 4)) {

 $get_active_semester = Crud_op::get_active_semester_tbl_row_count1();
if($get_active_semester!=null) {
 
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
 //var_dump($current_Date>=$from_date && $current_Date<=$to_date);
// }
 
 if($current_Date>=$from_date && $current_Date<=$to_date){
   
$status=true;}
}

if ($status) {
  # code...
 
  ?>
<?php
if(
 isset($_POST['update_reject_status_into_pending']) || isset($_POST['delete_request_row'])|| isset($_POST['insert_a_new_pending_request'])
&&
(isset($_POST['grp_id']))
){
 $grp_id=$_POST['grp_id'];
 $grp_name=$_POST['grp_name'];
try{
if(isset($_POST['insert_a_new_pending_request'])){
$confirm_send_grp_request=Crud_op::send_a_join_request($grp_id,$student_id);
if($confirm_send_grp_request>0){
$success_send_request="تم إرسال طلب انضمامك لمجموعة ".$grp_name." بنجاح";
$err_send_request=null;
}
else{
$success_send_request=null;
$err_send_request="لم يتم إرسال طلب انضمامك لمجموعة ".$grp_name." بنجاح"; 
} 
}

elseif (isset($_POST['delete_request_row'])) {
$row2= Crud_op::delete_grp_req($student_id,$grp_id);
if($row2>0){
$success_send_request="تم إلغاء طلب انضمامك لمجموعة ".$grp_name." بنجاح";
$err_send_request=null;
}
else{
$success_send_request=null;
$err_send_request="لم يتم إلغاء طلب انضمامك لمجموعة ".$grp_name." بنجاح"; 
}

}

elseif (isset($_POST['update_reject_status_into_pending'])) {
$row2= Crud_op::update_reject_status_into_pending($student_id,$grp_id);
if($row2>0){
$success_send_request="تم إرسال طلب انضمامك لمجموعة ".$grp_name." بنجاح";
$err_send_request=null;
}
else{
$success_send_request=null;
$err_send_request="لم يتم إرسال طلب انضمامك لمجموعة ".$grp_name." بنجاح"; 
}

}
}
catch(PDOException $ex){
echo $ex->getMessage();
$success_send_request=null;
$err_send_request="ـﺏ ﺓﺎﻤﺴﻤﻟا ﺔﻋﻮﻤﺠﻤﻟا ﺲﻔﻨﻟ ﻡﺎﻤﻀﻧﻻا ﺐﻠﻃ ﺭﺮﻜﺗ ﻻ ".$grp_name." ﺎﻣ ﺄﻄﺧ ﻞﺼﺣ ﻪﻧﺃ ﻭﺄﻣﺎﻤﺿﻻا ﺔﺤﻔﺼﻟ ﻚﻠﻴﻤﺤﺗ ﺓﺩﺎﻋﺈﺑ ﻞﻴﻠﻗ ﺬﻨﻣ ﺎﻬﻟ ﻡﺎﻤﻀﻧا ﺐﻠﻃ ﻝﺎﺳﺭﺈﺑ ﺖﻤﻗ ﺚﻴﺣ "; 
}

 //echo "<meta http-equiv=refresh content=\"0; URL=join_into_another_grp.php\">";
 $_POST = array();
}
?>
<!-- 
<div class="container" style="width:100%; text-align:right; direction:rtl"> -->


      <div class="row" align="Center">
       <h1 style="color: #1e527f;">الانضمام إلى مجموعات أخرى لهذا الفصل</h1>
       <div class="row" style="margin-bottom: 12px;">
  <div class="form-group text-center">
    <div class="col-sm-4"></div>
    <div class="col-sm-4 alert alert-success">
       
  <strong>الفصل الفعّال: <?php
  if ( $get_active_semester!=null) {
  //  echo $_SESSION['active'];
 echo  $get_active_semester[0]['sem_name'].' '.$get_active_semester[0]['year_val'];
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
     
     $rec_count =Crud_op::get_all_grp_has_less_than_five_members($student_id,$maximum_no_of_grp_mem);
     $rec_limit=8;
     $page_count=floor($rec_count/$rec_limit);
     
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
         echo "<li class='$active' style='float:right;'><a style='border-top-right-radius: 0px !important;border-bottom-right-radius: 0px !important;margin-left:2px; ' href='join_into_another_grp.php?page=$index2'> صفحة $index2 </a></li>";

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
  }   elseif(isset($err_send_request)){
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
 
$row=Crud_op::get_specific_no_of_grp_member_row($student_id,$maximum_no_of_grp_mem,$start,$rec_limit);
         if($row!=null){
           for ($i=0;$i<count($row);$i++) {

//
            ?>
 <!---->
 

 <!---->
<div class="accordion f-group">
 <div class="header">
  <div class="title">
    <?php 
$value_of_submit_input_depend_on_grp_row_status = null;
  $grp_status=null;

$grp_id=$row[$i]['grp_id'];
  $get_status_of_row_grp = Crud_op::get_status_of_row_grp($grp_id,$student_id);
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
  
    ?>
   <h3 class="close"> <?php echo $row[$i]['grp_name']; ?></h3>
  </div>
  <form action="join_into_another_grp.php" method="post">
<?php
//accepted grp
// $return_grp_send_request_prev=Crud_op::return_grp_send_request_prev($student_id,$grp_id,$maximum_no_of_grp_mem);
//  $grp_id1=$row[$i]['grp_id'];
//  $array=$return_grp_send_request_prev;
//  $key='grp_id';
//  $key_value=$grp_id1; 
//has grp that is it's own grp and don't send request to this grp row
 
$grp_id=$row[$i]['grp_id'];
/**/
$check_if_he_has_a_grp1 = 0;
$check_if_he_has_a_grp1 = Crud_op::check_if_he_has_a_grp1($student_id);
if ($check_if_he_has_a_grp1!=null) {
 
$check_if_he_has_a_grp1 = $check_if_he_has_a_grp1[0]['group_id'];
}

/**/
$check_if_he_has_a_grp = Crud_op::check_if_he_has_a_grp($student_id);
  $check_if_he_has_his_own_grp = Crud_op::check_if_he_has_his_own_grp($student_id);
  $check_if_he_is_a_last_one_in_his_own_grp = Crud_op::check_if_he_is_a_last_one_in_his_own_grp($student_id,$check_if_he_has_a_grp1);
 /* $check_if_this_std_send_a_request_into_another_grp = Crud_op::check_if_this_std_send_a_request_into_another_grp($grp_id,$student_id);
*/
 // $get_status_of_row_grp = Crud_op::get_status_of_row_grp($grp_id,$student_id);
  $value_of_submit_input_depend_on_grp_row_status = null;

  $name = null;
   /* start coding */
if ($check_if_he_has_a_grp>0) {
  # code...
if ( $check_if_he_has_his_own_grp>0) {
  # code...
if ($check_if_he_is_a_last_one_in_his_own_grp==0) {
//has it's own group and std is a last one
 
 if ($get_status_of_row_grp=="reject") {
    # code...
  // $grp_status="مرفوض";
$value_of_submit_input_depend_on_grp_row_status = "إعادة إرسال لهذا الطلب المرفوض";
  $name = "update_reject_status_into_pending";
  $on_click_val = "هل أنتَ متأكد من رغبتك بإعادة إرسال طلب انضمامك لمجموعة " .$row[$i]['grp_name']." علماً بأنه بعد قبولك بالمجموعة ستتمكن من مراسلة أعضاء مجموعتك المقبول بها و سيتم حذف باقي طلبات الانضمام و باعتبارك آخر عضو في مجموعتك فسيتم حذفها " ;
  $class_val = "btn btn-success btn-lg";
  }
  elseif ($get_status_of_row_grp=="pending") {
    # code...
// $grp_status = "قيد الانتظار";
$value_of_submit_input_depend_on_grp_row_status = "إلغاء إرسال هذا الطلب قيد الانتظار";
$name = "delete_request_row";
   $class_val = "btn btn-danger btn-lg";
  $on_click_val = "هل أنتَ متأكد من رغبتك بإلغاء طلب انضمامك لمجموعة " .$row[$i]['grp_name'] ." ليتم حذف الطلب ";
  }
  elseif ($get_status_of_row_grp==null) {
    # code...
// $grp_status="ليس هناك طلب مُرسل لهذه المجموعة";
$value_of_submit_input_depend_on_grp_row_status = "إرسال طلب انضمام لهذه المجموعة"; 
  $class_val = "btn btn-success btn-lg";
$name = "insert_a_new_pending_request";
$on_click_val = "هل أنتَ متأكد من رغبتك بإرسال طلب انضمامك لمجموعة " .$row[$i]['grp_name']." علماً بأنه بعد قبولك بالمجموعة ستتمكن من مراسلة أعضاء مجموعتك المقبول بها و سيتم حذف باقي طلبات الانضمام  و باعتبارك آخر عضو في مجموعتك فسيتم حذفها  " ;
    
  }
  
}
else{
/* std is owner of grp, but std isnot a last one*/
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
$value_of_submit_input_depend_on_grp_row_status = "إعادة إرسال لهذا الطلب المرفوض";
  $name = "update_reject_status_into_pending";
    $class_val = "btn btn-success btn-lg";
  $on_click_val = "هل أنتَ متأكد من رغبتك بإعادة إرسال طلب انضمامك لمجموعة " .$row[$i]['grp_name']." علماً بأنه بعد قبولك في المجموعة سيتخوّل أقدم عضو مسؤولية مالك المجموعة و سيتم حذف باقي طلبات الانضمام " ;
  }
  elseif ($get_status_of_row_grp=="pending") {
    # code...
// $grp_status = "قيد الانتظار";
$value_of_submit_input_depend_on_grp_row_status = "إلغاء إرسال هذا الطلب قيد الانتظار";
  $class_val = "btn btn-danger btn-lg";
$name = "delete_request_row"; 
  $on_click_val = "هل أنتَ متأكد من رغبتك بحذف طلب انضمامك لمجموعة " .$row[$i]['grp_name'] ;
  }
  elseif ($get_status_of_row_grp==null) {
    # code...
// $grp_status="ليس هناك طلب مُرسل لهذه المجموعة";
$value_of_submit_input_depend_on_grp_row_status = "إرسال طلب انضمام لهذه المجموعة"; 
  $class_val = "btn btn-success btn-lg";
$name = "insert_a_new_pending_request";
$on_click_val = "هل أنتَ متأكد من رغبتك بإرسال طلب انضمامك لمجموعة " .$row[$i]['grp_name']." علماً بأنه بعد قبولك في المجموعة سيتخوّل أقدم عضو مسؤولية مالك المجموعة و سيتم حذف باقي طلبات الانضمام" ;
    
  }
/**/

}

}
else{
 
/**/
 
if ($get_status_of_row_grp=="accepted") {
    # code...
$value_of_submit_input_depend_on_grp_row_status = "حذف هذا الطلب المقبول";
$name = "delete_request_row";
  $class_val = "btn btn-danger btn-lg";
 $on_click_val = "هل أنتَ متأكد من حذف طلب انضمامك لمجموعة " .$row[$i]['grp_name'] ;
// $grp_status = "مقبول";
  }
 
  elseif ($get_status_of_row_grp=="reject") {
    # code...
  // $grp_status="مرفوض";
$value_of_submit_input_depend_on_grp_row_status = "إعادة إرسال لهذا الطلب المرفوض";
  $name = "update_reject_status_into_pending";
    $class_val = "btn btn-success btn-lg";
  $on_click_val = "هل أنتَ متأكد من رغبتك بإعادة إرسال طلب انضمامك لمجموعة " .$row[$i]['grp_name']." علماً بأنه بعد قبولك في المجموعة سيتم حذف باقي طلبات الانضمام و ستتمكن من مراسلة أعضاء المجموعة المقبول بها " ;
  }
  elseif ($get_status_of_row_grp=="pending") {
    # code...
// $grp_status = "قيد الانتظار";
$value_of_submit_input_depend_on_grp_row_status = "إلغاء إرسال هذا الطلب قيد الانتظار";
  $class_val = "btn btn-danger btn-lg";
$name = "delete_request_row"; 
  $on_click_val = "هل أنتَ متأكد من رغبتك بحذف إرسال طلب انضمامك لمجموعة " .$row[$i]['grp_name'] ;
  }
  elseif ($get_status_of_row_grp==null) {
    # code...
// $grp_status="ليس هناك طلب مُرسل لهذه المجموعة";
$value_of_submit_input_depend_on_grp_row_status = "إرسال طلب انضمام لهذه المجموعة"; 
$name = "insert_a_new_pending_request";
  $class_val = "btn btn-success btn-lg";
$on_click_val = "هل أنتَ متأكد من رغبتك بإرسال طلب انضمامك لمجموعة " .$row[$i]['grp_name']." علماً بأنه بعد قبولك في المجموعة  سيتم حذف باقي طلبات الانضمام و ستتمكن من مراسلة أعضاء مجموعتك بعد إنضمامك لمحادثتها تلقائيا بعد قبولك" ;
    
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
$value_of_submit_input_depend_on_grp_row_status = "إعادة إرسال لهذا الطلب المرفوض";
  $name = "update_reject_status_into_pending";
    $class_val = "btn btn-success btn-lg";
  $on_click_val = "هل أنتَ متأكد من رغبتك بإعادة إرسال طلب انضمامك لمجموعة " .$row[$i]['grp_name']." علماً بأنه بعد قبولك في المجموعة  سيتم حذف باقيطلبات الانضمام و سيتم انضمامك بشكل تلقائي إلى محادثة الجروب المشتركة " ;
  }
  elseif ($get_status_of_row_grp=="pending") {
    # code...
// $grp_status = "قيد الانتظار";
$value_of_submit_input_depend_on_grp_row_status = "إلغاء إرسال هذا الطلب قيد الانتظار";
  $class_val = "btn btn-danger btn-lg";
$name = "delete_request_row"; 
  $on_click_val = "هل أنتَ متأكد من رغبتك بإلغاء إرسال طلب انضمامك لمجموعة " .$row[$i]['grp_name'] ;
  }
  elseif ($get_status_of_row_grp==null) {
    # code...
// $grp_status="ليس هناك طلب مُرسل لهذه المجموعة";
$value_of_submit_input_depend_on_grp_row_status = "إرسال طلب انضمام لهذه المجموعة"; 
$name = "insert_a_new_pending_request";
  $class_val = "btn btn-success btn-lg";
$on_click_val = "هل أنتَ متأكد من رغبتك بإرسال طلب انضمامك لمجموعة " .$row[$i]['grp_name']." علماً بأنه بعد قبولك في المجموعة  سيتم حذف باقي طلبات الانضمام و سيتم انضمامك بشكل تلقائي إلى محادثة الجروب المشتركة" ;
    
  }

  /**/
  # code...
  //if student doesn't have a grp this doesn't mean that he doesn't send request into another grps

}
  /* end coding */
 ?>
<input type="submit" 
style="min-width: 201px;" 
class="<?php echo  $class_val; ?>" 
value="<?php echo $value_of_submit_input_depend_on_grp_row_status; ?>"
 
onclick="alert(<?php echo "'".$on_click_val."'"; ?>)"
 
name="<?php echo $name; ?>"
 >
<input type="hidden" name="grp_id"<?php echo 'value="'.$row[$i]['grp_id'].'"'  ?> /> 
<input type="hidden" name="grp_name"<?php echo 'value="'.$row[$i]['grp_name'].'"'  ?> />
  </form>
 </div>
 
  <div class="content hidden">
 
  
 <?php 
 
 echo '<h2 style="margin-right:5px;">مسؤول المجموعة: </h2>
    <h4 style="margin-right:8px;"> اسم الطالب: '.$row[$i]['std_name'].' -  رقمه: '.$row[$i]['owner'].'</h4>';
    echo '<hr>';
echo '<h2 style="margin-right:5px;">أعضاء المجموعة</h2>';
echo '<ul>';

    $group_id=$row[$i]['grp_id'];
$grp_member=Crud_op::get_grp_member_for_specific_grp($group_id);
for($j=0;$j<count($grp_member);$j++){

echo'<li> اسم الطالب: '.$grp_member[$j]['usr_id'].'  --  رقمه: '.$grp_member[$j]['name'].'</li>';
 
}echo ' </ul>  <hr> <h2 style="margin-right:5px;">حالتك مع هذه المجموعة: </h2> 
    <h4 style="margin-right:8px;"> الحالة: '. $grp_status  .'</h4> ';
    echo '<hr>';
    
 


 
  ?>

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
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo "لا يتوفر مجموعات لهذا الفصل"; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
          <?php
         }
       }
        else{
        ?>
<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo "غير مسموح تشكيل مجموعات هذه الفترة"; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
    <?php  }
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