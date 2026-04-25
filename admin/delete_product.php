<?php
include('../includes/connect.php');

if(isset($_GET['delete_product'])){
    $delete_id = $_GET['delete_product'];
    $stmt_check=$con->prepare("SELECT oi.order_id, u.track_status FROM order_items oi JOIN user_orders u ON  oi.order_id=u.order_id WHERE oi.product_id=?");  
    $stmt_check->bind_param("i", $delete_id);
    $stmt_check->execute();
    $result_check=$stmt_check->get_result();

    if($result_check->num_rows>0){
        $stmt_delete=$con->prepare("DELETE FROM products WHERE product_id=?");
        $stmt_delete->bind_param("i", $delete_id);
        $stmt_delete->execute();

        echo"<script>alert('Product Deteted successfully')</script>";
        echo"<script>window.location.href='index.php?view_products'</script>";
        exit();
    }

    $can_delete=true;
    while($row=$result_check->fetch_assoc()){
        if(strtolower($row['track_status']!='delivered')){
            $can_delete=false;
            break;
        }
    }
    if(!$can_delete){
        echo"<script>alert('This Product cannot Be Deleted. Some orders are not devilered')</script>";
        echo"<script>window.location.href='index.php?view_products'</script>";
        exit();
    }
    $stmt_delete=$con->prepare("DELETE FROM products WHERE product_id=?");
        $stmt_delete->bind_param("i", $delete_id);
        $stmt_delete->execute();

        echo"<script>alert('Product Deteted successfully')</script>";
        echo"<script>window.location.href='index.php?view_products'</script>";
        exit();
    
}

?>