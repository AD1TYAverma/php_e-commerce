<div class="container-fluid mt-4">
    <h2 class="mb-4">
        <i class="fas fa-box-open"></i>All Products
    </h2>
    <div class="table-scroll-wrapper">
        <div class="table-responsive">
            <table class="table table-hover product-table">
                <thead>
                    <tr>
                        <th>S No.</th>
                        <th>Product Title</th>
                        <th>Image</th>
                        <th>Price</th>
                        <th>Total Sold</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include('../includes/connect.php');
                    $stmt_product=$con->prepare("SELECT * FROM products ORDER BY product_id
                     DESC");
                     $stmt_product->execute();
                     $result_product=$stmt_product->get_result();
                     $number=0;
                     if($result_product->num_rows>0){
                        $stmt_count=$con->prepare("SELECT SUM(quantity) AS total_sold FROM order_items WHERE product_id=?");
                        while($row=$result_product->fetch_assoc()){
                            $product_id=$row['product_id'];
                            $product_title=$row['product_title'];
                            $product_image1=$row['product_image1'];
                            $product_price=$row['product_price'];
                            $number++;

                            $stmt_count->bind_param("i", $product_id);
                            $stmt_count->execute();
                            $result_count=$stmt_count->get_result();
                            $row_count=$result_count->fetch_assoc();
                            $total_sold=$row_count['total_sold']??0;
                        
                    ?>
                    <tr>
                        <td><strong><?php echo $number?></strong></td>
                        <td><strong><?php echo $product_title?></strong></td>
                        <td>
                            <img src="./product_images/<?php echo $product_image1?>" class="product_img" alt="">
                        </td>
                        <td><span><?php echo $product_price?></span></td>

                        <td><span class="badge bg-info"><?php echo $total_sold?> sold</span></td>
                        <td>
                            <?php if($total_sold>0) :?>
                            <span class="badge badge-success">
                                <i class="fas fa-check-circle"></i>Active
                            </span>
                            <?php else :?>
                            <span class="badge badge-warning">
                                <i class="fas fa-clock"></i>New
                            </span>
                            <?php endif ?>
                        </td>
                            
                        <td>
                            <div class="btn-group">
                                <a href="index.php?edit_product=<?php echo $product_id ?>" class="btn btn-info btn-sm" title="Edit Product"><i class="fa-solid fa-pen-to-square"></i></a>

                                <a href="index.php?delete_product=<?php echo $product_id ?>" class="btn btn-danger btn-sm" title="Delete Product" onclick="return confirm('Are You Sure')"><i class="fa-solid fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                <?php }
                     }else{
                        echo"<tr>
                        <td colspan='7' class='text-center'>
                            <div class='empty-state'>
                                <i class='fas fa-box-open'></i>
                                <h4>No Product Found</h4>
                                <p>Start by adding your first product</p>
                                <a href='index.php?insert_product' class='btn btn-primary'><i class='fas fa-plus-circle'></i>Add Products</a>
                            </div>
                        </td>
                    </tr>";
                     }
                      ?>


                </tbody>
            </table>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card-box">
                <div class="d-flex justify-content-between align-itens center">
                    <div>
                        <h6>Total Products</h6>
                        <h3><?php echo $number?></h3>
                    </div>
                    <div>
                        <a href="index.php?insert_product" class="btn btn-primary">
                            <i class="fas fa-plus-circle"></i>Add Product
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>