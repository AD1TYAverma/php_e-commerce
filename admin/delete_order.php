<?php
include('../includes/connect.php');

if(isset($_GET['delete_order'])){

    $order_id = $_GET['delete_order'];

    // CHECK ORDER
    $check_query = $con->prepare("SELECT track_status FROM user_orders WHERE order_id=? LIMIT 1");
    $check_query->bind_param("i", $order_id);
    $check_query->execute();
    $result = $check_query->get_result();

    if($result->num_rows == 1){

        $row = $result->fetch_assoc();
        $track_status = strtolower($row['track_status']); // ✅ FIX

        if($track_status == 'delivered'){

            // DELETE ITEMS FIRST
            $delete_items = $con->prepare("DELETE FROM order_items WHERE order_id=?");
            $delete_items->bind_param("i", $order_id);
            $delete_items->execute();

            // DELETE ORDER
            $delete_order = $con->prepare("DELETE FROM user_orders WHERE order_id=?");
            $delete_order->bind_param("i", $order_id);

            if($delete_order->execute()){
                echo "<script>
                    alert('Order Deleted Successfully');
                    window.location.href='index.php?list_orders';
                </script>";
                exit();
            } else {
                echo "<script>alert('Error Deleting Order');</script>";
            }

        } else {
            echo "<script>alert('Only Delivered Orders can be Deleted'); window.location.href='index.php?list_orders';</script>";
            exit();
        }

    } else {
        echo "<script>alert('Invalid Order ID'); window.location.href='index.php?list_orders';</script>";
        exit();
    }
}
?>