<?php
    session_start();
   require_once("db_op.php");
  
       //1=>admin,2=>supervior,3->discussion_committee,5->std,4->Dean_of_the_College
         if(isset($_SESSION["role"])){
			 
			 if($_SESSION["role"]==4){
          //عشان لما يفوت يكون معروض له الجروبات لهاي السنة  اللي هوفيها
      
           header("Location:std/index.php"); 
           
         }
         else if($_SESSION["role"]==1){
     
           header("Location:admin/index.php"); 
                              
             
         }
         else if($_SESSION["role"]==2){
         
            header("Location:supervisor/index.php"); 
           
         }
         else if($_SESSION["role"]==3){
         
           
           header("Location:Dean_of_the_College/index.php"); 
           
         }
          
		 }
      

         
		 
   if(isset($_POST['login']) && !empty($_POST['login'])){ 
   
   $username=trim($_POST['u_num']);
   $password=trim($_POST['u_pwd']);
   $arr_usr_name_and_pass = array( $username,$password );

   try
       {
    
   /*
SELECT `university_no`, `semester_id`, `name`, `pass`, `user_type`, `available`,`semester_name`, `year_val` FROM `users`,semester WHERE  semester_id=id and university_no=:university_no and pass=:pass;
   */
       $res=Crud_op::check_valid_usr_login($arr_usr_name_and_pass); 
 
       if(count($res)!=0) {
        $usr_id=$res[0]['usr_id'];
           $_SESSION["user_id"] = $usr_id;
            $_SESSION['pass']= $res[0]['password'];
             $_SESSION['u_name']= $res[0]['u_name'];
            $_SESSION["role"] ;
            if($usr_id[0]=='a'){

  $_SESSION["role"]=1;
            }
           else if($usr_id[0]=='l'){
  $_SESSION["role"]=2;
            }
            else if($usr_id[0]=='d'){
  $_SESSION["role"]=3;
            }
            else if($usr_id[0]=='s'){
  $_SESSION["role"]=4;
            }
 $errors =null;
    //1=>admin,2=>supervior,3->discussion_committee,5->std,4->Dean_of_the_College
        if($_SESSION["role"]==4){
          //عشان لما يفوت يكون معروض له الجروبات لهاي السنة  اللي هوفيها
      
           header("Location:std/index.php"); 
           
         }
         else if($_SESSION["role"]==1){
     
           header("Location:admin/index.php"); 
                              
             
         }
         else if($_SESSION["role"]==2){
         
            header("Location:supervisor/index.php"); 
           
         }
         else if($_SESSION["role"]==3){
         
           
           header("Location:Dean_of_the_College/index.php"); 
           
         }
          

         else 
         {

        $errors = 'ليس لديك صلاحيات للدخول للموقع';

         }

       }

       else{

           $errors = "رقمك أو كلمة مرورك خطأ";

        }
     }
        
         // }
        
        catch(Exception $e) {

         echo $e->getMessage();

       }

       } 
        
  
        
    /**/
   ?>
 
<!DOCTYPE html>
<html lang="en">
   <head>
      <!-- Required meta tags always come first --> 
      <!-- Bootstrap CSS -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta http-equiv="x-ua-compatible" content="ie=edge">
      <title>إدارة مشاريع التخرج</title>

      <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css'>
<link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css'>
      <link rel="stylesheet" href="css/font-awesome.min.css">
      <link rel="stylesheet" href="css/my-style.css">
   <style>
     
/*
 * Specific styles of signin component
 */
/*
 * General styles
 */
body, html {
    height: 100%;
    background-repeat: no-repeat;
    background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));
}

.card-container.card {
    max-width: 350px;
    padding: 40px 40px;
}

.btn {
    font-weight: 700;
    height: 36px;
    -moz-user-select: none;
    -webkit-user-select: none;
    user-select: none;
    cursor: default;
}

/*
 * Card component
 */
.card {
    background-color: #F7F7F7;
    /* just in case there no content*/
    padding: 20px 25px 30px;
    margin: 0 auto 25px;
    margin-top: 50px;
    /* shadows and rounded borders */
    -moz-border-radius: 2px;
    -webkit-border-radius: 2px;
    border-radius: 2px;
    -moz-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
    -webkit-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
    box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
}

.profile-img-card {
    width: 96px;
    height: 96px;
    margin: 0 auto 10px;
    display: block;
    -moz-border-radius: 50%;
    -webkit-border-radius: 50%;
    border-radius: 50%;
}

/*
 * Form styles
 */
