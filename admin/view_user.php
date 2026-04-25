<?php
include('../includes/connect.php');

/* ================= VALIDATION ================= */
if(!isset($_GET['view_user']) || empty($_GET['view_user'])){
    echo "<script>alert('Invalid Request'); window.location.href='index.php?list_users';</script>";
    exit();
}

$user_id = (int)$_GET['view_user'];

/* ================= USER FETCH ================= */
$get_user = $con->prepare("SELECT * FROM user_table WHERE user_id=? LIMIT 1");
$get_user->bind_param("i", $user_id);
$get_user->execute();
$user = $get_user->get_result()->fetch_assoc();

if(!$user){
    echo "<div class='alert alert-danger text-center mt-4'>User Not Found</div>";
    exit();
}

/* ================= ORDERS FETCH ================= */
$get_order = $con->prepare("SELECT * FROM user_orders WHERE user_id=? ORDER BY order_date DESC");
$get_order->bind_param("i", $user_id);
$get_order->execute();
$result = $get_order->get_result();

$total_orders = $result->num_rows;
$total_spent = 0;
$completed_orders = 0;
$orders = [];

while($row = $result->fetch_assoc()){
    $orders[] = $row;
    $total_spent += $row['amount_due'];

    if(strtolower($row['order_status']) == 'completed'){
        $completed_orders++;
    }
}

/* ================= JOIN DATE ================= */
$join_date = isset($user['created_at']) 
    ? date('d M Y', strtotime($user['created_at'])) 
    : 'N/A';
?>

<div class="container mt-4 user_page">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fa-solid fa-user-circle me-2"></i>User Details
        </h2>
        <a href="index.php?list_users" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="row">

        <!-- LEFT SIDE -->
        <div class="col-lg-4">

            <div class="card user-card">
                <div class="card-body text-center">

                    <img src="../user/user_images/<?php echo $user['user_image']; ?>" 
                         class="user-img rounded-2" width="120">

                    <h5 class="user-name mt-2">
                        <?php echo $user['user_name']; ?>
                    </h5>

                    <p class="user-join">
                        <i class="fas fa-calendar-alt"></i> 
                        Member Since <?php echo $join_date; ?>
                    </p>

                    <hr>

                    <div class="text-start">
                        <p><strong>Email :</strong> <?php echo $user['user_email']; ?></p>
                        <p><strong>Mobile :</strong> <?php echo $user['user_mobile']; ?></p>
                        <p><strong>Address :</strong> <?php echo $user['user_address']; ?></p>
                    </div>

                </div>
            </div>

            <!-- STATS -->
            <div class="card mt-3 text-center">
                <div class="card-body">
                    <h6>Total Orders</h6>
                    <h3><?php echo $total_orders; ?></h3>
                </div>
            </div>

            <div class="card mt-3 text-center">
                <div class="card-body">
                    <h6>Total Spent</h6>
                    <h3>₹<?php echo $total_spent; ?></h3>
                </div>
            </div>

            <div class="card mt-3 text-center">
                <div class="card-body">
                    <h6>Completed Orders</h6>
                    <h3><?php echo $completed_orders; ?></h3>
                </div>
            </div>

            <!-- DELETE -->
            <div class="card mt-3">
                <div class="card-body">
                    <a href="index.php?delete_user=<?php echo $user_id; ?>" 
                       class="btn btn-danger w-100"
                       onclick="return confirm('Delete user: <?php echo $user['user_name']; ?>?')">
                       Delete User
                    </a>
                </div>
            </div>

        </div>

        <!-- RIGHT SIDE -->
        <div class="col-lg-8">

            <div class="card card-user">
                <div class="card-body">

                    <h5 class="mb-3">
                        <i class="fas fa-history"></i> Order History
                    </h5>

                    <?php if(empty($orders)){ ?>
                        <div class="alert alert-info text-center">
                            No Orders Found
                        </div>
                    <?php } else { ?>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Invoice</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Products</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php foreach($orders as $order){ 

                                    // 🔥 CHANGE 1: product count from order_items
                                    $item_stmt = $con->prepare("SELECT SUM(quantity) as total_items FROM order_items WHERE order_id=?");
                                    $item_stmt->bind_param("i", $order['order_id']);
                                    $item_stmt->execute();
                                    $item_result = $item_stmt->get_result()->fetch_assoc();
                                    $total_items = $item_result['total_items'] ?? 0;

                                    // badge color
                                    $status = strtolower($order['order_status']);

                                    if($status == 'completed'){
                                        $badge = 'success';
                                    } elseif($status == 'pending'){
                                        $badge = 'warning';
                                    } elseif($status == 'cancelled'){
                                        $badge = 'danger';
                                    } else {
                                        $badge = 'secondary';
                                    }
                                ?>

                                <tr>
                                    <td><?php echo $order['invoice_number']; ?></td>

                                    <td>
                                        <?php echo date('d-m-Y', strtotime($order['order_date'])); ?>
                                    </td>

                                    <td>₹<?php echo $order['amount_due']; ?></td>

                                    <!-- 🔥 CHANGE 2 -->
                                    <td><?php echo $total_items; ?> items</td>

                                    <td>
                                        <span class="badge bg-<?php echo $badge; ?>">
                                            <?php echo ucfirst($order['order_status']); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <a href="index.php?view_order_detail=<?php echo $order['order_id']; ?>" 
                                           class="btn btn-info btn-sm">
                                           <i class="fa-solid fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>

                                <?php } ?>

                            </tbody>
                        </table>
                    </div>

                    <?php } ?>

                </div>
            </div>

        </div>

    </div>

</div>