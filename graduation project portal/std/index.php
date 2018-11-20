<?php
    session_start();
  

?>
   <!DOCTYPE html>
<?php include('../includes/header.php'); ?>
<!--End fixed menu-->
<!-- </div> -->
<?php if (isset($_SESSION["user_id"]) && (!empty($_SESSION["user_id"])) && (isset($_SESSION["role"])) && (!empty($_SESSION["role"])) && ($_SESSION["role"] == 4)) {?>
<div style="direction: rtl;align-items: center;text-align: center;">
<b style="font-size: 20px;justify-content: center;">
أهلا بكم في إدارة مشاريع التخرج
</b>
</div>
<?php } 
 
?>
    <!--script src="../js/jquery-1.12.4.min.js"></script-->
     <?php include('../includes/footer.php');?>