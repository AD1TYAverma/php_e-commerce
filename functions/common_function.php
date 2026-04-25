<?php
function get_occasion(){
    global $con;

    $query = "SELECT * FROM occasions";
    $result = mysqli_query($con, $query);

    while($row = mysqli_fetch_assoc($result)){
        $occasion_id = $row['occasion_id'];
        $occasion_title = $row['occasion_title'];
        echo "<li class='nav-item'><a href='index.php?occasion=$occasion_id' class='nav-link'>$occasion_title</a></li>";
    };

}

function get_gift_categories(){
    global $con;

    $query = "SELECT * FROM gift_categories";
    $result = mysqli_query($con, $query);

    while($row = mysqli_fetch_assoc($result)){
        $category_id = $row['category_id'];
        $category_title = $row['category_title'];
        echo "<li class='nav-item'><a href='index.php?category=$category_id' class='nav-link'>$category_title</a></li>";
    };

}

function get_products(){
    global $con;
    if(!isset($_GET['category']) && !isset($_GET['occasion'])){
    $query = "SELECT * FROM products ORDER BY RAND() LIMIT 0,9";
    $result = mysqli_query($con, $query);
    while($row = mysqli_fetch_assoc($result)){
        $product_id = $row['product_id'];
        $product_title  = $row['product_title'];
        $product_description = $row['product_description'];
        $product_image1 = $row['product_image1'];
        $product_price = $row['product_price'];
        echo "<div class='col-md-4 mb-4'>
                    <div class='card'>
                        <img src='admin/product_images/$product_image1' class='card-img-top' style='height:250px;' alt='...'>
                        <div class='card-body'>
                            <h5 class='card-title'>$product_title</h5>
                            <p class='card-text'>".substr($product_description,0,30)."...</p>
                            <span class='card-price'> &#8377; $product_price</span>
                            <div class='card-actions d-flex gap-4 mt-2'>
                             <a href='index.php?add_to_cart=$product_id' class='btn btn-custom'><i class='fas fa-cart-plus me-2'></i>Add To Cart</a>
                             <a href='product_details.php?product_id=$product_id' class='btn btn-view-product'><i class='fas fa-eye me-2'></i>View</a>
                            </div>
                        </div>
                    </div>
                </div>";
    }
  }
}

function get_unique_gift_categories(){
    global $con;
    if(isset($_GET['category'])){
        $category_id = intVal($_GET['category']);
        $query = "SELECT * FROM products WHERE category_id = $category_id";
        $result = mysqli_query($con, $query);
        $num_of_rows = mysqli_num_rows($result);
        if($num_of_rows == 0){
            echo "<div class='col-12'><h2 class='text-center text-danger '>No Product In This Caregory</h2></div>";
        }
         while($row = mysqli_fetch_assoc($result)){
        $product_id = $row['product_id'];
        $product_title  = $row['product_title'];
        $product_description = $row['product_description'];
        $product_image1 = $row['product_image1'];
        $product_price = $row['product_price'];
        echo "<div class='col-md-4 mb-4'>
                    <div class='card'>
                        <img src='admin/product_images/$product_image1' class='card-img-top' style='height:250px;' alt='...'>
                        <div class='card-body'>
                            <h5 class='card-title'>$product_title</h5>
                            <p class='card-text'>".substr($product_description,0,30)."...</p>
                            <span class='card-price'>&#8377;$product_price</span>
                            <div class='card-actions d-flex gap-4 mt-2'>
                             <a href='index.php?add_to_catd=$product_id' class='btn btn-custom'><i class='fas fa-cart-plus me-2'></i>Add To Cart</a>
                             <a href='product_details.php?product_id=$product_id' class='btn btn-view-product'><i class='fas fa-eye me-2'></i>View</a>
                            </div>
                        </div>
                    </div>
                </div>";
    }
    }
}

