<?php
// session_start();
require_once('../includes/connect.php');
require_once('../functions/common_function.php');
// require_once('../user/config.php'); 
require_once('../razorpay/src/Razorpay.php');
require('config.php');

use Razorpay\Api\Api;
if(!isset($_SESSION['username'])){
    echo"<script>alert('Please Login First'); window.location.href='user_login.php';</script>";
    exit();
}

$username=$_SESSION['username'];
$stmt=$con->prepare('SELECT user_id, user_email, user_mobile FROM user_table WHERE user_name=?');
$stmt->bind_param("s", $username);
$stmt->execute();
$user=$stmt->get_result()->fetch_assoc();
if(!$user){
    echo"<script>alert('User Not Found. Please re-loging'); window.location.href='user_login.php';</script>";
    exit();
}
$user_id=$user['user_id'];
$total_price=0;
$cart_items=[];
$stmt_cart = $con->prepare("SELECT c.product_id, p.product_title, p.product_price, c.quantity FROM card_details c JOIN products p ON c.product_id=p.product_id WHERE c.user_id=?");
$stmt_cart->bind_param("i", $user_id);
$stmt_cart->execute();
$result_cart = $stmt_cart->get_result();
if($result_cart->num_rows==0){
    echo"<script>alert('Your Cart Is Empty'); window.location.href='../card.php';</script>";
    exit();
}

while($row=$result_cart->fetch_assoc()){
    $row['subtotal'] = $row['product_price']*$row['quantity'];
    // $total_price=$row['subtotal'];
    $total_price += $row['subtotal'];
    $cart_items[]=$row;
}
$amount_in_paise=$total_price*100;

$api = new Api($api_key, $api_secret);

//cerate order

try{
    $orderData=[
        'receipt' => 'order_rcpid_'.uniqid(),
        'amount' => $amount_in_paise,
        'currency' => 'INR',
        'payment_capture' => 1
    ];
    $order=$api->order->create($orderData);
    $order_id=$order['id'];
}catch(\Exception $e){
    echo"<div class='text-center my-5'><h3 class='text-danger'>Payment Gateway Error<p>Could not create order : ".htmlspecialchars($e->getMessage())."</p></h3></div>";
    exit();
}
?>

<div class="col-md-10 payment-container">
    <h2 class="text-center mb-4 custom-heading">Complete Your Payment</h2>
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card p-4 payment-card">
        <h4 class="cart-summary-title">Cart Summary</h4>
        <hr>
        <?php foreach($cart_items as $item) : ?>
            <div class="cart-summary-item">
                <span><?= htmlspecialchars($item['product_title']); ?> x <?= htmlspecialchars($item['quantity']); ?></span> 
                <span><?= number_format($item['subtotal'],2) ?></span>
            </div>
        <?php endforeach; ?>
        <div class="total-price-row">
            <span>Total Amount:</span>
            <span><?= number_format($total_price,2) ?></span>
        </div>
        <button id="payBtn" class="btn btn-razorpay-pay w-100 mt-4">
            <i class="fa-solid fa-money-check-dollar me-2"></i>Pay Now
        </button>
    </div>
    </div>
    </div>
</div>
</div>

<form action="paymentSuccess.php" id="paymentForm" method="POST" style="display: none;">
    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
    <input type="hidden" name="razorpay_order_id" id="razorpay_order_id" value="<?= $order_id ?>">
    <input type="hidden" name="razorpay_signature" id="razorpay_signature">
</form>


<script src="https://checkout.razorpay.com/v1/checkout.js"></script>


<script>
        var options = {
            key: "<?= htmlspecialchars($api_key) ?>", // Enter the Key ID generated from the Dashboard
            amount: "<?= htmlspecialchars($amount_in_paise) ?>", // Amount is in currency subunits. 
            currency: "INR",
            name: "Giftos",
            description: "Online Purches From Giftos",
            image: "https://cdn.razorpay.com/logos/GhRQcyean79PqE_medium.png",
            // image: " <img src='../images/favicon.png'>",
            order_id: "<?= htmlspecialchars($order_id) ?>", // This is a sample Order ID. Pass the `id` obtained in the response of Step 1
            "handler": function (response){
                document.getElementById("razorpay_payment_id").value=response.razorpay_payment_id;
                document.getElementById("razorpay_order_id").value=response.razorpay_order_id;
                document.getElementById("razorpay_signature").value=response.razorpay_signature;
                document.getElementById("paymentForm").submit();
            },
            prefill: {
                name: "<?= htmlspecialchars($username) ?>",
                email: "<?= htmlspecialchars($user['user_email']) ?>",
                contact: "<?= htmlspecialchars($user['user_mobile']) ?>"
            },
            notes: {
                address: "Razorpay Corporate Office"
            },
            method: {
        upi: true,
        card: true,
        netbanking: true,
        wallet: true
    },
            theme: {
                "color": "#0d6efd"
            },
            callback_url: "paymentSuccess.php"
        };
        document.getElementById('payBtn').onclick=function(e){
            var rzp = new Razorpay(options);
            rzp.open();
            e.preventDefault();
        }
</script>


