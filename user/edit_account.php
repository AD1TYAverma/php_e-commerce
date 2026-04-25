<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('../includes/connect.php');
require_once('../functions/common_function.php');

if(!isset($_SESSION['username'])){
    $_SESSION['toast_message']="Please login to view your profile";
    echo "<script>window.location.href='user_login.php'</script>";
    exit();
}

$username = $_SESSION['username'];

$query = $con->prepare("SELECT * FROM user_table WHERE user_name=?");
$query->bind_param("s", $username);
$query->execute();

$result = $query->get_result();
$row_fetch = $result->fetch_assoc();

$user_id = $row_fetch['user_id'];
$user_name = $row_fetch['user_name'];
$user_email = $row_fetch['user_email'];
$user_address = $row_fetch['user_address'];
$user_mobile = $row_fetch['user_mobile'];
$user_image = $row_fetch['user_image'];

// ================= UPDATE =================
if(isset($_POST['user_update'])){
    $new_name = $_POST['user_name'];
    $new_email = $_POST['user_email'];
    $new_address = $_POST['user_address'];
    $new_mobile = $_POST['user_mobile'];

    $new_image = $user_image;

    if(!empty($_FILES['user_image']['name'])){
        $new_image = $_FILES['user_image']['name'];
        $new_image_tmp = $_FILES['user_image']['tmp_name'];

        if(!move_uploaded_file($new_image_tmp, "./user_images/$new_image")){
            $_SESSION['toast_message']="Image upload failed!";
            echo "<script>window.location.href='profile.php?edit_account'</script>";
            exit();
        }
    }

    $stmt = $con->prepare("
        UPDATE user_table 
        SET user_name=?, user_email=?, user_image=?, user_address=?, user_mobile=? 
        WHERE user_id=?
    ");

    $stmt->bind_param("sssssi", 
        $new_name, 
        $new_email, 
        $new_image, 
        $new_address, 
        $new_mobile, 
        $user_id
    );

    if($stmt->execute()){
        $_SESSION['username']=$new_name;
        $_SESSION['toast_message']="Account Updated Successfully";
        echo "<script>window.location.href='profile.php'</script>";
        exit();
    } else {
        $_SESSION['toast_message']="Update Failed!";
        echo "<script>window.location.href='profile.php?edit_account'</script>";
        exit();
    }
}
?>

<!-- ================= FORM ================= -->

<h3 class="text-custom-maroon mb-4">Update Account Details</h3>

<form action="" method="POST" enctype="multipart/form-data" class="w-75">
    
    <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="user_name" class="form-control"
            value="<?php echo htmlspecialchars($user_name) ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="user_email" class="form-control"
            value="<?php echo htmlspecialchars($user_email) ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">User Image</label>
        <div class="d-flex align-items-center">
            <input type="file" name="user_image" class="form-control">
            <img src="../user/user_images/<?php echo htmlspecialchars($user_image) ?>" 
                 class="ms-3 rounded-circle" width="60" height="60">
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Address</label>
        <textarea name="user_address" class="form-control" required><?php echo htmlspecialchars($user_address) ?></textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Mobile Number</label>
        <input type="text" name="user_mobile" class="form-control"
            value="<?php echo htmlspecialchars($user_mobile) ?>" maxlength="10" required>
    </div>

    <div class="d-grid">
        <button type="submit" name="user_update" class="btn btn-custom">
            Update Profile
        </button>
    </div>

</form>