function get_unique_occasion(){
    global $con;
    if(isset($_GET['occasion'])){
        $occasion_id = intVal($_GET['occasion']);
        $query = "SELECT * FROM products WHERE occasion_id = $occasion_id";
        $result = mysqli_query($con, $query);
        $num_of_rows = mysqli_num_rows($result);
        if($num_of_rows == 0){
            echo "<div class='col-12'><h2 class='text-center text-danger '>No Product For This Occasion</h2></div>";
        }
         while($row = mysqli_fetch_assoc($result)){
        $product_id = $row['product_id'];
        $product_title  = $row['product_title'];
        $product_description = $row['product_description'];
        $product_image1 = $row['product_image1'];
        $product_price = $row['product_price'];
        echo "<div class='col-md-4 mb-4'>
                    <div class='card'>
                        <img src='admin/product_images/$product_image1' class='card-img-top' style='height:250px;' alt='...'>
                        <div class='card-body'>
                            <h5 class='card-title'>$product_title</h5>
                            <p class='card-text'>".substr($product_description,0,30)."...</p>
                            <span class='card-price'>&#8377;$product_price</span>
                            <div class='card-actions d-flex gap-4 mt-2'>
                             <a href='index.php?add_to_catd=$product_id' class='btn btn-custom'><i class='fas fa-cart-plus me-2'></i>Add To Cart</a>
                             <a href='product_details.php?product_id=$product_id' class='btn btn-view-product'><i class='fas fa-eye me-2'></i>View</a>
                            </div>
                        </div>
                    </div>
                </div>";
    }
    }
}

function getAllProducts(){
    global $con;
    if(!isset($_GET['category']) && !isset($_GET['occasion'])){
    $query = "SELECT * FROM products ORDER BY RAND() ";
    $result = mysqli_query($con, $query);
    while($row = mysqli_fetch_assoc($result)){
        $product_id = $row['product_id'];
        $product_title  = $row['product_title'];
        $product_description = $row['product_description'];
        $product_image1 = $row['product_image1'];
        $product_price = $row['product_price'];
        echo "<div class='col-md-4 mb-4'>
                    <div class='card'>
                        <img src='admin/product_images/$product_image1' class='card-img-top' style='height:250px;' alt='...'>
                        <div class='card-body'>
                            <h5 class='card-title'>$product_title</h5>
                            <p class='card-text'>".substr($product_description,0,30)."...</p>
                            <span class='card-price'>&#8377; $product_price</span>
                            <div class='card-actions d-flex gap-4 mt-2'>
                             <a href='index.php?add_to_catd=$product_id' class='btn btn-custom'><i class='fas fa-cart-plus me-2'></i>Add To Cart</a>
                             <a href='product_details.php?product_id=$product_id' class='btn btn-view-product'><i class='fas fa-eye me-2'></i>View</a>
                            </div>
                        </div>
                    </div>
                </div>";
    }
  }
}


