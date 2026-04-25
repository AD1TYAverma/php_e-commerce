<?php
session_start();
include('../includes/connect.php');
include('../functions/common_function.php');
if(!isset($_SESSION['username'])){
    $_SESSION['toast_message']="Please login to manage your orders";
    header("Location: user_login.php");
    exit();
}

if(!isset($_GET['order_id']) || empty($_GET['order_id'])){
    $_SESSION['toast_message']="Error : Order ID Not Provided";
    echo"<script>window.location.href='profile.php?orders'</script>";
    exit();
}

$user_id=$_SESSION['user_id']??null;
$username=$_SESSION['username'];
if(!$user_id){
    $stmt=$con->prepare("SELECT user_id FROM user_table WHERE user_name=?");
    $stmt->bind_param("s",$username);
    $stmt->execute();
    $result=$stmt->get_result();
    if($result && $row=$result->fetch_assoc()){
        $user_id=$row['user_id'];
    }
}

$order_id=(int)$_GET['order_id'];
if(!$user_id){
    $_SESSION['toast_message']="Error. Could Not Retrieve user data.";
    echo"<script>window.location.href='profile.php'</script>";
    exit();
}

//query

$stmt=$con->prepare("SELECT track_status, user_id FROM user_orders WHERE order_id=?");
$stmt->bind_param("i",$order_id);
$stmt->execute();
$result=$stmt->get_result();
if($result->num_rows==0){
    $_SESSION['toast_message']="Error. Order not found.";
    header("Location: profile.php?orders");
    exit();
}

$order_data= $result->fetch_assoc();
$track_status=strtolower($order_data['track_status']);
$order_user_id=$order_data['user_id'];
if($order_user_id!==$user_id){
    $_SESSION['toast_message']="Access Denied! You do not own this order.";
    header("Location: profile.php?orders");
    exit();
}
if($track_status!=='processing' && $track_status!=='pending'){
    $_SESSION['toast_message']="Errro. Order cannot be deleted as tracking status is $track_status .";
    header("Location: profile.php?orders");
    exit();
}

$success=true;
$stmt=$con->prepare("DELETE FROM user_orders WHERE order_id=?");
$stmt->bind_param("i",$order_id);
$delete_main=$stmt->execute();
if(!$delete_main){
    $success=false;
    $_SESSION['toast_message']="Failed to delete main order".$stmt->error;
}

$stmt->close();


$stmt=$con->prepare("DELETE FROM order_items WHERE order_id=?");
$stmt->bind_param("i",$order_id);
$delete_detail=$stmt->execute();
if(!$delete_detail){
    $success=false;
    $_SESSION['toast_message']="Failed to delete main order items".$strm->error;
}

$stmt->close();

if($success){
    $_SESSION['toast_message']="Success : Order ID $order_id has been deleted successfully";
}else{
    $_SESSION['toast_message']="Order Deletion Failed";
}

header("Location:profile.php?orders");
exit();
// $page_title = "Order #". htmlspecialchars($order_id)." Details Giftos" ;
?>

<?php 
include('../includes/notification.php');
include('../includes/scripts_footer.php');
?>
