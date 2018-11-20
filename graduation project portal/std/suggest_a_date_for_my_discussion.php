<?php
    session_start();
    require_once("../db_op.php");
// I will add users types in select option 
    //1=>admin,2=>supervior,3->discussion_committee,4->std,5->Dean_of_the_College
 $user_id=null;
 if(isset($_SESSION["user_id"])){
$user_id=$_SESSION["user_id"];	 
 }
 $status=false;

date_default_timezone_set('israel');
 $usr_grp = Crud_op::check_if_this_usr_has_grp($user_id);
  
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
 !empty($_SESSION["role"]) && !empty($_SESSION["user_id"]) && $_SESSION["role"]==4 )){die($err_login);}
 
  $chk_no_of_semester_tbl_rows=Crud_op::get_active_semester_tbl_row_count1();
 $semeter_err='<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong>لم يتم تفعيل الفصل الدراسي</strong></div>
<div class="col-sm-4 "></div>
</div>';
	if($chk_no_of_semester_tbl_rows==null) {
include('suggest_a_date_for_my_discussion_footer.php');die($semeter_err);}
  $event_name="period_allowed_for_discussions";
	$period_allowed_for_discussions = Crud_op::get_first_and_end_date_for_evt($event_name);
	$time_of_discussion_is_not_allowed_err=
	'<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong>لم يتم تحديد تاريخ بداية و نهاية كفترة للمناقشة</strong></div>
<div class="col-sm-4 "></div>
</div>';
	if($period_allowed_for_discussions==null){
include('suggest_a_date_for_my_discussion_footer.php');die($time_of_discussion_is_not_allowed_err);
include('suggest_a_date_for_my_discussion_footer.php');} 
	$get_rooms_err=
	'<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong>لم يتم تعبئة قاعات</strong></div>
<div class="col-sm-4 "></div>
</div>'; 
$get_rooms=Crud_op::get_rooms();
	if($get_rooms==null){
include('suggest_a_date_for_my_discussion_footer.php');die($get_rooms_err);}
	
	
 
  
   $auto_inc_id=$chk_no_of_semester_tbl_rows[0]['auto_inc_id']; 
   $get_times_err=
	'<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong>لم يتم تعيين أوقات للمناقشات خلال اليوم الواحد</strong></div>
<div class="col-sm-4 "></div>
</div>'; 
$get_times=Crud_op::get_times();
	if($get_times==null){
include('suggest_a_date_for_my_discussion_footer.php');die($get_times_err);}
    
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

//$from_date = strtotime($from_date);
$from_date= date("Y-m-d H:i:s", $from_date);

//$to_date = strtotime($to_date);
$to_date= date("Y-m-d H:i:s", $to_date);