function viewDetails(){
    global $con;
    if(isset($_GET['product_id']) && filter_var($_GET['product_id'], FILTER_VALIDATE_INT)){
        $product_id = (int)$_GET['product_id'];
        $query = "SELECT * FROM products WHERE product_id = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "i", $product_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while($row = mysqli_fetch_assoc($result)){
            $product_title = htmlspecialchars($row['product_title']);
            $product_description = htmlspecialchars($row['product_description']);
            $product_image1 = htmlspecialchars($row['product_image1']);
            $product_image2 = htmlspecialchars($row['product_image2']);
            $product_image3 = htmlspecialchars($row['product_image3']);
            $product_price = htmlspecialchars($row['product_price']);
            $base_image_path = 'admin/product_images/';
            echo "<div class='col-md-6 text-center'>
            <img src='{$base_image_path}{$product_image1}' alt='{$product_title}' class='product-main-image mb-4 img-fluid' style='width: 300px; object-fit:contain;'>

            <div class='d-flex justify-content-center gap-3'>
                <img src='{$base_image_path}{$product_image1}' alt='{$product_title}' class='product-small-image' style='width: 100px;cursor:pointer;' onclick=\"changeImage('{$base_image_path}{$product_image1}')\">
                ";
                if(!empty($product_image2)){
                   echo "<img src='{$base_image_path}{$product_image2}' alt='' class='product-small-image' style='width: 100px;cursor:pointer;' onclick=\"changeImage('{$base_image_path}{$product_image2}')\">";
                }
                if(!empty($product_image2)){
                   echo "<img src='{$base_image_path}{$product_image3}' alt='{$product_title}' class='product-small-image' style='width: 100px;cursor:pointer;' onclick=\"changeImage('{$base_image_path}{$product_image3}')\">";
                }
           echo" </div>
        </div>
        
        <div class='col-md-6'>
            <h2 class='product-title'>{$product_title}</h2>
            <h4 class='product-price'>{$product_price}</h4>
            <p class='product-description'>{$product_description}</p>
            <a href='index.php?add_to_card={$product_id}' class='btn btn-custom mt-3'><i class='fa-solid fa-cart-shopping text-white'></i>Add To Cart</a>
            <a href='index.php' class='btn btn-view-product mt-3'><i class='fa-solid fa-house text-white'></i>Go Home</a>
        </div>";
        }
        mysqli_stmt_close($stmt);
    }else{
        echo"<h1 class='text-center text-danger my-5'>Invalid Product Selected Or Product ID Is Missing</h1>";
    }
}

function serachProduct(){
    global $con;
    if(isset($_GET['search_data_product'])){
        $search_data_value = mysqli_real_escape_string($con, $_GET['search_data']);
        $query = "SELECT * FROM products WHERE product_keywords LIKE '%$search_data_value%' OR product_title LIKE '%$search_data_value%'";
        $result = mysqli_query($con, $query);
        $num_of_rows =mysqli_num_rows($result);
        if($num_of_rows==0){
            echo "<div class='col-12'><h1 class='text-center text-danger'>No Result Found For This Search</h1></div>";
        }
        while($row = mysqli_fetch_assoc($result)){
        $product_id = $row['product_id'];
        $product_title  = $row['product_title'];
        $product_description = $row['product_description'];
        $product_image1 = $row['product_image1'];
        $product_price = $row['product_price'];
        echo "<div class='col-md-4 mb-4'>
                    <div class='card'>
                        <img src='admin/product_images/$product_image1' class='card-img-top' style='height:250px;' alt='...'>
                        <div class='card-body'>
                            <h5 class='card-title'>$product_title</h5>
                            <p class='card-text'>".substr($product_description,0,30)."...</p>
                            <span class='card-price'>&#8377; $product_price</span>
                            <div class='card-actions d-flex gap-4 mt-2'>
                             <a href='index.php?add_to_cart=$product_id' class='btn btn-custom'><i class='fas fa-cart-plus me-2'></i>Add To Cart</a>
                             <a href='product_details.php?product_id=$product_id' class='btn btn-view-product'><i class='fas fa-eye me-2'></i>View</a>
                            </div>
                        </div>
                    </div>
                </div>";
    }
    }
}

