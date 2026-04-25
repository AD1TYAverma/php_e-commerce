<?php
include('auth.php');
include('../includes/connect.php');

if(isset($_GET['delete_category'])){

    $delete_id = $_GET['delete_category'];

    // 🔒 Check if category used in products
    $check = $con->prepare("SELECT COUNT(*) AS total FROM products WHERE category_id=?");
    $check->bind_param("i", $delete_id);
    $check->execute();
    $res = $check->get_result();
    $row = $res->fetch_assoc();

    if($row['total'] > 0){
        echo "<script>alert('Cannot delete: Category is used in products')</script>";
        echo "<script>window.location.href='index.php?view_category'</script>";
        exit();
    }

    // ✅ Delete
    $delete = $con->prepare("DELETE FROM gift_categories WHERE category_id=?");
    $delete->bind_param("i", $delete_id);

    if($delete->execute()){
        echo "<script>alert('Category deleted successfully')</script>";
        echo "<script>window.location.href='index.php?view_category'</script>";
    }else{
        echo "<script>alert('Error deleting category')</script>";
    }
}
?>