//$current_Date = strtotime($current_Date);
$current_Date= date("Y-m-d H:i:s", $current_Date); 
 
 if($current_Date>=$from_date && $current_Date<=$to_date){
   
$status=true;}
}
$status_err='<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong>غير مسموح اقتراح موعد لمناقشتك هذه الفترة</strong></div>
<div class="col-sm-4 "></div>
</div>';
if(!$status){
include('suggest_a_date_for_my_discussion_footer.php');die($status_err);}
 
  # code...
  //بدي افحص ان كان الطالب مقبول بالمناقشة و الا لا من خلال جدول ال
  //examination 
  //بفحص الستيتس
  //
  if($usr_grp!=null){ 
	  if(Crud_op::check_if_this_user_grp_accept_in_examination($usr_grp)!=0){
		  //start
			  echo '
	   <div class="row" style="margin-bottom: 12px;">
  <div class="form-group text-center">
    <div class="col-sm-4"></div>
    <div class="col-sm-4">
        <a class="btn icon-btn btn-success" href="suggest_a_date_for_my_discussion.php?action=add" 
		name="add-old-year" style="font-size: 19px;font-weight: bold;">
		<span class="glyphicon btn-glyphicon glyphicon-plus-sign img-circle text-success" 
		style="color: white;background-color: #83e88b" >
		</span>اقتراح موعد لمناقشتي</a>
 
    </div>
    <div class="col-sm-4"></div>
</div>
</div>

	  ';
//
if(isset($_REQUEST['action']) && $_REQUEST['action'] == "add")     {
		$get_grp_time_status = Crud_op::check_if_grp_time_status_is_accepted($usr_grp); 
		if($get_grp_time_status!=null   ){ //this means this grp time accepted
	  
//`room_id_fk`,from_time,to_time,
$date_val = $get_grp_time_status[0]['date_val'];
$from_time = $get_grp_time_status[0]['from_time'];
$to_time = $get_grp_time_status[0]['to_time'];
$period_time = $from_time.' - '.$to_time;
$room_no =$to_time = $get_grp_time_status[0]['room_id_fk'];;
$output= ' <div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong>'.'تم قبول موعد مقترح لك و هو '.'<br>'
.'رقم القاعة: '.$room_no.'<br>'.
"بموعد: ".$date_val." / ".$period_time."<br>".
 "<br>" 

."لذا لن  تتمكن من اقتراح المزيد من المواعيد إلآ إذا حذفتَ موعد المقبول به".'</strong></div>
<div class="col-sm-4 "></div>
</div>';  
		echo $output; 
		}
		 
		elseif(Crud_op::get_grp_time_status($usr_grp)==null || $get_grp_time_status==null ){
			//this means no grp_time_accepted 
			?><form method="post" action="suggest_a_date_for_my_discussion.php">
			<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-6 col-sm-offset-3 ">
			
			<div class="form-group">
			<label class="defult text-center">اختر رقم القاعة</label>
			<select class="form-control" name="hall_name" id="evt_name" style="background-color: #eee;margin-bottom: 12px;" required="required"> 
  
<option class="defult text-center">يرجى الاختيار</option>

			<?php
		for($k=0;$k<count($get_rooms);$k++){ 
		echo '<option class="text-center" value="'.$get_rooms[$k]["room_id"].'"
       >'.$get_rooms[$k]["room_description"].'</option>'; 
	   }
	  ?>
	  </select>
			<label class="defult text-center">اختر التوقيت</label>
			<select class="form-control" name="time_val" id="evt_name" style="background-color: #eee;margin-bottom: 12px;" required="required"> 
  
<option class="defult text-center">يرجى الاختيار</option>

			<?php
		for($k=0;$k<count($get_times);$k++){ 
		echo '<option class="text-center" value="'.$get_times[$k]["id"].'"
       >'.$get_times[$k]["from_time"].' - '.$get_times[$k]["to_time"].'</option>'; 
	   }
	  ?>
	  </select>
			<label class="defult text-center">اختر التاريخ</label>
			 <!-- datepicker source: https://github.com/uxsolutions/bootstrap-datepicker -->
<!-- tutorial: https://formden.com/blog/date-picker -->

  
        <div id="filterDate2">
          
          <!-- Datepicker as text field -->         
          <div class="input-group date" data-date-format="yyyy-mm-dd">
            <input  type="text" name="date_value" class="form-control" placeholder="اختر التاريخ" required>
            <div class="input-group-addon" >
              <span class="glyphicon glyphicon-th"></span>
            </div>
          </div>
          
        </div>  




	  </div>
	  <?php
	   $subnit_input_val=' <input type="submit" class="btn btn-success text-center submit" 
style="margin-bottom: 12px;align-items: center;text-align: center;"

  onClick="return confirm(\'هل أنتَ متأكد من إضافة هذه البيانات؟\')"'
 
         .  ' value="إضافة هذا الموعد" '.' name="add_suggested_examination_date" '.' disabled="disabled">  ';
        
        echo $subnit_input_val;
	  ?>
	  
	  </div>
	  </div>
	  </form>
	  <?php
			 
		}
		 
		 
		}
	 
 elseif(
 isset($_POST['add_suggested_examination_date'])
 && isset($_POST['date_value'])
 && isset($_POST['hall_name'])
 && isset($_POST['time_val'])
 ){
	 try{
		$time_val = $_POST['time_val'];
		$hall_name = $_POST['hall_name'];
		$date_value = $_POST['date_value'];
		$get_from_and_to_time = Crud_op::get_specific_info_about_specific_time($time_val);
		$from_time = $get_from_and_to_time[0]['from_time'];
		$to_time = $get_from_and_to_time[0]['to_time'];
		
		$date_value1=date('Y-m-d',strtotime($date_value)) ;
	 	// $to_date_and_time=date('Y-m-d H:i:s',strtotime($to_date_and_time));
		//$to_date_and_time=date('Y-m-d H:i:s',strtotime($to_date_and_time));
		 $from_date_and_time=$date_value.' '. $from_time;
		 $to_date_and_time=$date_value.' '. $to_time;
		 
	 	// $from_date_and_time=date('Y-m-d H:i:s',strtotime($from_date_and_time));
		  $check_overlapping = Crud_op::check_overlapping_for_group_time($from_date_and_time,$to_date_and_time,$hall_name);
	$check_overlapping_err=' 
	<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong>هناك طالب حجز هذا المووعد</strong></div>
<div class="col-sm-4 "></div>
</div>';
	if($check_overlapping!=null){
		echo  $check_overlapping_err ;
	}
	$semester_no = $chk_no_of_semester_tbl_rows[0]['auto_inc_id'];
	$rowCount = Crud_op::insert_into_group_time($usr_grp,$time_val,$date_value1,$hall_name,$user_id,$status="pending",$semester_no);	
	
	if($rowCount==0){
	$msg='لم تتم العملية بنجاح';
		$class_val='danger';
	}
	else{
	$msg='تمت العملية بنجاح';
		$class_val='success';	
	}
	$no_row_inserted_msg = '<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-'.$class_val.' text-center"><strong>'.$msg.'</strong></div>
<div class="col-sm-4 "></div>
</div>';
echo  $no_row_inserted_msg ;
	
	 }
	 catch(PDOException $ex){
	//	echo $ex->getMessage();
		 $repeatition_err=' 
		 <div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong>لم تتم العملية بنجاح لربما قمتَ بإضافة موعد أضفته سابقا أو أن مجموعة غيرك اقترحت هذا الموعد فيرجى تغيرره</strong></div>
<div class="col-sm-4 "></div>
</div>';
		echo  $repeatition_err ;
	 }
$_POST = array();	 
 }
		    }
	  else{
		echo'
  <div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong>لستّ مقبولا في المناقشة فلن تتمكن من اقتراح أي مواعيد لمناقشتك</strong></div>
<div class="col-sm-4 "></div>
</div>
'; 
	  }
	 
  }
  else{
	 echo '
  <div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong>
 لستَ منضما لأي مجموعة لذا لن تتمكن من اقتراح مواعيد للمناقشة 
</strong></div>
<div class="col-sm-4 "></div>
</div>';
   
  }
  
