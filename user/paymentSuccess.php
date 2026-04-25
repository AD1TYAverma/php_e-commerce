<?php
session_start();
require_once('../includes/connect.php');
require_once('../functions/common_function.php');
require_once('../razorpay/src/Razorpay.php');
require('config.php');

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

$page_title = "Payment Status";
$content = "";

if (
    isset($_POST['razorpay_payment_id']) &&
    isset($_POST['razorpay_order_id']) &&
    isset($_POST['razorpay_signature'])
) {

    $razorpay_payment_id = $_POST['razorpay_payment_id'];
    $razorpay_order_id = $_POST['razorpay_order_id'];
    $razorpay_signature = $_POST['razorpay_signature'];

    $api = new Api($api_key, $api_secret);

    try {
        // ================= VERIFY PAYMENT =================
        $attributes = [
            'razorpay_order_id' => $razorpay_order_id,
            'razorpay_payment_id' => $razorpay_payment_id,
            'razorpay_signature' => $razorpay_signature,
        ];

        $api->utility->verifyPaymentSignature($attributes);

        // ================= CHECK SESSION =================
        if (!isset($_SESSION['username'])) {
            throw new Exception("User session not found");
        }

        $username = $_SESSION['username'];

        // ================= GET USER ID =================
        $stmt = $con->prepare("SELECT user_id FROM user_table WHERE user_name=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();

        $user_result = $stmt->get_result();
        $user_data = $user_result->fetch_assoc();

        if (!$user_data) {
            throw new Exception("User not found");
        }

        $user_id = $user_data['user_id'];

        // ================= GET CART ITEMS =================
        $stmt_cart = $con->prepare("
            SELECT c.product_id, c.quantity, p.product_price
            FROM card_details c
            JOIN products p ON c.product_id = p.product_id
            WHERE c.user_id=?
        ");

        $stmt_cart->bind_param("i", $user_id);
        $stmt_cart->execute();

        $cart_items = $stmt_cart->get_result();

        if ($cart_items->num_rows == 0) {
            $content = "<h3 class='text-danger text-center'>No items found in your cart</h3>";
        } else {

            $total_amount = 0;
            $total_products = 0;
            $temp_cart_items = [];

            while ($row = $cart_items->fetch_assoc()) {
                $total_amount += $row['product_price'] * $row['quantity'];
                $total_products += $row['quantity'];
                $temp_cart_items[] = $row;
            }

            $invoice_number = 'INV' . strtoupper(uniqid());
            $payment_mode = 'Razorpay';
            $status = 'Completed';

            // ================= INSERT ORDER =================
            $stmt_order = $con->prepare("
                INSERT INTO user_orders
                (user_id, amount_due, invoice_number, total_product, payment_id, payment_mode, order_status)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt_order->bind_param(
                "idsssss",
                $user_id,
                $total_amount,
                $invoice_number,
                $total_products,
                $razorpay_payment_id,
                $payment_mode,
                $status
            );

            $stmt_order->execute();
            $order_id = $con->insert_id;

            // ================= INSERT ORDER ITEMS =================
            $stmt_items = $con->prepare("
                INSERT INTO order_items(order_id, product_id, quantity, price)
                VALUES (?, ?, ?, ?)
            ");

            foreach ($temp_cart_items as $row) {
                $price = $row['product_price'] * $row['quantity'];

                $stmt_items->bind_param(
                    "iiid",
                    $order_id,
                    $row['product_id'],
                    $row['quantity'],
                    $price
                );

                $stmt_items->execute();
            }

            // ================= CLEAR CART =================
            $stmt_delete = $con->prepare("DELETE FROM card_details WHERE user_id=?");
            $stmt_delete->bind_param("i", $user_id);
            $stmt_delete->execute();

            // ================= SUCCESS MESSAGE =================
            $page_title = "Payment Successful";

            $content = "
                <i class='fa-solid fa-circle-check text-success fs-1 mb-3'></i>
                <h2 class='text-success'>Order Placed Successfully!</h2>
                <p>Thank you for your purchase. Your order has been confirmed.</p>
                <hr>
                <div class='info-row'><strong>Total Amount:</strong> ₹" . number_format($total_amount, 2) . "</div>
                <div class='info-row'><strong>Invoice Number:</strong> $invoice_number</div>
                <div class='info-row'><strong>Transaction ID:</strong> $razorpay_payment_id</div>
                <a href='profile.php?orders' class='btn btn-success mt-4'>View My Orders</a>
                <a href='../index.php' class='btn btn-secondary mt-4'>Continue Shopping</a>
            ";
        }

    } catch (SignatureVerificationError $e) {
        $page_title = "Verification Failed";

        $content = "
            <i class='fa-solid fa-circle-xmark text-danger fs-1 mb-3'></i>
            <h2 class='text-danger'>Payment Verification Failed</h2>
            <p>". htmlspecialchars($e->getMessage())."</p>
            <a href='../card.php' class='btn btn-warning mt-3'>Go Back to Cart</a>
        ";

    } catch (Exception $e) {
        $page_title = "Server Error";

        $content = "
            <i class='fa-solid fa-triangle-exclamation text-danger fs-1 mb-3'></i>
            <h2 class='text-danger'>Server Error</h2>
            <p>" . htmlspecialchars($e->getMessage()) . "</p>
            <a href='../card.php' class='btn btn-warning mt-3'>Go Back to Cart</a>
        ";
    }

} else {
    $page_title = "Invalid Request";

    $content = "
        <i class='fa-solid fa-circle-question text-danger fs-1 mb-3'></i>
        <h2 class='text-danger'>Invalid Request</h2>
        <p>Please complete checkout properly.</p>
        <a href='../card.php' class='btn btn-warning mt-3'>Go Back to Cart</a>
    ";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?> | E-Commerce Website</title>
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="./user_style.css">

    <link rel="stylesheet" href="../style/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />


</head>
<body class="payment-status-body">
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="payment-status-card text-center">
            <?= $content ?>
        </div>
    </div>
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>