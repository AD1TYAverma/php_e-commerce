<?php
    session_start();
    include('../includes/connect.php');
    $page_title = "User Login - Giftos";
?>
    <title><?php echo $page_title; ?></title>
    <link rel="icon" href="../images/favicon.png">

<?php
if(isset($_SESSION['username'])){
        header("Location: profile.php");
        exit();
}   

$checkout_intent=0;
if(isset($_GET['checkout']) && $_GET['checkout'] == 1){
    $checkout_intent=1;
    $_SESSION['checkout_intent']=1;
}elseif(isset($_SESSION['checkout_intent']) && $_SESSION['checkout_intent']==1){
    $checkout_intent=1;
}

// login Submission
if(isset($_POST['user_login'])){
    $user_name = trim($_POST['user_name']);
    $user_password = $_POST['user_password'];

    $query = "SELECT*FROM user_table WHERE user_name = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $user_name);
    $stmt->execute();

    $result = $stmt->get_result();
    $row_count = $result->num_rows;
    if($row_count>0){
        $row_data = $result->fetch_assoc();
        if(password_verify($user_password, $row_data['user_password'])){
            $_SESSION['username']=$row_data['user_name'];
            $user_id =$row_data['user_id'];
            $_SESSION['user_id']=$user_id;
            if(isset($_SESSION['cart']) && count($_SESSION['cart'])>0){
            foreach($_SESSION['cart'] as $pid => $qty){

                $check = $con->prepare("SELECT * FROM card_details WHERE user_id=? AND product_id=?");
                $check->bind_param("ii", $user_id, $pid);
                $check->execute();   // ✅ FIX
                $result_cart = $check->get_result();

                if($result_cart->num_rows > 0){

                    $update = $con->prepare("
                        UPDATE card_details 
                        SET quantity = quantity + ? 
                        WHERE user_id=? AND product_id=?
                    ");
                    $update->bind_param("iii", $qty, $user_id, $pid);
                    $update->execute();

                }else{

                    $insert = $con->prepare("
                        INSERT INTO card_details(user_id, product_id, quantity)
                        VALUES(?, ?, ?)
                    ");
                    $insert->bind_param("iii", $user_id, $pid, $qty);
                    $insert->execute();
                }
            }
            unset($_SESSION['cart']);
            
           }
           $should_go_to_checkout = false;
           if(isset($_POST['checkout_intent'])&& $_POST['checkout_intent']==1){
            $should_go_to_checkout = true;

           }elseif((isset($_SESSION['checkout_intent'])&& $_SESSION['checkout_intent']==1)){
                $should_go_to_checkout = true;
           }
           if($should_go_to_checkout){
            unset($_SESSION['checkout_intent']);
            $_SESSION['toast_message'] = "Login Successfull Proceed TO Checkout";
            echo"<script>window.location.href='checkout.php'</script>";
           }
            else{
            $_SESSION['toast_message'] = "Welcome " .htmlspecialchars($user_name). " Login Successfull";
            header("Location: profile.php");
            exit();
        }}else{
           $_SESSION['toast_message'] = "Invalid Username Or Password Please Try Again!"; 
        }
    }else{
        $_SESSION['toast_message'] = "User not found! Please check your username"; 
    }
}

include('../includes/header.php');
include('../includes/navbar.php');  
?>

<div class="container my-5">
    <h2 class="text-center mb-4 text-custom-maroon">User Login</h2>
    <form action="" method="POST" class="mx-auto" style="max-width: 400px;">
        <div class="mb-3">
            <label for="user_name" class="form-label">Username</label>
            <input type="text" class="form-control" name="user_name" id="user_name" required>
        </div>
        <div class="mb-3">
            <label for="user_password" class="form-label">Password</label>
            <input type="password" class="form-control" name="user_password" id="user_password" required>
        </div>
        <?php if($checkout_intent): ?>
        <input type="hidden" name="checkout_intent" value="1">
        <?php endif; ?>
        <button type="submit" class="btn btn-custom w-100 mb-3" name="user_login">Login</button>
        <p class="text-center ">Don't have an account? <a href="user_registration.php" class="text-custom-maroon fw-bold text-decoration-none">Register Here</a></p>
    </form>
</div>



<?php include('../includes/footer.php') ?>
<?php include('../includes/notification.php');?>
<?php include('../includes/scripts_footer.php') ?>

