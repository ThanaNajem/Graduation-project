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
	//start build form here and allow edit and delete using submit and input of type hidden and makw a refresh after every post condition
?>

 <div class="row" style="margin-bottom: 12px;">
	<div class="form-group text-center">
	  <div class="col-sm-4"></div>
    <div class="col-sm-4">
        <a class="btn icon-btn btn-success" href="fill_main_grade_creteria.php?action=add" name="fill_main_grade_creteria" style="font-size: 19px;font-weight: bold;"><span class="glyphicon btn-glyphicon glyphicon-plus-sign img-circle text-success" style="color: white;background-color: #83e88b" ></span>إضافة معايير علامة المشروع الرئيسية</a>
 
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
  ( (isset($_REQUEST['action']) && $_REQUEST['action'] == "edit")&& !empty($_GET['criteria_id']) &&
  (Crud_op::check_if_this_creteria_in_db($_GET['criteria_id'])!=null))
  ){
	  $get_key_creteria_info =''; 
	  if ((isset($_REQUEST['action']) && $_REQUEST['action'] == "edit")    ) {
		 $criteria_id = $_GET['criteria_id'];
		 $get_key_creteria_info =Crud_op::check_if_this_creteria_in_db($_GET['criteria_id']);
	 
		
	  $input_hidden_val='<input type="hidden" name="creteria_id" value="'.$criteria_id.'" />';
	  
	  }
	  ?>
	  <div class="row" style="margin-bottom: 12px;">
	  <div class="col-sm-8 col-sm-offset-2">
	<div class="form-group text-center">
	  <form method="post" action="fill_main_grade_creteria.php">
	 <?php echo $input_hidden_val;?>
	  <label for="idea_name" style="margin-bottom: 12px;">اسم المعيار</label>
	 
  <input type="text" class="form-control" id="idea_name" name="creteria_name" 
  placeholder="أدخل اسم المعيار الرئيسي الأول" style="margin-bottom: 12px;" required="required" autofocus="autofocus"
<?php 
if(isset($_REQUEST['action']) && $_REQUEST['action'] == "add"){
	 echo'
value=""> ';
	 
}
elseif ((isset($_REQUEST['action']) && $_REQUEST['action'] == "edit")     ) {
	# code...
	
	 
	echo 'value="'.$get_key_creteria_info[0]['criteria_name'].'">';
	 
}
echo '<label for="idea_name" style="margin-bottom: 12px;">أقل علامة لهذا البند</label>
  <input type="number"  min="1" max="100" class="form-control" id="min_mark" name="min_mark"
  placeholder="أدخل أقل علامة ممكنة لهذا البند" style="margin-bottom: 12px;" required="required"';
 
if(isset($_REQUEST['action']) && $_REQUEST['action'] == "add"){
	 echo'
value=""> ';
	 
}
elseif ((isset($_REQUEST['action']) && $_REQUEST['action'] == "edit") ) {
	# code...
	
	 
	echo 'value="'.$get_key_creteria_info[0]['low_end_of_mak'].'">';
	 
}
echo '<label for="idea_name" style="margin-bottom: 12px;">أعلى علامة لهذا البند</label>
  <input type="number"  min="1" max="100"  class="form-control" id="max_mark" name="max_mark"
  placeholder="أدخل أعلى علامة ممكنة لهذا البند" style="margin-bottom: 12px;" required="required"';
 
if(isset($_REQUEST['action']) && $_REQUEST['action'] == "add"){
	 echo'
value=""> ';
	 
}
elseif ((isset($_REQUEST['action']) && $_REQUEST['action'] == "edit") ) {
	# code...
	
	 
	echo 'value="'.$get_key_creteria_info[0]['top_end_of_mak'].'">';
	 
}
 echo ' 
 

<input type="submit" class="btn btn-success"  style="margin-bottom: 12px; align-items: center;text-align: center;"

  required="required"
 ';
 
 
if(isset($_REQUEST['action']) && $_REQUEST['action'] == "add"){
	 
 echo 'value="إضافة هذا المعيار"

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
    elseif((isset($_REQUEST['action']) && $_REQUEST['action'] == "del") && !empty($_GET['creteria_id'])){
	  try{
		  $deleted_row_count = Crud_op::delete_specific_creteria($_GET['creteria_id']);
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
	  }
	  catch(PDOException $ex){
		  $err='<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong>لدى هذا المعيار الرئيسي معيار فرعي تابع له فلا يمكنك حذفه</strong></div>
<div class="col-sm-4 "></div>
</div>';}
	  //
	  
echo $err;
//
  }
  if(
(  (isset($_POST['add_this_creteria'])) ||   (isset($_POST['edit_this_creteria']) && isset($_POST['creteria_id'])) ) 
&&
(isset($_POST['creteria_name'])&&isset($_POST['min_mark']) && isset($_POST['max_mark']))
  
  ){
	  $max_mark = $_POST['max_mark'];
	  $min_mark = $_POST['min_mark'];
	  $creteria_name = $_POST['creteria_name'];
	  
	  $check_if_this_new_grade_dont_exceed_max_value_allowed_for_each_semester = 
	  Crud_op::check_if_this_new_grade_dont_exceed_max_value_allowed_for_each_semester($max_mark);
	  
	   $check_if_this_new_grade_dont_exceed_max_value_allowed_for_each_semester_in_edit_part="";
	   if(isset($_POST['edit_this_creteria'])){
		   
		   
	  $check_if_this_new_grade_dont_exceed_max_value_allowed_for_each_semester_in_edit_part=
	  Crud_op::check_if_this_new_grade_dont_exceed_max_value_allowed_for_each_semester_in_edit_part($_POST['creteria_id'],$max_mark);
	   }
	  
 if( 
 
 (isset($_POST['add_this_creteria']) && intval($check_if_this_new_grade_dont_exceed_max_value_allowed_for_each_semester)>100)
 ||
 (isset($_POST['edit_this_creteria']) && intval($check_if_this_new_grade_dont_exceed_max_value_allowed_for_each_semester_in_edit_part)>100)
 
 ){
	  $err='<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong>القيمةالجديدة المضافة جعلت مجموع العلامات الكلي يزيد المئة لذا يرجى وضع قيمة صحيحة</strong></div>
<div class="col-sm-4 "></div>
</div>';
	  echo $err;
  }
  else{
	  if(isset($_POST['add_this_creteria'])){
		//start insert
	  $inserted_row = Crud_op::add_grade_to_a_new_creteria($creteria_name,$max_mark,$min_mark);
	  if($inserted_row!=0){ $err='<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-success text-center"><strong>تمت العملية بنجاح</strong></div>
<div class="col-sm-4 "></div>
</div>';
	  }
	  else{$err='<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong>لم تتم العملية بنجاح</strong></div>
<div class="col-sm-4 "></div>
</div>';}
echo $err;
	 //end insert  
	  }
	  elseif(isset($_POST['edit_this_creteria'])){
		 //start update
	   $creteria_id = $_POST['creteria_id'];
	  $updated_row = Crud_op::update_creteria_depend_on_old_creteria($creteria_id,$creteria_name,$max_mark,$min_mark);
	  if($updated_row){ $err='<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-success text-center"><strong>تمت العملية بنجاح</strong></div>
<div class="col-sm-4 "></div>
</div>';
	  }
	  else{$err='<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong>لم تتم العملية بنجاح</strong></div>
<div class="col-sm-4 "></div>
</div>';}
echo $err;
	 //end update 
	  }
	   
	 
  }
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
   
  	<th>اسم المعيار</th>
  	<th>أدنى علامة</th>
  	<th>أعلى علامة</th>
  	<th>الإجراء</th>
   
</thead>
<tbody>';
	 

$get_all_key_creteria = Crud_op::get_all_key_creteria($chk_no_of_semester_tbl_rows[0]['auto_inc_id']);
if ($get_all_key_creteria!=null) {
	for($l=0;$l<count($get_all_key_creteria);$l++) {
		# code...
	
	 echo '
	<tr>
<td>'.$get_all_key_creteria[$l]["criteria_name"].'</td>
<td>'.$get_all_key_creteria[$l]["low_end_of_mak"].'</td>
<td>'.$get_all_key_creteria[$l]["top_end_of_mak"].'</td>


<td style="display: none;"><input type="hidden" name="criteria_id" value="'.$get_all_key_creteria[$l]["key_criteria_id"] .'" /></td> 
	 <td> <a href="fill_main_grade_creteria.php?action=edit&criteria_id='.$get_all_key_creteria[$l]["key_criteria_id"].'"
  class="btn btn-primary btn-sm a-btn-slide-text" id="edit_idea"  style="margin-top: 3px;"">
        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
        <span><strong>تعديل</strong></span>            
    </a>
 
	<!-- data-toggle="modal" data-target="#confirm-delete" -->
<a href="fill_main_grade_creteria.php?action=del&creteria_id='.$get_all_key_creteria[$l]["key_criteria_id"].'"
  class="btn btn-danger btn-sm a-btn-slide-text" onClick="return confirm(\'هل أنت متأكد من حذف هذا المقياس\')" id="del_user" style="margin-top: 3px;">
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
<td colspan="4">لا يوجد بيانات</td>
</form>';

 
	}
	   
echo '
</tbody>
</table>
</div>

</div>

   ';
  // end tbl
  echo'</div>';
  include('../includes/footer.php');
?>