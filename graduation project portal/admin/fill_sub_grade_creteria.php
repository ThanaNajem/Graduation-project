<?php
    session_start();
   // ob_start();
  include('../db_op.php');  
  date_default_timezone_set('israel');
  
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
 !empty($_SESSION["role"]) && !empty($_SESSION["user_id"]) && $_SESSION["role"]==1 )){die($err_login);}
 
  $chk_no_of_semester_tbl_rows=Crud_op::get_active_semester_tbl_row_count1();
 $semeter_err='<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong>لم يتم تفعيل الفصل الدراسي</strong></div>
<div class="col-sm-4 "></div>
</div>';
	if($chk_no_of_semester_tbl_rows==null) {include('../includes/footer.php');die($semeter_err);}
	
$get_all_sub_creteria = Crud_op::get_all_key_creteria($chk_no_of_semester_tbl_rows[0]['auto_inc_id']);
 $get_all_sub_creteria_err='<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong>لا يتوفر علامات للمعايير الرئيسية بالنسبة للفصل المفعّل حاليا</strong></div>
<div class="col-sm-4 "></div>
</div>';
	if($get_all_sub_creteria==null) {include('../includes/footer.php');die($get_all_sub_creteria_err);}	
	if(Crud_op::get_row_count_of_sub_cretria()){include('../includes/footer.php');die('<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong>هناك علامات للطلاب قام الممتحنون بإضافتها يمنع لك إضافة أو تعديل أي معيار حتى يقوم الممتحنون بحذف العلامات المدرجة</strong></div>
<div class="col-sm-4 "></div>
</div>');}
	//start build form here and allow edit and delete using submit and input of type hidden and make $get_all_sub_creteria result as 
	//value content of select option of main key in select opyion then put text to fill sub key then put minmum mark and maximum mark
	//min and max must be number and submit and I must check if sumation of all sub less than max mark of main every submit in add or edit
	//as I do in fill_main_grade_creteria .php 
	//افتحي ملف المشاريع اللي من عطاء مشان اوخذ منه شغلات الفورم
	
	
?>

 <div class="row" style="margin-bottom: 12px;">
	<div class="form-group text-center">
	  <div class="col-sm-4"></div>
    <div class="col-sm-4">
        <a class="btn icon-btn btn-success" href="fill_sub_grade_creteria.php?action=add"
		name="fill_sub_grade_creteria" style="font-size: 19px;font-weight: bold;"
		><span class="glyphicon btn-glyphicon glyphicon-plus-sign img-circle text-success"
		style="color: white;background-color: #83e88b" ></span>إضافة معايير علامة المشروع الفرعية</a>
 
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
 $input_hidden_val='';
  if(
  ( isset($_REQUEST['action']) && $_REQUEST['action'] == "add" )
  ||
  ( (isset($_REQUEST['action']) && $_REQUEST['action'] == "edit")&& !empty($_GET['sub_criteria_id']) &&
  (Crud_op::check_if_this_sub_criteria_id_in_db($_GET['sub_criteria_id'])!=null))
  ){
	   $get_key_creteria_info =''; 
	  if ((isset($_REQUEST['action']) && $_REQUEST['action'] == "edit")    ) {
		 $criteria_id = $_GET['sub_criteria_id'];
		 $get_key_creteria_info =Crud_op::check_if_this_sub_criteria_id_in_db($_GET['sub_criteria_id']);
	 
		
	  $input_hidden_val='<input type="hidden" name="creteria_id" value="'.$criteria_id.'" />';
	  
	  }
	  ?>
	  <div class="row" style="margin-bottom: 12px;">
	  <div class="col-sm-8 col-sm-offset-2">
	<div class="form-group text-center">
	  <form method="post" action="fill_sub_grade_creteria.php">
	 <?php echo $input_hidden_val;?>
	 <!-- start select -->
	 
  <label class="main_creteria_name">اختر اسم المعيار الرئيسي</label>


    <select class="form-control" name="main_creteria_name" id="main_creteria_name" style="background-color: #eee;margin-bottom: 12px;" required="required"> 
  
<option class="defult text-center">يرجى الاختيار</option>
<?php 
$get_all_key_creteria = Crud_op::get_all_key_creteria($chk_no_of_semester_tbl_rows[0]['auto_inc_id']);
  
 if (isset($_REQUEST['action']) && $_REQUEST['action'] == "add") {
if($get_all_key_creteria!=null){
    for($i=0;$i<count($get_all_key_creteria);$i++){
		$name_and_min_max_mark_for_main_creteria=
		"اسم المعيار: ".$get_all_key_creteria[$i]["criteria_name"].' / '.
		'أعلى علامة: '.$get_all_key_creteria[$i]["top_end_of_mak"].' - '.
		'أقل علامة: '.$get_all_key_creteria[$i]["low_end_of_mak"]
		;
       echo '<option class="text-center" value="'.$get_all_key_creteria[$i]["key_criteria_id"].'"
       >'.$name_and_min_max_mark_for_main_creteria.'</option>';  
    }
     
  }
   

 }
  elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == "edit") {
  
  if($get_all_key_creteria!=null){
    for($i=0;$i<count($get_all_key_creteria);$i++){
       echo '<option class="text-center" value="'.$get_all_key_creteria[$i]['key_criteria_id'].'"';


      echo'';
if($get_key_creteria_info[0]['key_criteria_id_fk']==$get_all_key_creteria[$i]['key_criteria_id']) { 
      echo ' selected="selected"'; 
    }
	$name_and_min_max_mark_for_main_creteria=
		"اسم المعيار: ".$get_all_key_creteria[$i]["criteria_name"].' / '.
		'أعلى علامة: '.$get_all_key_creteria[$i]["top_end_of_mak"].' - '.
		'أقل علامة: '.$get_all_key_creteria[$i]["low_end_of_mak"]
		;
      echo ' >'.$name_and_min_max_mark_for_main_creteria.'</option>';  
    }
     
  }
 
   }
   ?>
   </select>
	 <!-- end select -->
	  <label for="idea_name" style="margin-bottom: 12px;">اسم المعيار الفرعي</label>
	 
  <input type="text" class="form-control" id="idea_name" name="creteria_name" 
  placeholder="أدخل اسم المعيار الفرعي للرئيسي المختار" style="margin-bottom: 12px;" required="required" autofocus="autofocus"
<?php 
if(isset($_REQUEST['action']) && $_REQUEST['action'] == "add"){
	 echo'
value=""> ';
	 
}
elseif ((isset($_REQUEST['action']) && $_REQUEST['action'] == "edit")     ) {
	# code...
	
	 
	echo 'value="'.$get_key_creteria_info[0]['sub_name'].'">';
	 
}
echo '<label for="idea_name" style="margin-bottom: 12px;">أقل علامة لهذا البند</label>
  <input type="number" min="1" max="100" class="form-control" id="min_mark" name="min_mark"
  placeholder="أدخل أقل علامة ممكنة لهذا البند" style="margin-bottom: 12px;" required="required"';
 
if(isset($_REQUEST['action']) && $_REQUEST['action'] == "add"){
	 echo'
value=""> ';
	 
}
elseif ((isset($_REQUEST['action']) && $_REQUEST['action'] == "edit") ) {
	# code...
	
	 
	echo 'value="'.$get_key_creteria_info[0]['sub_min_mark'].'">';
	 
}
echo '<label for="idea_name" style="margin-bottom: 12px;">أعلى علامة لهذا البند</label>
  <input type="number" min="1" max="100"  class="form-control" id="max_mark" name="max_mark"
  placeholder="أدخل أعلى علامة ممكنة لهذا البند" style="margin-bottom: 12px;" required="required"';
 
if(isset($_REQUEST['action']) && $_REQUEST['action'] == "add"){
	 echo'
value=""> ';
	 
}
elseif ((isset($_REQUEST['action']) && $_REQUEST['action'] == "edit") ) {
	# code...
	
	 
	echo 'value="'.$get_key_creteria_info[0]['sub_max_mark'].'">';
	 
}
 echo ' 
 

<input type="submit" class="btn btn-success text-center submit"  style="margin-bottom: 12px; align-items: center;text-align: center;"

  required="required"
 ';
 
 
if(isset($_REQUEST['action']) && $_REQUEST['action'] == "add"){
	 
 echo 'value="إضافة هذا المعيار"  disabled="disabled"

onsubmit="return confirm(\'هل أنت متأكد من رغبتك بإضافة هذا المعيار\')"
 name="add_this_creteria">';
	 
}
elseif ((isset($_REQUEST['action']) && $_REQUEST['action'] == "edit")  ) {
	# code...
	 
	echo 'value="تعديل المعيار المختار"
	
onsubmit="return confirm(\'هل أنت متأكد من رغبتك بتعديل المعيار المختار\')" 
	  name="edit_this_creteria">';
	 
}
  echo '
	  </form>
	  </div>
	  </div>
	  </div>
	  ';
  }
    elseif((isset($_REQUEST['action']) && $_REQUEST['action'] == "del") && !empty($_GET['sub_criteria_id'])){
	  $deleted_row_count = Crud_op::delete_specific_sub_creteria($_GET['sub_criteria_id']);
	  $err='';
	  if($deleted_row_count!=0){$err='<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-success text-center"><strong>تمت العملية بنجاح</strong></div>
<div class="col-sm-4 "></div>
</div>';}
	  else{$err='<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong>لم تتم العملية بنجاح أو أنك قمت بالعبث بالعنوان </strong></div>
<div class="col-sm-4 "></div>
</div>';}
echo $err;
  }
   if(
	 
	( isset($_POST['add_this_creteria'])
	 ||
	 (isset($_POST['edit_this_creteria'])&& isset($_POST['creteria_id']))
	 )
	 && 
	 (isset($_POST['main_creteria_name']) && isset($_POST['creteria_name']) && isset($_POST['min_mark']) && isset($_POST['max_mark']))
	 ){
		 
		 $creteria_id='';
		 
		 if(isset($_POST['edit_this_creteria'])){
			
			$creteria_id = $_POST['creteria_id'];
		}
		   $main_key_creteria_id = $_POST['main_creteria_name'];
		 $check_if_this_main_creteria_has_a_sub_creteria=null; 
		 $check_if_this_main_creteria_has_a_sub_creteria1=null;
		 if(isset($_POST['add_this_creteria'])){
			$check_if_this_main_creteria_has_a_sub_creteria = Crud_op::check_if_this_main_creteria_has_a_sub_creteria($main_key_creteria_id); 
		  
		 }
		 /*
		 elseif(isset($_POST['edit_this_creteria']))){
			 $check_if_this_main_creteria_has_a_sub_creteria1=Crud_op::$check_if_this_main_creteria_has_a_sub_creteria1();
		 }
		 */
		 if(
		( $check_if_this_main_creteria_has_a_sub_creteria==0 &&  (isset($_POST['add_this_creteria'])) )
		||
		  (isset($_POST['edit_this_creteria']))
		 ){
			 
			 if(Crud_op::compare_btn_usr_input_sub_creteria_data_and_key_creteria_max_mark_if_sub_creteria_for_fixed_main_creteria
			 ($_POST['max_mark'],$main_key_creteria_id)!=0){
				 /* if sub for fixed main = null */
				 	/*start if sub !=null for fixed main*/ 
					/*
			$check_if_this_new_sub_grade_dont_exceed_max_value = 
		Crud_op::check_if_this_new_sub_grade_dont_exceed_max_value_allowed_for_each_semester($_POST['max_mark'],$_POST['main_creteria_name']);
		$check_if_this_new_sub_grade_dont_exceed_max_value1 = null;
		if(isset($_POST['edit_this_creteria'])){
			$check_if_this_new_sub_grade_dont_exceed_max_value1 = 
		Crud_op::check_if_this_new_sub_grade_dont_exceed_max_value_allowed_for_each_semester1(
		$_POST['max_mark'],$_POST['main_creteria_name'],$creteria_id);
		
		}
		
		if(
		($check_if_this_new_sub_grade_dont_exceed_max_value!=null && (isset($_POST['add_this_creteria'])))
		||
		($check_if_this_new_sub_grade_dont_exceed_max_value1!=null && (isset($_POST['edit_this_creteria'])))
		){
			*/
		if(isset($_POST['edit_this_creteria'])){
			 $updated_sub_grade = Crud_op::update_exist_sub_grade(
			 $_POST['creteria_name'],$_POST['min_mark'],$_POST['max_mark'],$_POST['main_creteria_name'],$creteria_id
			 );
			 if(!$updated_sub_grade){
				 $err='<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong>لم تتم عملية التعديل بنجاح</strong></div>
<div class="col-sm-4 "></div>
</div>';
			echo $err;
			 }
			 else{
				$err='<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-success text-center"><strong>تمت عملية التعديل بنجاح</strong></div>
<div class="col-sm-4 "></div>
</div>';
			echo $err; 
			 }
			 
			
		} 
		elseif(isset($_POST['add_this_creteria'])){
			
			 $insert_new_sub_grade = Crud_op::insert_new_sub_grade($_POST['creteria_name'],$_POST['min_mark'],
			 $_POST['max_mark'],$_POST['main_creteria_name']);
			 if($insert_new_sub_grade==0){
				 $err='<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong>لم تتم عملية الحفظ بنجاح</strong></div>
<div class="col-sm-4 "></div>
</div>';
			echo $err;
			 }
			 else{
				$err='<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-success text-center"><strong>تمت عملية الحفظ بنجاح</strong></div>
<div class="col-sm-4 "></div>
</div>';
			echo $err; 
			 }
			 
			 }
			 /*
		}
		else{
			$err='<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center">
<strong>قمت بإضافة علامة فرعية تجاوزت قيمتها الحد الأعلى لعلامة المعيار الرئيسي المختار من القائمة 
</strong></div>
<div class="col-sm-4 "></div>
</div>';
			echo $err;
		}
		*/
/**/	
				 /**/
				 
			 }
			 else{$err='<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center">
<strong>قمت بإضافة علامة فرعية تجاوزت قيمتها الحد الأعلى لعلامة المعيار الرئيسي المختار من القائمة 
</strong></div>
<div class="col-sm-4 "></div>
</div>';
			echo $err;}
		 }
		 else{
			/*start if sub !=null for fixed main*/
			$check_if_this_new_sub_grade_dont_exceed_max_value = 
		Crud_op::check_if_this_new_sub_grade_dont_exceed_max_value_allowed_for_each_semester($_POST['max_mark'],$_POST['main_creteria_name']);
		$check_if_this_new_sub_grade_dont_exceed_max_value1 = null;
		if(isset($_POST['edit_this_creteria'])){
			$check_if_this_new_sub_grade_dont_exceed_max_value1 = 
		Crud_op::check_if_this_new_sub_grade_dont_exceed_max_value_allowed_for_each_semester1(
		$_POST['max_mark'],$_POST['main_creteria_name'],$creteria_id);
		
		}
		
		if(
		($check_if_this_new_sub_grade_dont_exceed_max_value!=null && (isset($_POST['add_this_creteria'])))
		||
		($check_if_this_new_sub_grade_dont_exceed_max_value1!=null && (isset($_POST['edit_this_creteria'])))
		){
		if(isset($_POST['edit_this_creteria'])){
			 $updated_sub_grade = Crud_op::update_exist_sub_grade(
			 $_POST['creteria_name'],$_POST['min_mark'],$_POST['max_mark'],$_POST['main_creteria_name'],$creteria_id
			 );
			 if(!$updated_sub_grade){
				 $err='<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong>لم تتم عملية التعديل بنجاح</strong></div>
<div class="col-sm-4 "></div>
</div>';
			echo $err;
			 }
			 else{
				$err='<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-success text-center"><strong>تمت عملية التعديل بنجاح</strong></div>
<div class="col-sm-4 "></div>
</div>';
			echo $err; 
			 }
			 
			
		} 
		elseif(isset($_POST['add_this_creteria'])){
			
			 $insert_new_sub_grade = Crud_op::insert_new_sub_grade($_POST['creteria_name'],$_POST['min_mark'],
			 $_POST['max_mark'],$_POST['main_creteria_name']);
			 if($insert_new_sub_grade==0){
				 $err='<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong>لم تتم عملية الحفظ بنجاح</strong></div>
<div class="col-sm-4 "></div>
</div>';
			echo $err;
			 }
			 else{
				$err='<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-success text-center"><strong>تمت عملية الحفظ بنجاح</strong></div>
<div class="col-sm-4 "></div>
</div>';
			echo $err; 
			 }
			 
			 }
		}
		else{
			$err='<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center">
<strong>قمت بإضافة علامة فرعية تجاوزت قيمتها الحد الأعلى لعلامة المعيار الرئيسي المختار من القائمة 
</strong></div>
<div class="col-sm-4 "></div>
</div>';
			echo $err;
		}
/**/			
		 }
		//
		
		$_POST = array();
	 }
  // start tbl
   echo 
   '
   <div class="row">
	<!-- get data from idea table-->

	<div class="table-responsive text-center " style="direction: rtl;margin-top: 12px;">          
  <table class="table  table-hover table-striped table-bordered text-center" id="tbl1" style="align-items: center;">
<thead>
   <th>اسم المعيار الرئيسي</th>
  <th>اسم المعيار الفرعي</th>
  	<th>أدنى علامة للفرعي</th>
  	<th>أعلى علامة للفرعي</th>
  	<th>الإجراء</th>
   
</thead>
<tbody>';
	 

$get_all_sub_creteria = Crud_op::get_all_sub_creteria($chk_no_of_semester_tbl_rows[0]['auto_inc_id']);
if ($get_all_sub_creteria!=null) {
	for($l=0;$l<count($get_all_sub_creteria);$l++) {
		# code...
	$name_and_min_max_mark_for_main_creteria=
		"اسم المعيار: ".$get_all_sub_creteria[$l]["criteria_name"].' / '.
		'أعلى علامة: '.$get_all_sub_creteria[$l]["top_end_of_mak"].' - '.
		'أقل علامة: '.$get_all_sub_creteria[$l]["low_end_of_mak"]
		;
	 echo '
	<tr>
	
<td>'.$name_and_min_max_mark_for_main_creteria.'</td>
<td>'.$get_all_sub_creteria[$l]["sub_name"].'</td>
<td>'.$get_all_sub_creteria[$l]["sub_min_mark"].'</td>
<td>'.$get_all_sub_creteria[$l]["sub_max_mark"].'</td>

 
	 <td> <a href="fill_sub_grade_creteria.php?action=edit&sub_criteria_id='.$get_all_sub_creteria[$l]["sub_criteria_id"].'"
  class="btn btn-primary btn-sm a-btn-slide-text" id="edit_idea"  style="margin-top: 3px;"">
        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
        <span><strong>تعديل</strong></span>            
    </a>
 
	<!-- data-toggle="modal" data-target="#confirm-delete" -->
<a href="fill_sub_grade_creteria.php?action=del&sub_criteria_id='.$get_all_sub_creteria[$l]["sub_criteria_id"].'"
  class="btn btn-danger btn-sm a-btn-slide-text" onClick="return confirm(\'هل أنت متأكد من حذف هذا المقياس الفرعي\')" id="del_user" style="margin-top: 3px;">
        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
        <span><strong>حذف</strong></span>            
    </a>

  
</td>';

	 
	}
	echo '
</tr>';
	 
}
	else{
echo '
<td colspan="5">لا يوجد بيانات</td>
</form>';

 
	}
	   
echo '
</tbody>
</table>
</div>

</div>

   ';
  // end tbl
	
	
 
	 include('../includes/footer.php');
?>