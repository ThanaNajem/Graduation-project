<?php
session_start();
require_once("../db_op.php");
// I will add users types in select option 
//1=>admin,2=>supervior,3->discussion_committee,4->std,5->Dean_of_the_College

?>
   <!DOCTYPE html>
<?php
include('../includes/header.php');
?>
<?php



if (isset($_SESSION["user_id"]) && isset($_SESSION["role"]) && !empty($_SESSION["role"]) && !empty($_SESSION["user_id"]) && $_SESSION["role"] == 1) {
     if (isset($_POST['save_users']) || isset($_POST['edit_users'])) {
      //  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD']=="post")) {
 
        if (isset($_POST['user_id']) && isset($_POST['user_fname']) && isset($_POST['user_lname']) && isset($_POST['user_type'])) {
            
            $user_id    = $_POST['user_id'];
            $user_fname = $_POST['user_fname'];
            $user_lname = $_POST['user_lname'];
            $user_type  = $_POST['user_type'];
            $tbl_name;
            if ($user_type == 1) {
                $user_id  = 'a' . $user_id;
                $tbl_name = 'admin';
            } else if ($user_type == 2) {
                $user_id  = 'l' . $user_id;
                $tbl_name = 'teacher';
            } else if ($user_type == 3) {
                $user_id  = 'd' . $user_id;
                $tbl_name = 'dean_of_college';
            } else if ($user_type == 4) {
                $user_id  = 's' . $user_id;
                $tbl_name = 'student';
            }
            
            if (isset($_POST['u_pwd'])) {
                $u_pwd = sha1($user_id);
            } else {
                $u_pwd = "";
            }
            if (isset($_POST['user_status'])) {
                 $user_status = $_POST['user_status'];
            } else {
                $user_status = "";
            }
            
            $users_tbl_data_filed = array(
                $user_id,
                $user_fname,
                $user_lname,
                $user_type,
                $u_pwd,
                $user_status
            );
            
            try {
                if (isset($_POST['save_users'])) {
                       
                        
                        $row = Crud_op::insert_into_users_and_rel_usr_tbl($users_tbl_data_filed, $tbl_name);
                       if ($row) {
                            $success = "تمت عملية الحفظ بنجاح";
                        }
						else{
							$err="هذا المستخدم موجود يرجى تغيير رقمه فهناك مستخدم يقوم بنفس وظيفة هذا المستخدم و حامل لنفس الرقم";
						} 
                        
                    
                    
                } else if (isset($_POST['edit_users'])) {
                     $u_id = $_POST['u_id'];
                     try {
                       
                        $row = Crud_op::update_data_of_users_tbl($users_tbl_data_filed, $u_id);
                        if ($row ) {
                            $success = "تمت عملية التعديل بنجاح";
                        }
					 else{
						 $err = "لم تتم عملية التعديل بنجاح";
					 }
                        
                    }
                    catch (PDOException $ex) {
                        $err = "لم تتم عملية التعديل بنجاح - يرجى التأكد من عدم التعديل بإضافة شخص موجود";
                    }
                    
                    
                }
                 
                
            }
            catch (PDOException $e) {
                //echo $e->getMessage();
                $err = 'هذا الشخص موجود في هذا الفصل الدراسي،أعد المحاولة . أما في حالة الحذف فإما غير موجود أو تم إلغاء عملية الحذف';
                
                
            }
            catch (Exception $e) {
                $err = 'هناك خطأ أعد المحاولة';
            
            }
            //	}
            
        }
		$_POST = array();
      // echo "<meta http-equiv=refresh content=\"0; URL=add_users.php\">"; 
    } //end post of save/edit cond
    
?>
 


<div class="container text-center">
<?php
    $chk_no_of_semester_tbl_rows = Crud_op::get_active_semester_tbl_row_count1();
    $chk_no_user_type_tbl_row    = Crud_op::get_user_type_tbl_row_count();
    
    if ($chk_no_of_semester_tbl_rows != null && $chk_no_user_type_tbl_row != 0) {
?>

<div class="row" style="margin-bottom: 12px;">
	<div class="form-group text-center">
	  <div class="col-sm-4"></div>
    <div class="col-sm-4">
        <a class="btn icon-btn btn-success" href="add_users.php?action=add" name="add-old-year" style="font-size: 19px;font-weight: bold;"><span class="glyphicon btn-glyphicon glyphicon-plus-sign img-circle text-success" style="color: white;background-color: #83e88b" ></span>إضافة مستخدمين</a>
 
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
        $u_id_input_edit_hidden="";
       // $u_id_input_del_hidden="";
        
        if (((isset($_REQUEST['action']) && $_REQUEST['action'] == "edit") && !empty($_GET['u_id'])) || ((isset($_REQUEST['action']) && $_REQUEST['action'] == "add"))) {
            $success = null;
            $err     = null;
            if (isset($_REQUEST['action']) && $_REQUEST['action'] == "edit") {
                
                
                $usr_id1 = $_GET['u_id'];
               
        $u_id_input_edit_hidden="
				 <input type='hidden' name='u_id' 
                value='".$usr_id1."' /> 
				 ";
                 
		 
		  
  
                $get_info_about_specific_user = Crud_op::get_info_about_specific_user($usr_id1);
                
            }
            if ((isset($_REQUEST['action']) && ($_REQUEST['action'] == "edit") && ($get_info_about_specific_user != null)) ||
			(isset($_REQUEST['action']) && ($_REQUEST['action'] == "add"))) {
                
?>
	<form method="post" action="add_users.php">
	
	<div class="row">
		
		<div class="col-sm-4"></div>

		<div class="col-sm-4">

			<div class="form-group text-center">
			 
		 <?php echo $u_id_input_edit_hidden;
       // echo $u_id_input_del_hidden;
		?>

              <label  class="user_id">رقم المستخدم</label>

			<input type="number" id="user_id" min="1" style="margin-bottom: 12px;"  name="user_id" class="form-control quantity" placeholder="أدخل رقم المستخدم" required autofocus  autocomplete="off" <?php
                if (isset($_REQUEST['action']) && ($_REQUEST['action'] == "edit")) {
					
                    $u_id = $get_info_about_specific_user[0]['usr_id'];
                    $u_id = substr($u_id, 1);
                    
                    
                    echo 'value="' . $u_id . '"';
                } else if (isset($_REQUEST['action']) && $_REQUEST['action'] == "add") {
                    echo 'value=""';
                }
?>> 
		 
              <label class="user_name">الاسم الأول للمستخدم</label>
			 
			<input type="text" id="user_fname" style="margin-bottom: 12px;" name="user_fname" maxlength="11" class="form-control" placeholder="أدخل الاسم الأول للمستخدم"   autocomplete="off" required <?php
                if (isset($_REQUEST['action']) && ($_REQUEST['action'] == "edit")) {
                    
                    $u_name = $get_info_about_specific_user[0]['fname'];
                    
                    echo 'value="' . $u_name . '"';
                    
                }
                
                else if (isset($_REQUEST['action']) && $_REQUEST['action'] == "add") {
                    echo 'value=""';
                }
?>>
			  <label class="user_name">الاسم الثاني للمستخدم</label>
			 
			<input type="text" id="user_lname" style="margin-bottom: 12px;" name="user_lname" maxlength="11" class="form-control" placeholder="أدخل الاسم الثاني للمستخدم" onchange="CheckArabicOnly(this);" autocomplete="off" required <?php
                if (isset($_REQUEST['action']) && ($_REQUEST['action'] == "edit")) {
                    
                    $u_name = $get_info_about_specific_user[0]['lname'];
                    
                    echo 'value="' . $u_name . '"';
                    
                }
                
                else if (isset($_REQUEST['action']) && $_REQUEST['action'] == "add") {
                    echo 'value=""';
                }
?>>
  <?php
                if (isset($_REQUEST['action']) && $_REQUEST['action'] == "add") {
?>
<label class="user_pwd">كلمة السر</label>
		<span class="eyes-cont">      
<i id="close" class="glyphicon glyphicon-eye-close hide"></i> 
<i id="open" class="glyphicon glyphicon-eye-open"></i>
            </span>
               <!--  <input type="password" class="form-control" name="u_pwd" maxlength="40" placeholder="كلمة المرور"  id="user_pwd" title="كلمة المرور"  required readonly="readonly"  -->
<?php
                    echo '<input type="password" class="form-control" name="u_pwd" maxlength="10" placeholder="كلمة المرور"  id="user_pwd" title="كلمة المرور"  required readonly="readonly" value="" style="background-color: #eee;margin-bottom: 12px;">';
                }
?>
 
			 
	<label class="user_type">اختر نوع المستخدم</label>


		<select class="form-control" name="user_type" id="user_type" style="background-color: #eee;margin-bottom: 12px;" required="required"> 
  
<option class="defult text-center">يرجى الاختيار</option>
<?php
                if (isset($_REQUEST['action']) && $_REQUEST['action'] == "add") {
                    $get_user_type_rows_res = Crud_op::get_user_type_rows();
                    if ($get_user_type_rows_res != null) {
                        for ($i = 0; $i < count($get_user_type_rows_res); $i++) {
                            echo '<option class="text-center" value="' . $get_user_type_rows_res[$i]["id"] . '"
 			 >' . $get_user_type_rows_res[$i]["type"] . '</option>';
                        }
                        
                    }
?>


 

  <?php
                    
                } else if (isset($_REQUEST['action']) && $_REQUEST['action'] == "edit") {
                    
                    $get_user_type_rows = Crud_op::get_user_type_rows();
                    if ($get_user_type_rows != null) {
                        for ($i = 0; $i < count($get_user_type_rows); $i++) {
                            echo '<option class="text-center" value="' . $get_user_type_rows[$i]['id'] . '"';
                            
                            
                            echo '';
                            if ($get_info_about_specific_user[0]['role'] == $get_user_type_rows[$i]['id']) {
                                echo ' selected="selected"';
                            }
                            echo ' >' . $get_user_type_rows[$i]['type'] . '</option>';
                        }
                        
                    }
                    
                }
?>

 
</select>

<!--Start-->
	
 <?php
                if (isset($_REQUEST['action']) && $_REQUEST['action'] == "edit") {
?>
<label class="user_type">اختر حالة المستخدم</label>


		<select class="form-control" name="user_status" id="user_status" style="background-color: #eee;margin-bottom: 12px;" required="required"> 
  
<option class="defult text-center">يرجى الاختيار</option>
 <?php
                    
                    
                    echo '<option class="text-center" value="regular"';
                    
                    
                    
                    if ($get_info_about_specific_user[0]['status'] == "regular") {
                        echo ' selected="selected"';
                    }
                    
                    echo ' >منتظم</option>';
                    //2nd option
                    echo '<option class="text-center" value="withdrawn"';
                    
                    
                    
                    if ($get_info_about_specific_user[0]['status'] == "withdrawn") {
                        echo ' selected="selected"';
                    }
                    
                    echo ' >منسحب</option>';
                    
                    
                    
                    
?>
</select>
 <?php
                }
?>

 
<!--End-->
<!---->
 <input type="submit" class="btn btn-success text-center submit" 
style="margin-bottom: 12px;align-items: center;text-align: center;"
 <?php
                
                if (isset($_REQUEST['action']) && $_REQUEST['action'] == "add") {
                    echo 'value="حفظ" ' . ' name="save_users" ' . ' disabled="disabled"';
                }
                
                else if (isset($_REQUEST['action']) && $_REQUEST['action'] == "edit") {
                    echo 'value="تعديل" ' . ' name="edit_users" ';
                }
                
?>

> 
<!---->
</form>
		</div>
	</div>
	<div class="col-sm-4"></div>
			<!-- Start tbl info -->
		</div>
			<?php
            }
        }
        
        else if (isset($_GET['action']) && !empty($_GET['action']) && $_GET['action'] == "del") {
            $success = null;
            $err     = null;
            if (!empty($_GET['u_id3'])) {
                $u_id = $_GET['u_id3'];
                
                
                try {
                    $row = Crud_op::delete_usr_from_users_tbl($u_id);
                    if ($row > 0) {
                        $success = "تمت عملية الحذف بنجاح";
                        $err     = null;
                        
                    }
                    /*
                    else{
                    $err="لم تتم عملية الحذف بنجاح";
                    }
                    */
                }
                catch (PDOException $ex) {
                    $err = "هذا المستخدم له بيانات مرتبطة به فلن تتمكن من حذفه يرجى طلب منه لحذف بياناته";
                     
                    $success = null;
                }
            }
            
            
        } else if ((isset($_REQUEST['action']) && $_REQUEST['action'] == "reset") && !empty($_GET['u_id2']) && isset($_GET['u_id2'])) {
            $usr_id1 = $_GET['u_id2'];
            try {
                $row = Crud_op::reset_usr_pass($usr_id1);
                if ($row ) {
                    $success = "تم تعديل كلمة السر بنجاح";
                    $err     = null;
                    
                }
				else{
					$success = null;
                    $err     = "لم يتم تعديل كلمة السر بنجاح";
				}
                /*
                else{
                $err="لم تتم عملية الحذف بنجاح";
                }
                */
            }
            catch (PDOException $ex) {
                $err     = "لم يتم تعديل كلمة السر بنجاح";
                $success = null;
            }
        }
?>
 
 
<?php
        if (!empty($success)) {
?><div class="row" style="margin-top: 12px;" >
  <div class="col-sm-4 "></div>
<div class="col-sm-4 alert alert-success"><strong><?php
            echo $success;
?></strong></div>
<div class="col-sm-4 "></div>
</div>
<?php
        }
 
        elseif (!empty($err)) {
?>
<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger"><strong><?php
            echo $err;
?></strong></div>
<div class="col-sm-4 "></div>
</div>
<?php
        }
        
?>

 
 
 
</div>
<!--form  method="post" action="add_users.php"-->
<div class="row">
<div class="col-sm-2"></div>
<div class="col-sm-8">
<div class="form-group text-center">
<div class="table-responsive text-center " style="direction: rtl;margin-top: 12px;">          
  <table class="table  table-hover table-striped table-bordered text-center" id="tbl1" style="align-items: center;">
<thead>
   
  	<th>الفصل الدراسي</th>
  	<th>رقم المستخدم</th>
  	<th>الاسم الأول للمستخدم</th> 
  	<th>الاسم الثاني للمستخدم</th> 
  	<th>نوع المستخدم</th>
  	<th>حالة المستخدم</th>
  	<th>الإجراء</th>
   
</thead>
<tbody>
<?php
        $get_all_users       = Crud_op::get_all_users();
        /*
        SELECT users.usr_id,users.usr_status,users.role,user_type.type  from users,user_type where user_type.id=users.role
        */
        $get_active_semester = Crud_op::get_active_semester();
        $year_val            = $get_active_semester[0]['year_val'];
        $sem_name            = $get_active_semester[0]['sem_name'];
        /*
        SELECT semester.year_val ,semester_names.sem_name  FROM semester,semester_names WHERE semester_names.id=semester.semester_id and semester.active=1;
        */
        
        if ($get_all_users != null && $get_active_semester != null) {
            
            /*
            SELECT  `usr_id`, `role`, `fname`, `lname`, `status`,users.role,user_type.type  from users,user_type where user_type.id=users.role
            */
            for ($i = 0; $i < count($get_all_users); $i++) {
?>
	<tr>
		  
		<td><?php
                echo $sem_name . ' ' . $year_val;
?></td>
		 <td><?php
                echo $get_all_users[$i]['usr_id'];
?></td>
		 <td><?php
                echo $get_all_users[$i]['fname'];
?></td> 
		 <td><?php
                echo $get_all_users[$i]['lname'];
?></td> 
		 <td><?php
                echo $get_all_users[$i]['type'];
?></td> 
		 <td><?php
                if ($get_all_users[$i]['status'] == 1) {
                    
                    echo 'ُمسجّل للمساق';
                    
                } else {
                    echo 'مُنسَحب من المساق';
                }
?></td>
	
		 
		 <td> <a href="add_users.php?action=edit&u_id=<?php
                echo $get_all_users[$i]['usr_id'];
?>"
  class="btn btn-primary btn-sm a-btn-slide-text" id="edit_semester"  style="margin-top: 3px;"">
        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
        <span><strong>تعديل</strong></span>            
    </a>
<a href="add_users.php?action=reset&u_id2=<?php
                echo $get_all_users[$i]['usr_id'];
?>"
  class="btn btn-primary btn-sm a-btn-slide-text" id="edit_pass" onClick="return confirm('هل أنت متأكد من إعادة تعيين كلمة سر هذا المستخدم')"  style="margin-top: 3px;"">
        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
        <span><strong>إعادة تعيين كلمة السر</strong></span>            
    </a>
	<!-- data-toggle="modal" data-target="#confirm-delete" -->
<a href="add_users.php?action=del&u_id3=<?php
                echo $get_all_users[$i]['usr_id'];
?>"
  class="btn btn-danger btn-sm a-btn-slide-text" onClick="return confirm('هل أنت متأكد من حذف هذا المستخدم')" id="del_user" style="margin-top: 3px;">
        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
        <span><strong>حذف</strong></span>            
    </a>

  
</td>
 
</tr>
    <?php
            }
        } else {
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
    } else {
?>
 
<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger"><?php
        echo 'يٌرجى تفعيل الفصل الدراسي في نافذة إضافة فصول لسنوات أو تأكد من وجود أنواع للمستخدمين';
?></div>
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