<?php
    session_start();    
    require_once("../db_op.php");
    $chk_no_of_semester_tbl_rows = Crud_op::get_active_semester_tbl_row_count1();
	// I will add users types in select option 
    // 1=>admin,2=>supervior,3->discussion_committee,4->std,5->Dean_of_the_College       
?>
   <!DOCTYPE html>
<?php include('../includes/header.php'); ?>
<?php

 	if(isset($_SESSION["user_id"])  &&  
	 	isset($_SESSION["role"]) && 
	 	!empty($_SESSION["role"]) && 
	 	!empty($_SESSION["user_id"]) && 
	 	$_SESSION["role"] == 1 )
	{
 		if( isset($_POST['save_semester']) || (isset($_POST['edit_semester']) && isset($_POST['id_auto_inc'] )) ){
	 		
	 		if(isset($_POST['first_year']) && 
	 			isset($_POST['second_year']) && 
	 			isset($_POST['semester_id'])  
	 			
	 		){
	 			$year_val=$_POST['first_year']."/".$_POST['second_year'];
	 			$semester_id=$_POST['semester_id'];
	 			$semester_tbl_data_filed = array($year_val  ,$semester_id);

	 			$counter=Crud_op::check_if_semester_valid($semester_tbl_data_filed);
	 			 
					if(isset($_POST['save_semester'])){
	 			if($counter>0){
					$err_add_semester="هذا الفصل موجود أعد المحاولة";
					$success_add_semester=null;
	 
	 			}
	 			else {
	 				 $row=Crud_op::insert_data_into_add_semesters_into_years_tbl($semester_tbl_data_filed);
						if($row>0){
							$success_add_semester="تمت عملية الحفظ بنجاح";
							 $err_add_semester=null;
						}else{
							$err_add_semester="لم تتم عملية الحفظ بنجاح";
							$success_add_semester=null;
	 
						}
	 			}
						
					}
					
					elseif (isset($_POST['edit_semester'])){
						$id_auto_inc=$_POST['id_auto_inc'] ;  
						
						$row=Crud_op::update_data_in_add_semesters_into_years_tbl($semester_tbl_data_filed,$id_auto_inc);
						if($row){
							$success_add_semester="تمت عملية التعديل بنجاح";
							 $err_add_semester=null;
						}else{
							$err_add_semester="لم تتم عملية التعديل بنجاح";	
							$success_add_semester=null;
	 
						}
					}
	 			 
	 		}
			$_POST = array();
		}

?>
 


<div class="container text-center">
<?php 
$get_semester_rows=Crud_op::get_semester_rows();
if($get_semester_rows!=null)
{
 ?>

<div class="row" style="margin-bottom: 12px;">
	<div class="form-group text-center">
	  <div class="col-sm-4"></div>
    <div class="col-sm-4">
        <a class="btn icon-btn btn-success" href="add_semesters_into_years.php?action=add" name="add-year" style="font-size: 19px;font-weight: bold;"><span class="glyphicon btn-glyphicon glyphicon-plus-sign img-circle text-success" style="color: white;background-color: #83e88b" ></span>إضافة فصول لسنوات دراسية</a>
 
    </div>
    <div class="col-sm-4"></div>
</div>
</div>
<!--000000-->
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
<!--000000-->
	<!-- start -->
	
	<!-- end -->
<?php
$semester_id_needed_to_edit = "";
if( ((isset($_REQUEST['action']) && $_REQUEST['action'] == "edit")  && !empty($_GET['s_id'])) || ((isset($_REQUEST['action']) && $_REQUEST['action'] == "add")  )) {

	$success_add_semester=null;
	$err_add_semester=null;
	
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == "edit"){
		
	$semester_id_tbl=$_GET['s_id'];
		$semester_id_needed_to_edit = "
		 <input type='hidden' name='id_auto_inc'  value='".$semester_id_tbl."' />";
	$get_info_of_specific_semester=Crud_op::get_info_of_specific_semester($semester_id_tbl);
/*SELECT `semester_id`, `sem_name`,`auto_inc_id`, `year_val`, `active` FROM `semester_names`,`semester` where semester_id=id and auto_inc_id=:auto_inc_id;*/
}
if( (isset($_REQUEST['action']) && ($_REQUEST['action'] == "edit")
 && ($get_info_of_specific_semester!=null) ) 
	|| (isset($_REQUEST['action']) && ($_REQUEST['action'] == "add"))
){

?>
	<form method="post" action="add_semesters_into_years.php" id="form1">
	
	<div class="row">
		
		<div class="col-sm-4"></div>

		<div class="col-sm-4">

			<div class="form-group text-center">
<?php  echo $semester_id_needed_to_edit; ?>
              <label class="first_year">عام بدء السنة الدراسية</label>

			<input type="number" min="1" id="first_year" style="margin-bottom: 12px;" name="first_year" class="form-control " placeholder="عام بدء السنة الدراسية" required autofocus  autocomplete="off" <?php 
if(isset($_REQUEST['action']) && ($_REQUEST['action'] == "edit")){
	    
	$full_year=$get_info_of_specific_semester[0]['year_val'];
	$split_full_year=preg_split("#/#",$full_year);
$first_year=$split_full_year[0];
$second_year=$split_full_year[1];
	echo'value="'.$first_year.'"';
}
else if(isset($_REQUEST['action']) && $_REQUEST['action'] =="add"){
	echo 'value=""';
}
			 ?>> 
		 
              <label class="second_year">عام نهاية السنة الدراسية</label>
			 
			<input type="number" min="1" id="second_year" style="margin-bottom: 12px;"  name="second_year" class="form-control" placeholder="عام نهاية السنة الدراسية" required readonly="readonly" <?php 
if(isset($_REQUEST['action']) && ($_REQUEST['action'] == "edit")){

	$full_year=$get_info_of_specific_semester[0]['year_val'];
	$split_full_year=preg_split("#/#",$full_year);
$first_year=$split_full_year[0];
$second_year=$split_full_year[1];
	echo'value="'.$second_year.'"';
	 
}
else if(isset($_REQUEST['action']) && $_REQUEST['action'] =="add"){
	echo 'value=""';
}
			 ?>>
			
			<label class="semester">اختر الفصل الدراسي من القائمة</label>


		<select class="form-control" name="semester_id" id="semester_val" style="background-color: #eee;" required="required"> 
  
<option class="defult text-center">يرجى الاختيار</option>
<?php 
 if (isset($_REQUEST['action']) && $_REQUEST['action'] == "add") {
 	$get_semester_rows_res=Crud_op::get_semester_rows();
 	if($get_semester_rows_res!=null){
 		for($i=0;$i<count($get_semester_rows_res);$i++){
 			 echo '<option class="text-center" value="'.$get_semester_rows_res[$i]["id"].'"
 			 >'.$get_semester_rows_res[$i]["sem_name"].'</option>';	
 		}
		 
 	}
  ?>


 

  <?php

 }
   if (isset($_REQUEST['action']) && $_REQUEST['action'] == "edit") {
 
 	$get_semester_rows_res=Crud_op::get_semester_rows();
 	if($get_semester_rows_res!=null){
 		for($i=0;$i<count($get_semester_rows_res);$i++){
 			 echo '<option class="text-center" value="'.$get_semester_rows_res[$i]['id'].'"';


 			echo'';
if($get_info_of_specific_semester[0]['semester_id']==$get_semester_rows_res[$i]['id']) { 
      echo ' selected="selected"'; 
    }
 			echo ' >'.$get_semester_rows_res[$i]['sem_name'].'</option>';	
 		}
		 
 	}
 
   }
?>

 
</select>


 <input type="submit" class="btn btn-success text-center submit" 
style="margin-top: 12px;"
 <?php
        
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == "add") {
            echo 'value="حفظ" '.' name="save_semester" '.' disabled="disabled"';
        }
        
        else if (isset($_REQUEST['action']) && $_REQUEST['action'] == "edit" ) {
              echo 'value="تعديل" '.' name="edit_semester" ';
        }
        
  ?>
 style="align-items: center;text-align: center;"
> 
		</div>
	</div>
	<div class="col-sm-4"></div>
			<!-- Start tbl info -->
		</div>
			<?php
		}
		
	}elseif (	(isset($_REQUEST['action']) && 
				$_REQUEST['action'] == "state") && 
				isset($_GET['s_op']) &&
				is_numeric($_GET['s_op']) && 				
				isset($_GET['sem_id']) && 
				!empty($_GET['sem_id']) 
		 	)
	{		
		try{	
			$success_add_semester	= null;
			$err_add_semester		= null;
			$sem_id 	= $_GET['sem_id'];

			if($_GET['s_op']==1){				
				$state_no 	= 0;
				$state_arr_data = array($sem_id , $state_no);				
				$update_year_state_rowCount=Crud_op::update_studying_year_state($state_arr_data);
				if($update_year_state_rowCount>0){
					$success_add_semester="تمت عملية التعديل بنجاح";

				}				
			}
		
			elseif($_GET['s_op']==0){				
				$state_no 	=1;			
				$state_arr_data = array($sem_id , $state_no);
				//$update_only_year_state_rowCount=Crud_op::update_studying_year_to_only_one_active($sem_id);
				$update_year_state_rowCount=Crud_op::update_studying_year_state($state_arr_data);
				if($update_year_state_rowCount>0){
					$success_add_semester="تمت عملية التعديل بنجاح";

				}				
			}

		}
		catch(PDOException $ex){
			echo $ex->getMessage();
			$err_add_semester='إما أن عملية التعديل لم تتم بنجاح أو تم العبث بالعنوان فيرجى إعادة المحاولة';
			$success_add_semester=null;
 
		}

	}
