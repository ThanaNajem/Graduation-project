<?php
    session_start();
   // ob_start();
  include('../db_op.php');  
  date_default_timezone_set('israel');
  $supervisor_login_id='';
  $status=false;
  if (isset($_SESSION["user_id"])) {
  	 $supervisor_login_id= $_SESSION["user_id"];
  }
 
 

?>
  <!DOCTYPE html>
<?php include('../includes/header.php'); 
?>
<div class="container">
<?php
$err_login=
	'<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong>لم تقم بتسجيل الدخول</strong></div>
<div class="col-sm-4 "></div>
</div>';
 if(!(isset($_SESSION["user_id"])  &&  isset($_SESSION["role"]) && 
 !empty($_SESSION["role"]) && !empty($_SESSION["user_id"]) && $_SESSION["role"]==2 )){die($err_login);}
 
  $chk_no_of_semester_tbl_rows=Crud_op::get_active_semester_tbl_row_count1();
 $semeter_err='<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong>لم يتم تفعيل الفصل الدراسي</strong></div>
<div class="col-sm-4 "></div>
</div>';
	if($chk_no_of_semester_tbl_rows==null) {include('../includes/footer.php');die($semeter_err);}
	 $evt_name="Sorting student marks";
 $arr_from_and_to_evt_date=Crud_op::get_first_and_end_date_for_evt($evt_name);
$time_err='<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong>غير مسموح فرز العلامات هذه الفترة</strong></div>
<div class="col-sm-4 "></div>
</div>';
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
 
//$current_Date = strtotime($current_Date);
$current_Date= date("Y-m-d H:i:s", $current_Date); 

//$from_date = strtotime($from_date);
$from_date= date("Y-m-d H:i:s", $from_date);

//$to_date = strtotime($to_date);
$to_date= date("Y-m-d H:i:s", $to_date);

  
 
 if($current_Date>=$from_date && $current_Date<=$to_date){
 	 
$status=true;}

if (!$status) { 
/**/
 
	  include('../includes/footer.php');die($time_err); 
/**/
}}else{

	  include('../includes/footer.php');die($time_err); 
}

$check_if_this_examiner_has_accepted_grp=Crud_op::check_if_this_examiner_has_accepted_grp($supervisor_login_id);
 $check_if_this_examiner_has_accepted_grp_err='<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong>ليس لديك مجموعات مقبول مناقشتها</strong></div>
<div class="col-sm-4 "></div>
</div>';
if($check_if_this_examiner_has_accepted_grp==null){
include('../includes/footer.php');die($check_if_this_examiner_has_accepted_grp_err);	
}
$get_all_key_creteria=Crud_op::get_all_key_creteria();
$get_all_key_creteria_err='<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong>لم يتم إضافة عنوانين رئيسية لهذا الفصل</strong></div>
<div class="col-sm-4 "></div>
</div>';
if($get_all_key_creteria==null){
	include('../includes/footer.php');
die($get_all_key_creteria_err);	
}

//check if sum of sub =100
$check_if_sum_of_sub_creteria_equal_to_100 = Crud_op::check_if_sum_of_sub_creteria_equal_to_100();

 $check_if_sum_of_sub_creteria_equal_to_100_err='<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong>لم يتحدد توزيع المئة علامة كاملة على المعايير الفرعية</strong></div>
<div class="col-sm-4 "></div>
</div>'; 

	if(intval($check_if_sum_of_sub_creteria_equal_to_100)!=100) {include('../includes/footer.php');die($check_if_sum_of_sub_creteria_equal_to_100_err);}	
	 
	 