.profile-name-card {
    font-size: 16px;
    font-weight: bold;
    text-align: center;
    margin: 10px 0 0;
    min-height: 1em;
}

.reauth-email {
    display: block;
    color: #404040;
    line-height: 2;
    margin-bottom: 10px;
    font-size: 14px;
    text-align: center;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
}

.form-signin #inputEmail,
.form-signin #inputPassword {
    direction: ltr;
    height: 44px;
    font-size: 16px;
}

 
.form-signin input[type=password],
.form-signin input[type=text],
.form-signin button {
    width: 100%;
    display: block;
    margin-bottom: 10px;
    z-index: 1;
    position: relative;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
}

.form-signin .form-control:focus {
    border-color: rgb(104, 145, 162);
    outline: 0;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgb(104, 145, 162);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgb(104, 145, 162);
}

.btn.btn-signin {
    /*background-color: #4d90fe; */
    background-color: rgb(104, 145, 162);
    /* background-color: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));*/
    padding: 0px;
    font-weight: 700;
    font-size: 14px;
    height: 36px;
    -moz-border-radius: 3px;
    -webkit-border-radius: 3px;
    border-radius: 3px;
    border: none;
    -o-transition: all 0.218s;
    -moz-transition: all 0.218s;
    -webkit-transition: all 0.218s;
    transition: all 0.218s;
}

.btn.btn-signin:hover,
.btn.btn-signin:active,
.btn.btn-signin:focus {
    background-color: rgb(12, 97, 33);
}

 

   </style>
 

   </head>
   <body style="margin-top: 208px;">
  

 

   <div class="row">
 
  <?php 
if(isset($_SESSION["user_id"]) && (!empty($_SESSION["user_id"])))
{
   if($_SESSION["role"]==4){
          //عشان لما يفوت يكون معروض له الجروبات لهاي السنة  اللي هوفيها
      
           header("Location:std/index.php"); 
           
         }
         else if($_SESSION["role"]==1){
     
           header("Location:admin/index.php"); 
                              
             
         }
         else if($_SESSION["role"]==2){
         
            header("Location:supervisor/index.php"); 
           
         }
         else if($_SESSION["role"]==3){
         
           
           header("Location:Dean_of_the_College/index.php"); 
           
         }
          

         else 
         {

        $errors = 'ليس لديك صلاحيات للدخول للموقع';

         }
}
else{
?>

  <div class="container" style="direction:rtl;">
        <div class="card card-container">
            <!-- <img class="profile-img-card" src="//lh3.googleusercontent.com/-6V8xOA6M7BA/AAAAAAAAAAI/AAAAAAAAAAA/rzlHcD0KYwo/photo.jpg?sz=120" alt="" /> -->
            <img id="profile-img" class="profile-img-card" src="//ssl.gstatic.com/accounts/ui/avatar_2x.png" />
           <!--  <p id="profile-name" class="profile-name-card"></p> -->
            <form class="form-signin" method="post" action="index.php">
                 <div class="form-group">
                <span id="reauth-email" class="reauth-email"></span>
                <input type="text" id="usr_id" name="u_num" class="form-control" placeholder="رقم المستخدم" required autofocus autocomplete="off" style="margin-bottom: 12px;">
                <input type="password" id="usr_pwd" name="u_pwd" class="form-control" placeholder="كلمة السر" required  autocomplete="off">
              <span class="eyes-cont1">      
<i id="close" class="glyphicon glyphicon-eye-close hide"></i> 
<i id="open" class="glyphicon glyphicon-eye-open"></i>
            </span>
                <input type="submit" name="login" class="btn btn-lg btn-primary btn-block btn-signin" value="دخول">
</div>
            </form><!-- /form -->
            
        </div><!-- /card-container -->
   
    <div class="row"> 
<div class="col-md-4 col-md-offset-4">
      <?php 
               if(isset($errors)){
                  
                  ?>
                  <div style="font-size: 15px;text-shadow: 0 0 3px #FF0000, 0 0 5px #0000FF;margin-top: 22px;max-width: 350px;" class="alert alert-danger"><center><?php echo $errors; ?></center>
                  </div>

                  <?php
                }

              ?>
            </div>
              <div class="col-md-2 "></div>
               </div><!--End of login div-->
             </div>
<?php

}
   ?>
 
      
    <?php
                 // }
                  ?>
                  
      <script src="js/jquery-1.12.4.min.js"></script>
      
  <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js'></script>

      <script src="js/bootstrap.min.js"></script>

<script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js'></script>
      <script src="js/custom.js"></script>


 
 

   </body>
</html>