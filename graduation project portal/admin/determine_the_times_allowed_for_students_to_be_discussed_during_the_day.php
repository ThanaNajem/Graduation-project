<?php
    session_start();
    require_once("../db_op.php");
	  $chk_no_of_semester_tbl_rows=Crud_op::get_active_semester_tbl_row_count1();
// I will add users types in select option 
    //1=>admin,2=>supervior,3->discussion_committee,4->std,5->Dean_of_the_College
   
   date_default_timezone_set('israel');
?>
   <!DOCTYPE html>
<?php include('../includes/add_evt_header.php');  



 if( isset($_SESSION["user_id"])  &&  isset($_SESSION["role"]) && !empty($_SESSION["role"]) 
	 && !empty($_SESSION["user_id"]) && $_SESSION["role"]==1 ){

//$chk_no_get_event_row_count=Crud_op::get_event_row_count();
 
if($chk_no_of_semester_tbl_rows!=null //&& $chk_no_get_event_row_count!=0   
) {  
 $auto_inc_id= $chk_no_of_semester_tbl_rows[0]['auto_inc_id'];

  if( isset($_POST['save_suggested_time']) || isset($_POST['edit_suggested_time']) )
{ 
   if( isset($_POST['from_time']) && isset($_POST['to_time'])  ){
$time_id="";
    $from_time= $_POST['from_time'];
    $to_time=$_POST['to_time'];
	if(isset($_POST['time_id'])){
	 $time_id=$_POST['time_id'];	
	}
   
	 
    # $evt_tbl_data_filed = array($from_time,$to_time,$auto_inc_id);
 

try{
 
 
$extract_time_from_date_and_time1=date("H:m",strtotime($from_time));

$extract_time_from_date_and_time2=date("H:m",strtotime($to_time));
 
 
 $time_id=0;
if(isset($_POST['edit_suggested_time'])){

$time_id=$_POST['time_id'];}
 $rowData=Crud_op::check_timeOverLapping_for_time_tbl_in_active_semester($from_time, $to_time);
 $rowData1=Crud_op::check_timeOverLapping_for_time_tbl_in_active_semester_except_selection_time($from_time, $to_time,$time_id);
 
  if( ($extract_time_from_date_and_time1< $extract_time_from_date_and_time2)){
if((    
    ($extract_time_from_date_and_time1< $extract_time_from_date_and_time2) && (count($rowData)==0) && isset($_POST['save_suggested_time']))
||(
(    
    ($extract_time_from_date_and_time1< $extract_time_from_date_and_time2) && (count($rowData1)==0) && isset($_POST['edit_suggested_time']))
)
	){
if(isset($_POST['save_suggested_time'])){

   $row=Crud_op::insert_new_suggested_time($from_time,$to_time,$auto_inc_id);
  if($row>0){
 
  $success_add_evt="تمت عملية الحفظ بنجاح";

} 
}

  elseif(isset($_POST['edit_suggested_time'])){

 $time_id=$_POST['time_id'];
 

     $row=Crud_op::update_exist_suggested_time($from_time,$to_time,$auto_inc_id,$time_id);
  if($row){
 
  $success_add_evt="تمت عملية التعديل بنجاح";

} 
else{
$err_add_evt="لم تتم عملية التعديل بنجاح";	
}
  }

 

}
else{
 
 $from_time1 = $rowData[0]['from_time'];
 $to_time1 = $rowData[0]['to_time'];
 if(isset($_POST['save_suggested_time'])){ 
 
 $err_add_evt="يوجد تعارض مع الوقت<br>".$rowData[0]['from_time']." - ".$rowData[0]['to_time'];
 
 }
 elseif(isset($_POST['edit_suggested_time'])){
	 $err_add_evt="يوجد تعارض مع الوقت<br>".$rowData1[0]['from_time']." - ".$rowData1[0]['to_time'];
 
	 }

 
  $success_add_evt=null;
}
  }
else{ 
$err_add_evt="يجب أن يكون وقت النهاية أكبر من وقت البداية"; 
$success_add_evt=null;
}
  
 
 }
 catch(PDOException $ex){
 echo $ex->getMessage();
$err_add_evt="لم تتم عملية الحفظ بنجاح-لربما قمت بإضافة حدث موجود";  
$success_add_evt=null;
 }
  
 
 
  
}//end post of save/edit cond
//echo "<meta http-equiv=refresh content=\"0; URL=determine_the_times_allowed_for_students_to_be_discussed_during_the_day.php\">";
$_POST = array();
} 
?>
<body>


<div class="container text-center">
 
<div class="row" style="margin-bottom: 12px;">
  <div class="form-group text-center">
    <div class="col-sm-4"></div>
    <div class="col-sm-4">
        <a class="btn icon-btn btn-success"
		href="determine_the_times_allowed_for_students_to_be_discussed_during_the_day.php?action=add" 
		name="determine_the_times_allowed_for_students_to_be_discussed_during_the_day" 
		style="font-size: 19px;font-weight: bold;">
		<span class="glyphicon btn-glyphicon glyphicon-plus-sign img-circle text-success" 
		style="color: white;background-color: #83e88b" >
		</span>تحديد الأوقات المقترح للطالب اختيارها كموعد لمناقشته</a>
 
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

  ?>
 </strong>
    </div>
    <div class="col-sm-4"></div>
</div>
</div>
  
<?php
 
if( (isset($_REQUEST['action']) && ($_REQUEST['action'] == "edit")
 && (!empty($_GET['t_id']) && isset($_GET['t_id']) &&  Crud_op::check_if_this_time_in_db($_GET['t_id']) ) ) 
  || (isset($_REQUEST['action']) && ($_REQUEST['action'] == "add"))
) {
	$hidden_val ="";
 $t_id="";
 $sem_active=$auto_inc_id;
 if(isset($_REQUEST['action']) && ($_REQUEST['action'] == "edit")){
	 $t_id_needed_to_edit = Crud_op::check_if_this_time_in_db($_GET['t_id']);
	$t_id= $_GET['t_id'];
	$hidden_val = '
<input type="hidden" name="semester_id"     value="'.$auto_inc_id.'"   /> 
     
  <input type="hidden" name="time_id"      value="'.$t_id.'"   />';
 }
 
echo '
  <form method="post" action="determine_the_times_allowed_for_students_to_be_discussed_during_the_day.php">
  
  <div class="row">
    
    <div class="col-sm-4"></div>

    <div class="col-sm-4">

      <div class="form-group text-center">
      
      

<!--Start-->
     <label class="from_date">يبدأ من</label>
   <div class="handler" id="handler">
  <div class="form-group">
     <div class="input-group date" id="datetimepicker6">

      <input type="text" class="form-control" name="from_time" id="from_time" required  autocomplete="off" ';
       
if(isset($_REQUEST['action']) && $_REQUEST['action'] =="add"){
  echo 'value="">';
}
elseif(isset($_REQUEST['action']) && $_REQUEST['action'] =="edit"){
 $from_time= $t_id_needed_to_edit[0]['from_time'];
echo'value="'.$from_time.'">';
 
}
?>
        

      <span class="input-group-addon">
        <span class="glyphicon-calendar glyphicon"></span>
      </span>
    </div>
  </div>

</div>
<?php

echo $hidden_val;
?>
<label class="to_date">ينتهي بـِ</label>
   <div class="handler" id="handler">
  <div class="form-group">
     <div class='input-group date' id='datetimepicker7'>

      <input type="text" class="form-control  " name="to_time" id="to_time"  required autocomplete="off"
      <?php
if(isset($_REQUEST['action']) && $_REQUEST['action'] =="add"){
  echo 'value="">';
}
elseif(isset($_REQUEST['action']) && $_REQUEST['action'] =="edit"){
 $to_time= $t_id_needed_to_edit[0]['to_time'];
 
  
echo 'value="'.$to_time.'">';
}
 
         
echo '
      <span class="input-group-addon">
        <span class="glyphicon-calendar glyphicon"></span>
      </span>
    </div>
  </div>

</div>
 
 <input type="submit" class="btn btn-success text-center submit" 
style="margin-bottom: 12px;align-items: center;text-align: center;" ';
  
        
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == "add") {
            echo 'value="حفظ" '.' name="save_suggested_time" '.' >';
        }
        
        elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == "edit" ) {
              echo 'value="تعديل" '.' name="edit_suggested_time" >';
        }
        
   

 
 
   echo ' </div>
  </div>
  <div class="col-sm-4"></div>
       
    </div> </form>';
      
    }
 

