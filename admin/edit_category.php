<?php
include('../includes/connect.php');
if(isset($_GET['edit_gift_category'])){
    $edit_id=$_GET['edit_gift_category'];
    $stmt=$con->prepare("SELECT * FROM gift_categories WHERE category_id =?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result=$stmt->get_result();
    $row=$result->fetch_assoc();
    if(!$row){
        echo "<script>alert('Category not found')</script>";
        echo "<script>window.location.href='index.php?view_category'</script>";
    }
    $category_title=$row['category_title'];
}

?>


<div class="container mt-4">
    <h2 class="mb-4">
        <i class="fas fa-edit me-2"></i>Edit Gift Category
    </h2>

    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4">

                    <form method="POST">

                        <!-- CURRENT CATEGORY -->
                        <div class="alert d-flex align-items-center mb-4"
                             style="background-color: var(--light-bg); border-left:4px solid var(--primary-color); border-radius:8px;">
                            
                            <i class="fas fa-info-circle me-2 text-primary"></i>
                            <div>
                                <strong>Currently Editing:</strong>
                                <span class="ms-1 text-dark fw-semibold"><?php echo htmlspecialchars($category_title)?></span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-tag me-1"></i> Category Title
                            </label>

                            <input type="text" name="cat_title" class="form-control" required value="<?php echo htmlspecialchars($category_title)?>">

                            <small class="text-muted">Update the category name</small>
                        </div>

                        <div class="d-flex justify-content-center gap-3 mt-4">
                            <button class="btn btn-primary px-4 py-2" type="submit" name="update_cat">
                                <i class="fas fa-save me-2"></i>Update Category
                            </button>

                            <a href="index.php?view_category" class="btn btn-secondary px-4 py-2">
                                <i class="fas fa-times-circle me-2"></i>Cancel
                            </a>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<?php
if(isset($_POST['update_cat'])){

    $update_category = trim($_POST['cat_title']); // ✅ FIX

    // EMPTY CHECK
    if(empty($update_category)){
        echo "<script>alert('Category name cannot be empty')</script>";
        exit();
    }

    // DUPLICATE CHECK
    $check_stmt = $con->prepare("SELECT * FROM gift_categories WHERE category_title=? AND category_id!=?");
    $check_stmt->bind_param("si", $update_category, $edit_id);
    $check_stmt->execute();
    $check_run = $check_stmt->get_result();

    if($check_run->num_rows > 0){
        echo "<script>alert('Category already exists')</script>";
    }else{

        // UPDATE QUERY
        $update_stmt = $con->prepare("UPDATE gift_categories SET category_title=? WHERE category_id=?");
        $update_stmt->bind_param("si", $update_category, $edit_id);

        if($update_stmt->execute()){
            echo "<script>alert('Category updated successfully')</script>";
            echo "<script>window.location.href='index.php?view_category'</script>";
        }else{
            echo "<script>alert('Error updating category')</script>";
        }
    }
}
?>