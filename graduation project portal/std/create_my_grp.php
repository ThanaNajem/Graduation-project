<?php 
    session_start();
    require_once("../db_op.php");
    $get_active_semester=Crud_op::get_active_semester();
    $status=false;
$usr_id=null;
date_default_timezone_set('israel');

if (isset($_SESSION['user_id'])) {
  # code...
   $usr_id=$_SESSION['user_id'];
}
   

    // $get_usr_grp=Crud_op::get_usr_grp_id_for_specific_usr($usr_id);   
 // I will add users types in select option 
    // 1=>admin,2=>supervior,3->discussion_committee,4->std,5->Dean_of_the_College
       
?>
   <!DOCTYPE html>
<?php include('../includes/header.php'); ?>

<?php 
 if((isset($_POST['save_grp']) || 
  isset($_POST['edit_my_grp_name']) || 
  isset($_POST['join_into_other_grp']) || 
  isset($_POST['edit_other_grp_name']) ||
  isset($_POST['join_into_other_grp_and_del_prev_grp'])
    ) && isset($_POST['user_grp_name'])  )
 { 

  $grp_name=$_POST['user_grp_name'];
   $semester_id=$get_active_semester[0]['auto_inc_id'];
   
   try{
     if(isset($_POST['save_grp'])){ 
 $no_of_created_grp=Crud_op::check_if_this_owner_make_his_own_group_to_prevent_repeated($usr_id,$semester_id); 
 
if($no_of_created_grp==0){
 $inserted_row=Crud_op::set_status_to_accepted_for_grp_owner($grp_name,$semester_id,$usr_id);

    if($inserted_row==2){
     $grp_success="ﺗﻢ ﺇﻧﺸﺎء ﻣﺠﻤﻮﻋﺘﻚ ﺑﻨﺠﺎﺡ";

     $grp_err=null;
    }else{
      $grp_err="ﻟﻢ ﻳﺘﻢ ﺇﻧﺸﺎء ﻣﺠﻤﻮﻋﺘﻚ ﺑﻨﺠﺎﺡ";
      $grp_success=null;
 
    }
}
 else {
  $grp_err="ﻳٌﻤﻨﻊ ﺇﻧﺸﺎءﻙ ﻷﻛﺜﺮ ﻣﻦ ﻣﺠﻤﻮﻋﺔ ﻭاﺣﺪﺓ";
  $grp_success=null;
 
 }
//
    
//

     }
     //
    elseif(isset($_POST['edit_my_grp_name'])){
     // die('you are here');
     $grp_id  = $_POST['grp_id'];
     $updated_row=Crud_op::update_grp_name_for_owner_grp($grp_id,$grp_name,$semester_id,$usr_id);
     // echo $updated_row;die();
    if($updated_row){
     $grp_success="ﺗﻢ ﺗﻌﺪﻳﻞ اﺳﻢ ﻣﺠﻤﻮﻋﺘﻚ ﺑﻨﺠﺎﺡ";

      $grp_err=null;
    }else{
     $grp_err="ﻟﻢ ﻳﺘﻢ ﺗﻌﺪﻳﻞ اﺳﻢ ﻣﺠﻤﻮﻋﺘﻚ ﺑﻨﺠﺎﺡ ";
     $grp_success=null;
 
    }
    }
   //
    elseif(isset($_POST['join_into_other_grp'])){
     //join into other grp and previous grp has other std
     //if he find in other grp and want to create grp it will prevent him to create his grp twice
     $updated_row=Crud_op::update_usr_grp_by_join_into_other_grp($grp_name,$usr_id,$semester_id);

    if($updated_row){
     $grp_success="تم إنشاء مجموعتك بنجاح";

    $grp_err=null;
    }else{
     $grp_err="لم يتم إنشاء مجموعتك بنجاح";
     $grp_success=null;
 
    }
    }
   //
   elseif(isset($_POST['edit_other_grp_name'])){
    $grp_err="ﻻ ﻳﻤﻜﻨﻚ ﺗﻌﺪﻳﻞ اﺳﻢ اﻟﻤﺠﻤﻮﻋﺔ ﻷﻧﻚ ﻟﺴﺖ اﻟﻤُﻨﺸﺊ ﻟﻬﺎ - ﻓﻤﺴﺆﻭﻟﻬﺎ ﻣﺨﻮّﻝ ﺑﻬﺬﻩ اﻟﻤﻬﻤﺔ";
    $grp_success=null;
 
   }
   elseif(isset($_POST['join_into_other_grp_and_del_prev_grp'])){
 /**/

$chk_if_has_other_grp=Crud_op::chk_if_has_other_grp($usr_id,$semester_id);
if($chk_if_has_other_grp!=null){
  $grp_id=$chk_if_has_other_grp[0]['grp_id'];
$row=Crud_op::join_into_other_grp_and_del_prev_grp($usr_id,$grp_id,$grp_name,$semester_id);
  if($row==2){
   $grp_err=null;
    $grp_success="ﺗﻢ ﺇﻧﺸﺎء ﻣﺠﻤﻮﻋﺘﻚ ﺑﻨﺠﺎﺡ ﻭ ﻣﻐﺎﺩﺭﺗﻚ ﻟﻤﺠﻤﻮﻋﺘﻚ اﻟﺴﺎﺑﻘﺔ";
  }
  else{
  $grp_err= "ﻟﻢ ﺗﺘﻢ اﻟﻌﻤﻠﻴﺔ ﺑﻨﺠﺎﺡ" ;
    $grp_success= null; 
  }
  }

 
 /**/
  
   }
   }
   catch(PDOException $ex){
     echo   $ex->getMessage();
    $grp_err="ﻳﺮﺟﻰ ﻣﺮاﺟﻌﺔ ﻣﺒﺮﻣﺞ اﻟﻨﻈﺎﻡ";
    $grp_success=null;
 
   }
   $_POST = array();
 //  	echo "<meta http-equiv=refresh content=\"0; URL=create_my_grp.php\">";
 }
