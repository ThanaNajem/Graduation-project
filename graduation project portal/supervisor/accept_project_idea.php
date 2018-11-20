<?php
    session_start();
   // ob_start();
  include('../db_op.php'); 
  $supervisor_login_id=null;
  date_default_timezone_set('israel');
  
  $status=false;
  if (isset($_SESSION["user_id"])) {
  	 $supervisor_login_id= $_SESSION["user_id"];
  }
 
$proposer=0;

  if (isset($_SESSION["user_id"]) && (!empty($_SESSION["user_id"])) && (isset($_SESSION["role"])) && (!empty($_SESSION["role"])) && ($_SESSION["role"] == 2)) {
	 ?>

     <!DOCTYPE html>
<?php include('../includes/header.php');  
	  

$get_active_semester_tbl_row_count = Crud_op::get_active_semester_tbl_row_count();
if($get_active_semester_tbl_row_count!=0) {
  $evt_name="create_ideas";
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
 
//$current_Date = strtotime($current_Date);
$current_Date= date("Y-m-d H:i:s", $current_Date); 

//$from_date = strtotime($from_date);
$from_date= date("Y-m-d H:i:s", $from_date);

//$to_date = strtotime($to_date);
$to_date= date("Y-m-d H:i:s", $to_date);

  
 
 if($current_Date>=$from_date && $current_Date<=$to_date){
 	 
$status=true;}
if ($status) {
	 /* Start view Ideas of project*/

if(
	isset($_POST['accept_an_idea_has_pending_request'])
	&&
	isset($_POST['idea_id'])
	&&
	isset($_POST['grp_id'])
  ){
  	 
$idea_id1=$_POST['idea_id'];
$grp_id1=$_POST['grp_id'];
$accept_one_idea_and_reject_other_for_specific_grp = Crud_op::accept_one_idea_and_reject_other_for_specific_grp($grp_id1,$idea_id1,$supervisor_login_id);
	if ($accept_one_idea_and_reject_other_for_specific_grp) {
	 
	 $accept_project_idea_success="تمت العملية بنجاح";
	 $accept_project_idea_err=null;

															}
	else{
$accept_project_idea_success=null;
	 $accept_project_idea_err="لم تتم العملية بنجاح";
		}
		//echo "<meta http-equiv=refresh content=\"0; URL=accept_project_idea.php\">";
	//header("Location: accept_project_idea.php");
   
   $_POST = array();
   }//end first if 
elseif (isset($_POST['reject_this_once_idea_has_accepted_request'])
	&&
	isset($_POST['idea_id'])
	&&
	isset($_POST['grp_id'])
	   ) {
	$idea_id1= $_POST['idea_id'] ;
$grp_id1= $_POST['grp_id'] ;
	$reject_this_once_idea_has_accepted_request = Crud_op::reject_this_once_idea_has_accepted_request($grp_id1,$idea_id1,$supervisor_login_id);
	if ($reject_this_once_idea_has_accepted_request) {
	 
	 $accept_project_idea_success="تمت العملية بنجاح";
	 $accept_project_idea_err=null;

															}
	else{
$accept_project_idea_success=null;
	 $accept_project_idea_err="لم تتم العملية بنجاح";
		}
		echo "<meta http-equiv=refresh content=\"0; URL=accept_project_idea.php\">";
			//header("Location: accept_project_idea.php");
         }//end second if 
elseif (
	isset($_POST['accept_an_idea_has_reject_request'])
	&&
	isset($_POST['idea_id'])

	&&
	isset($_POST['grp_id']) 
       ) {
$idea_id1= $_POST['idea_id'] ;
$grp_id1= $_POST['grp_id'] ;
 
$accept_one_idea_and_reject_other_for_specific_grp = Crud_op::accept_one_idea_and_reject_other_for_specific_grp($grp_id1,$idea_id1,$supervisor_login_id);
	if ($accept_one_idea_and_reject_other_for_specific_grp) {
	 
	 $accept_project_idea_success="تمت العملية بنجاح";
	 $accept_project_idea_err=null;

															}
	else{
$accept_project_idea_success=null;
	 $accept_project_idea_err="لم تتم العملية بنجاح";
		}
		//echo "<meta http-equiv=refresh content=\"0; URL=accept_project_idea.php\">";
			//header("Location: accept_project_idea.php");
         }//end third if 
         ?>
         
<?php  
                if(isset($accept_project_idea_success)){
?><div class="row" style="margin-top: 12px;" >
  <div class="col-sm-4 "></div>
<div class="col-sm-4 alert alert-success text-center"><strong><?php echo $accept_project_idea_success; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
<?php
  }   elseif(isset($accept_project_idea_err)){
?>
<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo $accept_project_idea_err; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
<?php
  }
  
/* Start pagination */
 ?>
  <div class="row" align="Center">
       <h1> متابعة طلبات الأفكار </h1>
                <?php
     
     $rec_count =  Crud_op::get_no_of_groups_of_sup($supervisor_login_id);
     $rec_limit=8;
     $page_count= ($rec_count/$rec_limit);
   //  $page_count=floor($rec_count/$rec_limit);
     
     if( isset($_GET{'page'} ) )
     {
      $page = $_GET{'page'}-1;
      $start = ($rec_limit * $page) ;

      }else {
      $page=0;
      $start = 0;
      }

     echo '<ul class="pagination pagination-sm" style="direction:rtl !important;">';
     for($index = 0; $index<$page_count;$index=$index+1)
     {
         $index2=$index+1;
      if($index2==($page+1))
      $active="active";
      else
      $active="";
         echo "<li class='$active' style='float:right;'><a style='border-top-right-radius: 0px !important;
		 border-bottom-right-radius: 0px !important;margin-left:2px; ' href='accept_project_idea.php?page=$index2'> ﺻﻔﺤﺔ$index2 </a></li>";

     }
     echo '</ul>';

   ?>
                </div> 
      
    <?php
/* End pagination */

$get_sub_groups_of_sup = Crud_op::get_sub_groups_of_sup($supervisor_login_id,$start,$rec_limit);
if($get_sub_groups_of_sup!=null){
for($i=0;$i<count($get_sub_groups_of_sup);$i++){

	$grp_id = $get_sub_groups_of_sup[$i]['grp_id'] ;

	?>
<!-- start for loop to get groups -->
<div class="accordion f-group">
  <div class="header">
    <div class="title">
      <h3 class="close"> <?php echo $get_sub_groups_of_sup[$i]['grp_name'] ; ?></h3>
    </div> 
  </div>
  <div class="content hidden">
    <h2>مسؤول المجموعة</h2>
    <h4>اسم مسؤول المجموعة</h4>
    <h2>أعضاء المجموعة</h2>
    <!--ul--><!-- start grp member -->
	<ul> 
	<?php 
	$grp_member = Crud_op::get_group_member_for_specific_groups($grp_id);
	for($k=0;$k<count($grp_member);$k++){

		$proposer = $grp_member[$k]['student_id'];
	?>
		 <li>  
     <div class="accordion f-group">
  <div class="header">
    <div class="title">
      <h3 class="close"> <?php echo $grp_member[$k]['name']; ?></h3>
    </div> 
  </div>
  <div class="content hidden">
    <!--h2>مسؤول المجموعة</h2>
    <h4>اسم مسؤول المجموعة</h4-->
    <h2></h2>
 
    <ul>
     <!--start idea -->
<?php
	 $get_std_id_idea = Crud_op::get_std_id_idea($proposer,$grp_id);
	if ($get_std_id_idea!=null) {
		
	for($t=0;$t<count($get_std_id_idea);$t++){ 
	 
	?>
	<form action="accept_project_idea.php" method="post">  
	<input type="hidden" value="<?php echo $grp_id ; ?>" name="grp_id" />
    <input type="hidden" value="<?php echo $proposer; ?>" name="proposer" />
    
	  <input type="hidden" value="<?php echo $get_std_id_idea[$t]['id'];  ?>" name="idea_id" />
	  
		 <li>
			 <div class="accordion f-group">
  <div class="header">
    <div class="title">
      <h3 class="close"><?php echo $get_std_id_idea[$t]['idea_name']; ?></h3>
    </div>
    	<?php
	$idea_status = $get_std_id_idea[$t]['idea_status'];
	$idea_status1="";
	 if($idea_status=="pending"){
$idea_status1="ذات الحالة قيد الانتظار";
	 }
	 	elseif($idea_status=="accepted"){

$idea_status1="المقبولة";
	 	}
	 		elseif($idea_status=="reject"){

$idea_status1="المرفوضة";
	 		}

	if($idea_status=="pending"){
		//if this pending, then other may be acceptable or not
		?>
		 <input type="submit" class="btn btn-success" 
	onClick="return confirm('هل أنتَ متأكد من قبولك لهذه الفكرة علما بأنه بمجرد موافقتك على هذه الفكرة <?php echo $idea_status1; ?> سيتم رفض قبولك بآخر فكرة قبلتها')" 
	value="قبول هذه الفكرة" name="accept_an_idea_has_pending_request">
	<?php
		
	}
	elseif($idea_status=="accepted"){
		//if this accepted, then just reject this idea
	?>
	<input type="submit" class="btn btn-danger" 
		onClick="return confirm('هل أنتَ متأكد من رفضك لهذه الفكرة <?php echo $idea_status1; ?>')"
	value="رفض هذه الفكرة" name="reject_this_once_idea_has_accepted_request">
	<?php	
		
	}
	elseif($idea_status=="reject"){
		//if this reject,may be other acceptable or not
		?>
		<input type="submit" class="btn btn-success" 
		onClick="return confirm('هل أنتَ متأكد من قبولك لهذه الفكرة علما بأنه بمجرد موافقتك على هذه الفكرة <?php echo $idea_status1; ?> سيتم رفض قبولك بآخر فكرة قبلتها')"
	value="قبول هذه الفكرة" name="accept_an_idea_has_reject_request">
		<?php
		
	}
	?>
  </div>
  <div class="content hidden">
    <h2>وصف الفكرة</h2>
     
    <ul>
      
      <li><?php  echo $get_std_id_idea[$t]['description'];  ?></li>	
    </ul>
  </div>
</div>
	</form>		<!-- end idea -->
	</li>
		 <?php
	}
	}
	else{
		
		// start null idea for this std 
		?>
		
	 
	  
		 <li>
			 <div class="accordion f-group">
    
  <div class="content">
    <h3> الفكرة</h3>
     
    <ul>
      
      <li><?php  echo '<h3>لا يوجد أفكار مقترحة من هذا الطالب</h3>';  ?></li>	
    </ul>
  </div>
</div>
	 </li>
		<?php
		// end null idea for this std
		
	}
		 ?>
		  </ul>
</li>		  
		 <?php
	}
		 ?>
		</ul>  
  </div>
</div>
	    <!-- end grp member loop -->
    
<!-- end for loop to get groups -->
 <?php
}}

else{
	?>
	<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo 'لا يتوفر أي مجموعات'; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
	<?php
	
}
	 /* End view ideas of project */
}
else{
?>
<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo 'غير مسموح قبول طلبات الأفكار هذه الفنرة' ?></strong></div>
<div class="col-sm-4 "></div>
</div>
<?php	
}
	// Start pagination
	
	// End pagination
	}
	else{
?>
<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo 'لم يتم تحديد الفترة المسموح بها لقبول الأفكار'; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
		<?php
	}

	}
	else{
		?>
<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php echo 'لم يتم إضافة فصول لهذه السنة يرجى مراجعة مدير الموقع'; ?></strong></div>
<div class="col-sm-4 "></div>
</div>
		<?php
	}

													} 
 
?>
    <!--script src="../js/jquery-1.12.4.min.js"></script-->
     <?php include('../includes/footer.php');?>