function cart(){
    global $con;

    if(isset($_GET['add_to_cart'])){

        $get_product_id = intval($_GET['add_to_cart']);

        // ================= LOGIN USER =================
        if(isset($_SESSION['username'])){

            $username = $_SESSION['username'];

            // get user id
            $stmt = $con->prepare("SELECT user_id FROM user_table WHERE user_name=?");
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows > 0){
                $user_data = $result->fetch_assoc();
                $user_id = $user_data['user_id'];

                // check cart
                $check_cart = $con->prepare("SELECT * FROM card_details WHERE user_id=? AND product_id=?");
                $check_cart->bind_param("ii", $user_id, $get_product_id);
                $check_cart->execute();
                $result_check = $check_cart->get_result();

                if($result_check->num_rows > 0){

                    // UPDATE
                    $update = $con->prepare("UPDATE card_details SET quantity = quantity + 1 WHERE user_id=? AND product_id=?");
                    $update->bind_param("ii", $user_id, $get_product_id);

                    if($update->execute()){
                        $_SESSION['toast_message'] = "Product Quantity Updated Successfully";
                    }

                }else{

                    // INSERT
                    $insert = $con->prepare("INSERT INTO card_details(user_id, product_id, quantity) VALUES(?, ?, 1)");
                    $insert->bind_param("ii", $user_id, $get_product_id);

                    if($insert->execute()){
                        $_SESSION['toast_message'] = "Product Added To Cart Successfully";
                    }
                }

            }else{
                $_SESSION['toast_message'] = "User not found!";
            }

        }
        // ================= GUEST USER =================
        else{

            if(!isset($_SESSION['cart'])){
                $_SESSION['cart'] = [];
            }

            if(isset($_SESSION['cart'][$get_product_id])){
                $_SESSION['cart'][$get_product_id] += 1;
                $_SESSION['toast_message'] = "Product Quantity Updated Successfully";
            }else{
                $_SESSION['cart'][$get_product_id] = 1;
                $_SESSION['toast_message'] = "Product Added To Cart Successfully";
            }
        }

        // ✅ IMPORTANT REDIRECT (for both cases)
        $redirect_url = strtok($_SERVER['REQUEST_URI'], '?');
        header("Location: $redirect_url");
        exit();
    }
}

function cart_item(){
    global $con;

    $count = 0;

    // ================= LOGIN USER =================
    if(isset($_SESSION['username'])){

        $username = $_SESSION['username'];

        // get user id safely
        $stmt = $con->prepare("SELECT user_id FROM user_table WHERE user_name=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();

        if($result && $result->num_rows > 0){

            $user_data = $result->fetch_assoc();
            $user_id = $user_data['user_id'];

            // count total quantity
            $count_query = $con->prepare("SELECT SUM(quantity) AS total FROM card_details WHERE user_id=?");
            $count_query->bind_param("i", $user_id);
            $count_query->execute();

            $data = $count_query->get_result()->fetch_assoc();

            $count = $data['total'] ?? 0;
        }

    }

    // ================= GUEST USER =================
    else{
        if(isset($_SESSION['cart'])){
            $count = array_sum($_SESSION['cart']);
        }
    }

    echo $count;
}

function total_price(){
    global $con;
    $total=0;
    if(isset($_SESSION['username'])){
        $username = $_SESSION['username'];
        if(isset($_SESSION['user_id'])){
            $stmt = $con->prepare("SELECT user_id FROM user_table WHERE user_name=?");
            $stmt ->bind_param("s", $username);
            $stmt->execute();
            $_SESSION['user_id']= $stmt->get_result()->fetch_assoc()['user_id'];
        }
        $uid = $_SESSION['user_id'];
        $query = "SELECT p.product_price, c.quantity FROM card_details c JOIN products p ON c.product_id=p.product_id WHERE c.user_id=?";
        $stmt= $con->prepare($query);
        $stmt->bind_param("i", $uid);
        $stmt->execute();
        $items=$stmt->get_result();
        while($row= $items->fetch_assoc()){
            $total+=$row['product_price']*$row['quantity'];
        }
    }else{
        if(isset($_SESSION['cart']) && isset($_SESSION['cart'])>0){
            $product_ids = array_keys($_SESSION['cart']);
            $placeholder = implode(',', array_fill(0, count($product_ids),'?'));
            $query = "SELECT product_id, product_price FROM products WHERE product_id IN ($placeholder)";
            $stmt=$con->prepare($query);
            $types= str_repeat('i', count($product_ids));
            $stmt->bind_param($types,...$product_ids);
            $stmt->execute();
            $result=$stmt->get_result();
            while($row= $result->fetch_assoc()){
                $pid = $row['product_id'];
                $total+=$row['product_price']*$_SESSION['cart'][$pid];
            }
        }
    }
    echo $total;
}
?>