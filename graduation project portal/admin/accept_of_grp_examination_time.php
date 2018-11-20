<?php
    session_start();
    require_once("../db_op.php");
// I will add users types in select option 
    //1=>admin,2=>supervior,3->discussion_committee,4->std,5->Dean_of_the_College
 $user_id=null;
 if(isset($_SESSION["user_id"])){
$user_id=$_SESSION["user_id"];	 
 }
 date_default_timezone_set('israel');
 $status=false;

 
  
?>
   <!DOCTYPE html>
<?php include('../includes/header.php'); ?>
<div class="container">
<?php

$err_login=
	'<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong>لم تقم بتسجيل الدخول</strong></div>
<div class="col-sm-4 "></div>
</div>';
if(!(isset($_SESSION["user_id"])  &&  isset($_SESSION["role"]) && 
 !empty($_SESSION["role"]) && !empty($_SESSION["user_id"]) && $_SESSION["role"]==1 )){die($err_login);}
 
  $chk_no_of_semester_tbl_rows=Crud_op::get_active_semester_tbl_row_count1();
 
  $event_name="period_allowed_for_discussions";
	$period_allowed_for_discussions = Crud_op::get_first_and_end_date_for_evt($event_name);
	

	if($period_allowed_for_discussions==null || $chk_no_of_semester_tbl_rows==null){

  include('../includes/footer.php');
$time_of_discussion_is_not_allowed_err=
	'<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong>لم يتم تحديد تاريخ بداية و نهاية كفترة للمناقشة للفصل الدراسي المفعّل أو لم يتم تفعيل الفصل الدراسي</strong></div>
<div class="col-sm-4 "></div>
</div>';
  die($time_of_discussion_is_not_allowed_err);} 
  /*  $_SESSION['active']=  $chk_no_of_semester_tbl_rows[0]['sem_name'].' '.$chk_no_of_semester_tbl_rows[0]['year_val'];
	*/
   $evt_name="allow_students_to_submit_a_discussion_time_request_with_the_halls";
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
 
// $from_date= date("Y-m-d H:i:s", $from_date); 
 $from_date= gmdate("Y-m-d H:i:s", $from_date);
$to_date= gmdate("Y-m-d H:i:s", $to_date);
 
 //$to_date= date("Y-m-d H:i:s", $to_date);
 
$current_Date= date("Y-m-d H:i:s", $current_Date); 
 if($current_Date>=$from_date && $current_Date<=$to_date){
   
$status=true;}
}
$status_err='<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong>غير مسموح الموافقة على موعد المناقشات هذه الفترة</strong></div>
<div class="col-sm-4 "></div>
</div>';
if(!$status){
  include('../includes/footer.php');die($status_err);}
 
  # code...
  //بدي افحص ان كان الطالب مقبول بالمناقشة و الا لا من خلال جدول ال
  //examination 
  //بفحص الستيتس
  //
 
	   
		  //start
		 
// 
	 
  
echo '

</div>';
 

  
 
