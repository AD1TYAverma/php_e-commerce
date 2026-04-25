<?php
session_start();
include('../includes/connect.php');
$page_title = "User Registration - Giftos";

?>
    <title><?php echo $page_title; ?></title>
    

    <link rel="icon" href="../images/favicon.png">


<?php


if(isset($_SESSION['username'])){
    echo"<script>window.location.href='profile.php'</script>";
    exit();
}


if(isset($_POST['user_register'])){
    $user_name = trim($_POST['user_name']);
    $user_email = trim($_POST['user_email']);
    $user_password = $_POST['user_password'];
    $conf_user_password = $_POST['conf_user_password'];
    $user_address = trim($_POST['user_address']);
    $user_mobile = trim($_POST['user_mobile']);
    $user_image = $_FILES['user_image']['name'];
    $user_image_tmp = $_FILES['user_image']['tmp_name'];
    $toast_message="";
    // $registration_success=false;

    if(empty($user_name) || empty($user_email) || empty($user_password) || empty($user_address) || empty($user_mobile)){
        $toast_message= "Please Fill Required Fields";
    }elseif(!preg_match("/^\d{10}$/", $user_mobile)){
        $toast_message= "Mobile Number Must Be Exactly 10 digits";
    }elseif($user_password!=$conf_user_password){
        $toast_message="Password do not match";
    }else{
    
        $check_query = "SELECT*FROM user_table WHERE user_name=? OR user_email=?";
        $stmt_check=$con->prepare($check_query);
        $stmt_check->bind_param("ss", $user_name, $user_email);
        $stmt_check->execute();
        $result = $stmt_check->get_result();
        if($result->num_rows>0){
            $toast_message= "Username Or Email already exists";
        }else{
            $hashed_password =  password_hash($user_password, PASSWORD_DEFAULT);

    $image_upload_path = "./user_images/$user_image";
    if(move_uploaded_file($user_image_tmp, $image_upload_path)){

        $query = "INSERT INTO user_table(user_name, user_email, user_password, user_image, user_address, user_mobile)VALUES(?, ?, ?, ?, ?, ?);";

        $stmt_insert = $con->prepare($query);
        $stmt_insert->bind_param('ssssss', $user_name, $user_email, $hashed_password, $user_image, $user_address, $user_mobile);

        $result = $stmt_insert->execute();

        if($result){
           $user_id = $con->insert_id;
           if(isset($_SESSION['cart']) && count($_SESSION['cart'])>0){
            foreach($_SESSION['cart'] as $product_id=>$qty){
                $insert_cart = $con->prepare("INSERT INTO card_details(user_id, product_id, quantity)VALUES(?, ?, ?)");
                $insert_cart->bind_param("iii", $user_id, $product_id, $qty);
                $insert_cart->execute();
            }
            unset($_SESSION['cart']);
           }
           $_SESSION['username'] = $user_name;
           $_SESSION['user_id'] = $user_id;
           $toast_message= "Registration Is Successful Wellcome To Giftos";
           $_SESSION['toast_message']= $toast_message;
           echo"<script>window.location.href='../index.php'</script>";
           exit();
        }else{
           $toast_message= "Registration Is Failes! Please Try Again";
           $_SESSION['toast_message']= $toast_message;
           header("Location: user_registration.php");
           exit(); 
        }

        
        // if($registration_success){
        //     echo"<script>window.location.href='../index.php'</script>";
        //     exit();
        // }else{
        //     header("Location: user_registration.php");
        //     exit();
        // }
    }
  }
}
}

include('../includes/header.php');
include('../includes/navbar.php'); 
?>


<div class="container my-5">
    <div class="card p-4 mx-auto registration-card">
        <h2 class="text-center mb-4 text-custom-maroon">Create New Account</h2>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="user_name" class="form-label">Username</label>
                <input type="text" class="form-control" name="user_name" id="user_name" required>
            </div>
            <div class="mb-3">
                <label for="user_email" class="form-label">Email</label>
                <input type="text" class="form-control" name="user_email" id="user_email" required>
            </div>
            <div class="mb-3">
                <label for="user_image" class="form-label">Profile Image</label>
                <input type="file" class="form-control" name="user_image" id="user_image" required>
            </div>
            <div class="mb-3">
                <label for="user_password" class="form-label">Password</label>
                <input type="password" class="form-control" name="user_password" id="user_password" required>
            </div>
            <div class="mb-3">
                <label for="conf_user_password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" name="conf_user_password" id="conf_user_password" required>
            </div>
            <div class="mb-3">
                <label for="user_address" class="form-label">Address</label>
                <textarea class="form-control" name="user_address" id="user_address" rows="1" required></textarea>
            </div>
            <div class="mb-3">
                <label for="user_mobile" class="form-label">Moblie</label>
                <input type="text" class="form-control" name="user_mobile" id="user_mobile" required>
            </div>
            <div class="d-grid">
                <input type="submit" value="Register" name="user_register" class="btn btn-custom">
            </div>
            <p class="text-center mt-3">Already have an account? <a href="user_login.php" class="text-custom-maroon fw-bold text-decoration-none">Login</a></p>
        </form>
    </div>
</div>

<?php include('../includes/footer.php') ?>
<?php include('../includes/notification.php');?>
<?php include('../includes/scripts_footer.php') ?>