?>

</div>
 

 
<?php
 
$get_all_time_of_this_grp=Crud_op::get_all_time_of_this_grp($usr_grp,$user_id);

 ?>
 <div class="row">
<div class="col-sm-2"></div>
<div class="col-sm-8">
<div class="form-group text-center">
<div class="table-responsive text-center " style="direction: rtl;margin-top: 12px;">          
  <table class="table  table-hover table-striped table-bordered text-center" id="tbl1" style="align-items: center;">
<thead>
   
     
    <th>رقم القاعة</th>
    <th>وقت المناقشة</th> 
    <th>تاريخ المناقشة</th>
<th>حالة الطلب</th>	
    <th>الإجراء</th>
   
</thead>
<tbody>


<?php
if(
(
isset($_POST['resend_suggested_examination_date'])
||
isset($_POST['cancel_pending_suggested_examination_date'])
||
isset($_POST['cancel_accepted_suggested_examination_date'])
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
	$grp_id = $_POST['grp_id'];
	$room_id= $_POST['room_id'];
	$time_id = $_POST['time_id'];
	$date_id= $_POST['date_id']; 
	 $semester_no1=$chk_no_of_semester_tbl_rows[0]['auto_inc_id']; 
	 if(isset($_POST['cancel_accepted_suggested_examination_date'])
	)	{
		 $change_status="pending";
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
	
	$data = Crud_op::check_if_this_grp_has_accepted_status_in_this_semester($usr_grp,$semester_no1);
	if($data!=null){
		$err=' 
		 <div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center">
<strong>تم قبولك بهذا المكان و التوقيت <br>رقم القاعة : '.$data[0]['room_id_fk'].'<br> التوقيت: '.$data[0]['from_time'].' - '.$data[0]['to_time'].' / '.$data[0]['date_val'].'</strong>
</div>
<div class="col-sm-4 "></div>
</div>';
		echo $err;
	}
	else{
	if(
	isset($_POST['resend_suggested_examination_date'])
	 
	)	{
		 $change_status="pending";
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
	elseif(isset($_POST['cancel_pending_suggested_examination_date'])){
		 $deleted_row_count = Crud_op::delete_pending_status($semester_no1,$grp_id,$time_id,$date_id,$room_id);
		if($deleted_row_count==0){
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
		 
	}
	echo "<meta http-equiv=refresh content=\"0; URL=suggest_a_date_for_my_discussion.php\">";
}

if($get_all_time_of_this_grp==null){
	echo '<tr>
<td colspan="5">لا يوجد بيانات</td></tr>';
 }
else{
	for($i=0;$i<count($get_all_time_of_this_grp);$i++){
		echo'<form method="post" action="suggest_a_date_for_my_discussion.php">
		<tr><td>';
		echo
		$get_all_time_of_this_grp[$i]['room_description']; 
		?></td>
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
 if($status=="reject"){
	 $subnit_input_val=' <input type="submit" class="btn btn-success text-center" 
style="margin-bottom: 12px;align-items: center;text-align: center;"

  onClick="return confirm(\'هل أنتَ متأكد من إعادة إرسال هذا الموعد الرفوض سابقا؟\')"'
 
         .  ' value="إعادة إرسال هذا الموعد" '.' name="resend_suggested_examination_date" > ';
    
}
		elseif($status=="accepted"){
				 $subnit_input_val=' <input type="submit" class="btn btn-danger text-center" 
style="margin-bottom: 12px;align-items: center;text-align: center;"

  onClick="return confirm(\'هل أنتَ متأكد من إلغاء هذا الموعد المطلوب؟\')"'
  
         .   ' value="حذف هذا الموعد" '.' name="cancel_accepted_suggested_examination_date"> ';
		 
         }
		elseif($status=="pending"){
				 $subnit_input_val=' <input type="submit" class="btn btn-danger text-center" 
style="margin-bottom: 12px;align-items: center;text-align: center;"
  onClick="return confirm(\'هل أنتَ متأكد من إلغاء هذا الموعد المطلوب؟\')"'
 
         .  ' value="إلغاء إرسال هذا الموعد" '.' name="cancel_pending_suggested_examination_date" >';
		 
         }
		echo $subnit_input_val;
 
	
?>  
  
 

  
</td>
<input type="hidden" name="grp_id" value="<?php echo
		$get_all_time_of_this_grp[$i]['g_id'];  ?>" />
<input type="hidden" name="room_id" value="<?php echo
		$get_all_time_of_this_grp[$i]['room_id_fk'];  ?>" />
<input type="hidden" name="time_id" value="<?php echo
		$get_all_time_of_this_grp[$i]['time_id'];  ?>" />
<input type="hidden" name="date_id" value="<?php echo
		$get_all_time_of_this_grp[$i]['date_val'];  ?>" />
		</tr>

</form>
		<?php
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
include('suggest_a_date_for_my_discussion_footer.php');
?>