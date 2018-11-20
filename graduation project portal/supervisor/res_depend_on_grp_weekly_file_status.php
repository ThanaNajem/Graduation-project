<?php
include('../db_op.php');
$output='';
if(isset($_POST['grp_id'])){
	 
		$grp_id=$_POST['grp_id'];
			
 	$get_file_grp_gor_this_week = Crud_op::get_file_grp_gor_this_week($grp_id);
		if($get_file_grp_gor_this_week !=null){
			$reciever='المرسل إليهم: ';
			$get_grp_member_for_specific_grp = Crud_op::get_grp_member_for_specific_grp($grp_id);
 
for($k=0;$k<count($get_grp_member_for_specific_grp);$k++){
   $reciever.=($k+1).'. '.$get_grp_member_for_specific_grp[$k]['name'].' ';
}
/* start calc current date */
/* 9/4/2018 9:47*/
//set $date_difference_btn_current_and_start_evt_date=0; then check for thesis and file then  
$date_difference_btn_current_and_start_evt_date=0;
 

/*9/4/2018 9:47*/
/* end calc current date */	
  
	 $hour=date('H');
 $min=date('i');
 $sec=date('s');
 $month=date('m');
 $day=date('d');
 $year=date('Y');
 $current_Date = mktime($hour, $min, $sec, $month, $day, $year);
 
$current_Date= date("Y-m-d H:i:s", $current_Date);
$start_date = $get_file_grp_gor_this_week[0]['sending_time1'];
 $start_date = date("Y-m-d H:i:s", $start_date);
 $datediff = $current_Date - $start_date  ;

$date_difference_btn_current_and_start_evt_date =(ceil(round(($datediff)/(60 * 60 * 24))/7.0))+1;
		$check_if_this_file_accepted_in_this_week=Crud_op::check_if_this_file_accepted_in_this_week($grp_id);
		if($check_if_this_file_accepted_in_this_week=="pending"){
	  
			$output=
			'
		 <!--div class="row" style="margin-top:12px;">
<div class="col-md-8 col-md-offset-2">
  
  <div class="form-group"-->
   <label class="form-control" style="height:75px;direction:rtl;margin-bottom:12px;"  >'.$reciever.'</label>
  <textarea class="form-control" name="txt_msg" placeholder="يُرجى كتابة رسالة نصية لا تتجاوز أحرفها 500 كلمة" maxlength="500"
  style="height:150px;resize: none;margin-bottom:12px;direction:rtl ;
  " autofocus="autofocus" required="required" id="txt_msg"  ></textarea>
   <!--/div-->
  <!---->
  <!-- COMPONENT START -->
  <!--div class="form-group"-->
      
        <input type="file" accept="application/pdf" id="file_picker" name="weekly_peoject_works_for_specific_grp" style="direction:rtl; float:right;clear:right !important;margin-bottom:12px;" required="required" /> 
      <br> 
    <!--/div-->
    
  <!-- COMPONENT END -->
  <!--div class="form-group"-->
   <br>
      <div class="radio">
      <label style="position:relative;right:16px;">قبول</label><input type="radio" style="margin: -17px 1px 0 !important;" class="radio" name="status" required value="accepted">
    </div>
    <div class="radio">
      <label style="position:relative;right:16px;">رفض</label><input type="radio" style="margin: -17px 1px 0 !important;" class="radio" name="status" value="reject">
    </div>
     
  <!--/div-->
     
<!--div id="response"></div-->
 </div>
 </div>

 </div>
 
 </div> 
 </div>
<!--div class="form-group text-center" style="direction:rtl;"-->
<div class="table-responsive text-center " style="direction: rtl;margin-bottom:12px;">          
  <table class="table  table-hover table-striped table-bordered text-center" id="tbl1" style="align-items: center;">
  
<thead>
   
    <th>رقم الأسبوع</th>
   
  <th>نص الرسالة</th>
  <th>الإجراء</th>
  </thead>
  <tbody style="align-text:center;">
   <td>'. $date_difference_btn_current_and_start_evt_date.'</td>
  <td>'.$get_file_grp_gor_this_week[0]['messages_text'].'</td>
  <td><a target="_blank" href="'.$get_file_grp_gor_this_week[0]['url_str'].'" class="btn btn-primary" style="margin-bottom:4px;">عرض محتويات الملف قيد الانتظار لهذا الأسبوع</a>
  <input type="submit" class="btn btn-success text-center submit"  name="sending_response_weekly_work_at_pending_status"  value="إرسال" style="margin-right:4px;" >
  </td>
  <input type="hidden" name="to_which_msg_reply_id" value="'.$get_file_grp_gor_this_week[0]['messages_id'].'" />
  <input type="hidden" name="attachments_id" value="'.$get_file_grp_gor_this_week[0]['attachments_id'].'" />
  <input type="hidden" name="is_this_thesis_file" value="'.$get_file_grp_gor_this_week[0]['is_this_thesis_file'].'" />
  
  
  </form>
  
  </tbody>
  </table>
   
  <!--/div-->
  </div> 
  ';
		 
			;
		}
		elseif($check_if_this_file_accepted_in_this_week==null && $grp_id!=0){
			$output ='<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger"><strong>لآ يوجد رسائل من هذه المجموعة</strong></div>
<div class="col-sm-4 "></div>
</div>';
		}
		elseif($check_if_this_file_accepted_in_this_week==null && $grp_id==0){
			$output ='<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger"><strong>يرجى الاختيار</strong></div>
<div class="col-sm-4 "></div>
</div>';
		}
		elseif($check_if_this_file_accepted_in_this_week=="reject"){
			//start 8-4-2018 02:30
			$output=
			'
		 <!--div class="row" style="margin-top:12px;">
<div class="col-md-8 col-md-offset-2">
  
  <div class="form-group"-->
   <label class="form-control" style="height:75px;direction:rtl;margin-bottom:12px;"  >'.$reciever.'</label>
  <textarea class="form-control" name="txt_msg" placeholder="يُرجى كتابة رسالة نصية لا تتجاوز أحرفها 500 كلمة" maxlength="500"
  style="height:150px;resize: none;margin-bottom:12px;direction:rtl ;
  " autofocus="autofocus" required="required" id="txt_msg"  ></textarea>
   <!--/div-->
  <!---->
  <!-- COMPONENT START -->
  <!--div class="form-group"-->
      
        <input type="file" accept="application/pdf" id="file_picker" name="weekly_peoject_works_for_specific_grp" style="direction:rtl; float:right;clear:right !important;margin-bottom:12px;" required="required" /> 
      <br> 
    <!--/div-->
    
  <!-- COMPONENT END -->
  <!--div class="form-group"-->
   <br>
      <div class="radio">
      <label style="position:relative;right:16px;">قبول</label><input type="radio" checked="checked" style="margin: -17px 1px 0 !important; " class="radio" name="status" required value="accepted">
    </div>
    
     
  <!--/div-->
     
<!--div id="response"></div-->
 </div>
 </div>

 </div>
 
 </div> 
 </div>
<!--div class="form-group text-center" style="direction:rtl;"-->
<div class="table-responsive text-center " style="direction: rtl;margin-bottom:12px;">          
  <table class="table  table-hover table-striped table-bordered text-center" id="tbl1" style="align-items: center;">
  
<thead>
   
    <th>رقم الأسبوع</th>
   
  <th>نص الرسالة</th>
  <th>الإجراء</th>
  </thead>
  <tbody style="align-text:center;">
   <td>'. $date_difference_btn_current_and_start_evt_date.'</td>
  <td>'.$get_file_grp_gor_this_week[0]['messages_text'].'</td>
  <td><a target="_blank" href="'.$get_file_grp_gor_this_week[0]['url_str'].'" class="btn btn-primary" style="margin-bottom:4px;">عرض محتويات الملف المرفوض لهذا الأسبوع</a>
  <input type="submit" class="btn btn-success text-center submit"  name="sending_response_weekly_work_at_pending_status"  value="قبول الملف المرفوض" style="margin-right:4px;" >
  </td>
  <input type="hidden" name="to_which_msg_reply_id" value="'.$get_file_grp_gor_this_week[0]['messages_id'].'">
  <input type="hidden" name="attachments_id" value="'.$get_file_grp_gor_this_week[0]['attachments_id'].'">
  <input type="hidden" name="is_this_thesis_file" value="'.$get_file_grp_gor_this_week[0]['is_this_thesis_file'].'">
  
  
  </form>
  
  </tbody>
  </table>
   
  <!--/div-->
  </div> 
  ';
		 
			//end 8-4-2018 02:30
		}
		elseif($check_if_this_file_accepted_in_this_week=="accepted"){
				$output=
			'
		 <!--div class="row" style="margin-top:12px;">
<div class="col-md-8 col-md-offset-2">
  
  <div class="form-group"-->
   <label class="form-control" style="height:75px;direction:rtl;margin-bottom:12px;"  >'.$reciever.'</label>
  <textarea class="form-control" name="txt_msg" placeholder="يُرجى كتابة رسالة نصية لا تتجاوز أحرفها 500 كلمة" maxlength="500"
  style="height:150px;resize: none;margin-bottom:12px;direction:rtl ;
  " autofocus="autofocus" required="required" id="txt_msg"  ></textarea>
   <!--/div-->
  <!---->
  <!-- COMPONENT START -->
  <!--div class="form-group"-->
      
        <input type="file" accept="application/pdf" id="file_picker" name="weekly_peoject_works_for_specific_grp" style="direction:rtl; float:right;clear:right !important;margin-bottom:12px;" required="required" /> 
      <br> 
    <!--/div-->
    
  <!-- COMPONENT END -->
  <!--div class="form-group"-->
   <br>
      
    <div class="radio">
      <label style="position:relative;right:16px;">رفض</label><input type="radio" checked="checked" style="margin: -17px 1px 0 !important;" class="radio" name="status" value="reject">
    </div>
     
  <!--/div-->
     
<!--div id="response"></div-->
 </div>
 </div>

 </div>
 
 </div> 
 </div>
<!--div class="form-group text-center" style="direction:rtl;"-->
<div class="table-responsive text-center " style="direction: rtl;margin-bottom:12px;">          
  <table class="table  table-hover table-striped table-bordered text-center" id="tbl1" style="align-items: center;">
  
<thead>
   
    <th>رقم الأسبوع</th>
   
  <th>نص الرسالة</th>
  <th>الإجراء</th>
  </thead>
  <tbody style="align-text:center;">
   <td>'. $date_difference_btn_current_and_start_evt_date.'</td>
  <td>'.$get_file_grp_gor_this_week[0]['messages_text'].'</td>
  <td><a target="_blank" href="'.$get_file_grp_gor_this_week[0]['url_str'].'" class="btn btn-primary" style="margin-bottom:4px;">عرض محتويات الملف المرفوض لهذا الأسبوع</a>
  <input type="submit" class="btn btn-success text-center submit"  name="sending_response_weekly_work_at_pending_status"  value="قبول الملف المرفوض" style="margin-right:4px;" >
  </td>
  <input type="hidden" name="to_which_msg_reply_id" value="'.$get_file_grp_gor_this_week[0]['messages_id'].'">
  <input type="hidden" name="attachments_id" value="'.$get_file_grp_gor_this_week[0]['attachments_id'].'">
  <input type="hidden" name="is_this_thesis_file" value="'.$get_file_grp_gor_this_week[0]['is_this_thesis_file'].'">
  
  
  </form>
  
  </tbody>
  </table>
   
  <!--/div-->
  </div> 
  ';
		
		}
		}
else{
 
		$output ='<div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger"><strong>لا يوجد ملفات لهذا الأسبوع لهذه المجموعة</strong></div>
<div class="col-sm-4 "></div>
</div>';
} 
	 
	/*
	
	$output.='<div class="row"'...etc;
	echo $output;
	*/
	echo $output;
}
?>