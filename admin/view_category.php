<div class="container mt-4">
    <h2 class="mb-4">
        <i class="fas fa-list me-2"></i>All Gift Category
    </h2>

    <div class="table-reponsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>S No.</th>
                    <th>Category Name</th>
                    <th>Total Products</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt=$con->prepare("SELECT * FROM gift_categories ORDER BY category_id DESC");
                $stmt->execute();
                $result=$stmt->get_result();
                $number=0;
                if($result->num_rows>0){
                    while($row=$result->fetch_assoc()){
                        $category_id=$row['category_id'];
                        $category_title=$row['category_title'];
                        $number++;
                        $count_stmt=$con->prepare("SELECT COUNT(*) AS total FROM products WHERE category_id=?");
                        $count_stmt->bind_param("i", $category_id);
                        $count_stmt->execute();
                        $count_result=$count_stmt->get_result();
                        $count_row=$count_result->fetch_assoc();
                        $product_count=$count_row['total'];
                ?>
                <tr>
                    <td><strong><?php echo $number?></strong></td>
                    <td><strong><?php echo htmlspecialchars($category_title)?></strong></td>
                    <td><span class="badge bg-info"><?php echo $product_count?> Product</span></td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="index.php?edit_gift_category=<?php echo $category_id?>" class="btn btn-info btn-sm" title="Edit Category"><i class="fa -solid fa-pen-to-square"></i></a>
                             <a href="index.php?delete_category=<?php echo $category_id?>" class="btn btn-danger btn-sm" title="Delete Category" onclick="return confirm('Are You Sure')"><i class="fa -solid fa-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php
                    
                    }
                }else{
                    echo"<tr>
                    <td colspan='4' class='text-center'>
                            <div class='empty-state'>
                                <i class='fas fa-folder-open'></i>
                                <h4>No Categories Found</h4>
                                <p>Start by adding your first Categories</p>
                                <a href='index.php?insert_category' class='btn btn-primary'><i class='fas fa-plus-circle'></i>Add Categories</a>
                            </div>
                        </td>
                </tr>";
                }
                ?>
                
            </tbody>
        </table>
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
                        <a href="index.php?insert_category" class="btn btn-primary">
                            <i class="fas fa-plus-circle"></i>Add New Category
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>