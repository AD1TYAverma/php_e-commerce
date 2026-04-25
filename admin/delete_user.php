<?php
include('../includes/connect.php');


if(!isset($_GET['delete_user']) || empty($_GET['delete_user'])){
    echo "<script>alert('Invalid Request'); window.location.href='index.php?list_users';</script>";
    exit();
}

$user_id = (int)$_GET['delete_user'];


$get_user = $con->prepare("SELECT * FROM user_table WHERE user_id=? LIMIT 1");
$get_user->bind_param("i", $user_id);
$get_user->execute();
$user = $get_user->get_result()->fetch_assoc();

if(!$user){
    echo "<script>alert('User Not Found'); window.location.href='index.php?list_users';</script>";
    exit();
}


if(isset($_POST['confirm_delete'])){

    // delete order_items
    $delete_items = $con->prepare("
        DELETE oi FROM order_items oi
        INNER JOIN user_orders uo ON oi.order_id = uo.order_id
        WHERE uo.user_id = ?
    ");
    $delete_items->bind_param("i", $user_id);
    $delete_items->execute();

    //  delete user_orders
    $delete_orders = $con->prepare("DELETE FROM user_orders WHERE user_id=?");
    $delete_orders->bind_param("i", $user_id);
    $delete_orders->execute();

    //  delete card_details
    $delete_card = $con->prepare("DELETE FROM card_details WHERE user_id=?");
    $delete_card->bind_param("i", $user_id);
    $delete_card->execute();

    //  delete user
    $delete_user = $con->prepare("DELETE FROM user_table WHERE user_id=?");
    $delete_user->bind_param("i", $user_id);

    if($delete_user->execute()){
        echo "<script>
            alert('User Deleted Successfully');
            window.location.href='index.php?list_users';
        </script>";
    } else {
        echo "<script>alert('Error deleting user');</script>";
    }
}
?>



<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">

            <div class="card border-0 shadow-lg"
                 style="border-radius:15px; overflow:hidden; position:relative;">

                <!-- LEFT STRIP -->
                <div style="
                    position:absolute;
                    left:0;
                    top:0;
                    height:100%;
                    width:6px;
                    background:#800000;
                "></div>

                <!-- HEADER -->
                <div class="text-center p-4"
                     style="background:#fff5f5;">
                    
                    <div style="
                        width:90px;
                        height:90px;
                        background:#ffe6e6;
                        border-radius:50%;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        margin:auto;
                    ">
                        <i class="fas fa-exclamation-triangle"
                           style="font-size:45px; color:#ff4d4d;"></i>
                    </div>

                    <h3 class="mt-3 fw-bold" style="color:#b30000;">
                        Delete User Confirmation
                    </h3>
                </div>

                <!-- BODY -->
                <div class="card-body p-4">

                    <!-- USER INFO -->
                    <div class="mb-3"
                         style="background:#f8f9fa; border-radius:10px; padding:15px;">
                        
                        <p style="margin:0;">
                            <strong>User :</strong> 
                            <?php echo $user['user_name']; ?>
                        </p>

                        <p style="margin:0;">
                            <strong>User ID :</strong> 
                            <?php echo $user_id; ?>
                        </p>
                    </div>

                    <p style="font-size:15px; color:#555;">
                        Are you sure you want to delete this user?
                    </p>

                    <!-- WARNING BOX -->
                    <div style="
                        background:#fff0f0;
                        border-left:5px solid #ff4d4d;
                        border-radius:10px;
                        padding:15px;
                        margin-bottom:20px;
                    ">
                        <ul style="margin:0; padding-left:20px; font-size:14px;">
                            <li>Delete the user account permanently</li>
                            <li>Remove all user data from database</li>
                            <li style="color:#cc0000; font-weight:600;">
                                Delete all orders associated with this user
                            </li>
                            <li>This action <strong>cannot be undone</strong></li>
                        </ul>
                    </div>

                    <!-- BUTTONS -->
                    <form method="POST">
                        <div class="d-flex gap-3 justify-content-center">

                            <button type="submit" name="confirm_delete"
                                class="btn btn-danger px-4 py-2"
                                style="border-radius:30px;">
                                
                                <i class="fas fa-trash me-2"></i>
                                Yes, Delete
                            </button>

                            <a href="index.php?list_users"
                               class="btn btn-light px-4 py-2"
                               style="border-radius:30px; border:1px solid #ccc;">
                                <i class="fas fa-times me-2"></i>
                                Cancel
                            </a>

                        </div>
                    </form>

                </div>

            </div>

        </div>
    </div>
</div>