$get_all_time_of_this_grp=Crud_op::get_all_time_grps_of_this_semester($chk_no_of_semester_tbl_rows[0]['auto_inc_id']);

	 $semester_no1=$chk_no_of_semester_tbl_rows[0]['auto_inc_id']; 
 ?>
 <div class="row" style="margin-bottom: 12px;">
	<div class="form-group text-center">
	  <div class="col-sm-4"></div>
    <div class="col-sm-4 alert alert-success">
       
  <strong>الفصل الفعّال: <?php
  if ( $chk_no_of_semester_tbl_rows!=null) {
  //	echo $_SESSION['active'];
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
 <div class="row">
<div class="col-sm-2"></div>
<div class="col-sm-8">
<div class="form-group text-center">
<div class="table-responsive text-center " style="direction: rtl;margin-top: 12px;">          
  <table class="table  table-hover table-striped table-bordered text-center" id="tbl1" style="align-items: center;">
<thead>
   
     
    <th>رقم القاعة</th>
	<th>اسم المجموعة</th>
	
    <th>وقت المناقشة</th> 
    <th>تاريخ المناقشة</th>
<th>حالة الطلب</th>	
    <th>الإجراء</th>
   
</thead>
<tbody>


<?php
if(
(
isset($_POST['change_previous_accepted_into_pending_and_edit_reject_suggested_examination_date_into_accepted'])
||
isset($_POST['accept_reject_or_pending_suggested_examination_date_into_accepted'])
||
isset($_POST['change_accepted_suggested_examination_date_into_reject'])
) 
&&
(isset($_POST['grp_id']))
&&
(isset($_POST['room_id']))
&&
(isset($_POST['time_id']))
&&
(isset($_POST['date_id']))

){
	try{
	$grp_id = $_POST['grp_id'];
	$room_id= $_POST['room_id'];
	$time_id = $_POST['time_id'];
	$date_id= $_POST['date_id']; 
	 if(isset($_POST['change_previous_accepted_into_pending_and_edit_reject_suggested_examination_date_into_accepted'])
	)	{
		 $change_status="accepted";
		 $updated_row='';
		 try{
		$updated_row = 
		Crud_op::change_previous_accepted_into_pending_and_edit_reject_suggested_examination_date_into_accepted( 
		$semester_no1,$grp_id,$time_id,$date_id,$room_id);
		 }catch(PDOException $ex){ echo $ex->getMessage();}
		 
	if(!$updated_row){
		echo ' 
		 <div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center">
<strong>لم تتم العملية بنجاح</strong>
</div>
<div class="col-sm-4 "></div>
</div>' ;
	}
	else{
		echo ' 
		 <div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-success text-center">
<strong>تمت العملية بنجاح</strong>
</div>
<div class="col-sm-4 "></div>
</div>' ;
	}
	}
	
	 
	elseif(
	isset($_POST['accept_reject_or_pending_suggested_examination_date_into_accepted'])
	 
	)	{
		 $change_status="accepted";
		 $updated_row='';
		 try{
		$updated_row = Crud_op::change_reject_or_accepted_into_specific_status( $semester_no1,$grp_id,$time_id,$date_id,$room_id,$change_status);
		 }catch(PDOException $ex){ echo $ex->getMessage();}
		 
	if($updated_row==0){
		echo ' 
		 <div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center">
<strong>لم تتم العملية بنجاح</strong>
</div>
<div class="col-sm-4 "></div>
</div>' ;
	}
	else{
		echo ' 
		 <div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-success text-center">
<strong>تمت العملية بنجاح</strong>
</div>
<div class="col-sm-4 "></div>
</div>' ;
	}
	}
	elseif(isset($_POST['change_accepted_suggested_examination_date_into_reject'])){
		$change_status="pending";
		 $updated_row_count =Crud_op::change_reject_or_accepted_into_specific_status( 
		 $semester_no1,$grp_id,$time_id,$date_id,$room_id,$change_status);
		if($updated_row_count==0){
		echo ' 
		 <div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center">
<strong>لم تتم العملية بنجاح</strong>
</div>
<div class="col-sm-4 "></div>
</div>';
	}
	else{
		echo ' 
		 <div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-success text-center">
<strong>تمت العملية بنجاح</strong>
</div>
<div class="col-sm-4 "></div>
</div>';
	}
	
		}
}catch(PDOException $ex){
	$duplicated_err=
	' <div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-success text-center">
<strong>لربما حدث خطأ أو مجموعة أخرى تملك هذا الموعد</strong>
</div>
<div class="col-sm-4 "></div>
</div>';
	echo $duplicated_err;
}	 
	 $_POST = array();
	 echo "<meta http-equiv=refresh content=\"0; URL=accept_of_grp_examination_time.php\">";
}

if($get_all_time_of_this_grp==null){
	echo '<tr>
<td colspan="6">لا يوجد بيانات</td></tr>';
 }
else{
	for($i=0;$i<count($get_all_time_of_this_grp);$i++){
		echo'<form method="post" action="accept_of_grp_examination_time.php">
		<tr><td>';
		echo
		$get_all_time_of_this_grp[$i]['room_id_fk']; 
		?></td>
		<td>
		<?php
		$grp_name = $get_all_time_of_this_grp[$i]['grp_name']; 
		$group_id=$get_all_time_of_this_grp[$i]['grp_id'];
		$l='-'; 
			$grp_member=Crud_op::get_grp_member_for_specific_grp($group_id);
			$grp_name_and_member='';
 for($t=0;$t<count($grp_member);$t++){
	 if($t==count($grp_member)-1){
		$grp_name_and_member .=($t+1).'. '.$grp_member[$t]['name'];
	}
	else{
	$grp_name_and_member .=($t+1).'. '.$grp_member[$t]['name'].$l;
	}
 }
 echo $grp_name.' '.$grp_name_and_member;
		?>
		</td>
		<td><?php
		 echo
		$get_all_time_of_this_grp[$i]['from_time'].' - '.$get_all_time_of_this_grp[$i]['to_time'];
		?></td>
		<td><?php
		echo
		$get_all_time_of_this_grp[$i]['date_val'];
		 ?></td>
 <td><?php
		$status_val="";
		$status = $get_all_time_of_this_grp[$i]['status'];
		 
		if($status=="accepted"){$status_val="مقبول";}
		elseif($status=="reject"){$status_val="مرفوض";}
		elseif($status=="pending"){$status_val="قيد الانتظار";}
		echo $status_val;
		 ?></td>
  <td>

<?php
$status = $get_all_time_of_this_grp[$i]['status'];
 
$subnit_input_val='';
 if($status=="reject" || $status=="pending"){
	 	$data = Crud_op::check_if_this_grp_has_accepted_status_in_this_semester($group_id,$semester_no1);
		if($data !=null){
			$subnit_input_val=' <input type="submit" class="btn btn-success text-center" 
style="margin-bottom: 12px;align-items: center;text-align: center;"

  onClick="return confirm(\'لدى هذا الطالب موعد موافق عليه مسبقا فإن أردت حذف المقبول سابقا و استبدال الموعد الحالي به فاضغط على موافق\')"'
 
         .  ' value="قبول" '.' name="change_previous_accepted_into_pending_and_edit_reject_suggested_examination_date_into_accepted" > ';
		}
		else{
			
	 $subnit_input_val=' <input type="submit" class="btn btn-success text-center" 
style="margin-bottom: 12px;align-items: center;text-align: center;"

  onClick="return confirm(\'هل أنتَ متأكد من قبولك لهذا الموعد المرفوض سابقا علما بأن المجموعة التي اخترتها لم يتم قبول أي موعد لها مسبقا؟\')"'
 
         .  ' value="قبول" '.' name="accept_reject_or_pending_suggested_examination_date_into_accepted" > ';
		}
    
}
		elseif($status=="accepted"){
				 $subnit_input_val=' <input type="submit" class="btn btn-danger text-center" 
style="margin-bottom: 12px;align-items: center;text-align: center;"

  onClick="return confirm(\'هل أنتَ متأكد من رفضك لهذا الموعد المقبول؟\')"'
  
         .   ' value="رفض" '.' name="change_accepted_suggested_examination_date_into_reject"> ';
		 
         }
		 
		echo $subnit_input_val;
 
	
   

 

 echo '
</td>
<input type="hidden" name="grp_id" value="'.
		$get_all_time_of_this_grp[$i]["g_id"].'" />
<input type="hidden" name="room_id" value="'.
		$get_all_time_of_this_grp[$i]["room_id_fk"].'" />
<input type="hidden" name="time_id" value="'.
		$get_all_time_of_this_grp[$i]["time_id"].'" />
<input type="hidden" name="date_id" value="'.
		$get_all_time_of_this_grp[$i]["date_val"].'" />
		</tr>

</form>';
		 
	}
}
 
?>
 
 
</tbody>
</table>
</div>
</div>
</div>
</div>
 
 
 <?php
  
  include('../includes/footer.php');
?>
 
