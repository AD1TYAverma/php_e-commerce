<?php
include('../includes/connect.php');

if(isset($_GET['delete_occasion'])){

    $delete_id = $_GET['delete_occasion'];

    // 🔒 Check if used in products
    $check = $con->prepare("SELECT COUNT(*) AS total FROM products WHERE occasion_id=?");
    $check->bind_param("i", $delete_id);
    $check->execute();
    $res = $check->get_result();
    $row = $res->fetch_assoc();

    if($row['total'] > 0){
        echo "<script>alert('Cannot delete: Occasion is used in products')</script>";
        echo "<script>window.location.href='index.php?view_occasions'</script>";
        exit();
    }

    // ✅ Delete
    $delete = $con->prepare("DELETE FROM occasions WHERE occasion_id=?");
    $delete->bind_param("i", $delete_id);

    if($delete->execute()){
        echo "<script>alert('Occasion deleted successfully')</script>";
        echo "<script>window.location.href='index.php?view_occasion'</script>";
    }else{
        echo "<script>alert('Error deleting occasion')</script>";
    }
}
?>