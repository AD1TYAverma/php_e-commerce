<?php
session_start();
include('../includes/connect.php');
include('../functions/common_function.php');


if(!isset($_GET['order_id']) || !filter_var($_GET['order_id'], FILTER_VALIDATE_INT)){
    $_SESSION['toast_message']="Invalid Order ID Specified";
    echo"<script>window.location.href='profile.php?orders'</script>";
    exit();
}
$username= $_SESSION['username'];
$order_id=(int)$_GET['order_id'];
$user_id=$_SESSION['user_id']??null;

if(!$user_id){
    $stmt=$con->prepare("SELECT user_id FROM user_table WHERE user_name=?");
    $stmt->bind_param("s",$username);
    $stmt->execute();
    $result=$stmt->get_result();
    if($result && $row=$result->fetch_assoc()){
        $user_id=$row['user_id'];
        $_SESSION['user_id']=$user_id;
    }
}
if(!$user_id){
    $_SESSION['toast_message']="Error. Could Not Retrieve user data.";
    echo"<script>window.location.href='profile.php'</script>";
    exit();
}

$stmt=$con->prepare("SELECT * FROM user_orders WHERE order_id=? AND user_id=?");
$stmt->bind_param("ii",$order_id, $user_id);
$stmt->execute();
$result=$stmt->get_result();
if($result->num_rows==0){
    $_SESSION['toast_message']="Access Denied. The order was not found or order does not belong to your account";
    echo"<script>window.location.href='profile.php?orders'</script>";
    exit();
}

$order_data=$result->fetch_assoc();
$product_query=$con->prepare("SELECT oi.quantity, oi.product_id, p.product_title, p.product_image1, p.product_price FROM order_items oi JOIN products p ON oi.product_id=p.product_id WHERE oi.order_id=?");
$product_query->bind_param("i",$order_id);
$product_query->execute();
$result=$product_query->get_result();
$product_items=[];
if($result){
    while($row=$result->fetch_assoc()){
        $product_items[]=$row;
    }
}

// if(!empty($product_items) && $order_data['total_product']>0){
//     $_SESSION['toast_message']="Warning : Product details could not be loaded for this order";
//     echo"<script>window.location.href='profile.php?orders'</script>";
//     exit();
// }
$total_products = $order_data['total_product'] ?? 0;

if(empty($product_items) && $total_products > 0){
    $_SESSION['toast_message']="Warning : Product details could not be loaded for this order";
    echo "<script>window.location.href='profile.php?orders'</script>";
    exit();
}
$page_title = "Order #". htmlspecialchars($order_id)." Details Giftos" ;
?>
<title><?php echo $page_title; ?></title>
<link rel="icon" href="../images/favicon.png">

<?php
include('../includes/header.php');
include('../includes/navbar.php');
?>


<div class="container view-order-container">
    <h3 class="text-center mb-4 text-custom-maroon">
        Order Details - Invoice <?php echo $order_data['invoice_number']?>
    </h3>

    <div class="order-card-detail order-summary">
        <h4 class="custom-heading">
                    <i class="fas fa-file-invoice me-2"></i>Order Summary
                </h4>
        <div class="row">

            <div class="col-md-6 mb-3">
                

                <div class="summary-item"><strong class="summary-label">Order ID :</strong><?php echo $order_data['order_id']?></div>
                <div class="summary-item"><strong class="summary-label">Order Date :</strong><?php echo $order_data['order_date']?></div>
                <div class="summary-item"><strong class="summary-label">Total Amount :</strong><?php echo $order_data['amount_due']?></div>

                <div class="summary-item"><strong class="summary-label ">Total Product :</strong><?php echo $order_data['total_product']?></div>

            </div>
            <div class="col-md-6">
                <div class="summary-item"><strong class="summary-label">Payment Mode : </strong><?php echo $order_data['payment_mode']?></div>

                <?php 

                $order_status=strtolower($order_data['order_status']);
                $status_class=($order_status=='completed')?'bg-success':(($order_status=='pending')?'bg-secondary':'bg-danger');

                $track_class=(strtolower($order_data['track_status'])=='delivered')?'bg-success': 'bg-info text-dark';

                ?>

                <div class="summary-item"><strong class="summary-label">Payment Status : </strong><span class="badge <?php echo $status_class?>"><?php echo $order_data['order_status']?></span></div>
                <div class="summary-item"><strong class="summary-label">Tracking Status : </strong><span class="badge <?php echo $track_class?>"><?php echo $order_data['track_status']?></span></div>
                <div class="summary-item"><strong class="summary-label">Payment ID :</strong><?php echo $order_data['payment_id']?></div>
            </div>

        </div>
    </div>
    <div class="order-card-detail product-table">
        <h4 class="custom-heading"><i class="fas fa-boxes me-2"></i>Items in this Order</h4>
        <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-header-custom.text-white">
                <tr>
                    <th>Product</th>
                    <th>Image</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach($product_items as $item):
                    $unit_price = $item['product_price'] ?? 0;
                    $subtotal = $unit_price * $item['quantity'];
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['product_title']) ?></td>
                    <td>
                        <img src="../admin/product_images/<?php echo $item['product_image1']; ?>" 
                            style="width:50px; height:50px; object-fit:cover;">
                    </td>
                    <td><?php echo number_format($unit_price,2); ?></td>
                    <td><?php echo (int)$item['quantity']; ?></td>
                    <td><?php echo number_format($subtotal,2); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    </div>

    <div class="text-center mb-5">
        <a href="profile.php?orders" class="btn btn-secondary btn-custom-view"><i class="fas fa-arrow-left"></i>Back To Order History</a>
    </div>
    
</div>


<?php 
include('../includes/footer.php');
include('../includes/notification.php');
include('../includes/scripts_footer.php');
?>