elseif (isset($_GET['action']) && !empty($_GET['action']) && $_GET['action']=="del" && !empty($_GET['t_id'])&&
  Crud_op::check_if_this_time_in_db($_GET['t_id'])
){
  $success_add_evt=null;
 $err_add_evt=null;
 $t_id_needed_to_edit = Crud_op::check_if_this_time_in_db($_GET['t_id']);
   
 
  try{
$row=Crud_op::delete_specific_date($_GET['t_id']);
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
$err_add_evt="لا يمكن حذف هذا الموعد فقد تم استخدامه كموعد مقترح في قاعات المناقشة";
$success_add_evt=null;
//echo $ex->getMessage();
 
  }
   


 }
 
  if(isset($success_add_evt)){
?><div class="row" style="margin-top: 12px;" >
  <div class="col-sm-4 "></div>
<div class="col-sm-4 alert alert-success"><strong><?php echo $success_add_evt; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
<?php
  } elseif(isset($err_add_evt)){
?>
<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger"><strong><?php echo $err_add_evt; ?></strong></div>
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
   
    
    <th>السنة الدراسية</th>
	 
	
    <th>وقت البدء</th> 
    <th>وقت الانتهاء</th> 
    <th>الإجراء</th>
   
</thead>
<tbody>
<?php
$suggested_dates_during_the_day = Crud_op::get_all_suggested_time_for_this_semester();
/*
SELECT `evt_id`, `from_date`, `to_date`,`id`, `name` FROM `evt_date`,evt,semester WHERE evt_id=id and semester.auto_inc_id=evt_date.semester_id and active=1;
*/
// $get_active_semester = Crud_op::get_active_semester();

 

if($suggested_dates_during_the_day!=null ){ 
 
$year_val=$suggested_dates_during_the_day[0]['year_val'];
$sem_name=$suggested_dates_during_the_day[0]['sem_name'];
 
for($i=0;$i<count($suggested_dates_during_the_day);$i++){
  ?>
  <tr>
       
     <td><?php echo $sem_name.' '.$year_val; ?></td> 
     
	 
     <td><?php echo $suggested_dates_during_the_day[$i]['from_time']; ?></td> 
     <td><?php echo $suggested_dates_during_the_day[$i]['to_time']; ?></td> 
     
                                </td>
  
     
   
     <td> <a href="determine_the_times_allowed_for_students_to_be_discussed_during_the_day.php?action=edit&t_id=<?php echo $suggested_dates_during_the_day[$i]['id'] ;?>"
  class="btn btn-primary btn-sm a-btn-slide-text" id="edit_evt"  style="margin-top: 3px;"">
        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
        <span><strong>تعديل</strong></span>            
    </a>
 
  <!-- data-toggle="modal" data-target="#confirm-delete" -->
<a href="determine_the_times_allowed_for_students_to_be_discussed_during_the_day.php?action=del&t_id=<?php echo $suggested_dates_during_the_day[$i]['id'] ;?>"
  class="btn btn-danger btn-sm a-btn-slide-text" onClick="return confirm('هل أنتَ متأكد من حذف هذا التوقيت؟')" id="del_evt" style="margin-top: 3px;">
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
<td colspan="4">لآ توجد بيانات</td>
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
<div class="col-sm-3 alert alert-danger text-center"><?php echo 'يٌرجى تفعيل الفصل الدراسي في نافذة إضافة فصول لسنوات'; ?></div>
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
          allowInputToggle:true,format: 'HH:mm'
        });
        $('#datetimepicker7').datetimepicker({
            useCurrent: false ,
            allowInputToggle:true,format: 'HH:mm'

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
