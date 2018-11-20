<?php
    session_start();
    require_once("../db_op.php");
// I will add users types in select option 
    //1=>admin,2=>supervior,3->discussion_committee,4->std,5->Dean_of_the_College
   $chk_no_of_semester_tbl_rows=Crud_op::get_active_semester_tbl_row_count1();
    date_default_timezone_set('israel');
?>
   <!DOCTYPE html>
<?php include('../includes/add_evt_header.php');  



 if( isset($_SESSION["user_id"])  &&  isset($_SESSION["role"]) && !empty($_SESSION["role"]) && !empty($_SESSION["user_id"]) && $_SESSION["role"]==1 ){
 
$chk_no_get_event_row_count=Crud_op::get_event_row_count();
 
if($chk_no_of_semester_tbl_rows!=null && $chk_no_get_event_row_count!=0   ) {
  $get_active_semester = Crud_op::get_active_semester();
   $auto_inc_id=$get_active_semester[0]['auto_inc_id'];
  if( isset($_POST['save_event']) || isset($_POST['edit_event']) )
{
	$evt_id="";
	if(isset($_POST['edit_event'])){

	$evt_id=$_POST['evt_id'];}
   if( isset($_POST['from_date']) && isset($_POST['to_date']) &&  isset($_POST['evt_name']) ){

    $from_date= $_POST['from_date'];
    $to_date=$_POST['to_date'];
    $evt_name=$_POST['evt_name'];
    
    
 
     
    

    $evt_tbl_data_filed = array($from_date,$to_date,$evt_name);
 
   // try{
   //  if(isset($_POST['save_event'])){
 
try{
 
 
$extract_date_from_date_and_time1=date("Y-m-d",strtotime($from_date));
$extract_date_from_date_and_time2=date("Y-m-d",strtotime($to_date));
$extract_time_from_date_and_time1=date("H:i:s",strtotime($from_date));
$extract_time_from_date_and_time2=date("H:i:s",strtotime($to_date));

  $rowData=Crud_op::check_timeOverLapping_in_active_semester($from_date,$to_date);
  $rowData1=Crud_op::check_timeOverLapping_in_active_semester1($from_date,$to_date,$evt_id);
  if( ($extract_date_from_date_and_time1<$extract_date_from_date_and_time2) || 

  (($extract_date_from_date_and_time1==$extract_date_from_date_and_time2) && 
    ($extract_time_from_date_and_time1<$extract_time_from_date_and_time2)) ){
/*
if(   
	( (($rowData==null) && isset($_POST['save_event'])) || (($rowData1==null) && isset($_POST['edit_event'])))

	){
		*/
if(isset($_POST['save_event'])){

   $row=Crud_op::insert_into_evt_tbl($evt_tbl_data_filed,$auto_inc_id);
  if($row>0){
 
  $success_add_evt="تمت عملية الحفظ بنجاح";

} 
else{
 $err_add_evt="لم تتم عملية الحفظ بنجاح";	
}
}
  elseif(isset($_POST['edit_event'])){

$evt_id=$_POST['evt_id'];
 
     $row=Crud_op::update_evt_tbl($evt_tbl_data_filed,$auto_inc_id,$evt_id);
  if($row){
 
  $success_add_evt="تمت عملية التعديل بنجاح";

}
else{
 
  $success_add_evt="لم تتم عملية التعديل بنجاح";

} 
  }

 
/*
}
else {

 $name = $rowData[0]['name'];
 $from_date = $rowData[0]['from_date'];
 $to_date = $rowData[0]['to_date'];
 
 $err_add_evt="يوجد تعارض مع الحدث<br>".$name."<br>ذي الوقت<br>". $from_date." - ".$to_date;
  $success_add_evt=null;
}
*/
	}
else{ 
$err_add_evt="يجب أن يكون تاريخ النهاية أكبر من تاربخ البداية"; 
$success_add_evt=null;
}
  

 }
 catch(PDOException $ex){
 //echo $ex->getMessage();
$err_add_evt="لم تتم عملية الحفظ بنجاح لأنه لا يجوز تحديد موعدين لنفس الحدث في نفس الفصل";  
$success_add_evt=null;
 }
  

 
  }
  $_POST = array();
 // echo "<meta http-equiv=refresh content=\"0; URL=add_dates_for_evt.php\">";
}//end post of save/edit cond
  
?>
<body>


<div class="container text-center">
 

<div class="row" style="margin-bottom: 12px;">
  <div class="form-group text-center">
    <div class="col-sm-4"></div>
    <div class="col-sm-4">
        <a class="btn icon-btn btn-success" href="add_dates_for_evt.php?action=add" name="add-old-year" style="font-size: 19px;font-weight: bold;"><span class="glyphicon btn-glyphicon glyphicon-plus-sign img-circle text-success" style="color: white;background-color: #83e88b" ></span>تحديد تواريخ للأحداث</a>
 
    </div>
    <div class="col-sm-4"></div>
</div>
</div>
<div class="row" style="margin-bottom: 12px;">
  <div class="form-group text-center">
    <div class="col-sm-4"></div>
    <div class="col-sm-4 alert alert-success">
       
  <strong>الفصل الفعّال: <?php
  if ( $chk_no_of_semester_tbl_rows!=null) {
 echo  $chk_no_of_semester_tbl_rows[0]['sem_name'].' '.$chk_no_of_semester_tbl_rows[0]['year_val'];
   }
   else{
    echo  'لم يتم تفعيل أي فصل بعد';
   }

  ?></strong>
 
    </div>
    <div class="col-sm-4"></div>
</div>
</div>
  
<?php


if( 
((isset($_REQUEST['action']) && $_REQUEST['action'] == "edit") 
	&& !empty($_GET['evt_id']) )

	|| 
	((isset($_REQUEST['action']) && $_REQUEST['action'] == "add")  )) {
   $evt_id_hidden='';
   $success_add_evt=null;
 $err_add_evt=null;
  if(isset($_REQUEST['action']) && $_REQUEST['action'] == "edit"){

   
  $evt_id=$_GET['evt_id'];
  
	 
	$evt_id_hidden='<input type="hidden" name="evt_id"   value="'.$evt_id.'" />';
     
	 
   
  $get_info_about_specific_evt=Crud_op::get_info_about_specific_evt($evt_id, $auto_inc_id);
/*
"SELECT `semester_id`, `evt_id`, `from_date`, `to_date` FROM `evt_date` WHERE  semester_id=:semester_id and evt_id=:evt_id;" 
*/
}
if( (isset($_REQUEST['action']) && ($_REQUEST['action'] == "edit")
 && ($get_info_about_specific_evt!=null) ) 
  || (isset($_REQUEST['action']) && ($_REQUEST['action'] == "add"))
) {
	
	

?>
  <form method="post" action="add_dates_for_evt.php">
  
  <div class="row">
    
    <div class="col-sm-4"></div>

    <div class="col-sm-4">

      <div class="form-group text-center">
      
     
<?php
echo $evt_id_hidden;
?>
                
  <label class="evt_name">اختر اسم الحدث</label>


    <select class="form-control" name="evt_name" id="evt_name" style="background-color: #eee;margin-bottom: 12px;" required="required"> 
  
<option class="defult text-center">يرجى الاختيار</option>
<?php 
 if (isset($_REQUEST['action']) && $_REQUEST['action'] == "add") {
  $get_all_evt_name=Crud_op::get_all_evt_name();
  if($get_all_evt_name!=null){
    for($i=0;$i<count($get_all_evt_name);$i++){
       echo '<option class="text-center" value="'.$get_all_evt_name[$i]["id"].'"
       >'.$get_all_evt_name[$i]["name"].'</option>';  
    }
     
  }
   

 }
  else if (isset($_REQUEST['action']) && $_REQUEST['action'] == "edit") {
 
  $get_all_evt_name=Crud_op::get_all_evt_name();
  if($get_all_evt_name!=null){
    for($i=0;$i<count($get_all_evt_name);$i++){
       echo '<option class="text-center" value="'.$get_all_evt_name[$i]['id'].'"';


      echo'';
if($get_info_about_specific_evt[0]['evt_id']==$get_all_evt_name[$i]['id']) { 
      echo ' selected="selected"'; 
    }
      echo ' >'.$get_all_evt_name[$i]['name'].'</option>';  
    }
     
  }
 
   }
?>

 
</select>

<!--Start-->
     <label class="from_date">يبدأ من</label>
   <div class="handler" id="handler">
  <div class="form-group">
     <div class='input-group date' id='datetimepicker6'>

      <input type="text" class="form-control" name="from_date" id="from_date" required
      <?php
if(isset($_REQUEST['action']) && $_REQUEST['action'] =="add"){
  echo 'value=""';
}
else if(isset($_REQUEST['action']) && $_REQUEST['action'] =="edit"){
 $from_date= $get_info_about_specific_evt[0]['from_date'];
echo'value="'.$from_date.'"';
}
?>
       /> 

      <span class="input-group-addon">
        <span class="glyphicon-calendar glyphicon"></span>
      </span>
    </div>
  </div>

</div>

<label class="to_date">ينتهي بـِ</label>
   <div class="handler" id="handler">
  <div class="form-group">
     <div class='input-group date' id='datetimepicker7'>

      <input type="text" class="form-control  " name="to_date" id="to_date"  required
      <?php
if(isset($_REQUEST['action']) && $_REQUEST['action'] =="add"){
  echo 'value=""';
}
else if(isset($_REQUEST['action']) && $_REQUEST['action'] =="edit"){
 $from_date= $get_info_about_specific_evt[0]['to_date'];
echo'value="'.$from_date.'"';
}
?>
       /> 

      <span class="input-group-addon">
        <span class="glyphicon-calendar glyphicon"></span>
      </span>
    </div>
  </div>

</div>

<!--End-->
<!---->
 <input type="submit" class="btn btn-success text-center submit"

style="margin-bottom: 12px;align-items: center;text-align: center;"
 <?php
        
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == "add") {
          echo ' onClick="return confirm(\'هل أنتَ متأكد من رغبتك بالحفظ؟\')"';
            echo ' value="حفظ" '.' name="save_event" '.' disabled="disabled"';
        }
        
        else if (isset($_REQUEST['action']) && $_REQUEST['action'] == "edit" ) {
              echo ' onClick="return confirm(\'له أنتَ متأكد من رغبتك بالتعديل؟\')"';
             echo ' value="تعديل" '.' name="edit_event" ';
        }
        
  ?>

> 
<!---->
    </div>
  </div>
  </form>
  <div class="col-sm-4"></div>
      <!-- Start tbl info -->
    </div>
      <?php
    }
}

else if (isset($_GET['action']) && !empty($_GET['action']) && $_GET['action']=="del" && !empty($_GET['evt_id'])){
  $success_add_evt=null;
 $err_add_evt=null;
  if(!empty($_GET['evt_id']) ){
   $evt_id=$_GET['evt_id'];
  
 
  try{
$row=Crud_op::delete_evt_from_specific_year( $auto_inc_id,$evt_id);
if($row>0){
$success_add_evt="تمت عملية الحذف بنجاح";
$err_add_evt=null;

}
/*
else{
$err_add_evt="لم تتم عملية الحذف بنجاح";
}
*/
  }
  catch(PDOException $ex){
$err_add_evt="لم تتم عملية الحذف بنجاح";
$success_add_evt=null;
echo $ex->getMessage();
 
  }
  }


 }
 else if( (isset($_REQUEST['action']) && $_REQUEST['action'] == "reset") && !empty($_GET['u_id2']) && isset($_GET['u_id2']) ){
  $usr_id1=$_GET['u_id2'];
  try{
$row=Crud_op::reset_usr_pass($usr_id1);
if($row>0){
$success_add_evt="تم تعديل كلمة السر بنجاح";
$err_add_evt=null;

}
/*
else{
$err_add_evt="لم تتم عملية الحذف بنجاح";
}
*/
  }
  catch(PDOException $ex){
$err_add_evt="لم يتم تعديل كلمة السر بنجاح";
$success_add_evt=null;
  }
}
?>
 
 
<?php if(isset($success_add_evt)){
?><div class="row" style="margin-top: 12px;" >
  <div class="col-sm-4 "></div>
<div class="col-sm-4 alert alert-success"><strong><?php echo $success_add_evt; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
<?php
  } ?>




<?php if(isset($err_add_evt)){
?>
<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger"><strong><?php echo$err_add_evt; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
<?php
  } 
 
  ?>

 
 
 
</div>
<!--form  method="post" action="add_dates_for_evt.php"-->
<div class="row">
<div class="col-sm-2"></div>
<div class="col-sm-8">
<div class="form-group text-center">
<div class="table-responsive text-center " style="direction: rtl;margin-top: 12px;">          
  <table class="table  table-hover table-striped table-bordered text-center" id="tbl1" style="align-items: center;">
<thead>
   
    <th>الفصل الدراسي</th>
    <th>اسم الحدث</th>
    <th>تاريخ البدء</th> 
    <th>تاريخ الانتهاء</th> 
    <th>الإجراء</th>
   
</thead>
<tbody>
<?php
$get_all_evt_for_specific_year = Crud_op::get_all_evt_for_specific_year();
/*
SELECT `evt_id`, `from_date`, `to_date`,`id`, `name` FROM `evt_date`,evt,semester WHERE evt_id=id and semester.auto_inc_id=evt_date.semester_id and active=1;
*/
// $get_active_semester = Crud_op::get_active_semester();
$year_val=$get_active_semester[0]['year_val'];
$sem_name=$get_active_semester[0]['sem_name'];
 

if($get_all_evt_for_specific_year!=null && $get_active_semester!=null){ 
 
 
for($i=0;$i<count($get_all_evt_for_specific_year);$i++){
  ?>
  <tr>
      
    <td><?php echo $sem_name.' '.$year_val; ?></td>
     <td><?php echo $get_all_evt_for_specific_year[$i]['name']; ?></td>
     <td><?php echo $get_all_evt_for_specific_year[$i]['from_date']; ?></td> 
     <td><?php echo $get_all_evt_for_specific_year[$i]['to_date']; ?></td> 
     
                                </td>
  
     <td> <a href="add_dates_for_evt.php?action=edit&evt_id=<?php echo $get_all_evt_for_specific_year[$i]['evt_id'] ;?>"
  class="btn btn-primary btn-sm a-btn-slide-text" id="edit_evt"  style="margin-top: 3px;"">
        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
        <span><strong>تعديل</strong></span>            
    </a>
 
  <!-- data-toggle="modal" data-target="#confirm-delete" -->
<a href="add_dates_for_evt.php?action=del&evt_id=<?php echo $get_all_evt_for_specific_year[$i]['evt_id'] ;?>"
  class="btn btn-danger btn-sm a-btn-slide-text" onClick="return confirm('هل أنت متأكد من حذف هذا الموعد')" id="del_evt" style="margin-top: 3px;">
        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
        <span><strong>حذف</strong></span>            
    </a>

  
</td>
 
</tr>
    <?php
}
}
else{
?>
<td colspan="6">لآ توجد بيانات</td>
<?php

}
    ?>
 
</tbody>
  </table>

</div>
      


</div><!-- End tbl info -->
    </div>

    <div class="col-sm-2"></div>

  <!-- </div> -->

<!--/form-->

<?php
}
else{
  ?>
 
<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger"><?php echo 'يٌرجى تفعيل الفصل الدراسي في نافذة إضافة فصول لسنوات أو تأكد من وجود أحداث لتحديد مواعيدها'; ?></div>
<div class="col-sm-4 "></div>
</div>
 
  <?php
}
?>
</div>
 

 
<?php
}
 

?>

<script  src="../js/custom.js"></script>
<script type="text/javascript">
  
  $(function () {
        $('#datetimepicker6').datetimepicker({
          allowInputToggle:true,format: 'YYYY-MM-DD HH:mm'
        });
        $('#datetimepicker7').datetimepicker({
            useCurrent: false ,
            allowInputToggle:true,format: 'YYYY-MM-DD HH:mm'

        });
        $("#datetimepicker6").on("dp.change", function (e) {
            $('#datetimepicker7').data("DateTimePicker").minDate(e.date);
        });
        $("#datetimepicker7").on("dp.change", function (e) {
            $('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
        });
    });
</script>



</body>

</html>
