<?php
include('../includes/connect.php');
?>

<div class="container mt-4">
    <h2 class="mb-4">
        <i class="fas fa-gifts me-2"></i>All Occasion
    </h2>

    <!-- FIXED CLASS -->
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>S No.</th>
                    <th>Occasion Name</th>
                    <th>Total Products</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

                <?php
                $stmt = $con->prepare("SELECT * FROM occasions ORDER BY occasion_id DESC");
                $stmt->execute();
                $result = $stmt->get_result();
                $number = 0;

                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){

                        // ✅ FIXED (no space)
                        $occasion_id = $row['occasion_id'];
                        $occasion_title = $row['occasion_title'];
                        $number++;

                        // COUNT PRODUCTS
                        $count_stmt = $con->prepare("SELECT COUNT(*) AS total FROM products WHERE occasion_id=?");
                        $count_stmt->bind_param("i", $occasion_id);
                        $count_stmt->execute();
                        $count_result = $count_stmt->get_result();
                        $count_row = $count_result->fetch_assoc();
                        $product_count = $count_row['total'];
                ?>

                <tr>
                    <td><strong><?php echo $number ?></strong></td>

                    <!-- ✅ DYNAMIC TITLE -->
                    <td><strong><?php echo $occasion_title ?></strong></td>

                    <!-- ✅ DYNAMIC COUNT -->
                    <td>
                        <span class="badge bg-info">
                            <?php echo $product_count ?> Product<?php echo $product_count > 1 ? 's' : '' ?>
                        </span>
                    </td>

                    <!-- ✅ FIXED ACTION BUTTONS -->
                    <td>
                        <div class="btn-group" role="group">
                            <a href="index.php?edit_occasion=<?php echo $occasion_id ?>" 
                               class="btn btn-info btn-sm" title="Edit Occasion">
                               <i class="fa-solid fa-pen-to-square"></i>
                            </a>

                            <a href="index.php?delete_occasion=<?php echo $occasion_id ?>" 
                               class="btn btn-danger btn-sm" 
                               onclick="return confirm('Are you sure you want to delete this?')"
                               title="Delete Occasion">
                               <i class="fa-solid fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>

                <?php
                        $count_stmt->close();
                    }
                } else {
                    echo "
                    <tr>
                        <td colspan='4' class='text-center'>
                            <div class='empty-state'>
                                <i class='fas fa-folder-open'></i>
                                <h4>No Occasion Found</h4>
                                <p>Start by adding your first Occasion</p>
                                <a href='index.php?insert_occasion' class='btn btn-primary'>
                                    <i class='fas fa-plus-circle'></i> Add Occasion
                                </a>
                            </div>
                        </td>
                    </tr>";
                }

                $stmt->close();
                ?>

            </tbody>
        </table>
    </div>

    <?php
    // TOTAL OCCASIONS COUNT
    $total_stmt = $con->prepare("SELECT COUNT(*) AS total FROM occasions");
    $total_stmt->execute();
    $total_res = $total_stmt->get_result();
    $total_row = $total_res->fetch_assoc();
    $total_occasions = $total_row['total'];
    ?>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card-box">
                
                <!-- ✅ FIXED ALIGN -->
                <div class="d-flex justify-content-between align-items-center">
                    
                    <div>
                        <h6>Total Occasions</h6>
                        <h3><?php echo $total_occasions ?></h3>
                    </div>

                    <div>
                        <!-- ✅ FIXED LINK -->
                        <a href="index.php?insert_occasion" class="btn btn-primary">
                            <i class="fas fa-plus-circle"></i> Add New Occasion
                        </a>
                    </div>

                </div>

            </div>
        </div>
    </div>

</div>