?> 
 <div class="row" style="margin-bottom: 12px;">
	<div class="form-group text-center">
	  <div class="col-sm-4"></div>
    <div class="col-sm-4">
        <a class="btn icon-btn btn-success" href="put_std_grade_edit.php?action=add"
		name="put_std_grade_edit" style="font-size: 19px;font-weight: bold;"
		><span class="glyphicon btn-glyphicon glyphicon-plus-sign img-circle text-success"
		style="color: white;background-color: #83e88b" ></span>إضافة علامات كل طالب</a>
 
    </div>
    <div class="col-sm-4"></div>
	</div>
	</div>
	<?php
	if(
	((isset($_POST['add_this_grade']) && isset($_POST['std'])) ||(isset($_POST['std_id_val'])&&isset($_POST['edit_this_grade'])))
	&&
	(
	
	 
	isset($_POST['sub_creteria_id'])
	&&
	isset($_POST['sub_max_mark'])
	&& 
	isset($_POST['mark'])
	)
	){
		  $selected_std_id='';
		 if(isset($_POST['add_this_grade'])){
			 $selected_std_id = $_POST['std'];
		 }
		
		
		$sub_creteria_id_arr = $_POST['sub_creteria_id']; 
		$sub_max_mark_arr = $_POST['sub_max_mark'] ;
		$mark_arr = $_POST['mark'] ;
		$check_if_this_mark_in_high_bounded_range=true;
		for($e=0;$e<count($mark_arr);$e++){
			if($mark_arr[$e]>$sub_max_mark_arr[$e]){
				$check_if_this_mark_in_high_bounded_range=false;
				break;
				 
				
				
			}
			
		}
		if($check_if_this_mark_in_high_bounded_range){
		//
		
		$insert_all_std_grade_for_sub_creteria=Crud_op::insert_all_std_grade_for_sub_creteria($selected_std_id,$sub_creteria_id_arr,$mark_arr);
		$update_all_std_grade=false;
		if(isset($_POST['std_id_val'])){
		$update_all_std_grade = Crud_op::update_all_std_grade($sub_creteria_id_arr,$mark_arr,$_POST['std_id_val']);
			
		}
		
		if($insert_all_std_grade_for_sub_creteria || $update_all_std_grade){	
		$err='<div class="row"  style="margin-top: 12px;" >
 
		<div class="col-sm-8 col-sm-offset-2 alert alert-success text-center"><strong>تمت العملية بنجاح</strong></div>
		 
		</div>';}
				else{	$err='<div class="row"  style="margin-top: 12px;" >
		 
		<div class="col-sm-8 col-sm-offset-2 alert alert-danger text-center"><strong>لم تتم العملية بنجاح</strong></div>
		 
		</div>';}
		
												}
												else{
													$err='<div class="row"  style="margin-top: 12px;" >
		 
		<div class="col-sm-8 col-sm-offset-2 alert alert-danger text-center"><strong>أدخلت علامة أعلى من الحد المسموح به</strong></div>
		 
		</div>';
												}
												echo $err;
	//	
	$_POST = array();
		}
		
	$input_hidden_val='';
  if(
  ( isset($_REQUEST['action']) && $_REQUEST['action'] == "add" )
  ||
  ( (isset($_REQUEST['action']) && $_REQUEST['action'] == "edit")&& !empty($_GET['std_id']) &&
  (Crud_op::check_if_this_std_id_has_grade_in_db($_GET['std_id'])!=null))
  ){
	  $check_if_this_std_id_has_grade_in_db='';
	  if(isset($_REQUEST['action']) && $_REQUEST['action'] == "edit"){
		  
	 $check_if_this_std_id_has_grade_in_db = Crud_op::check_if_this_std_id_has_grade_in_db($_GET['std_id']);
	  }
	   $get_all_students_for_this__supervisors_dont_has_a_sub_grade=Crud_op::get_all_students_for_this__supervisors_dont_has_a_sub_grade($supervisor_login_id);
 
	  if( isset($_REQUEST['action']) && $_REQUEST['action'] == "add" ){
		
		$get_all_students_for_this__supervisors_dont_has_a_sub_grade_err=
'<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong>لا يوجد طلاب علاماتهم غير مفرزة أو أنك لم تقبلهم كمجموعات لديك كممتحن</strong></div>
<div class="col-sm-4 "></div>
</div>';

if($get_all_students_for_this__supervisors_dont_has_a_sub_grade==null ){
	echo  $get_all_students_for_this__supervisors_dont_has_a_sub_grade_err;	
}  
	  }
	   $get_key_creteria_info =''; 
	  if ((isset($_REQUEST['action']) && $_REQUEST['action'] == "edit")    ) {
		 $criteria_id = $_GET['std_id']; 
	 
		
	  $input_hidden_val='<input type="hidden" name="std_id_val" value="'.$_GET['std_id'].'" />';
	  
	  }
	  ?>
	  <!--div class="row" style="margin-bottom: 12px;">
	  <div class="col-sm-8 col-sm-offset-2"-->
	<div class="form-group text-center">

<form method="POST" action="put_std_grade_edit.php">

  <div class="row">
  <div class="col-sm-8 col-sm-offset-2">
  <!-- start return supervisor's student -->
  
  
<?php 
echo $input_hidden_val;  
 if (isset($_REQUEST['action']) && $_REQUEST['action'] == "add" &&($get_all_students_for_this__supervisors_dont_has_a_sub_grade!=null)) {
	 echo '<label class="std">اختر اسم الطالب المراد وضع علامة له</label>
  <select class="form-control" name="std" id="std" style="margin: 20px 12px; 
    width: 100%;background-color: #eee; " required="required" autofocus="autofocus"> 
  <option class="defult text-center">يرجى الاختيار</option>';
if($get_all_students_for_this__supervisors_dont_has_a_sub_grade!=null){
    for($i=0;$i<count($get_all_students_for_this__supervisors_dont_has_a_sub_grade);$i++){
		 
       echo '<option class="text-center" value="'.$get_all_students_for_this__supervisors_dont_has_a_sub_grade[$i]["student_id"].'"
       >'.$get_all_students_for_this__supervisors_dont_has_a_sub_grade[$i]["student_id"].' - '.$get_all_students_for_this__supervisors_dont_has_a_sub_grade[$i]['fname'].' '.$get_all_students_for_this__supervisors_dont_has_a_sub_grade[$i]['lname'].'</option>';  
    }
      echo ' </select>'; 
  }
   

 }
  elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == "edit") {
  echo'<label class="std">اسم و رقم الطالب المراد تعديل علامته</label>';
   echo '<input type="text" style="margin-bottom:12px;" class="form-control" readonly value="'. $check_if_this_std_id_has_grade_in_db[0]["std_id"].' - '.
 $check_if_this_std_id_has_grade_in_db[0]['fname'].
 
 ' '.$check_if_this_std_id_has_grade_in_db[0]['lname'].'" name="std_id" >';

   }
   ?>
  
	 </div>
	 </div>
	 <?php
	 if( (isset($_REQUEST['action']) && $_REQUEST['action'] == "add" &&($get_all_students_for_this__supervisors_dont_has_a_sub_grade!=null))
		 ||
	 
		 (isset($_REQUEST['action']) && $_REQUEST['action'] == "edit")
	 
	 ){
	 ?>
	 <div class="row" style="direction: rtl;
      
     
    border: 1px solid rgba(0, 0, 0, .15);
  ">
  <div class="col-sm-8 col-sm-offset-2">
	
  <!-- end return supervisor's student -->
  <?php
  for($i=0;$i<count($get_all_key_creteria);$i++){
	 $key_criteria_id = $get_all_key_creteria[$i]['key_criteria_id'];
	 
	   $criteria_name=$get_all_key_creteria[$i]['criteria_name'];
	   $get_sub_creteria_for_specific_key = Crud_op::get_sub_creteria_for_specific_key($key_criteria_id);
	   if($get_all_key_creteria!=null && $get_sub_creteria_for_specific_key!=null ){
  ?>
 <fieldset  style="direction: rtl;
    padding: 20px;
    margin: 20px;
    border: 1px solid rgba(0, 0, 0, .15);
    
    width: 571px;
    display: block;
    border-radius: 3px; 
   ">
   
	
  <legend style=" border: 2px dashed rgba(0, 0, 0, 0.5);
    padding: 2px 5px;
    width: 100%;
    height: 42px;
    border-radius: 3px;"><span ><?php  echo 'اسم المعيار الرئيسي :'. $criteria_name; ?></span></legend>
    
	 
	<?php 

for($k=0;$k<count($get_sub_creteria_for_specific_key);$k++){
	?>
 <label><?php
 echo 'اسم المعيار الفرعي: '. $get_sub_creteria_for_specific_key[$k]['sub_name'].' /'.' أعلى علامة مسموحة به: '. $get_sub_creteria_for_specific_key[$k]['sub_max_mark'];
 ?></label>
 
 
 <?php
  if (isset($_REQUEST['action']) && $_REQUEST['action'] == "add" &&($get_all_students_for_this__supervisors_dont_has_a_sub_grade!=null)) {
	  
	  echo ' <input type="number" style="margin-bottom=12px; display:block; " class="form-control" placeholder="أدخل علامة هذا المعيار الفرعي" 
 name="mark[]">';
  }
  elseif(isset($_REQUEST['action']) && $_REQUEST['action'] == "edit"){
	  echo ' <input type="number" style="margin-bottom=12px; display:block; " class="form-control" max="'
	  .$check_if_this_std_id_has_grade_in_db[$k]['sub_max_mark'].'" 
 name="mark[]" value="'.$check_if_this_std_id_has_grade_in_db[$k]['grade_of_fixed_sub_creteria'].'">';
  }
 ?>

  
  <input type="hidden" value="<?php echo $get_sub_creteria_for_specific_key[$k]['sub_criteria_id']; ?>" name="sub_creteria_id[]" />
  <input type="hidden" value="<?php echo $get_sub_creteria_for_specific_key[$k]['sub_max_mark']; ?>" name="sub_max_mark[]" />
  
  <?php 
}
  ?>
  
  
  </fieldset> 
<?php
  }
  }
   echo ' 
 
	 </div>';}
	 echo '
 </div>
  <div class="row" style="direction: rtl; 
  ">
  <div class="col-sm-8 col-sm-offset-2">
';
  
 
 
if(isset($_REQUEST['action']) && $_REQUEST['action'] == "add"  &&($get_all_students_for_this__supervisors_dont_has_a_sub_grade!=null)){
	 
 echo '<input type="submit" class="btn btn-success text-center submit"  style="margin-top: 12px; align-items: center;/*float:right;*/text-align: center;"

  required="required" value="إضافة علامة الطالب"  disabled="disabled"

onClick="return confirm(\'هل أنت متأكد من رغبتك بإضافة هذه العلامات للطالب المختار\')"
 name="add_this_grade">';
	 
}
elseif ((isset($_REQUEST['action']) && $_REQUEST['action'] == "edit")  ) {
	# code...
	 
	echo '<input type="submit" class="btn btn-success text-center submit"  style="margin-top: 12px; align-items: center;/*float:right;*/text-align: center;"

  required="required" value="تعديل علامة الطالب "
	
onClick="return confirm(\'هل أنت متأكد من رغبتك بتعديل هذه العلامات للطالب المختار\')" 
	  name="edit_this_grade">';
	 
}
?>  
 
 
</form>

</div>
</div>
 
<?php
  }
  elseif( (isset($_REQUEST['action']) && $_REQUEST['action'] == "del") && (isset($_GET['std_id']))){
	  
	 $delete_fixed_sub_creteria_grade_for_std = Crud_op::delete_fixed_sub_creteria_grade_for_std($_GET['std_id']);
	 if($delete_fixed_sub_creteria_grade_for_std==0){
		 echo '<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong>لم تتم العملية بنجاح</strong></div>
<div class="col-sm-4 "></div>
</div>';
	 }
	 else{
		 echo '<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-success text-center"><strong>تمت العملية بنجاح</strong></div>
<div class="col-sm-4 "></div>
</div>';
	 }
  }
 $get_all_student_has_sub_creteria_grades = Crud_op::get_all_student_has_sub_creteria_grades();
 
 
 	$get_rowCount_of_sub_creteria_in_this_semester = Crud_op::get_rowCount_of_sub_creteria_in_this_semester();
  echo 
   '
   <div class="row"style="margin-bottom: 12px;">
	<!-- get data from idea table-->

	<div class="table-responsive text-center " style="direction: rtl;margin-top: 12px;">          
  <table class="table  table-hover table-striped table-bordered text-center" id="tbl1" style="align-items: center;">
<thead>
<tr>
 <th>معلومات الطالب</th>
 ';
 $thead_output="";
$get_all_key_creteria_which_has_sub_creteria = Crud_op::get_all_key_creteria_which_has_sub_creteria();
  for ($i=0; $i < count($get_all_key_creteria_which_has_sub_creteria); $i++) { 
  	$creteria_id=$get_all_key_creteria_which_has_sub_creteria[$i]['key_criteria_id'];

  	 $get_all_sub_creteria = Crud_op::get_all_sub_creteria_for_specific_main_creteria($chk_no_of_semester_tbl_rows[0]['auto_inc_id'],$creteria_id);
  	  
$thead_output.='<th colspan="'.count($get_all_sub_creteria).'" >'.'اسم العيار الرئيسي '.($i+1).$get_all_key_creteria_which_has_sub_creteria[$i]['criteria_name'].'</th>';
  }
echo $thead_output;
echo '<th rowspan="2" class="text-center">الإجراء</th>';
 echo '
</tr>
   
   
   ';
echo '<tr>';
  echo '<th>اسم الطالب و رقمه</th>';
$get_all_key_creteria_which_has_sub_creteria=Crud_op::get_all_key_creteria_which_has_sub_creteria();
   for($o=0;$o<count($get_all_key_creteria_which_has_sub_creteria);$o++){

$creteria_id=$get_all_key_creteria_which_has_sub_creteria[$o]['key_criteria_id'];
   	 $get_all_sub_creteria = Crud_op::get_all_sub_creteria_for_specific_main_creteria($chk_no_of_semester_tbl_rows[0]['auto_inc_id'],$creteria_id);
for ($i=0; $i <count($get_all_sub_creteria) ; $i++) { 
	# code...
	  


	  echo'<th>'.'اسم المعيار الفرعي'.' : '.$get_all_sub_creteria[$i]['sub_name'].'</th>';
}  

   }
   
  

  echo ' 
  	  
   </tr>
</thead>
<tbody>';
 $get_std_which_has_grades_for_sub_creteria =Crud_op::get_std_which_has_grades_for_sub_creteria();
if($get_all_student_has_sub_creteria_grades!=null && $get_std_which_has_grades_for_sub_creteria!=null){ 
		
		for($w=0;$w<count($get_std_which_has_grades_for_sub_creteria);$w++){
			echo '<tr>';
			echo '<td>'.
	 $get_std_which_has_grades_for_sub_creteria[$w]['usr_id'].
	 ' - '.
	 $get_std_which_has_grades_for_sub_creteria[$w]['fname'].
	 ' '.
	 $get_std_which_has_grades_for_sub_creteria[$w]['lname'].
	 '</td>';
			for($z=0;$z<count($get_all_student_has_sub_creteria_grades);$z++){
				if($get_std_which_has_grades_for_sub_creteria[$w]['usr_id'] == $get_all_student_has_sub_creteria_grades[$z]['usr_id'] ){
				  
				//	echo '<td>'.$get_all_student_has_sub_creteria_grades[$z]['sub_name'].'</td>'; 
				echo '<td>'.$get_all_student_has_sub_creteria_grades[$z]['grade_of_fixed_sub_creteria'].'</td>';
				
				}


			}
			//start tst
			 echo ' <td> <a href="put_std_grade_edit.php?action=edit&std_id='.$get_all_student_has_sub_creteria_grades[$w]['usr_id'].'" 
  class="btn btn-primary btn-sm a-btn-slide-text" id="edit_semester"  style="margin-top: 3px;" >
        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
        <span><strong>تعديل</strong></span>            
    </a></td> ';
	 //end tst
			
			echo '</tr>';
			
			
		}
	 
}
else{
	$sum = ($get_rowCount_of_sub_creteria_in_this_semester*2)+2;
	echo '<td colspan="'.$sum.'">لآ يوجد بيانات</td>';
}
 
echo '
</tbody>
</table>
</div>
</div>
';
   
  include('../includes/footer.php');
?>