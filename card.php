<?php
ob_start();
session_start();
include('./includes/connect.php');
include('./functions/common_function.php');

$user_id = null;

// ================= USER FETCH =================
if(isset($_SESSION['username'])){
    $username = $_SESSION['username'];

    $stmt = $con->prepare("SELECT user_id FROM user_table WHERE user_name=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $user_data = $result->fetch_assoc();
        $user_id = $user_data['user_id'];
    }
}

// ================= DELETE ITEM =================
if(isset($_GET['delete_item'])){
    $delete_id = (int)$_GET['delete_item'];

    if($user_id){
        $stmt = $con->prepare("DELETE FROM card_details WHERE user_id=? AND product_id=?");
        $stmt->bind_param("ii", $user_id, $delete_id);
        $stmt->execute();
    }else{
        unset($_SESSION['cart'][$delete_id]);
    }

    $_SESSION['toast_message'] = "Item Removed Successfully";
    header("Location: card.php");
    exit();
}

// ================= UPDATE CART =================
if(isset($_POST['update_cart']) && isset($_POST['qty'])){
    foreach($_POST['qty'] as $pid => $qty){

        $pid = (int)$pid;
        $qty = (int)$qty;

        if($qty <= 0) continue;

        if($user_id){
            // ✅ FIXED TABLE + COLUMN NAME
            $stmt = $con->prepare("UPDATE card_details SET quantity=? WHERE user_id=? AND product_id=?");
            $stmt->bind_param("iii", $qty, $user_id, $pid);
            $stmt->execute();
        }else{
            $_SESSION['cart'][$pid] = $qty;
        }
    }

    $_SESSION['toast_message'] = "Cart Updated Successfully";
    header("Location: card.php");
    exit();
}

// ================= CART FETCH =================
$total_price = 0;
$cart_items = [];

if($user_id){

    $stmt = $con->prepare("
        SELECT p.product_id, p.product_title, p.product_price, p.product_image1, c.quantity 
        FROM card_details c 
        JOIN products p ON p.product_id = c.product_id 
        WHERE c.user_id=?
    ");

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while($row = $result->fetch_assoc()){
        $cart_items[] = $row;
        $total_price += ($row['product_price'] * $row['quantity']);
    }

}elseif(!empty($_SESSION['cart'])){

    $ids = implode(",", array_keys($_SESSION['cart']));

    $query = "SELECT * FROM products WHERE product_id IN ($ids)";
    $result = mysqli_query($con, $query);

    while($row = mysqli_fetch_assoc($result)){
        $row['quantity'] = $_SESSION['cart'][$row['product_id']];
        $cart_items[] = $row;
        $total_price += ($row['product_price'] * $row['quantity']);
    }
}

//delete card
if(isset($_GET['delete_item'])){
    $pid = (int)$_GET['delete_item'];
    if($user_id){
        $stmt=$con->prepare("DELETE FROM card_details WHERE user_id=? AND product_id=?");
        $stmt->bind_param("ii", $user_id, $pid);
        $stmt->execute();
    }else{
        unset($_SESSION['cart'][$pid]);
    }
    $_SESSION['toast-message']="Item Remove from Cart";
    header("Location:card.php");
    exit();
}

//delete mutiple items
if(isset($_POST['delete_all_cart']) && isset($_POST['remove'])){
    foreach($_POST['remove'] as $pid){

        $pid = (int)$pid;

        if($user_id){
            $stmt = $con->prepare("DELETE FROM card_details WHERE user_id=? AND product_id=?");
            $stmt->bind_param("ii", $user_id, $pid);
            $stmt->execute();
        }else{
            unset($_SESSION['cart'][$pid]);
        }
    }

    $_SESSION['toast_message'] = "Selected Items Removed Successfully";
    header("Location: card.php");
    exit();
}

ob_end_clean();

include('./includes/header.php');

// cart function (add to cart)
if(function_exists('cart')){
    cart();
}
?>

<script>document.title = 'Giftos - Shopping Cart'</script>

<?php include('./includes/navbar.php') ?>

<div class="container my-4">
    <div class="hero-banner">Shopping Cart</div>
</div>

<div class="container mb-5">
    <div class="row">

        <form action="card.php" method="POST">
            <?php if(!empty($cart_items)):?>
            <div class="table-responsive">
                <table class="table table-bordered text-center align-middle">

                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>Product Title</th>
                            <th>Product Image</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php 
                    if(!empty($cart_items)){

                        foreach($cart_items as $item){

                            $product_id = $item['product_id'];
                            $subtotal = $item['product_price'] * $item['quantity'];

                            echo "<tr>

                                <td>
                                    <input type='checkbox' name='remove[]' value='$product_id'>
                                </td>

                                <td>{$item['product_title']}</td>

                                <td>
                                    <img src='./admin/product_images/{$item['product_image1']}' style='width:100px;'>
                                </td>

                                <td>
                                    <input type='number' 
                                           name='qty[$product_id]' 
                                           min='1' 
                                           value='{$item['quantity']}'
                                           class='form-control w-50 mx-auto text-center'>
                                </td>

                                <td>&#8377; {$item['product_price']}</td>

                                <td>&#8377; $subtotal</td>

                                <td>
                                    <div class='d-flex justify-content-center gap-2'>

                                        <input type='submit' 
                                               value='Update' 
                                               class='btn btn-update-custom-maroon btn-sm' 
                                               name='update_cart'>

                                        <a href='card.php?delete_item={$product_id}' class='btn btn-danger btn-sm' onclick=\"return confirm('Are You Sure?');\">
                                            Remove
                                         </a>

                                    </div>
                                </td>

                            </tr>";
                        }

                    }else{
                        echo "<tr>
                                <td colspan='7'>Cart is Empty</td>
                              </tr>";
                    }
                    ?>
                    </tbody>

                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center flex-wrap mt-4">
                <div class="col-md-6 mb-3">

                    <h4 class="text-custom-maroon">
                        Total amount : 
                        <span class="text-danger">&#8377; <?php echo $total_price ?>/-</span>
                    </h4>

                    <div class="mt-3">
                        <a href="index.php" class="btn btn-outline-secondary me-2">Continue Shopping</a>

                        <?php 
                        if(isset($_SESSION['username'])){
                            echo "<a href='./user/checkout.php' class='btn btn-custom'>Proceed to Checkout</a>";
                        }else{
                            echo "<a href='user/user_login.php?checkout=1' class='btn btn-login-custom'>Login to Checkout</a>";
                        }
                        ?>
                    </div>

                </div>
                <div class="col-ms-6 text-end mb-3">
                    <input type="submit" value="Delete Selected" class="btn btn-remove-custom" name="delete_all_cart">
                </div>
                
            </div>
            <?php else:?>
            <div class="col-12 text-center mt-2">
                <h4 class="text-muted">No Items in your card</h4>
                <a href="index.php" class="btn btn-custom mt-3">Explore Products</a>
            </div>
            <?php endif; ?>

        </form>

    </div>
</div>

<?php include('./includes/footer.php') ?>
<?php include('./includes/notification.php') ?>
<?php include('./includes/scripts_footer.php') ?>