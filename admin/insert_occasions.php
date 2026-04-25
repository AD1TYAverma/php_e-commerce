<?php
include('../includes/connect.php');

if(isset($_POST['insert_occasion'])){

    $occasion_title = $_POST['occasion_title'] ?? '';

    if(empty($occasion_title)){
        echo "<script>alert('Please enter Occasion name')</script>";
    }else{

        // CHECK DUPLICATE
        $stmt_check = $con->prepare("SELECT * FROM occasions WHERE 	occasion_title=?");
        $stmt_check->bind_param("s", $occasion_title);
        $stmt_check->execute(); // ✅ FIX
        $result_check = $stmt_check->get_result();

        if($result_check->num_rows > 0){
            echo "<script>alert('This Occasion already exists')</script>";
        }else{

            // INSERT
            $stmt_insert = $con->prepare("INSERT INTO occasions (occasion_title) VALUES(?)");
            $stmt_insert->bind_param("s", $occasion_title);
            $result = $stmt_insert->execute();

            if($result){
                echo "<script>alert('Occasion inserted successfully')</script>";
                echo "<script>window.location.href='index.php?view_occasion'</script>";
            }else{
                echo "<script>alert('Error Inserting Occasion')</script>";
            }

            $stmt_insert->close();
        }

        $stmt_check->close();
    }
}
?>

<div class="container mt-4">
    <h2 class="mb-4">
        <i class="fas fa-folder-plus" style="color:var(--secondary-color)"></i>Insert Occasion
</h2>

<div class="row justify-content-center">
    <div class="col-lg-6 col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST">
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="fas fa-calender-alt"></i>Occasion Name
                        </label>
                        <input type="text" class="form-control" name="occasion_title" placeholder="e.g ,Birthday, Wedding etc..." required>
                        <small class="text-muted">Enter a unique occasion name</small>
                    </div>

                    <div class="text-center">
                        <button type="submit" name="insert_occasion" class="btn btn-primary">
                            <i class="fas fa-check-circle me-2"></i>Insert Category
                        </button>
                        <a href="index.php?view_occasion" class="btn btn-secondary px-4 ms-2">
                            <i class="fas fa-list me-2"></i>View All
                        </a>
                    </div>

                </form>
            </div>
        </div>

        <div class="card-box mt-4">
            <h6><i class="fas fa-info-circle me-2"></i>Quick Tips</h6>
            <ul>
                <li>Occasion name should be unique</li>
                <li>Use clear, descriptive names</li>
                <li>Example : Birthday, Wedding</li>
            </ul>
        </div>

    </div>
</div>
</div>