?>

<?php if (isset($_SESSION["user_id"]) && (!empty($_SESSION["user_id"])) && (isset($_SESSION["role"])) && (!empty($_SESSION["role"])) && ($_SESSION["role"] == 4)) {
 $get_active_semester_tbl_row_count = Crud_op::get_active_semester_tbl_row_count();
if($get_active_semester_tbl_row_count!=0) {
// $hour=date('H');
//  $min=date('i');
//  $sec=date('s');
//  $month=date('m');
//  $day=date('d');
//  $year=date('Y');
//  $from_date = mktime($hour, $min, $sec, $month, $day, $year);
//  $to_date = mktime($hour, $min, $sec, $month, $day, $year);

// $from_date= date("Y-m-d H:i:s", $from_date);
// $to_date= date("Y-m-d H:i:s", $to_date);

if($get_active_semester!=null){
   $evt_name="create_grps";
 $arr_from_and_to_evt_date=Crud_op::get_first_and_end_date_for_evt($evt_name);

  if($arr_from_and_to_evt_date!=null){
 
 
 $from_date=$arr_from_and_to_evt_date[0]['from_date'];
 $to_date=$arr_from_and_to_evt_date[0]['to_date'];
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
 //$current_Date=date("Y-m-d H:i:s");
 
 
if ($status) {
 # code...
 

   $auto_inc_id=$get_active_semester[0]['auto_inc_id'];
  $sem_name =$get_active_semester[0]['sem_name'];
   ?>

 


<div class="container text-center">
 <!-- Start add btn -->
 <div class="row" style="margin-bottom: 12px;">
 <div class="form-group text-center">
   <div class="col-sm-4"></div>
    <div class="col-sm-4">
        <a class="btn icon-btn btn-success" href="create_my_grp.php?action=add" name="create-grp" style="font-size: 19px;font-weight: bold;"><span class="glyphicon btn-glyphicon glyphicon-plus-sign img-circle text-success" style="color: white;background-color: #83e88b" ></span>ﺇﺿﺎﻓﺔ اﻟﻤﺠﻤﻮﻋﺔ اﻟﺨﺎﺻﺔ ﺑﻲ</a>
 
    </div>
    <div class="col-sm-4"></div>
</div>
</div>
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
if( (isset($_REQUEST['action']) && ($_REQUEST['action'] == "edit") && (isset($_GET['grp_id'])) ) || 
isset($_REQUEST['action']) && ($_REQUEST['action'] == "add") ){
$grp_id_hidden_val='';	
if(isset($_REQUEST['action']) && ($_REQUEST['action'] == "edit")){
$grp_id_hidden_val='<input type="hidden" name="grp_id"  value="'.$_GET["grp_id"].' "/>';	
}
	
 $grp_success=null;
$grp_err=null;
?>
 <!-- End add btn -->
<form method="post" action="create_my_grp.php">
 <?php
 echo $grp_id_hidden_val;
 ?>
 <div class="row">
  
  <div class="col-sm-4"></div>

  <div class="col-sm-4">

   <div class="form-group text-center">
   

              <label class="user_name">اﺳﻢ اﻟﻤﺠﻤﻮﻋﺔ</label>
    
   <input type="text" id="user_grp" style="margin-bottom: 12px;" name="user_grp_name" maxlength="44" class="form-control" placeholder="ﺃﺩﺧﻞ اﺳﻢ ﻣﺠﻤﻮﻋﺘﻚ" 
   autocomplete="off" autofocus required <?php 
if(isset($_REQUEST['action']) && ($_REQUEST['action'] == "edit")){
$usr_id=$_SESSION['user_id'];
$status="accepted";
$get_grp_info_for_specific_user=Crud_op::get_grp_info_for_specific_user($auto_inc_id,$usr_id,$status);
if($get_grp_info_for_specific_user!=null){
 $u_name=$get_grp_info_for_specific_user[0]['grp_name'];
  
 echo'value="'.$u_name.'"';
}
 
  
}

elseif(isset($_REQUEST['action']) && $_REQUEST['action'] =="add"){
 echo 'value=""';
}
    ?>>

   </div>
  </div>
 </div>
<div class="row">
  
  <div class="col-sm-4"></div>

  <div class="col-sm-4">

   <div class="form-group text-center">
   <?php $semester_id=$get_active_semester[0]['auto_inc_id'];
 //check if he has previous grp from other grp and maximum grp must specifying

 $chk_if_has_other_grp=Crud_op::chk_if_has_other_grp($usr_id,$semester_id);
 
 if($chk_if_has_other_grp!=null){
  $grp_id=$chk_if_has_other_grp[0]['grp_id'];
$grp_member_no=Crud_op::check_if_this_user_is_last_one_in_this_grp($grp_id,$semester_id,$usr_id);

  ?>
<input type="submit" class="btn btn-success text-center submit"
style="margin-bottom: 12px;align-items: center;text-align: center;"
 <?php
        
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == "add") {
if($grp_member_no>0){
?>

 onClick="return confirm('ﻫﻞ ﺃﻧﺖ ﻣﺘﺄﻛﺪ ﻣﻦ ﺭﻏﺒﺘﻚ ﺑﻤﻐﺎﺩﺭﺓ ﻣﺠﻤﻮﻋﺘﻚ اﻟﺴﺎﺑﻘﺔ ﻭ ﺇﻧﺸﺎء ﻣﺠﻤﻮﻋﺔ ﺧﺎﺻﺔ ﺑﻚ')"
 
<?php

  echo 'value="ﻣﻐﺎﺩﺭﺓ ﻣﺠﻤﻮﻋﺘﻲ اﻟﺴﺎﺑﻘﺔ ﻭ ﺇﻧﺸﺎء ﻣﺠﻤﻮﻋﺔ ﺧﺎﺻﺔ ﺑﻲ" '.' name="join_into_other_grp" ';
?>

<?php
}
else{
 ?>
onClick="return confirm('ﻫﻞ ﺃﻧﺖ ﻣﺘﺄﻛﺪ ﻣﻦ ﺭﻏﺒﺘﻚ ﺑﻤﻐﺎﺩﺭﺓ ﻣﺠﻤﻮﻋﺘﻚ اﻟﺴﺎﺑﻘﺔ ﻭ ﺣﺬﻓﻬﺎ ﻷﻧﻚ ﺁﺧﺮ ﻋﻀﻮ ﻓﻴﻬﺎ ﻭ ﺇﻧﺸﺎء ﻣﺠﻤﻮﻋﺔ ﺧﺎﺻﺔ ﺑﻚ')" <?php
echo 'value="ﻣﻐﺎﺩﺭﺓ ﻣﺠﻤﻮﻋﺘﻲ اﻟﺴﺎﺑﻘﺔ ﻭ ﺣﺬﻓﻬﺎ ﻭ ﺇﻧﺸﺎء ﻣﺠﻤﻮﻋﺔ ﺧﺎﺻﺔ ﺑﻲ" '.' name="join_into_other_grp_and_del_prev_grp" ';
}
         ?> 
         <?php
           
       
        }
        
        else if (isset($_REQUEST['action']) && $_REQUEST['action'] == "edit" ) {
              echo 'value="ﺗﻌﺪﻳﻞ اﺳﻢ ﻣﺠﻤﻮﻋﺔ اﻧﻀﻤﻤﺖ ﺇﻟﻴﻬﺎ" '.' name="edit_other_grp_name" ';
        }
        
  ?>

>
    
  <?php
 }
  else{
?>
<input type="submit" class="btn btn-success text-center submit" 
style="margin-bottom: 12px;align-items: center;text-align: center;"
 <?php
        
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == "add") {
            echo 'value="ﺣﻔﻆ" '.' name="save_grp" ';
        }
        
        else if (isset($_REQUEST['action']) && $_REQUEST['action'] == "edit" ) {
              echo 'value="ﺗﻌﺪﻳﻞ اﺳﻢ ﻣﺠﻤﻮﻋﺘﻲ" '.' name="edit_my_grp_name" ';
        }
        
  ?>

>
    
<?php


  } ?>
          
    </form>
   </div>
  </div>
 </div>
 <?php } ?>
<?php if(isset($grp_success)){
?><div class="row" style="margin-top: 12px;" >
  <div class="col-sm-4 "></div>
<div class="col-sm-4 alert alert-success"><strong><?php echo $grp_success; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
<?php
  } ?>




<?php if(isset($grp_err)){
?>
<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger"><strong><?php echo $grp_err; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
<?php
  } ?>
 
 
 

<div class="row">
<div class="col-sm-4"></div>
<div class="col-sm-4">
<div class="form-group text-center">
<div class="table-responsive text-center " style="direction: rtl;margin-top: 12px;">          
  <table class="table  table-hover table-striped table-bordered text-center" style="align-items: center;">
<thead>
   <th>اﻟﻔﺼﻞ اﻟﺪﺭاﺳﻲ</th>
   <th>اﺳﻢ اﻟﻤﺠﻤﻮﻋﺔ</th>
   <th>اﻹﺟﺮاء</th>
</thead>
<tbody>
<?php


/*SELECT semester.id, `year_val`,semester_name FROM `semester` ,semester_names where semester_id_ref=semester_names.id;*/
$usr_id=$_SESSION['user_id'];
$status="accepted";
$get_grp_info_for_specific_user=Crud_op::get_grp_info_for_specific_user($auto_inc_id,$usr_id,$status);
if($get_grp_info_for_specific_user!=null){
for($i=0;$i<count($get_grp_info_for_specific_user);$i++){
 ?>
 <tr>
 <td><?php echo   $sem_name; ?></td>
  <td><?php echo $get_grp_info_for_specific_user[$i]['grp_name']; ?></td> 
  
 <td> <a href="create_my_grp.php?action=edit&grp_id=<?php echo $get_grp_info_for_specific_user[$i]['grp_id'] ;?>"
  class="btn btn-primary btn-sm a-btn-slide-text" id="edit_semester">
        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
        <span><strong>ﺗﻌﺪﻳﻞ</strong></span>            
    </a></td>
</tr>
    <?php
}
}
else{
?>
<td colspan="3">ﻵ ﺗﻮﺟﺪ ﺑﻴﺎﻧﺎﺕ</td>
<?php

}
    ?>
 
</tbody>
  </table>
</div>
   


</div><!-- End tbl info -->
  </div>

  <div class="col-sm-4"></div>

 </div>

</div>

</body>
<?php  // to prevent grp name repeatition  ?>
 
</div>

<?php
}
else{
 ?>
<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo "ﻏﻴﺮ ﻣﺴﻤﻮﺡ ﺗﺸﻜﻴﻞ اﻟﻤﺠﻤﻮﻋﺎﺕ ﻫﺬﻩ اﻟﻔﺘﺮﺓ"; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
 <?php
}
 } 
 
?>

   <?php
// }
 
}
else{
 $grp_err="ﻳﺮﺟﻰ ﻣﺮاﺟﻌﺔ اﻟﻤﺴﺆﻭﻝ عن الموقع ﻟﺘﻔﻌﻴﻞ اﻟﻔﺼﻞ اﻟﺪﺭاﺳﻲ";
 ?>
 <div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo $grp_err ; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
 <?php
}
}
?>    <!--script src="../js/jquery-1.12.4.min.js"></script-->
     <?php include('../includes/footer.php');?>