<?php
include('../includes/connect.php');

if(!isset($_GET['view_order_detail'])){
    echo "<script>alert('Invalid Request'); window.location.href='index.php?list_orders';</script>";
    exit();
}

$order_id = $_GET['view_order_detail'];


$get_order = $con->prepare("SELECT * FROM user_orders WHERE order_id=?");
$get_order->bind_param("i", $order_id);
$get_order->execute();
$result = $get_order->get_result();

if($result->num_rows == 0){
    echo "<script>alert('Order Not Found'); window.location.href='index.php?list_orders';</script>";
    exit();
}

$order = $result->fetch_assoc();


$invoice = $order['invoice_number'];
$date = date('d M Y, h:i A', strtotime($order['order_date']));
$amount = $order['amount_due'];
$products = $order['total_product'];
$payment_status = ucfirst($order['order_status']);
$track_status = ucfirst($order['track_status']);


if(isset($_POST['update_truck'])){
    $new_status = $_POST['track-status'];

    $update = $con->prepare("UPDATE user_orders SET track_status=? WHERE order_id=?");
    $update->bind_param("si", $new_status, $order_id);

    if($update->execute()){
        echo "<script>
            alert('Status Updated');
            window.location.href='index.php?list_orders=<?php echo $order_id ?>';
        </script>";
    }
}


/* Agar user_id hai table me to use karo */
$customer = null;
if(isset($order['user_id'])){
    $uid = $order['user_id'];
    $user_q = $con->query("SELECT * FROM user_table WHERE user_id='$uid'");
    $customer = $user_q->fetch_assoc();
}
?>

<div class="container mt-4 order-details">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fa-solid fa-file-invoice me-2"></i>Order Details
        </h2>
        <a href="index.php?list_orders" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="row">

        <!-- LEFT -->
        <div class="col-lg-8">

            <!-- ORDER INFO -->
            <div class="card order-card mb-4 py-3">
                <div class="card-body">
                    <h5><i class="fas fa-shopping-cart"></i> Order Info</h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label>Invoice</label>
                            <p><?php echo $invoice ?></p>
                        </div>

                        <div class="col-md-6">
                            <label>Date</label>
                            <p><?php echo $date ?></p>
                        </div>

                        <div class="col-md-6">
                            <label>Amount</label>
                            <p>₹<?php echo number_format($amount,2) ?></p>
                        </div>

                        <div class="col-md-6">
                            <label>Products</label>
                            <span class="badge badge-info"><?php echo $products ?> Items</span>
                        </div>

                        <div class="col-md-6">
                            <label>Payment Status</label>
                            <span class="badge badge-success"><?php echo $payment_status ?></span>
                        </div>

                        <div class="col-md-6">
                            <label>Track Status</label>
                            <span class="badge badge-primary"><?php echo $track_status ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CUSTOMER -->
            <div class="card order-card mb-4">
                <div class="card-body">
                    <h5><i class="fas fa-user"></i> Customer Info</h5>

                    <?php if($customer){ ?>
                        <p><strong>Name:</strong> <?php echo $customer['user_name'] ?></p>
                        <p><strong>Email:</strong> <?php echo $customer['user_email'] ?></p>
                        <p><strong>Mobile:</strong> <?php echo $customer['user_mobile'] ?></p>
                        <p><strong>Address:</strong> <?php echo $customer['user_address'] ?></p>
                    <?php } else { ?>
                        <p class="text-muted">Customer data not available</p>
                    <?php } ?>
                </div>
            </div>

            <!-- PRODUCTS -->
            <div class="card order-card mb-4">
                <div class="card-body">
                    <h5><i class="fas fa-box"></i> Product Info</h5>

                    <div class="table-responsive">
                        <table class="table table-hover text-center">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Image</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>

                            <?php
                            $items = $con->prepare("
                                SELECT p.product_title, p.product_image1, oi.quantity, oi.price
                                FROM order_items oi
                                JOIN products p ON oi.product_id = p.product_id
                                WHERE oi.order_id=?
                            ");
                            $items->bind_param("i", $order_id);
                            $items->execute();
                            $items_result = $items->get_result();

                            while($item = $items_result->fetch_assoc()){
                                $total = $item['quantity'] * $item['price'];
                            ?>
                                <tr>
                                    <td><?php echo $item['product_title'] ?></td>
                                    <td>
                                        <img src="../admin/product_images/<?php echo $item['product_image1'] ?>" width="60">
                                    </td>
                                    <td><?php echo $item['quantity'] ?></td>
                                    <td>₹<?php echo number_format($item['price'],2) ?></td>
                                    <td>₹<?php echo number_format($total,2) ?></td>
                                </tr>
                            <?php } ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <!-- RIGHT -->
        <div class="col-lg-4">

            <div class="card order-card sticky-card">
                <div class="card-body">

                    <h5><i class="fas fa-truck"></i> Update Status</h5>

                    <form method="POST">
                        <?php $current = strtolower($order['track_status']); ?>

                        <select name="track-status" class="form-select" required>
                            <option value="Pending" <?php if($current=='pending') echo 'selected'; ?>>Pending</option>
                            <option value="Shipped" <?php if($current=='shipped') echo 'selected'; ?>>Shipped</option>
                            <option value="Out For Delivery" <?php if($current=='out for delivery') echo 'selected'; ?>>Out For Delivery</option>
                            <option value="Delivered" <?php if($current=='delivered') echo 'selected'; ?>>Delivered</option>
                        </select>

                        <button class="btn btn-primary w-100 mt-3" name="update_truck">
                            Update Status
                        </button>
                    </form>

                    <hr>

                    <a href="index.php?delete_order=<?php echo $order_id ?>" class="btn btn-danger w-100" onclick="return confirm('Are you sure?\n\nInvoice: <?php echo $order['invoice_number']; ?>\n \n Amount: ₹<?php echo number_format($order['amount_due'],2); ?>')">
                    Delete Order
                    </a>

                </div>
            </div>

        </div>

    </div>
</div>