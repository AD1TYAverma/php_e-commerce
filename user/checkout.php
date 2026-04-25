<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
include('../includes/connect.php');
include('../functions/common_function.php');
$page_title = "Checkout Page";
 
?>
<title><?php echo $page_title; ?></title>
<link rel="icon" href="../images/favicon.png">

<?php
include('../includes/header.php');
include('../includes/navbar.php');
?>

<div class="bg-light payment-container">
    <h3 class="text-center p-2">E-Commerce Checkout</h3>
    <p class="text-center pb-3">Complete your perchase securely</p>
</div>
<div class="container">
    <div class="row px-1">
    <div class="col-md-12">
        <div class="d-flex justify-content-center my-5">
            <?php
            if(!isset($_SESSION['username'])){
                header("Location: user_login.php?checkout=1");
                exit();
            }else{
                include('payment.php');
            }
            ?>
        </div>
    </div>
</div>
</div>

<?php include('../includes/footer.php') ?>
<?php include('../includes/notification.php');?>
<?php include('../includes/scripts_footer.php') ?>