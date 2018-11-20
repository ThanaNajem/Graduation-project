<?php
    session_start();
  include('../db_op.php');
  $status=false;
  $send_thesis_file_status=false;
  date_default_timezone_set('israel');
 /**/
 $user_id=null;
 $get_usr_grp=null;
 $errors=null;
if (isset($_SESSION["user_id"])) {
  $user_id=$_SESSION["user_id"];
}


  $get_usr_grp = Crud_op::check_if_this_usr_has_grp($user_id);
 /**/

?>
<!DOCTYPE html>
<?php include('../includes/header.php'); ?>
<div class="container">
<!--End fixed menu-->
<!-- </div> -->
<?php if (isset($_SESSION["user_id"]) && (!empty($_SESSION["user_id"])) && 
(isset($_SESSION["role"])) && (!empty($_SESSION["role"])) && ($_SESSION["role"] == 4)) { 
$usr_id=$_SESSION["user_id"];
//$check_if_he_has_a_grp=Crud_op::check_if_he_has_a_grp($usr_id);
 $get_active_semester_tbl_row_count = Crud_op::get_active_semester_tbl_row_count();
if($get_active_semester_tbl_row_count!=0){
   $evt_name="send_your_weekly_project_work";
 $send_your_weekly_project_work_begining_and_ending_evt_date=Crud_op::get_first_and_end_date_for_evt($evt_name);
 $send_thesis_file_evt_name="send_thesis_file";
 $send_thesis_file_begining_and_ending_evt_date=Crud_op::get_first_and_end_date_for_evt($send_thesis_file_evt_name);
 
 if(count($send_your_weekly_project_work_begining_and_ending_evt_date)!=0 || count($send_thesis_file_begining_and_ending_evt_date)!=0 ){
   //check send_your_weekly_project_work_begining_and_ending_evt_date
  if(count($send_your_weekly_project_work_begining_and_ending_evt_date)!=0){
   $send_your_weekly_project_work_begining_date=$send_your_weekly_project_work_begining_and_ending_evt_date[0]['from_date'];
 $send_your_weekly_project_work_ending_date=$send_your_weekly_project_work_begining_and_ending_evt_date[0]['to_date'];
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
$send_your_weekly_project_work_begining_date= date("Y-m-d H:i:s", $send_your_weekly_project_work_begining_date);

//$to_date = strtotime($to_date);
$send_your_weekly_project_work_ending_date= date("Y-m-d H:i:s", $send_your_weekly_project_work_ending_date);
 
if($current_Date>=$send_your_weekly_project_work_begining_date && $current_Date<=$send_your_weekly_project_work_ending_date){
  
$status=true;
} 
  } 
  
  if(count($send_thesis_file_begining_and_ending_evt_date)!=0){
     $send_thesis_file_begining_date=$send_thesis_file_begining_and_ending_evt_date[0]['from_date'];
 $send_thesis_file_ending_date=$send_thesis_file_begining_and_ending_evt_date[0]['to_date'];
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
$send_thesis_file_begining_date= date("Y-m-d H:i:s", $send_thesis_file_begining_date);

//$to_date = strtotime($to_date);
$send_thesis_file_ending_date= date("Y-m-d H:i:s", $send_thesis_file_ending_date); 
  

if($current_Date>=$send_thesis_file_begining_date && $current_Date<=$send_thesis_file_ending_date){
  
$send_thesis_file_status=true;
}   
  }
  if($status || $send_thesis_file_status || ($status && $send_thesis_file_status)){
    /* start upload and insert file and other msg content */
   $upload_file_err= "";
  $upload_file_success="";
    if(
   isset($_FILES['weekly_peoject_works'])
    && isset($_POST['sending_weekly_work'])
  ){
  /* &&
   isset($_POST['txt_msg'])*/
  
  /* Start upload file and insert in database */
  
/**/
$check_if_this_file_thesis=0;
if (isset($_POST['check_if_this_file_thesis'])) {
 
$check_if_this_file_thesis=$_POST['check_if_this_file_thesis'];
}

$messages_text=$_POST['txt_msg'];
              $errors= array();
      $file_name = $_FILES['weekly_peoject_works']['name'];
      $file_size =$_FILES['weekly_peoject_works']['size'];
      $file_tmp =$_FILES['weekly_peoject_works']['tmp_name'];
      $file_type=$_FILES['weekly_peoject_works']['type'];
    $tmp=explode('.',$_FILES['weekly_peoject_works']['name']);
      $file_ext=strtolower(end($tmp));
      $expensions= array("pdf");
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="هذا الامتداد غير مسموح - يرجى اختيار ملف امتداده PDF";
      }
      
    //  if($file_size > 2097152){
      if($file_size == 0 || $file_size > 104857600){
      
         $errors[]='الملف فارغ! أو تجاوز الحد المسموح به';
     
      }
        $dest_path = "../testupload/".$file_name;
     
      if(empty($errors)==true){
if(!file_exists ($dest_path)){      
    if( move_uploaded_file($file_tmp,$dest_path) ) {
      $row_count=0; 
      $row_count=Crud_op::insert_a_new_msg_with_its_attachment($get_usr_grp,$user_id,$messages_text,$dest_path,$check_if_this_file_thesis);
      if($row_count==2){$upload_file_success="تمت العملية بنجاح";
      $upload_file_err="";
        echo $upload_file_success;}
      else{  unlink($dest_path ); 
    $upload_file_success="";
       $upload_file_err="لم تتم العملية بنجاح";
       echo $upload_file_err;
   
     }
      
    }
     else{
       $upload_file_success="";
       $upload_file_err="لم تتم العملية بنجاح";
       foreach($errors as $key=>$value){
         $upload_file_err.=$value;
        // print_r($errors); 
       }
       
    }
}
else{
  $upload_file_err= 'الملف موجود،أعد تسمية ملفك أو أنك قمت بإضافته مسبقا';
  $upload_file_success="";
}   
      }else{
         foreach($errors as $key=>$value){
         $upload_file_err.=$value;
        // print_r($errors); 
       }
     //print_r($errors);
      }
       if(isset($errors)){
        foreach($errors as $key=>$value){
         $upload_file_err.=$value;
        // print_r($errors); 
       }
       
     }       
              /**/
  $_POST = array();
  /* end upload file and insert in database */   
    //echo "<meta http-equiv=refresh content=\"0; URL=send_your_weekly_project_work.php\">";
   }
   elseif(isset($_REQUEST['action']) && ($_REQUEST['action']=="del") && isset($_GET['u_msg'])){
   
   $deleted_status = Crud_op::delete_this_msg_and_its_attachment($_GET['u_msg'],$_GET['u_att_id']);
   
   if($deleted_status){ 
   unlink($_GET['u_p_f']);
     ?>
     <div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-success  text-center"><strong> 
           تمت العملية بنجاح
 </strong></div>
<div class="col-sm-4 "></div>
</div>
     <?php
    //echo "<meta http-equiv=refresh content=\"0; URL=send_your_weekly_project_work.php\">"; 
   }
   else{
      ?>
     <div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong> 
           لم تتم العملية بنجاح
 </strong></div>
<div class="col-sm-4 "></div>
</div>
     <?php
   }
   }
   if (isset($upload_file_err )  && (!empty($upload_file_err))) {
    # code...
     
    echo ' <div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong> 
          '.$upload_file_err.'
 </strong></div>
<div class="col-sm-4 "></div>
</div>';
   }
   elseif (isset($upload_file_success) && !empty($upload_file_success)) {
    # code...
      echo ' <div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-success text-center"><strong> 
           '.$upload_file_success.'
 </strong></div>
<div class="col-sm-4 "></div>
</div>';
   } 
$thesis_file = Crud_op::checkIfSenderFileIsThesis($get_usr_grp);//because accepted thesis will be at groups table record
  
 ?>
    <div class="col-md-8 col-md-offset-2">
    <h3 style="direction:rtl;text-align:center">إرسال أعمال مشروعك الأسبوعية</h3>
  <!---->
  <div class="row" style="margin-bottom: 12px;">
  <div class="form-group text-center">
    <!-- <div class="col-sm-4"></div> -->
    <div class="col-md-8 col-md-offset-2">
        <a class="btn icon-btn btn-success" href="send_your_weekly_project_work.php?action=add" name="add-old-year" 
    style="font-size: 19px;font-weight: bold;direction:rtl;"  ><span class="glyphicon btn-glyphicon glyphicon-plus-sign img-circle text-success" 
    style="color: white;background-color: #83e88b" ></span>إضافة أعمال مشروعك الأسبوعية</a>
  </div>
    <div class="col-sm-4"></div>
</div>
</div>

  <div class="row" style="margin-bottom: 12px;">
  <div class="form-group text-center">
    <!-- <div class="col-sm-4"></div> -->
    <div class="col-md-8 col-md-offset-2 alert-success">
  <?php
  if($thesis_file!=null){
  ?>
        <a   href="<?php echo $thesis_file ; ?>" name="add-old-year" 
    style="font-size: 19px;font-weight: bold;direction:rtl;">اضغط هنا لفتحه</a>
  <?php
  }
  else{
  echo 'ليس لدى مجموعتك أي ملف ثيسز مقبول'; 
  }
  ?>
  </div>
    <div class="col-sm-4"></div>
</div>
</div>
</div>
   <?php  
    $can_send_thesis_file=false;
    $can_send_weekly_work_file=false;
    
    if(isset($_REQUEST['action']) && $_REQUEST['action']=="add"){
      //first new
      if($get_usr_grp!=null && Crud_op::check_if_this_grp_has_a_supervisor($get_usr_grp)!=0 
      && Crud_op::check_if_this_grp_has_an_idea($get_usr_grp)!=0){
        $check_if_grp_send_their_weeky_group = Crud_op::get_file_grp_gor_this_week($get_usr_grp);
  $status_of_file_this_week ="";
  if ( $check_if_grp_send_their_weeky_group!=null) {
    # code...
    
      $status_of_file_this_week = $check_if_grp_send_their_weeky_group[0]['status'];
  }
    if ($check_if_grp_send_their_weeky_group==null) {
          # code...
        
           if ($thesis_file!=null) {
            $alert="تم قبول ملفك الثيسز فلن تتمكن من إرسال ملفات لهذا الأسبوع";
            ?>

<div class="row" style="margin-bottom: 12px;">
  <div class="form-group text-center">
    <div class="col-sm-4"></div>
    <div class="col-sm-4 alert-danger">
         <?php
  
  echo $alert;  include('../includes/footer.php');
    die( );

     
     
     ?>
    </div>
    <div class="col-sm-4"></div>
  </div>
  </div>
            <?php    } 
                                   
         else{
            if ($send_thesis_file_status && $status) {
            echo 'حسب اختيار المستخدم';
      $can_send_thesis_file=true;
      $can_send_weekly_work_file=true;
            }
              elseif ($send_thesis_file_status) {
                echo 'بقدر يرسل ملف ثيسز';
         
          
    $can_send_thesis_file=true;
          
         
    $can_send_weekly_work_file=false;
              }
              elseif ($status) {
               echo 'بقدر يرسل ملف اعمال اسبوعية';
         
    $can_send_thesis_file=false;
   
      
    $can_send_weekly_work_file=true;
      
     
              }
           }           
                   }
                   
        else{
            //group's send weekly work at this week
      /**/
      
          if ($thesis_file!=null) {
            $alert="تم قبول ملفك الثيسز فلن تتمكن من إرسال ملفات لهذا الأسبوع";
            ?>

<div class="row" style="margin-bottom: 12px;">
  <div class="form-group text-center">
    <div class="col-sm-4"></div>
    <div class="col-sm-4 alert-danger">
         <?php
  
  echo $alert;  include('../includes/footer.php');
    die( );

     
     
     ?>
    </div>
    <div class="col-sm-4"></div>
  </div>
  </div>
            <?php    } 
      /**/
          elseif ($check_if_grp_send_their_weeky_group[0]['is_this_thesis_file']==1) {
            # code...
            if ($thesis_file!=null) {
              //thesis file accepted
              $alert="تم قبول ملفك الثيسز فلن تتمكن من إرسال ملفات لهذا الأسبوع";
        
         ?>
<!-- Start appear file form-->

<!-- End appear file form-->

<div class="row" style="margin-bottom: 12px;">
  <div class="form-group text-center">
    <div class="col-sm-4"></div>
    <div class="col-sm-4 alert-danger">
         <?php
  
  echo $alert;  include('../includes/footer.php');
    die( );

     
     
     ?>
    </div>
    <div class="col-sm-4"></div>
  </div>
  </div>
            <?php  
                   
            }
            elseif (($check_if_grp_send_their_weeky_group[0]['status']=="reject")&&($check_if_grp_send_their_weeky_group[0]['is_this_thesis_file']==1)) {
              //echo 'بقدر يرسل ثيسز و ملفات عادي في كلا الحالتين';
         $alert='بقدر يرسل ثيسز و ملفات عادي في كلا الحالتين';
      if ($send_thesis_file_status && $status) {
            //echo 'حسب اختيار المستخدم';
$alert='حسب اختيار المستخدم';
      $can_send_thesis_file=true;
      $can_send_weekly_work_file=true;
            }
              elseif ($send_thesis_file_status) {
               // echo 'بقدر يرسل ملف ثيسز';
         $alert='بقدر يرسل ملف ثيسز';
          
    $can_send_thesis_file=true;
          
         
    $can_send_weekly_work_file=false;
              }
              elseif ($status) {
               //echo 'بقدر يرسل ملف اعمال اسبوعية';
         $alert='بقدر يرسل ملف اعمال اسبوعية';
    $can_send_thesis_file=false;
   
      
    $can_send_weekly_work_file=true;
      
     
              }
  
            }
            elseif (($check_if_grp_send_their_weeky_group[0]['status']=="pending")&&($check_if_grp_send_their_weeky_group[0]['is_this_thesis_file']==1)) {
              # code...
             // echo 'ثيسز ممنوع لكن ملفات اسوبعية ممكن اذا وقت الاسبوعية مفتوح';
        $alert='ثيسز ممنوع لكن ملفات اسوبعية ممكن اذا وقت الاسبوعية مفتوح';
    $can_send_thesis_file=false;
    if($status){
    $can_send_weekly_work_file=true;
//echo 'بإمكانك إرسال ملف أسبوعي لا ثيسز فالملفك الثيسز المرسل مؤخرا قيد انتظار الموافقة من مشرفك';  
$alert='بإمكانك إرسال ملف أسبوعي لا ثيسز فملفك الثيسز المرسل مؤخرا قيد انتظار الموافقة من مشرفك';  
    }
    
            } 
          }
          elseif ($check_if_grp_send_their_weeky_group[0]['is_this_thesis_file']==0) {
          //student send weekly work file not thesis
            if ($check_if_grp_send_their_weeky_group[0]['status']=="accepted") {
if($send_thesis_file_status&&$status){
$can_send_thesis_file=true;
  $can_send_weekly_work_file=false;
}              
        elseif ($send_thesis_file_status) {
                // echo 'مسموح ارسال ثيسز';
          $alert='مسموح ارسال ثيسز';
    $can_send_thesis_file=true;
  
         
                                             }
                                             elseif ($status) {
                                               # code...
                         $can_send_weekly_work_file=false;
                                             // echo 'ممنوع ارسال اعمال اسبوعيةلانه تم قبول ملفك الاسبوعي لهذا الاسبوع';
                                $alert='ممنوع ارسال اعمال اسبوعيةلانه تم قبول ملفك الاسبوعي لهذا الاسبوع';
                                              
                                             }

            }
            elseif ($check_if_grp_send_their_weeky_group[0]['status']=="reject") {
           if ($send_thesis_file_status&&$status) {
             //echo 'بعتمد على اختيار المستخدم هل ثيسز ام ملفات عاية';
            $alert='بعتمد على اختيار المستخدم هل ثيسز ام ملفات عاية';
        $can_send_weekly_work_file=true;
        $can_send_thesis_file=true;
           }
              elseif ($send_thesis_file_status) {
                // echo 'مسموح ارسال ثيسز';
                $alert='مسموح ارسال ثيسز';
        $can_send_thesis_file=true;
                                             }
                                             elseif ($status) {
                                               # code...
                                             // echo 'مسموح يرسل ملفات اسبوعية';
                                              $alert='مسموح يرسل ملفات اسبوعية';
                      $can_send_weekly_work_file=true;
                                             }
                          }
                          elseif ( $check_if_grp_send_their_weeky_group[0]['status']=="pending") {
                            if ($send_thesis_file_status&&$status) {
         //    echo 'بعتمد على اختيار المستخدم هل ثيسز ام ملفات عاية-ملفات اسبوعية ممنوع لانه ملفه قيد الانتظار';
$alert='بعتمد على اختيار المستخدم هل ثيسز ام ملفات عاية-ملفات اسبوعية ممنوع لانه ملفه قيد الانتظار';
             
       
           $can_send_weekly_work_file=false;
             $can_send_thesis_file=true;
           }
              elseif ($send_thesis_file_status) {
              //   echo 'مسموح ارسال ثيسز';
$alert= 'مسموح ارسال ثيسز';
           $can_send_thesis_file=true;
                                             }
                                             elseif ($status) {
                                               # code...
                                            //  echo 'ممنوع يرسل ملفات اسبوعية';
$alert='ممنوع يرسل ملفات اسبوعية';
                      $can_send_weekly_work_file=false;
                        
                                             }
                           }
          }
           
        }
          
        }
        //last new
        else{
          $upload_file_err="لربما لا تمتلك مجموعةأو مشرفا أو فكرة مشروعك غير مقبولة إن أرسلتها فتأكد من وجودهم جميعا حتى تتمكن من إرسال أعمالك الأسبوعية";
  $upload_file_success="";
  ?>
  <div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger"><strong><?php
            echo $upload_file_err;
?></strong></div>
<div class="col-sm-4 "></div>
</div>
  <?php 
      }
      if (isset($alert)) {
        # code...
        ?>
 <div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger"><strong><?php
            echo $alert;
?></strong></div>
<div class="col-sm-4 "></div>
</div>
        <?php
      }
      ?>
      <!--start form-->
    <?php
if($can_send_thesis_file||$can_send_weekly_work_file){
  ?>
  
<div class="row" style="margin-bottom: 12px;">
  
    <!-- <div class="col-sm-4"></div> -->
    <div class="col-md-8 col-md-offset-2">
    <form method="POST"  id="confirmationForm1"  action="send_your_weekly_project_work.php" enctype="multipart/form-data">
  <!---->
<div class="form-group">
   <label class="form-control" style="height:75px;direction:rtl;"  ><?php 
$get_supervisor_of_this_grp = Crud_op::get_supervisor_of_this_grp($get_usr_grp);
echo 'المرسل إليهم: ';
for($k=0;$k<count($get_supervisor_of_this_grp);$k++){
  echo ($k+1).'. '.$get_supervisor_of_this_grp[$k]['name'].' ';
}
  ?> </label>

  <textarea class="form-control" name="txt_msg" placeholder="يُرجى كتابة رسالة نصية لا تتجاوز أحرفها 500 كلمة" maxlength="500" style="height:150px;resize: none;direction:rtl ;
  " autofocus="autofocus" required="required" id="txt_msg"  ></textarea>
   </div>
  <!---->
  <!-- COMPONENT START -->
  <div class="form-group">
      
        <input type="file" name="weekly_peoject_works" style="direction:rtl; float:right;clear:right !important;" required="required" /> 
      <br> 
    </div>
  <!--if thesis-->
  <?php
  if($can_send_thesis_file && !$can_send_weekly_work_file){
    ?>
     <div class="form-group">
    <div class="checkbox" style="direction:rtl; margin-right:5px;">
    <label style="margin-right:5px;display:block;color:blue;">يرجى وضع علامة صح لأن الملف المرسل هو ثيسز</label>
     <input type="checkbox"  value="1" name="check_if_this_file_thesis" id="check_box_if_this_file_thesis" required /> <label style="margin-right:20px;"  for="check_box_if_this_file_thesis">إرسال ملفات ثيسز</label>
    </div>
    </div>
    <?php
  }
  elseif($can_send_thesis_file && $can_send_weekly_work_file){
    ?>
      <div class="form-group">
    <div class="checkbox" style="direction:rtl; margin-right:5px;">
    <label style="margin-right:5px;display:block;color:blue;">يرجى وضع علامة صح إن كان الملف المرسل هو ثيسز</label>
     <input type="checkbox"  value="1" name="check_if_this_file_thesis" id="check_if_this_file_thesis"  /> <label style="margin-right:20px;"  for="check_if_this_file_thesis">إرسال ملفات ثيسز</label>
    </div>
    </div>
    <?php
  }
  elseif(!$can_send_thesis_file && $can_send_weekly_work_file){
  ?>
  <label for="file">يرجى إرفاق ملف أعمالك الأسبوعية لا الثيسز</label>
  <div class="form-group" style="display:none;"> 
      <input type="hidden"   value="0" name="check_if_this_file_thesis" /> 
    </div>
  <?php
  }
  ?>

  <!--if weekly work-->
  
  <!--submit -->
  <div class="form-group">
    <input type="submit" class="btn btn-primary pull-right" name="sending_weekly_work"  value="إرسال" >
     
  </div>
     
</form>
</div>
</div>
  <?php
}
 

?>
    <!--end form-->
    <!-- start tbl content depend on status_of_grp_weekly_file -->
    <!-- start tbl content -->
<div class="row" style="margin-top:12px;">
<!--div class="col-sm-2"></div-->
<div class="col-md-8 col-md-offset-2">
<div class="form-group text-center" style="direction:rtl;">
<div class="table-responsive text-center " style="direction: rtl;margin-top: 12px;">          
  <table class="table  table-hover table-striped table-bordered text-center" id="tbl1" style="align-items: center;">
  
<thead>
   
    <th>رقم الأسبوع</th>
   
  <th>نص الرسالة</th>
  <th>نوع الملف المُرسَل</th>
  <th>الإجراء</th>
  </thead>
  <tbody style="align-text:center;">
  <?php
   $get_first_and_end_date_for_evt = Crud_op::get_first_and_end_date_for_evt($evt_name);
    $start_evt_date = $get_first_and_end_date_for_evt[0]['from_date'];
  /* start get current time */
   $hour=date('H');
 $min=date('i');
 $sec=date('s');
 $month=date('m');
 $day=date('d');
 $year=date('Y');
 $current_Date = mktime($hour, $min, $sec, $month, $day, $year);
//$current_Date = strtotime($current_Date);
$current_Date= date("Y-m-d H:i:s", $current_Date);
  /* end get current date */
   //$from_date = new DateTime($from_date);
          $datediff1 = $current_Date-$start_evt_date;
       $date_difference_btn_from_db_date_and_start_evt_date =ceil(round(($datediff1)/(60 * 60 * 24))/7.0);
      $get_file_grp_gor_this_week = Crud_op::get_this_grp_weekly_file_for_specific_grp($get_usr_grp);
      if($get_file_grp_gor_this_week!=null){//start if 
    for($j=0;$j<count($get_file_grp_gor_this_week);$j++){
  echo '<tr>';
        $sending_time_for_last_file = $get_file_grp_gor_this_week[$j]['sending_time1'];
        $datediff = $sending_time_for_last_file-$start_evt_date;
         $date_difference_btn_sending_time_and_start_evt_date =ceil(round(($datediff)/(60 * 60 * 24))/7.0);
        /* if(){ */
          //to check if this grp send it's file or not
           ?>
           <td><?php
           echo $date_difference_btn_sending_time_and_start_evt_date;
           ?></td>
           <td>
           <?php
           echo $get_file_grp_gor_this_week[$j]['messages_text'];
           ?>
           </td>
       <td>
       <?php
       
       if($get_file_grp_gor_this_week[$j]['is_this_thesis_file']==1){echo 'ملف ثيسز';}
       elseif($get_file_grp_gor_this_week[$j]['is_this_thesis_file']==0){echo 'ملف أعمال أسبوعية';}
       ?>
       </td>
           <?php
            if($status_of_file_this_week=="pending"){
              //is_this_thesis_file , url_str .. to check what is now file type is this thesis or file
                ?>
                <td>
              <a  target="_blank" href="<?php echo $sending_time_for_last_file = $get_file_grp_gor_this_week[$j]['url_str']; ?>"
  class="btn btn-primary btn-sm a-btn-slide-text"   id="del_user" style="margin-top: 3px;">
        <span class="glyphicon glyphicon-file" aria-hidden="true"></span>
        <span><strong>عرض محتويات الملف قيد الانتظار </strong></span>            
    </a>
  <a href="send_your_weekly_project_work.php?action=del&u_msg=<?php echo $get_file_grp_gor_this_week[$j]['messages_id'];  ?>&u_att_id=<?php echo $get_file_grp_gor_this_week[$j]['attachments_id'];  ?>&u_p_f=<?php echo $get_file_grp_gor_this_week[$j]['url_str']; ?> "
  class="btn btn-danger btn-sm a-btn-slide-text" onClick="return confirm('هل أنت متأكد من حذف هذه الرسالة مع مرفقاتها من الملفات')" id="del_user" style="margin-top: 3px;">
        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
        <span><strong>حذف الرسالة و مرفقاتها</strong></span>            
    </a></td>
              <?php
            }
            elseif($status_of_file_this_week=="reject"){
              ?>
              <td><a  target="_blank" href="<?php echo $sending_time_for_last_file = $get_file_grp_gor_this_week[$j]['url_str']; ?>"
  class="btn btn-primary btn-sm a-btn-slide-text"   id="del_user" style="margin-top: 3px;">
        <span class="glyphicon glyphicon-file" aria-hidden="true"></span>
        <span><strong>عرض محتويات الملف المرفوض</strong></span>            
    </a></td>
 
              <?php
            }
            elseif($status_of_file_this_week=="accepted"){
                ?>
              <td><a  target="_blank" href="<?php echo $sending_time_for_last_file = $get_file_grp_gor_this_week[$j]['url_str']; ?>"
  class="btn btn-primary btn-sm a-btn-slide-text"   id="del_user" style="margin-top: 3px;">
        <span class="glyphicon glyphicon-file" aria-hidden="true"></span>
        <span><strong>عرض محتويات الملف المقبول</strong></span>            
    </a></td>
   
              <?php  echo '</tr>';  
            }
            elseif($status_of_file_this_week==""){
              ?>
        <td colspan="3">لا يوجد بيانات</td>
    </tr>
        <?php
        }
            
         
        
    }  
         
      }//end if
    
   
      else{
        ?>
    <tr>
        <td colspan="4">لا يوجد بيانات</td>
    </tr>
        <?php
      }
  ?>
  </tbody>
  </table>
  </div>
  </div>
  </div>
  </div>
  <!-- end tbl content -->
    <!-- end tbl content depend on status_of_grp_weekly_file -->
    
    <?php
        
      }
    
    
    
    }
  else{
  $upload_file_err="لم يتم تحديد موعد إلاسال ملفاتك الأسبوعية بعد";
  $upload_file_success="";
  ?>
  <div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php
            echo $upload_file_err;
?></strong></div>
<div class="col-sm-4 "></div>
</div>
  <?php 
 }
    
  }
  else{
  $upload_file_err="غير مسموح إرسال ملفاتك الأسبوعية أو الثيسز هذه الفترة";
  $upload_file_success="";
  ?>
  <div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger"><strong><?php
            echo $upload_file_err;
?></strong></div>
<div class="col-sm-4 "></div>
</div>
  <?php   
  }
   

 
 
}
else{
  $upload_file_err="لم يتم تفعيل الفصل الدراسي يرجى مراجعة مسؤول الموقع";
  $upload_file_success="";
  ?>
  <div class="row"  style="margin-top: 12px;" >
<div class="col-sm-4"></div>
<div class="col-sm-4 alert alert-danger text-center"><strong><?php
            echo $upload_file_err;
?></strong></div>
<div class="col-sm-4 "></div>
</div>
  <?php
}
}
 
?>
</div>
  <?php include('../includes/footer.php');?>