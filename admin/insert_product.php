<?php
include('../includes/connect.php');
if(isset($_POST['insert_product'])){
    $product_title=$_POST['product_title'];
    $product_description=$_POST['description'];
    $product_keywords=$_POST['product_keywords'];
    $product_price=filter_var($_POST['product_price'],FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $product_category=$_POST['product_category'];
    $product_occasion=$_POST['product_occasion'];

    $status=true;

    $product_image1= $_FILES['product_image1']['name'];
    $temp_image1=$_FILES['product_image1']['tmp_name'];

    $product_image2 = isset($_FILES['product_image2']['name']) && $_FILES['product_image2']['error'] == 0 ? $_FILES['product_image2']['name'] : '';
    $temp_image2=isset($_FILES['product_image2']['tmp_name'])  && $_FILES['product_image2']['error'] == 0 ? $_FILES['product_image2']['tmp_name'] : '';

    $product_image3 = isset($_FILES['product_image3']['name']) && $_FILES['product_image3']['error'] == 0 ? $_FILES['product_image3']['name'] : '';
    $temp_image3=isset($_FILES['product_image3']['tmp_name'])  && $_FILES['product_image3']['error'] == 0 ? $_FILES['product_image3']['tmp_name'] : '';

    if(empty($product_title) || empty($product_description)|| empty($product_keywords)||empty($product_price)||empty($product_category)||empty($product_occasion)|| empty($product_image1)){
        echo "<script>alert('please fill all the required fields')</script>";
    }else{
        move_uploaded_file($temp_image1,"./product_images/$product_image1");
        if(!empty($product_immage2)){
            move_uploaded_file($temp_image2,"./product_images/$product_image2");
        }
        if(!empty($product_immage2)){
            move_uploaded_file($temp_image3,"./product_images/$product_image3");
        }

        $stmt=$con->prepare("INSERT INTO products(product_title, product_description, product_keywords, occasion_id, category_id, product_image1, product_image2, product_image3, product_price, status, created_at)VALUES(?,?,?,?,?,?,?,?,?,?, NOW())");

    $stmt->bind_param("sssiisssds", $product_title, $product_description, $product_keywords, $product_category, $product_occasion, $product_image1, $product_image2, $product_image3, $product_price, $status);
    $result=$stmt->execute();

    if($result){
        echo"<script>alert('Product **{$product_title}** inserted successfully')</script>";
        echo"<script>window.location.href='index.php?view_products'</script>";
    }else{
        echo"<script>alert('Error Inserting Product')</script>";
    }

    }
    
}

?>




<div class="container mt-4">
    <h2 class="mb-4">
        <i class="fas fa-plus-circle"></i>Insert New Product
    </h2>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-5">
                <div class="card-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label  class="form-label fw-bold">
                                <i class="fas fa-tag"></i>Product Title
                            </label>
                            <input type="text" name="product_title" class="form-control" placeholder="eg. Birthday gift Box" required>
                        </div>

                        <div class="mb-3">
                            <label  class="form-label fw-bold">
                                <i class="fas fa-align-left"></i>Product Description
                            </label>
                            <input type="text" name="description" class="form-control" placeholder="Enter product description.." required>
                        </div>

                        <div class="mb-3">
                            <label  class="form-label fw-bold">
                                <i class="fas fa-key"></i>Product Keywords
                            </label>
                            <input type="text" name="product_keywords" class="form-control" placeholder="eg. birthday gift, celebration(camma separated)" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label  class="form-label fw-bold">
                                    <i class="fas fa-folder"></i>Category
                                </label>
                                <select name="product_category" required class="form-select">
                                    <option value="">--Select Category--</option>
                                    <?php
                                    $stmt_cat=$con->prepare("SELECT category_id, category_title FROM gift_categories ORDER BY category_title");
                                    $stmt_cat->execute();
                                    $result_cat=$stmt_cat->get_result();
                                    while($row=$result_cat->fetch_assoc()){
                                        echo "<option value='{$row['category_id']}'>{$row['category_title']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label  class="form-label fw-bold">
                                    <i class="fas fa-calendar-alt"></i>Occasions
                                </label>
                                <select name="product_occasion" required class="form-select">
                                    <option value="">--Select Occasions--</option>
                                     <?php
                                    $stmt_occ=$con->prepare("SELECT occasion_id , occasion_title FROM occasions ORDER BY occasion_title");
                                    $stmt_occ->execute();
                                    $result_occ=$stmt_occ->get_result();
                                    while($row=$result_occ->fetch_assoc()){
                                        echo "<option value='{$row['occasion_id']}'>{$row['occasion_title']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="mb-3">
                            <label  class="form-label fw-bold">
                                <i class="fas fa-rupee-sign"></i>Product Price
                            </label>
                            <input type="text" name="product_price" class="form-control" placeholder="Enter Product Price" min="1" required>
                        </div>

                        </div>
                        <hr class="my-4">
                        <h5 class="mb-3"><i class="fas fa-images"></i>Product Image</h5>
                        <div class="mb-3">
                            <label  class="form-label fw-bold">
                                <i class="fas fa-image"></i>Product Image (Required)
                            </label>
                            <input type="file" name="product_image1" class="form-control" required accept="image/*">
                            <small class="text-muted">This will be the main display image </small>
                        </div>

                        <div class="mb-3">
                            <label  class="form-label fw-bold">
                                <i class="fas fa-image"></i>Addition Image 2 (Optional)
                            </label>
                            <input type="file" name="product_image2" class="form-control" required accept="image/*">
                        </div>

                        <div class="mb-3">
                            <label  class="form-label fw-bold">
                                <i class="fas fa-image"></i>Addition Image 3 (Optional)
                            </label>
                            <input type="file" name="product_image3" class="form-control" required accept="image/*">
                        </div>
                        <div class="text-center mt-4">
                            <button class="btn btn-primary px-5" type="submit" name="insert_product">
                                <i class="fas fa-check-circle me-2"></i>Insert Products
                            </button>
                            <a href="index.php?view_products" class="btn btn-secondary px-4 ms-2">
                                <i class="fas fa-times-circle me-2"></i>Cencel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>