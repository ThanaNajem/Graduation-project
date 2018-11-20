
<html lang="ar">
   <head>
      <!-- Required meta tags always come first --> 
      <!-- Bootstrap CSS -->
      <meta charset="utf-8">

      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
       <!-- <meta http-equiv="refresh" content="30"> -->
      <meta http-equiv="x-ua-compatible" content="ie=edge">
      <title>إدارة مشاريع التخرج</title>
   
  
  <link rel="stylesheet" href="../css/font-awesome.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.1/css/bootstrap-datepicker.min.css">
 <!-- <link rel="stylesheet" href="../bootstrap_js_and_css_and_datepicker_file/date_header/bootstrap.min.css">
 <link rel="stylesheet" href="../bootstrap_js_and_css_and_datepicker_file/date_header/bootstrap-datepicker.min.css">
  --> <link rel="stylesheet" href="../css/my-style.css">
   
   <style>
  /**********************************
Responsive navbar-brand image CSS
- Remove navbar-brand padding for firefox bug workaround
- add 100% height and width auto ... similar to how bootstrap img-responsive class works
***********************************/
body{
  direction: rtl;
  padding-left: 0;
 
}

div.fixed-menu{
 left:-240px;
}
 
.navbar-brand {
  padding: 0px;
}

.navbar-brand>img {
  height: 100%;
  padding: 15px;
  width: auto;
}







/*************************
EXAMPLES 2-7 BELOW 
**************************/

/* EXAMPLE 2 (larger logo) - simply adjust top bottom padding to make logo larger */

.example2 .navbar-brand>img {
  padding: 7px 15px;
}


/* EXAMPLE 3

line height is 20px by default so add 30px top and bottom to equal the new .navbar-brand 80px height  */


ul li:hover{color: #5121f3; background-color: #F8F8FF;border-left: 5px solid #598ecf;border-right: 5px solid #598ecf;}

 ul li{
    list-style-type: none;
     
   cursor: pointer;
    transition: .3s;
    margin-top: 3px;
}
}
 ul{
    position: relative;
    margin: 0;
    padding: 10px 0;
    text-align: center;
    
}
ul#menu.nav.navbar-nav.navbar-right{  position: relative;
    margin: 0;
    padding: 7px 0;
    text-align: center;}

ul#menu.nav.navbar-nav.navbar-right li,div.fixed-menu.is-visible ul#fixed_menu_1 li{
    list-style-type: none;
     
   cursor: pointer;
    transition: .3s;
}
.navbar-default .navbar-nav>li>a.active{color: #5121f3; background-color: #F8F8FF;border-left: 5px solid #598ecf;border-right: 5px solid #598ecf;}
ul#menu.nav.navbar-nav.navbar-right li.active,div.fixed-menu.is-visible ul#fixed_menu_1 li.active{
color: #5121f3; background-color: #F8F8FF;border-left: 5px solid #598ecf;border-right: 5px solid #598ecf;
}
.example3 .navbar-brand {
  height: 80px;
}

.example3 .nav >li >a {
  padding-top: 30px;
  padding-bottom: 30px;
}
.example3 .navbar-toggle {
  padding: 10px;
  margin: 25px 15px 25px 0;
}


/* EXAMPLE 4 - Small Narrow Logo*/
.example4 .navbar-brand>img {
  padding: 7px 14px;
}


/* EXAMPLE 5 - Logo with Text*/
.example5 .navbar-brand {
  display: flex;
  align-items: center;
}
.example5 .navbar-brand>img {
  padding: 7px 14px;
}


