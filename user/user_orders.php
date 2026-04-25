<h3 class="text-custom-maroon mb-4 text-center">My Order History</h3>
<?php
$stmt = $con->prepare("SELECT * FROM user_orders WHERE user_id=? ORDER BY order_id DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$order_result=$stmt->get_result();
if(!$order_result){
    echo "<p class='alert alert-danger'>Error Fetching orders ".$con->error."</p>";
}
if($order_result->num_rows==0){
    echo "<div class='text-center mt-5'> 
    <h4 class='text-secondary'>You haven't palce any order yet.</h4>
    <p>Start exploring our unique collection of gifts</p>
    <a href='../display_all.php' class='btn btn-custom mt-3'><i class='fas fa-gift me-2'></i>Shop Now</a> 
</div>";
    exit();
}
?>


<div class="table-responsive mt-4">
    <table class="table table-bordered table-hover align-middle text-center">
        
        <thead class="bg-custom-maroon text-light">
            <tr>
                <th>S No.</th>
                <th>Order Id</th>
                <th class="d-none d-md-table-cell">Invoice No.</th>
                <th>Total Items</th>
                <th>Amount</th>
                <th class="d-none d-lg-table-cell">Date</th>
                <th>Status</th>
                <th class="d-none d-md-table-cell">Tracking</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            <?php
            $counter=1;
            while($row=$order_result->fetch_assoc()){
                $order_id=htmlspecialchars($row['order_id']);
                $order_status=strtolower($row['order_status']);
                $track_status=strtolower($row['track_status']);
                $status_class=match($order_status){
                    'pending'=>'bg-secondary',
                    'completed'=>'bg-success',
                    'cencelled'=>'bg-danger',
                    default=>'bg-info'
                };
                $track_class=match($track_status){
                    'processing', 'pending'=>'bg-warning text-dark',
                    'shipped'=>'bg-primary',
                    'out of delivery'=>'bg-info',
                    'deliverrd'=>'bg-success',
                    default=>'bg-secondary'
                };
                $can_delete=($track_status=='processing') || ($track_status=='pending');
                $tooltip_text=htmlspecialchars(ucfirst($track_status)."Orders cannot be delivered"); 
                    
            echo"<tr>
                <td>{$counter}</td>
                <td>".htmlspecialchars($order_id)."</td>
                <td class='d-none d-md-table-cell'>".htmlspecialchars($row['invoice_number'])."</td>
                <td>".htmlspecialchars($row['total_product'])."</td>
                <td><strong>".number_format($row['amount_due'],2)."</strong></td>
                <td class='d-none d-lg-table-cell'>".$row['order_date']."</td>
                <td>
                    <span class='badge {$status_class}'>".htmlspecialchars(ucfirst($order_status))."</span>
                </td>
                <td class='d-none d-md-table-cell'>
                    <span class='badge {$track_class}'>".htmlspecialchars(ucfirst($track_status))."</span>
                </td>
                <td>
                    <div class='d-flex flex-column flex-md-row gap-2 justify-content-center'>
                        <a href='view_order.php?order_id={$order_id}' class='btn btn-sm btn-info'>
                            <i class='fas fa-eye'></i>
                        </a>";
                        if($can_delete){

                        echo"<a href='delete_order.php?order_id={$order_id}' onclick='return confirm(\"Are you sure you want to delete this order.\")' class='btn btn-sm btn-danger'>
                            <i class='fas fa-trash-alt'></i>
                        </a>";
                        }else{
                            echo"<span data-bs-toggle='tooltip' data-bs-placement='top' title='{$tooltip_text}'><button class='btn btn-sm btn-secondary order-btn disabled' disabled><i class='fas fa-ban me-1'></i>Delete</button></span>";
                        }
                        echo"</div>
                </td>
            </tr>";
            $counter++;
            }
            ?>
        </tbody>

    </table>
</div>