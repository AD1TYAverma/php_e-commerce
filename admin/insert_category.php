<?php
include('../includes/connect.php');

if(isset($_POST['insert_gift_cat'])){

    $gift_category_title = $_POST['gift_category_title'] ?? '';

    if(empty($gift_category_title)){
        echo "<script>alert('Please enter category name')</script>";
    }else{

        // CHECK DUPLICATE
        $stmt_check = $con->prepare("SELECT * FROM gift_categories WHERE category_title=?");
        $stmt_check->bind_param("s", $gift_category_title);
        $stmt_check->execute(); // ✅ FIX
        $result_check = $stmt_check->get_result();

        if($result_check->num_rows > 0){
            echo "<script>alert('This Gift Category already exists')</script>";
        }else{

            // INSERT
            $stmt_insert = $con->prepare("INSERT INTO gift_categories (category_title) VALUES(?)");
            $stmt_insert->bind_param("s", $gift_category_title);
            $result = $stmt_insert->execute();

            if($result){
                echo "<script>alert('Category inserted successfully')</script>";
                echo "<script>window.location.href='index.php?view_category'</script>";
            }else{
                echo "<script>alert('Error Inserting Category')</script>";
            }

            $stmt_insert->close();
        }

        $stmt_check->close();
    }
}
?>

<div class="container mt-4">
    <h2 class="mb-4">
        <i class="fas fa-folder-plus" style="color:var(--secondary-color)"></i>Insert Gift Category
</h2>

<div class="row justify-content-center">
    <div class="col-lg-6 col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST">
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="fas fa-tag"></i>Category Name
                        </label>
                        <input type="text" class="form-control" name="gift_category_title" placeholder="e.g , gift hampers, etc..." required>
                        <small class="text-muted">Enter a unique category name</small>
                    </div>

                    <div class="text-center">
                        <button type="submit" name="insert_gift_cat" class="btn btn-primary">
                            <i class="fas fa-check-circle me-2"></i>Insert Category
                        </button>
                        <a href="index.php?view_category" class="btn btn-secondary px-4 ms-2">
                            <i class="fas fa-list me-2"></i>View All
                        </a>
                    </div>

                </form>
            </div>
        </div>

        <div class="card-box mt-4">
            <h6><i class="fas fa-info-circle me-2"></i>Quick Tips</h6>
            <ul>
                <li>Category name should be unique</li>
                <li>Use clear, descriptive names</li>
                <li>Example : Gift Hampers, Home Decor, Birthday Hampers</li>
            </ul>
        </div>

    </div>
</div>
</div>