/* EXAMPLE 6 - Background Logo*/
.example6 .navbar-brand{ 
  background: url(https://res.cloudinary.com/candidbusiness/image/upload/v1455406304/dispute-bills-chicago.png) center / contain no-repeat;
  width: 200px;
}





/* EXAMPLE 8 - Center on mobile*/
  @media only screen and (max-width : 768px){
  .example-8 .navbar-brand {
  padding: 0px;
  transform: translateX(-50%);
  left: 50%;
  position: absolute;
}
.example-8 .navbar-brand>img {
  height: 100%;
  width: auto;
  padding: 7px 14px; 
}
}


/* EXAMPLE 8 - Center Background */
.example-8 .navbar-brand {
  background: url(https://res.cloudinary.com/candidbusiness/image/upload/v1455406304/dispute-bills-chicago.png) center / contain no-repeat;
  width: 200px;
  transform: translateX(-50%);
  left: 50%;
  position: absolute;
}





/* EXAMPLE 9 - Center with Flexbox and Text*/
.brand-centered {
  display: flex;
  justify-content: center;
  position: absolute;
  width: 100%;
  left: 0;
  top: 0;
}
.brand-centered .navbar-brand {
  display: flex;
  align-items: center;
}
.navbar-toggle {
    z-index: 1;
}




/* CSS Transform Align Navbar Brand Text ... This could also be achieved with table / table-cells */
.navbar-alignit .navbar-header {
    -webkit-transform-style: preserve-3d;
  -moz-transform-style: preserve-3d;
  transform-style: preserve-3d;
  height: 50px;
}
.navbar-alignit .navbar-brand {
  top: 50%;
  display: block;
  position: relative;
  height: auto;
  transform: translate(0,-50%);
  margin-right: 15px;
  margin-left: 15px;
}





.navbar-nav>li>.dropdown-menu {
  z-index: 9999;
}

body {
  font-family: "Lato";
}

 body{
     direction: ltr; 

/*
     background-image: url('../images/robustcoders.jpg') ;
     background-size:cover  ;
     background-repeat: no-repeat;
     */
}
.navbar-nav>li{
  float: right !important; 
  display: flex;


}
@media screen and (max-width: 767px) {
    .navbar-nav>li{
  float: right !important; 
  display: block;clear: both;
  

}
}
   </style>
     
   <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<![endif]-->
   </head>
   <body>

    <!-- <div class="container-fluid"> -->
<?php if(isset($_SESSION["user_id"]) && !empty($_SESSION["user_id"])){ 
 
  ?>
  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar2">
          <span class="sr-only">إدارة مشاريع التخرج</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#"><img src="https://res.cloudinary.com/candidbusiness/image/upload/v1455406304/dispute-bills-chicago.png" alt="Dispute Bills">
        </a>
      </div>
      <div id="navbar2" class="navbar-collapse collapse pull-right" >
        <ul class="nav navbar-nav navbar-right" id="menu" style="text-align: center; direction: : rtl;">
          <?php $role=$_SESSION["role"];
                $usr_id=$_SESSION["user_id"];
            
            
                 $Admin_full_name=$_SESSION["u_name"];
            
           ?>
          <li><a href="#"><?php echo $Admin_full_name.' - '.$_SESSION["user_id"]; ?></a></li>
          <li class="active"><a href="#">الصفحة الرئيسية</a></li>
          <li><a href="#">حول</a></li>
          <li><a href="#">معلومات الاتصال</a></li>
          <li><a href="../logout.php">تسجيل الخروج</a></li>
          </ul>
      </div>
      <!--/.nav-collapse -->
    </div>
    <!--/.container-fluid -->
  </nav>

<?php 
if(isset($_SESSION['role'])&& $_SESSION['role']==4){
include('../std/std-fixed-menu.php');
}
else if(isset($_SESSION['role'])&& $_SESSION['role']==1){
include('../admin/admin-fixed-menu.php');
} 
else if(isset($_SESSION['role'])&& $_SESSION['role']==2){
include('../supervisor/sup-fixed-menu.php');
} 


}
else {
 ?>
<!--if no user-->
<nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar2">
          <span class="sr-only">إدارة مشاريع التخرج</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#"><img src="https://res.cloudinary.com/candidbusiness/image/upload/v1455406304/dispute-bills-chicago.png" alt="Dispute Bills">
        </a>
      </div>
      <div id="navbar2" class="navbar-collapse collapse pull-right" >
        <ul class="nav navbar-nav navbar-right" id="menu" style="text-align: center; direction: : rtl;">
          <li><a href="../index.php">تسجيل الدخول</a></li>
          
          </ul>
      </div>
      <!--/.nav-collapse -->
    </div>
    <!--/.container-fluid -->
  </nav>
<!--Start fixed menu-->
<div class="fixed-menu">
أهلا بكم
  </div>
  <?php } ?>