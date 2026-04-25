<?php
session_start();
include('../includes/connect.php');
include('../functions/common_function.php');
$page_title = "User Profile";
if(!isset($_SESSION['username'])){
    $_SESSION['toast_mession']="please login to view your profile";
    echo"<script>window.location.href='user_login.php'</script>";
    exit();
}
$username=$_SESSION['username'];
$query= $con->prepare("SELECT * FROM user_table WHERE user_name=?");
$query->bind_param("s", $username);
$query->execute();
 $result=$query->get_result();
 $row_count=$result->num_rows;
 if($row_count>0){
    $user=$result->fetch_assoc();
 }else{
    $user=[
        'username'=>$username,
        'user_email'=>'Not Available',
        'user_mobile'=>'Not Available',
        'user_address'=>'Not Available',
        'user_image'=>'Not Available',
    ];
 }

 $user_id=$user['user_id']??null;

 $current_view='profile';
 if(isset($_GET['orders'])){
    $current_view='orders';
 }elseif(isset($_GET['edit_account'])){
    $current_view='edit_account';
 }elseif(isset($_GET['delete_account'])){
    $current_view='delete_account';
 }

 $page_title=ucfirst($user['user_name']).'| My Account - Giftos';
?>
<title><?php echo $page_title; ?></title>
<link rel="icon" href="../images/favicon.png">

<?php
include('../includes/header.php');
include('../includes/navbar.php');
?>

<div class="container-fluid my-5">
    <div class="row mx-auto">
        <!-- Left section -->
        <div class="col-md-3">
            <div class="card p-3 profile-sidebar">
                    <h4 class="text-custom-maroon text-center mb-4">Dashboard</h4>
                <div class="text-center mb-4">
                    <img src="./user_images/<?php echo $user['user_image']?>" alt="<?php echo $user['user_name']?>" onerror="this.src='../images/favicon.png'" class="rounded-circle profile-img">
                    <h5 class="mt-3"><?php echo ucfirst($user['user_name'])?></h5>
                </div>
                <ul class="nav flex-column nav-pills">
                    <li class="nav-items mb-2">
                        <a href="profile.php" class="nav-link link-custom <?php echo ($current_view=='profile'?'active bg-custom-maroon text-light':'')?> "><i class="fas fa-user-circle me-2"></i> My Profile</a>
                    </li>
                    <li class="nav-items mb-2">
                        <a href="profile.php?orders" class="nav-link  link-custom <?php echo ($current_view=='orders'?'active bg-custom-maroon text-light':'')?>"><i class="fas fa-box me-2"></i> My Orders</a>
                    </li>
                    <li class="nav-items mb-2">
                        <a href="profile.php?edit_account" class="nav-link  link-custom <?php echo ($current_view=='edit_account'?'active bg-custom-maroon text-light':'')?>"><i class="fas fa-edit me-2"></i>Edit Profile</a>
                    </li>
                    <li class="nav-items mb-2">
                        <a href="profile.php?delete_account" class="nav-link  link-custom <?php echo ($current_view=='delete_account'?'active bg-custom-maroon text-light':'')?>"><i class="fas fa-trash me-2"></i>Delete Account</a>
                    </li>
                    <li class="nav-items mb-2">
                        <a href="logout.php" class="nav-link  link-custom"><i class="fas fa-sign-out me-2"></i>Logout</a>
                    </li>
                </ul>
            </div>
        </div>    
        <!-- right section -->
         <div class="col-md-9">
            <div class="p-4 profile-content">
                <?php
                if($current_view=='edit_account'){
                    include('edit_account.php');
                }elseif($current_view=='orders'){
                    include('user_orders.php');
                }elseif($current_view=='delete_account'){
                    include('delete_account.php');
                }else{
                    
                    echo"<h3 class='text-custom-maroon mb-4'>
                    Welcome To Dashboard, ".htmlspecialchars($user['user_name']).";
                </h3>
                <p class='lead'>
                    Here you can manege your account information and track your orders.
                </p>
                <div class='card mt-4 border-light shadow-sm'>
                    <h5>Account Details</h5>
                    <table class='table table-borderless mt-3'>
                        <tr>
                            <th>User Name :</th>
                            <td>".htmlspecialchars($user['user_name'])."</td>
                        </tr>
                        <tr>
                            <th>Email :</th>
                            <td>".htmlspecialchars($user['user_email'])."</td>
                        </tr>
                        <tr>
                            <th>Mobile :</th>
                            <td>".htmlspecialchars($user['user_mobile'])."</td>
                        </tr>
                        <tr>
                            <th>Address :</th>
                            <td>".htmlspecialchars($user['user_address'])."</td>
                        </tr>
                    </table>
                    <a href='profile.php?edit_account' class='btn btn-custom w-50 my-3 ms-1'>Update Profile</a>
                </div>";
                }
                ?>
            </div>
         </div>
    </div>
</div>
<hr class="mt-5 mb-0" >

<?php include('../includes/footer.php') ?>
<?php include('../includes/notification.php') ?>
<?php include('../includes/scripts_footer.php') ?>