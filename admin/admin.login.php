<?php
session_start();
include('../includes/connect.php');
if(isset($_POST['admin_login'])){
    $admin_username=$_POST['admin_username'];
    $admin_password=$_POST['admin_password'];

    $stmt=$con->prepare("SELECT * FROM  admin_table WHERE admin_name=?");
    $stmt->bind_param("s", $admin_username);
    $stmt->execute();
    $result=$stmt->get_result();
    if($result->num_rows>0){
        $row_data= $result->fetch_assoc();
        $hashed_password=$row_data['admin_password'];

        if(password_verify($admin_password, $hashed_password)){
            $_SESSION['admin_username']=$admin_username;
            //  echo"<script>alert('Login Is Successfull ! Welcome to Dashboard');
            //     window.location.href='index.php';
            //  </script>";
            header("Location: index.php");
            exit();
        }else{
            echo "<script>alert('❌ Password does not match')</script>";
        }
    }else{
        echo "<script>alert('❌ Admin Not Found ! Please check your name or password')</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Giftos</title>

     <!-- <title><?php echo htmlspecialchars( $page_title )?></title> -->
    <link rel="icon" href="../images/favicon.png">
    
    <link rel="stylesheet" href="./admin_style.css">
    <link rel="stylesheet" href="../style/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<body>
    
<nav class="admin-navbar">
    <div class="container">
        <div class="d-flex justify-content-between">
            <a href="../index.php" class="navbar-brand"><img src="../images/favicon.png" alt=""></a>
            <div class="admin-badge">
                <i class="fas fa-shield-alt"></i>Admin Portal
            </div>
        </div>
    </div>
</nav>

<div class="admin-login-wrapper">
    <div class="login-container">
        <div class="login-header">
            <i class="fas fa-user-shield"></i>
            <h2>Admin Login</h2>
            <p>Access Your Adminstrative Dashboard</p>
        </div>
        <form action="" method="POST">
            <div class="mb-4">
                <label class="form-label">
                    <i class="fas fa-user"></i>Admin Name
                </label>
                <div class="input-group">
                    <input type="text" name="admin_username" class="form-control" required>
                    <i class="fas fa-user input-icon"></i>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label">
                    <i class="fas fa-lock"></i>Password
                </label>
                <div class="input-group">
                    <input type="password" name="admin_password" class="form-control" required>
                    <i class="fas fa-key input-icon"></i>
                </div>
            </div>
            <button class="btn-login" type="submit" name="admin_login">
                <i class="fas fa-sign-in-alt"></i>
                Login To Dashboard
            </button>
            <div class="back-link">
                <a href="../index.php">
                    <i class="fas fa-arrow-left"></i>Back To Store
                </a>
            </div>
        </form>
    </div>
</div>

<?php include('../includes/footer.php')?>
<script src="../js/bootstrap.bundle.min.js"></script>

</body>
</html>