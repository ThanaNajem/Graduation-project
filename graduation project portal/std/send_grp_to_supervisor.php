<?php
       session_start();
    require_once("../db_op.php");
    date_default_timezone_set('israel');
    
    $maximum_no_of_supervisor_for_each_grp = 2;
    $status=false;

?>
   <!DOCTYPE html>
<?php include('../includes/header.php'); ?>
<!--End fixed menu-->
<!-- </div> -->
<?php if (isset($_SESSION["user_id"]) && (!empty($_SESSION["user_id"])) && (isset($_SESSION["role"])) && (!empty($_SESSION["role"])) && ($_SESSION["role"] == 4)) {
$usr_id=$_SESSION["user_id"];
$check_if_he_has_a_grp=Crud_op::check_if_he_has_a_grp($usr_id);
 $get_active_semester_tbl_row_count = Crud_op::get_active_semester_tbl_row_count1();

if($get_active_semester_tbl_row_count!=null){
//start
  
   $evt_name="join_into_supervisor";
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
   
$status=true;
}
}
 
// var_dump($current_Date>=$from_date && $current_Date<=$to_date);

 if($status){
    // }
 if($check_if_he_has_a_grp!=0){
$check_if_this_usr_admin_of_this_grp=Crud_op::check_if_he_is_an_admin_of_any_group($usr_id);
if($check_if_this_usr_admin_of_this_grp!=null){
 $grp_id = $check_if_this_usr_admin_of_this_grp[0]['grp_id'];
  //
if (
  isset($_POST['add_new_pending_request_into_supervision_tbl'])
||
isset($_POST['cancel_pending_joinig_request_in_to_this_supervisor'])
||
isset($_POST['update_reject_to_pending_request'])
||
isset($_POST['do_nothing'])
) {//start of post condition
  # code...
  try {
   
  if (
    isset($_POST['supervisor_id'])
 
  ) {
    # code...
    $supervisor_id=$_POST['supervisor_id'];

 if(isset($_POST['add_new_pending_request_into_supervision_tbl'])){
 $add_new_pending_request_into_supervision_tbl= Crud_op::add_new_pending_request_into_supervision_tbl($grp_id,$supervisor_id);
if ($add_new_pending_request_into_supervision_tbl==0) {
  # code...
  $sucess_send_into_supervisor_request = null;
  $err_send_into_supervisor_request = "ﻟﻢ ﻳﺘﻢ ﺇﺭﺳﺎﻝ ﻃﻠﺒﻚ ﺑﻨﺠﺎﺡ";
}
else{
  $sucess_send_into_supervisor_request = "ﺗﻢ ﺇﺭﺳﺎﻝ ﻃﻠﺒﻚ ﺑﻨﺠﺎﺡ";
  $err_send_into_supervisor_request = null;
 
}


}//end if
elseif(isset($_POST['do_nothing'])){
	  $sucess_send_into_supervisor_request = null;
  $err_send_into_supervisor_request = "تجاوزت الحد المسموح به من المشرفين المرسل إليهم لتتمكن من الإرسال إلى المشرفين الغِ طلبا واحدا على الأقل لمن أرسلتَ لهم";
}
elseif (isset($_POST['cancel_pending_joinig_request_in_to_this_supervisor'])) {
  # code...
  $cancel_pending_joinig_request_in_to_this_supervisor = Crud_op::cancel_pending_joinig_request_in_to_this_supervisor($grp_id,$supervisor_id);

if ($cancel_pending_joinig_request_in_to_this_supervisor==0) {
  # code...
  $sucess_send_into_supervisor_request = null;
  $err_send_into_supervisor_request = "ﻟﻢ ﻳﺘﻢ ﺇﻟﻐﺎء ﻃﻠﺒﻚ ﺑﻨﺠﺎﺡ";
}
else{
  $sucess_send_into_supervisor_request = "ﺗﻢ ﺇﻟﻐﺎء ﻃﻠﺒﻚ ﺑﻨﺠﺎﺡ";
  $err_send_into_supervisor_request = null;
 
}
}
elseif (isset($_POST['update_reject_to_pending_request'])) {
  # code...
  $update_reject_to_pending_request=Crud_op::update_reject_to_pending_request($grp_id,$supervisor_id);
  if ($update_reject_to_pending_request==0) {
  # code...
  $sucess_send_into_supervisor_request = null;
  $err_send_into_supervisor_request = "ﻟﻢ ﻳﺘﻢ ﺇﺭﺳﺎﻝ ﻃﻠﺒﻚ ﺑﻨﺠﺎﺡ";
}
else{
  $sucess_send_into_supervisor_request = "ﺗﻢ ﺇﺭﺳﺎﻝ ﻃﻠﺒﻚ ﺑﻨﺠﺎﺡ";
  $err_send_into_supervisor_request = null;
 
}
}
    }
  
  

    
  }
  catch (PDOException $e) {
    echo $e->getMessage();
  } 
$_POST = array();
	//echo "<meta http-equiv=refresh content=\"0; URL=send_grp_to_supervisor.php\">";
  }//end of post condition
  ?>
  

 
 
<div class="row" align="Center">
       <h1 style="color: #1e527f;">اﻻﻧﻀﻤﺎﻡ ﺇﻟﻰ اﻟﻤﺸﺮﻓﻴﻦ ﻫﺬا اﻟﻔﺼﻞ</h1>
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
     ///
                 if(isset($sucess_send_into_supervisor_request)){
?><div class="row" style="margin-top: 12px;" >
  <div class="col-sm-4 "></div>
<div class="col-sm-4 alert alert-success text-center"><strong><?php echo $sucess_send_into_supervisor_request; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
<?php
  }   elseif(isset($err_send_into_supervisor_request)){
?>
<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo $err_send_into_supervisor_request; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
<?php
  }
  ?>

  <?php
                ///
    $rec_count=Crud_op::get_all_regular_teacher_status();
     $rec_limit=8;
     $page_count=floor($rec_count/$rec_limit);
     
     if( isset($_POST{'page'} ) )
     {
      $page = $_POST{'page'}-1;
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
         echo "<li class='$active' style='float:right;'><a style='border-top-right-radius: 0px !important;border-bottom-right-radius: 0px !important;margin-left:2px; ' href='send_grp_to_supervisor.php?page=$index2'> ﺻﻔﺤﺔ$index2 </a></li>";

     }
     echo '</ul>';
   $get_sub_supervisor_counter_for_this_semester = Crud_op::get_sub_regular_teacher_status($start,$rec_limit) ;

if($get_sub_supervisor_counter_for_this_semester!=null){
  $grp_id=$check_if_this_usr_admin_of_this_grp[0]['grp_id'];
for($i=0;$i<count($get_sub_supervisor_counter_for_this_semester);$i++){
?>
<div class="accordion f-group"><!-- Start accordation div -->
  <div class="header">
    <div class="title">
      <h3 class="close"><?php echo $get_sub_supervisor_counter_for_this_semester[$i]['usr_id'] .' - ' . $get_sub_supervisor_counter_for_this_semester[$i]['fname'] . ' '.$get_sub_supervisor_counter_for_this_semester[$i]['lname']; ?></h3>
    </div>
<!--Start input type submit-->

   

    <?php 
   $supervisor_id = $get_sub_supervisor_counter_for_this_semester[$i]['usr_id'];
   $supervisor_full_name = $get_sub_supervisor_counter_for_this_semester[$i]['fname'].' '.$get_sub_supervisor_counter_for_this_semester[$i]['lname'];

   $get_supervisor_status_for_this_grp = Crud_op::get_supervisor_status_for_this_grp($grp_id,$supervisor_id);
//first
   //check maximum no of dupervisor grp
$get_no_of_supervisor_of_these_grp=Crud_op::get_no_of_supervisor_of_these_grp($grp_id);
//start
?>
 
<form action="send_grp_to_supervisor.php" method="post"> 
<?php
if ($get_supervisor_status_for_this_grp==null) {
  # code...
  if($get_no_of_supervisor_of_these_grp>=0 && 
  $get_no_of_supervisor_of_these_grp < $maximum_no_of_supervisor_for_each_grp){

  ?>
   <input type="submit" class="btn btn-success" 
 onClick="return confirm('ﻫﻞ ﺗﺮﻏﺐ ﺑﺈﺭﺳﺎﻝ ﻃﻠﺐ اﻻﻧﻀﻤﺎﻡ ﻟﻠﻤﺸﺮﻑ  <?php echo $supervisor_id.' '.$supervisor_full_name; ?> ﻋﻠﻤﺎ ﺑﺄﻧﻪ ﺑﻤﺠﺮﺩ ﻗﺒﻮﻝ ﻃﻠﺒﻚ ﻭ ﻭﺻﻮﻟﻚ ﻟﻠﺤﺪ اﻷﻗﺼﻰ ﻟﺘﺸﻜﻴﻞ اﻟﻤﺠﻤﻮﻋﺎﺕ ﻭ ﻫﻮ <?php echo $maximum_no_of_supervisor_for_each_grp; ?> ﻓﺴﻴﺘﻢ ﺣﺬﻑ ﺑﺎﻗﻲ اﻟﻄﻠﺒﺎﺕ')"
    value="ﺇﺭﺳﺎﻝ ﻃﻠﺐ اﻧﻀﻤﺎﻡ ﻟﻬﺬا اﻟﻤﺸﺮﻑ" name="add_new_pending_request_into_supervision_tbl">
  <?php
}else{

  ?>
   <input type="submit" class="btn btn-success" 
onClick="return confirm('ﻟﻦ ﺗﺘﻤﻜﻦ ﻣﻦ اﻻﻧﻀﻤﺎﻡ ﻟﻠﻤﺮﻑ <?php echo $supervisor_id.' '.$supervisor_full_name; ?> ﻷﻧﻚ ﺗﺠﺎﻭﺯﺕ ﺃﻗﺼﻰ ﺣﺪ ﻣﺴﻤﻮﺡ ﺑﻪ ﻟﻌﺪﺩ ﻣﺸﺮﻓﺮﻱ اﻟﻤﺸﺮﻭﻉ')"
name="do_nothing" value="ﺇﺭﺳﺎﻝ ﻃﻠﺐ اﻧﻀﻤﺎﻡ ﻟﻬﺬا اﻟﻤﺸﺮﻑ">
  <?php
}
}
elseif ($get_supervisor_status_for_this_grp=="pending") {
  # code...
  ?>
   <input type="submit" class="btn btn-danger" 
   onClick="return confirm('ﻫﻞ ﺃﻧﺖ ﻣﺘﺄﻛﺪ ﻣﻦ ﺇﻟﻐﺎء ﻃﻠﺐ اﻻﻧﻀﻤﺎﻡ ﻟﻠﻤﺸﺮﻑ  <?php echo $supervisor_id.' '.$supervisor_full_name; ?>')"
 value="ﺇﻟﻐﺎء ﺇﺭﺳﺎﻝ ﻃﻠﺐ اﻧﻀﻤﺎﻡ ﻟﻬﺬا اﻟﻤﺸﺮﻑ" name="cancel_pending_joinig_request_in_to_this_supervisor">
  <?php
}
elseif ($get_supervisor_status_for_this_grp=="reject") {
  # code...
if($get_no_of_supervisor_of_these_grp>=0 && 
  $get_no_of_supervisor_of_these_grp < $maximum_no_of_supervisor_for_each_grp){

  ?>
   <input type="submit" class="btn btn-success" 
   onClick="return confirm('ﻫﻞ ﺃﻧﺖ ﻣﺘﺄﻛﺪ ﻣﻦ ﺇﺭﺳﺎﻝ ﻃﻠﺐ اﻧﻀﻤﺎﻣﻚ ﻟﻠﻤﺸﺮﻑ  <?php echo $supervisor_id.' '.$supervisor_full_name; ?> ﻋﻠﻤﺎ ﺑﺄﻧﻚ ﺳﺒﻘﺖ ﻭ ﺃﺭﺳﻠﺖ ﻭ ﺗﻢ ﺭﻓﻀﻚ ﻳﺮﺟﻰ ﻣﺮاﺟﻌﺔ ﻫﺬا اﻟﻤﺸﺮﻑ ﻟﻤﻌﺮﻓﺔ اﻟﺴﺒﺐ ﻋﻠﻤﺎ ﺑﺄﻧﻪ ﺑﻤﺠﺮﺩ ﻗﺒﻮﻝ ﻃﻠﺒﻚ ﻭ ﻭﺻﻮﻟﻚ ﻟﻠﺤﺪ اﻷﻗﺼﻰ ﻟﺘﺸﻜﻴﻞ اﻟﻤﺠﻤﻮﻋﺎﺕ ﻭ ﻫﻮ <?php echo $maximum_no_of_supervisor_for_each_grp; ?> ﻓﺴﻴﺘﻢ ﺣﺬﻑ ﺑﺎﻗﻲ اﻟﻄﻠﺒﺎﺕ ')"
name="update_reject_to_pending_request" value="ﺇﺭﺳﺎﻝ ﻃﻠﺐ اﻧﻀﻤﺎﻡ ﻟﻬﺬا اﻟﻤﺸﺮﻑ" >

   <?php
}
  else{
    ?>
     <input type="submit" class="btn btn-success" 
 onClick="return confirm('ﻟﻦ ﺗﺘﻤﻜﻦ ﻣﻦ اﻻﻧﻀﻤﺎﻡ ﻟﻠﻤﺵﺮﻑ <?php echo $supervisor_id.' '.$supervisor_full_name; ?> ﻷﻧﻚ ﺗﺠﺎﻭﺯﺕ ﺃﻗﺼﻰ ﺣﺪ ﻣﺴﻤﻮﺡ ﺑﻪ ﻟﻌﺪﺩ ﻣﺸﺮﻓﺮﻱ اﻟﻤﺸﺮﻭﻉ')"
name="do_nothing" value="ﺇﺭﺳﺎﻝ ﻃﻠﺐ اﻧﻀﻤﺎﻡ ﻟﻬﺬا اﻟﻤﺸﺮﻑ" >

    <?php
  }
  ?>



 
  <?php
}
elseif ($get_supervisor_status_for_this_grp=="accepted") {
  # code...
  ?>
   <input type="submit" class="btn btn-danger" 
   onClick="return confirm('ﻫﻞ ﺃﻧﺖَ ﻣﺘﺄﻛﺪ ﻣﻦ ﺇﻟﻐﺎء ﻃﻠﺐ اﻧﻀﻤﺎﻣﻚ ﻟﻠﻤﺸﺮﻑ  <?php echo $supervisor_id.' '.$supervisor_full_name; ?>')"
value="ﺇﻟﻐﺎء ﺇﺭﺳﺎﻝ ﻃﻠﺐ اﻧﻀﻤﺎﻡ ﻟﻬﺬا اﻟﻤﺸﺮﻑ" name="cancel_pending_joinig_request_in_to_this_supervisor">
  
  <?php
}
//end-for-start

    ?>
  
 <input type="hidden" name="supervisor_id" value="<?php echo $get_sub_supervisor_counter_for_this_semester[$i]['usr_id'];?>" />
<input type="hidden" name="usr_name" value="<?php echo $get_sub_supervisor_counter_for_this_semester[$i]['fname'].' '.$get_sub_supervisor_counter_for_this_semester[$i]['lname'] ;?>" />
<input type="hidden" name="grp_id" value="<?php echo $grp_id ;?>" />
</form>
  </div>
  <div class="content hidden">
    <h2>حالة مجموعتك مع هذا المشرف: 
    <!-- <h4>اﺳﻢ ﻣﺴﺆﻭﻝ اﻟﻤﺠﻤﻮﻋﺔ</h4>
    <h2>ﺃﻋﻀﺎء اﻟﻤﺠﻤﻮﻋﺔ</h2> -->
    
        <?php
        $status="";
if ($get_supervisor_status_for_this_grp==null) {
  $status="لم يتم إرسال طلب لهذا المشرف";
}
elseif ($get_supervisor_status_for_this_grp=="pending") {
 $status="قيد الانتظار";
}
elseif ($get_supervisor_status_for_this_grp=="reject") {
 $status="مرفوض";
}
elseif ($get_supervisor_status_for_this_grp=="accepted") {
 $status="مقبول";
}
     echo $status;   ?>

      </h2>
  </div>


</div> <!-- End accordation div -->

<?php
}
}
    ?>

                </div> 

 
 

<!-- </form> -->
 <?php
}
else{
  $err_send_into_supervisor_request='ﻟﺴﺖَ ﻣُﻨﺸﺌﺎ ﻷﻱ ﻣﺠﻤﻮﻋﺔ ﻟﺬا ﻻ ﻳﻤﻜﻨﻚ ﺇﺭﺳﺎﻝ ﻃﻠﺐ اﻧﻀﻤﺎﻡ ﻟﻠﻤﺸﺮﻓﻴﻦ';
?>
<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo $err_send_into_supervisor_request; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
<?php
  }
}
else{
  $err_send_into_supervisor_request='ﻟﺴﺖَ ﻣُﻨﻀﻤّﺎ ﻷﻱ ﻣﺠﻤﻮﻋﺔ ، ﻟﺬا ﻟﻦ ﺗﺘﻤﻜﻦ ﻣﻦ ﺭﺅﻳﺔ ﻣﺸﺮﻓﻲ ﻫﺬا اﻟﻔﺼﻞ';
  ?>
<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo $err_send_into_supervisor_request; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
  <?php
 
}
}
else{
  $err_send_into_supervisor_request='اﻻﻧﻀﻤﺎﻡ ﺇﻟﻰ اﻟﻤﺸﺮﻓﻴﻦ ﻏﻴﺮ ﻣﺴﻤﻮﺡ ﺑﻪ ﻫﺬﻩ اﻟﻔﺘﺮﺓ';
  ?>
<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo $err_send_into_supervisor_request; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
  <?php
  
}
//end
}
else{

  $err_send_into_supervisor_request='ﻟﻢ ﻳﺘﻢ ﺗﻔﻌﻴﻞ ﻫﺬا اﻟﻔﺼﻞ اﻟﺪﺭاﺳﻲ ﻳﺮﺟﻰ ﻣﺮاﺟﻌﺔ ﻣﺴﺆﻭﻝ اﻟﻤﻮﻗﻊ';
  ?>
<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo $err_send_into_supervisor_request; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
  <?php
  
 }


 } 
 
?>
    <!--script src="../js/jquery-1.12.4.min.js"></script-->
     <?php include('../includes/footer.php');?>