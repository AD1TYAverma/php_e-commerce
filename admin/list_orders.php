<div class="container-fluid mt-4">

    <h2 class="mb-4">
        <i class="fa-solid fa-cart-shopping me-2"></i>All Orders
    </h2>

<?php
include('../includes/connect.php');

$stmt = $con->prepare("SELECT * FROM user_orders ORDER BY order_id DESC");
$stmt->execute();
$result = $stmt->get_result();

$row_count = $result->num_rows;
$total_revenue = 0;
$completed_count = 0;

if($row_count == 0){
    echo"<div class='empty-state text-center py-5'>
    <i class='fa-solid fa-cart-shopping fa-3x mb-3'></i>
    <h4>No Orders Found</h4>
    <p>Orders will appear when customers start purchasing</p>
</div>";
?>



<?php } else { ?>

<!-- ✅ TABLE RESPONSIVE -->
<div class="table-responsive">
<table class="table table-hover align-middle text-nowrap">

<thead>
<tr>
    <th>S No</th>
    <th>Invoice</th>
    <th>Amount</th>
    <th>Products</th>
    <th>Date</th>
    <th>Payment</th>
    <th>Status</th>
    <th class="text-center">Action</th>
</tr>
</thead>

<tbody>

<?php
$number = 0;

while($row = $result->fetch_assoc()){

    $order_id = $row['order_id'];
    $invoice = $row['invoice_number'];
    $amount = $row['amount_due'];
    $products = $row['total_product'];
    $date = date('d M Y, h:i A', strtotime($row['order_date']));
    $payment = strtolower($row['order_status']);
    $track = strtolower($row['track_status']);

    $number++;

    // revenue count
    if($payment == 'completed' || $payment == 'paid'){
        $total_revenue += $amount;
        $completed_count++;
    }

    // payment badge
    $payment_class = match($payment){
        'completed','paid' => 'bg-success',
        'pending' => 'bg-warning text-dark',
        'failed','cancelled' => 'bg-danger',
        default => 'bg-secondary'
    };

    // track badge
    $track_class = match($track){
        'delivered' => 'bg-success',
        'shipped','pending' => 'bg-warning text-dark',
        'processing','cancelled' => 'bg-danger',
        default => 'bg-info'
    };
?>

<tr>
    <td><?= $number ?></td>
    <td><?= $invoice ?></td>
    <td>₹<?= number_format($amount,2) ?></td>
    <td><?= $products ?> items</td>
    <td><small><?= $date ?></small></td>

    <td>
        <span class="badge <?= $payment_class ?> px-2 py-1">
            <?= ucfirst($payment) ?>
        </span>
    </td>

    <td>
        <span class="badge <?= $track_class ?> px-2 py-1">
            <?= ucfirst($track) ?>
        </span>
    </td>

    <td class="text-center">
        <div class="btn-group btn-group-sm">
            <a href="index.php?view_order_detail=<?= $order_id ?>" 
               class="btn btn-info">
               <i class="fa-solid fa-eye"></i>
            </a>

            <a href="index.php?delete_order=<?= $order_id ?>" 
               class="btn btn-danger"
               onclick="return confirm('Are you sure?\n\n Invoice Number :<?= $invoice ?> Amount :₹<?= number_format($amount,2) ?>')">
               <i class="fa-solid fa-trash"></i>
            </a>
        </div>
    </td>
</tr>

<?php } ?>

</tbody>
</table>
</div>

<!-- ✅ CARDS RESPONSIVE -->
<div class="row mt-4 g-3">

    <div class="col-12 col-sm-6 col-md-4">
        <div class="card-box text-center p-3 shadow-sm">
            <h6>Total Orders</h6>
            <h3><?= $row_count ?></h3>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-4">
        <div class="card-box text-center p-3 shadow-sm">
            <h6>Total Revenue</h6>
            <h3>₹<?= number_format($total_revenue,2) ?></h3>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-4">
        <div class="card-box text-center p-3 shadow-sm">
            <h6>Completed Orders</h6>
            <h3><?= $completed_count ?></h3>
        </div>
    </div>

</div>

<?php } ?>

</div>