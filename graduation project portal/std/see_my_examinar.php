<?php
    session_start();
   require_once("../db_op.php");
$status=false;
?>
   <!DOCTYPE html>
<?php include('../includes/header.php'); ?>
<!--End fixed menu-->
<!-- </div> -->
<?php if (isset($_SESSION["user_id"]) && (!empty($_SESSION["user_id"])) && (isset($_SESSION["role"])) && (!empty($_SESSION["role"])) && ($_SESSION["role"] == 4)) {
	
	 $get_active_semester_tbl_row_count = Crud_op::get_active_semester_tbl_row_count();
if($get_active_semester_tbl_row_count!=0){
	/**/
	$user_id=$_SESSION["user_id"];
	
	 $evt_name="request_for_discussion_committees";
 $arr_from_and_to_evt_date=Crud_op::get_first_and_end_date_for_evt($evt_name);
 if($arr_from_and_to_evt_date!=null){
	 /**/
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
$current_Date= date("Y-m-d H:i:s", $current_Date); 

$from_date= date("Y-m-d H:i:s", $from_date); 
$to_date= date("Y-m-d H:i:s", $to_date); 

if($current_Date>=$from_date && $current_Date<=$to_date){
  
$status=true;
}
 
	if($status){
		$check_if_this_usr_has_grp = Crud_op::check_if_this_usr_has_grp($user_id);
		if($check_if_this_usr_has_grp!=null){
			$check_if_this_grp_has_a_supervisor = Crud_op::check_if_this_grp_has_a_supervisor($check_if_this_usr_has_grp);
			if($check_if_this_grp_has_a_supervisor!=0){
				$check_if_this_grp_has_an_idea=Crud_op::check_if_this_grp_has_an_idea($check_if_this_usr_has_grp);
				if($check_if_this_grp_has_an_idea!=0){
					$check_if_this_grp_has_a_thesis=Crud_op::check_if_this_grp_has_a_thesis($check_if_this_usr_has_grp);
					if($check_if_this_grp_has_a_thesis!=0){
						$get_examination_status_of_fixed_grp = Crud_op::get_examination_status_of_fixed_grp($check_if_this_usr_has_grp);
						if ($get_examination_status_of_fixed_grp!="") {
							 $check_if_this_grp_has_examinar=Crud_op::check_if_this_grp_has_examinar($check_if_this_usr_has_grp);
						 if($check_if_this_grp_has_examinar!=null){
							 for($t=0;$t<count($check_if_this_grp_has_examinar);$t++){
								$examination_accept_status=$check_if_this_grp_has_examinar[$t]['examination_accept_status']; 
								$fname=$check_if_this_grp_has_examinar[$t]['fname']; 
								$lname=$check_if_this_grp_has_examinar[$t]['lname']; 
								$full_name = $fname.' '.$lname ; 
								 
								$examiner_id=$check_if_this_grp_has_examinar[$t]['examiner_id'];
								$examiner_name_and_id=$examiner_id.' - '.$full_name;
							 }
							?>
							 <div class="row" style="margin-bottom: 12px;">
    <div class="col-md-8 col-md-offset-2">
  <div class="form-group text-center">
<div class="table-responsive text-center " style="direction: rtl;margin-bottom:12px;">          
  <table class="table  table-hover table-striped table-bordered text-center" id="tbl1" style="align-items: center;">
  <thead>
   <tr>
   <th>اسم الممتحن و رقمه</th>
   <th>حالة الطلب</th>
   </tr>
  
  
  </thead>
  
   
  
  <tbody>
  <tr>
  <td ><?php
  echo $examiner_name_and_id;
  ?></td>
   <td ><?php
   /*
   if($examination_accept_status=="pending"){$examination_status="قيد الانتظار";}
   elseif($examination_accept_status=="reject"){$examination_status="مرفوض";}
   elseif($examination_accept_status=="accepted"){$examination_status="مقبول";}
   */
  echo "مقبول" ;
  ?></td>
  </tr>
  </tbody>
  </table>
  </div>
  </div>
  </div>
  </div>
							<?php 
						 }
						 else{
								$see_my_examinar_err="ليس لديكم ممتحن بعد فيرجى مراجعة مشرفكم";
		$see_my_examinar_success=null;
		?>
		<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo $see_my_examinar_err; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
		<?php 
						 }
						}
						else{
							$see_my_examinar_err="لستَ مقبولا بالمناقشة يرجى مراجعة ممتحنيك";
		$see_my_examinar_success=null;
		?>
		<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo $see_my_examinar_err; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
		<?php
						}
						
					}
					else{
						$see_my_examinar_err="ليس لدى مجموعتكم ملفات ثيسز";
		$see_my_examinar_success=null;
		?>
		<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo $see_my_examinar_err; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
		<?php
					}
				}
				else{
				$see_my_examinar_err="مجموعتكم ليستَ مرسلة لأي فكرة أو ليست مقبولا في أي فكرة لمشروعكم";
		$see_my_examinar_success=null;	
		?>
		<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo $see_my_examinar_err; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
		<?php
				}
			}
			else{
				$see_my_examinar_err="مجموعتكم ليستَ منضمة لأي مشرف";
		$see_my_examinar_success=null;
		?>
		<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo $see_my_examinar_err; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
		<?php
			}
		}
		else{
			$see_my_examinar_err="مجموعتكم ليستَ مُنضمة لأي مجموعة بعد لذا فلن تتمكنوا من رؤية ممتحنيكم";
		$see_my_examinar_success=null;
		?>
		<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo $see_my_examinar_err; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
		<?php
		}
		 
	}else{
		$see_my_examinar_err="غير مسموح لكم رؤية ممتحنيكم هذه الفترة";
		$see_my_examinar_success=null;
		?>
		<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo $see_my_examinar_err; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
		<?php
		
	}
 }
 else{
	$see_my_examinar_err="لم يتم تحديد فترة رؤية ممتحنيكم بعد";
		$see_my_examinar_success=null; 
		?>
		<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo $see_my_examinar_err; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
		<?php
 }
}
else{
$see_my_examinar_err="لم يقم مسؤول الموقع بتفعيل الفصل الدراسي بعد";
		$see_my_examinar_success=null; 	
		?>
		<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo $see_my_examinar_err; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
		<?php
}
} 
 
?>
    <!--script src="../js/jquery-1.12.4.min.js"></script-->
     <?php include('../includes/footer.php');?>