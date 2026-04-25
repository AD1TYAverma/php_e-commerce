<?php
include('../includes/connect.php');

if(isset($_GET['edit_product'])){
    $edit_id= $_GET['edit_product'];
    $stmt=$con->prepare("SELECT * FROM products WHERE product_id=? ");
    $stmt->bind_param("i",$edit_id);
    $stmt->execute();
    $result=$stmt->get_result();
    $row=$result->fetch_assoc();

    $product_title=$row['product_title'];
    $product_description=$row['product_description'];
    $product_keywords=$row['product_keywords'];
    $category_id=$row['category_id'];
    $occasion_id=$row['occasion_id'];
    $product_image1=$row['product_image1'];
    $product_image2=$row['product_image2'];
    $product_image3=$row['product_image3'];
    $product_price=$row['product_price'];

    $stmt_cat=$con->prepare("SELECT * FROM gift_categories WHERE category_id=?");
    $stmt_cat->bind_param("i",$category_id);
    $stmt_cat->execute();
    $result_category=$stmt_cat->get_result();
    $row_category=$result_category->fetch_assoc();
    $current_category_title=$row_category['category_title'];

    $stmt_occ=$con->prepare("SELECT * FROM occasions WHERE occasion_id=?");
    $stmt_occ->bind_param("i",$occasion_id);
    $stmt_occ->execute();
    $result_occasion=$stmt_occ->get_result();
    $row_occasion=$result_occasion->fetch_assoc();
    $current_occasion_title=$row_occasion['occasion_title'];
}

?>



<div class="container mt-4">
    <h2 class="mb-4">
        <i class="fas fa-edit"></i>Edit Product
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
                            <input type="text" name="product_title" class="form-control"  required value="<?php echo htmlspecialchars($product_title)?>">
                        </div>

                        <div class="mb-3">
                            <label  class="form-label fw-bold">
                                <i class="fas fa-align-left"></i>Product Description
                            </label>
                            <textarea type="text" name="product_description" class="form-control" required  value="Product description" rows="2"><?php echo htmlspecialchars($product_description)?></textarea>
                        </div>

                        <div class="mb-3">
                            <label  class="form-label fw-bold">
                                <i class="fas fa-key"></i>Product Keywords
                            </label>
                            <input type="text" name="product_keywords" class="form-control" required  value="<?php echo htmlspecialchars($product_keywords)?>">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label  class="form-label fw-bold">
                                    <i class="fas fa-folder"></i>Category
                                </label>
                                <select name="category_id" required class="form-select">
                                    <option value="<?php echo htmlspecialchars($category_id)?>"><?php echo htmlspecialchars($current_category_title)?></option>
                                    <?php
                                    $stmt_cat=$con->prepare("SELECT category_id, category_title FROM gift_categories WHERE category_id!=? ORDER BY category_title");
                                    $stmt_cat->bind_param("i",$category_id);
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
                                <select name="occasion_id" required class="form-select">
                                    <option value="<?php echo htmlspecialchars($occasion_id)?>"><?php echo htmlspecialchars($current_occasion_title)?></option>
                                    <?php
                                    $stmt_occ=$con->prepare("SELECT occasion_id , occasion_title FROM occasions WHERE occasion_id!=? ORDER BY occasion_title");
                                    $stmt_occ->bind_param("i",$occasion_id);
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
                            <input type="text" name="product_price" class="form-control" min="1" required value="<?php echo htmlspecialchars($product_price)?>">
                        </div>

                        </div>
                        <hr class="my-4">
                        <h5 class="mb-3"><i class="fas fa-images"></i>Product Image</h5>
                        <p class="text-muted mb-3">Leave Empty to keep existing image</p>
                        <div class="mb-3">
                            <label  class="form-label fw-bold">
                                <i class="fas fa-image"></i>Primary Image
                            </label>
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <input type="file" name="product_image1" class="form-control" accept="image/*">
                                </div>
                                <div class="col-md-4">
                                    <div class="image-preview">
                                        <img src="./product_images/<?php echo ($product_image1)?>" alt="">
                                    </div>
                                    <small class="text-muted d-block text-center mt-1">
                                        Current Image
                                    </small>
                                </div>
                            </div>
                            
                        </div>

                        <div class="mb-3">
                            <label  class="form-label fw-bold">
                                <i class="fas fa-image"></i>Image 2
                            </label>
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <input type="file" name="product_image2" class="form-control" accept="image/*">
                                </div>
                                <div class="col-md-4">
                                    <div class="image-preview">
                                        <img src="./product_images/<?php echo ($product_image2)?>" alt="">
                                    </div>
                                    <small class="text-muted d-block text-center mt-1">
                                        Current Image
                                    </small>
                                </div>
                            </div>
                            
                        </div>

                        <div class="mb-3">
                            <label  class="form-label fw-bold">
                                <i class="fas fa-image"></i>Image 3
                            </label>
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <input type="file" name="product_image3" class="form-control" accept="image/*">
                                </div>
                                <div class="col-md-4">
                                    <div class="image-preview">
                                        <img src="./product_images/<?php echo ($product_image3)?>" alt="">
                                    </div>
                                    <small class="text-muted d-block text-center mt-1">
                                        Current Image
                                    </small>
                                </div>
                            </div>
                            
                        </div>

                        <div class="text-center mt-4">
                            <button class="btn btn-primary px-5" type="submit" name="edit_product">
                                <i class="fas fa-save me-2"></i>Update Products
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



<?php
if(isset($_POST['edit_product'])){
    $product_title=$_POST['product_title'];
    $product_description=$_POST['product_description'];
    $product_keywords=$_POST['product_keywords'];
    $category_id=$_POST['category_id'];
    $occasion_id=$_POST['occasion_id'];
    $product_image1_new=$_FILES['product_image1']['name'];
    $product_image2_new=$_FILES['product_image2']['name'];
    $product_image3_new=$_FILES['product_image3']['name'];
    $product_price=$_POST['product_price'];

    $temp1=$_FILES['product_image1']['tmp_name'];
    $temp2=$_FILES['product_image2']['tmp_name'];
    $temp3=$_FILES['product_image3']['tmp_name'];

    // IMAGE 1
    if(empty($product_image1_new)){
        $product_image1_new = $product_image1;
    } else {
        move_uploaded_file($temp1, "./product_images/$product_image1_new");
    }

    // IMAGE 2
    if(empty($product_image2_new)){
        $product_image2_new = $product_image2;
    } else {
        move_uploaded_file($temp2, "./product_images/$product_image2_new");
    }

    // IMAGE 3
    if(empty($product_image3_new)){
        $product_image3_new = $product_image3;
    } else {
        move_uploaded_file($temp3, "./product_images/$product_image3_new");
    }

    $update=$con->prepare("UPDATE products SET product_title=?, product_description=?, product_keywords=?, occasion_id=?, category_id=?, product_image1=?, product_image2=?, product_image3=?, product_price=? WHERE product_id=?");

    $update->bind_param("sssiisssdi",$product_title, $product_description, $product_keywords, $occasion_id, $category_id, $product_image1_new, $product_image2_new, $product_image3_new, $product_price, $edit_id);
    $run=$update->execute();
    if($run){
        echo"<script>alert('Product **{$product_title}** Updated successfully')</script>";
        echo"<script>window.location.href='index.php?view_products'</script>";
    }else{
        echo"<script>alert('Error Updating Product')</script>";
    }
}

?>