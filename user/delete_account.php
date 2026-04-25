<?php
// session_start();
require_once('../includes/connect.php');

if(!isset($_SESSION['username'])){
    $_SESSION['toast_message']="Please login first";
    echo "<script>window.location.href='user_login.php'</script>";
    exit();
}

$username = $_SESSION['username'];

$query = $con->prepare("SELECT user_id, user_password FROM user_table WHERE user_name=?");
$query->bind_param("s", $username);
$query->execute();
$result = $query->get_result();

if($result->num_rows > 0){
    $user = $result->fetch_assoc();
}else{
    $_SESSION['toast_message']="User not found";
    echo "<script>window.location.href='user_login.php'</script>";
    exit();
}

if(isset($_POST['delete'])){
    $entered_password = $_POST['confirm-password']; // ✅ FIX
    $hashed_password = $user['user_password'];
    $user_id = $user['user_id'];

    if(password_verify($entered_password, $hashed_password)){

        // 🔹 GET ORDERS
        $get_orders = $con->prepare("SELECT order_id FROM user_orders WHERE user_id=?");
        $get_orders->bind_param("i", $user_id); // ✅ FIX
        $get_orders->execute();
        $order_result = $get_orders->get_result();

        while($order = $order_result->fetch_assoc()){
            $order_id = $order['order_id'];

            $delete_items = $con->prepare("DELETE FROM order_items WHERE order_id=?");
            $delete_items->bind_param("i", $order_id);
            $delete_items->execute();
        }

        // 🔹 DELETE ORDERS
        $delete_orders = $con->prepare("DELETE FROM user_orders WHERE user_id=?");
        $delete_orders->bind_param("i", $user_id);
        $delete_orders->execute();

        // 🔹 DELETE USER
        $delete_user = $con->prepare("DELETE FROM user_table WHERE user_id=?");
        $delete_user->bind_param("i", $user_id);
        $result_delete = $delete_user->execute();

        if($result_delete){
            session_destroy(); // ✅ only here
            echo "<script>alert('Account Deleted Successfully'); window.location.href='../index.php'</script>";
            exit();
        }else{
            $_SESSION['toast_message']="Database error while deletion";
            echo "<script>window.location.href='profile.php?delete_account'</script>";
            exit();
        }

    }else{
        $_SESSION['toast_message']="Incorrect Password";
        echo "<script>window.location.href='profile.php?delete_account'</script>";
        exit();
    }
}
?>





<div class="container my-5">
    <div class="registration-card  m-auto">
        <h3 class="text-danger text-center mb-4">Permanently Delete Account</h3>
        <p class="text-center text-secondary fw-normal mb-4">
            Waring : This Action is irreversible. All Your order histery and data will be gone. 
        </p>
        <form action="" method="post">
            <div class="mb-4">
                <label for="" class="form-centrol"><strong>Confirm Your Password :</strong></label>
                <input type="password" name="confirm-password" class="form-control" required>
            </div>
            <input type="submit" name="delete" value="Delete My Account" class="btn btn-danger w-100 mb-3">
            <a href="profile.php" class="btn btn-secondary w-100">Cancel</a>
        </form>
    </div>
</div>