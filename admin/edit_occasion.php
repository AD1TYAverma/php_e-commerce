<?php
include('../includes/connect.php');

// ✅ GET EXISTING DATA
if(isset($_GET['edit_occasion'])){
    $edit_id = $_GET['edit_occasion'];

    $stmt = $con->prepare("SELECT * FROM occasions WHERE occasion_id=?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if(!$row){
        echo "<script>alert('Occasion not found')</script>";
        echo "<script>window.location.href='index.php?view_occasions'</script>";
        exit();
    }

    $occasion_title = $row['occasion_title'];
}else{
    header("Location: index.php?view_occasions");
    exit();
}
?>

<div class="container mt-4">
    <h2 class="mb-4">
        <i class="fas fa-edit me-2"></i>Edit Occasion
    </h2>

    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4">

                    <form method="POST">

                        <!-- CURRENT OCCASION -->
                        <div class="alert d-flex align-items-center mb-4"
                             style="background-color: var(--light-bg); border-left:4px solid var(--primary-color); border-radius:8px;">
                            
                            <i class="fas fa-info-circle me-2 text-primary"></i>
                            <div>
                                <strong>Currently Editing:</strong>
                                <span class="ms-1 text-dark fw-semibold">
                                    <?php echo htmlspecialchars($occasion_title); ?>
                                </span>
                            </div>
                        </div>

                        <!-- INPUT -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-calendar-alt me-1"></i> Occasion Title
                            </label>

                            <input type="text" name="occasion_title"
                                   class="form-control"
                                   value="<?php echo htmlspecialchars($occasion_title); ?>"
                                   required>

                            <small class="text-muted">Update the occasion name</small>
                        </div>

                        <!-- BUTTONS -->
                        <div class="d-flex justify-content-center gap-3 mt-4">
                            <button class="btn btn-primary px-4 py-2" type="submit" name="update_occasion">
                                <i class="fas fa-save me-2"></i>Update Occasion
                            </button>

                            <a href="index.php?view_occasions" class="btn btn-secondary px-4 py-2">
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
// ✅ UPDATE LOGIC
if(isset($_POST['update_occasion'])){

    $new_title = trim($_POST['occasion_title']);

    // EMPTY CHECK
    if(empty($new_title)){
        echo "<script>alert('Occasion name cannot be empty')</script>";
        exit();
    }

    // DUPLICATE CHECK
    $check = $con->prepare("SELECT * FROM occasions WHERE occasion_title=? AND occasion_id!=?");
    $check->bind_param("si", $new_title, $edit_id);
    $check->execute();
    $res = $check->get_result();

    if($res->num_rows > 0){
        echo "<script>alert('Occasion already exists')</script>";
    }else{

        // UPDATE QUERY
        $update = $con->prepare("UPDATE occasions SET occasion_title=? WHERE occasion_id=?");
        $update->bind_param("si", $new_title, $edit_id);

        if($update->execute()){
            echo "<script>alert('Occasion updated successfully')</script>";
            echo "<script>window.location.href='index.php?view_occasion'</script>";
        }else{
            echo "<script>alert('Error updating occasion')</script>";
        }
    }
}
?>