?>
 
 
<?php if(isset($success_add_semester)){
?><div class="row" style="margin-top: 12px;" >
  <div class="col-sm-4 "></div>
<div class="col-sm-4 alert alert-success"><strong><?php echo $success_add_semester; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
<?php
  } ?>




<?php if(isset($err_add_semester)){
?>
<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger"><strong><?php echo $err_add_semester; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
<?php
  } ?>
 
 
 
 </form>
<div class="row ">
<div class="col-sm-4"></div>
<div class="col-sm-4">
<div class="form-group text-center">
<div class="table-responsive text-center " style="direction: rtl;margin-top: 12px;">          
  <table class="table  table-hover table-striped table-bordered text-center" style="align-items: center;">
<thead>
  	<th>السنة</th>
  	<th>الفصل</th>

  	<th>الإجراء</th>	<th>حالة الفصل</th>
</thead>
<tbody>
<?php 
$get_all_semester = Crud_op::get_all_semester();
/*"SELECT `sem_name`,`auto_inc_id`, `year_val`, `active` FROM `semester_names`,`semester` where semester_id=id;";*/
if($get_all_semester!=null){
	$sem='sem';
for($i=0;$i<count($get_all_semester);$i++){
	?>
	<tr>
	<td><?php echo $get_all_semester[$i]['year_val']; ?></td>
	 <td><?php echo $get_all_semester[$i]['sem_name']; ?></td>  
		
	<td> <a href="add_semesters_into_years.php?action=edit&s_id=<?php echo $get_all_semester[$i]['auto_inc_id'] ;?>"
  class="btn btn-primary btn-sm a-btn-slide-text" id="edit_semester">
        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
        <span><strong>تعديل</strong></span>            
    </a></td> <td>
<?php 
if( $get_all_semester[$i]['active']==0){
?>
<!--&sem=<?php //echo $i;?>"-->
<a href="add_semesters_into_years.php?action=state&s_op=0&sem_id=<?php echo $get_all_semester[$i]['auto_inc_id'] ?>" class="inactive_button btn btn-danger btn-sm a-btn-slide-text">غير فعّال</a>
<?php

}

else if( $get_all_semester[$i]['active']==1){
?>
<!--&sem=<?php //echo $i;?>"-->
<a href="add_semesters_into_years.php?action=state&s_op=1&sem_id=<?php echo $get_all_semester[$i]['auto_inc_id'] ?>" class="active_button btn btn-success btn-sm a-btn-slide-text">فعّال</a>
<?php
}
?>
   

    	
</td>
</tr>
    <?php
}
}
else{
?>
<td colspan="3">لآ توجد بيانات</td>
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

<!-- </form> -->

<?php
}
else{
	?>
 
<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger"><?php echo 'لا يوجد فصول مُدرَجة يرجى إضافة فصول'; ?></div>
<div class="col-sm-4 "></div>
</div>
 
	<?php
}
?>
</div>
 

 
<?php
}
include('../includes/footer.php');

?>