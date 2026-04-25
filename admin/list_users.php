<div class="container-fluid mt-4">

    <h2 class="mb-4">
        <i class="fa-solid fa-users me-2"></i>All Users
    </h2>


    <?php
include('../includes/connect.php');

$stmt = $con->prepare("SELECT * FROM user_table ORDER BY user_id DESC");
$stmt->execute();
$result = $stmt->get_result();

$row_count = $result->num_rows;


if($row_count == 0){
    echo"<div class='empty-state text-center py-5'>
        <i class='fa-solid fa-user-slash mb-3'></i>
        <h4>No User Found</h4>
        <p>User will appear here once they refister on youe website</p>
    </div>";
?>



<?php } else { ?>

    
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>S No.</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Address</th>
                    <th>Total Action</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $number=0;
                while($row_data=$result->fetch_assoc()){
                    $number++;
                    $user_id=$row_data['user_id'];
                    $user_name=$row_data['user_name'];
                    $user_email=$row_data['user_email'];
                    $user_mobile=$row_data['user_mobile'];
                    $user_image=$row_data['user_image'];
                    $user_address=$row_data['user_address'];

                    $order_stmt=$con->prepare("SELECT COUNT(*) AS total FROM user_orders WHERE user_id=?");
                    $order_stmt->bind_param("i",$user_id);
                    $order_stmt->execute();
                    $order_result=$order_stmt->get_result();
                    $order_count=$order_result->fetch_assoc();
                    $total_orders = $order_count['total'];

                
                ?>
                <tr>
                    <td><strong><?php echo $number?></strong></td>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="../user/user_images/<?php echo $row_data['user_image']?>" alt="" class="user-img">
                            <div><strong class="username"><?php echo $row_data['user_name']?></strong></div>
                        </div>
                    </td>
                    <td><i class="fa-solid fa-envelope me-2"></i><?php echo $row_data['user_email']?></td>
                    <td><i class="fas fa-phone icon-space me-2"></i><?php echo $row_data['user_mobile']?></td>
                    <td><small class="address-text"><i class="fas fa-map-location me-2"></i></small><?php echo $row_data['user_address']?></td>
                    <td><span class="badge badge-info"><?php echo $order_count['total']?> orders</span></td>
                    <td>
                        <div class="btn-group">
                            <a href="index.php?view_user=<?php echo $user_id ?>" class="btn btn-info" title="View User Details"><i class="fa-solid fa-eye"></i></a>

                            <a href="index.php?delete_user=<?php echo $user_id ?>" class="btn btn-danger" title="Delete User" onclick="return confirm('Are you sure?')"><i class="fa-solid fa-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-sm-6 col-md-3">
        <div class="card-box text-center p-3 shadow-sm">
            <h6>Total Users</h6>
            <h3><?php echo $row_count?></h3>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="card-box text-center p-3 shadow-sm">
            <h6>Active Users</h6>
            <?php
            $active_stmt=$con->prepare("SELECT COUNT(DISTINCT user_id) AS total FROM user_orders");
            $active_stmt->execute();
            $active_result=$active_stmt->get_result();
            $active_users_row=$active_result->fetch_assoc();
            $active_users = $active_users_row['total'];
            ?>
            <h3><?php echo $active_users?></h3>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="card-box text-center p-3 shadow-sm">
            <h6>New User (This Month)</h6>
            <?php
            $new_stmt = $con->prepare("
    SELECT COUNT(*) AS total 
    FROM user_table 
    WHERE MONTH(created_at)=MONTH(CURRENT_DATE()) 
    AND YEAR(created_at)=YEAR(CURRENT_DATE())
");
            $new_stmt->execute();
            $new_result=$new_stmt->get_result();
            $new_users_row=$new_result->fetch_assoc();
            $new_users = $new_users_row['total'];
            ?>
            <h3><?php echo $new_users?></h3>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="card-box text-center p-3 shadow-sm">
            <h6>Inactive Users</h6>
            <h3><?php echo $row_count-$active_users?></h3>
        </div>
    </div>
    </div>
    <?php } ?>
</div>