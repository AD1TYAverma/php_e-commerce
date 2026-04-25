<?php
session_start();
include('../includes/connect.php');

// 🔐 LOGIN CHECK
if (!isset($_SESSION['admin_username'])) {
    header("Location: admin.login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Giftos</title>

    <link rel="icon" href="../images/favicon.png">
    <link rel="stylesheet" href="./admin_style.css">
    <link rel="stylesheet" href="../style/bootstrap.min.css">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
</head>

<body>

<!-- ================= SIDEBAR ================= -->
<div class="sidebar" id="adminSidebar">
    <img src="aditya.jpg" class="admin-pic mb-3">

    <a href="index.php"><i class="fa-solid fa-house"></i> Dashboard</a>
    <a href="index.php?insert_product"><i class="fa-solid fa-plus"></i> Insert Product</a>
    <a href="index.php?view_product"><i class="fa-solid fa-box"></i> View Product</a>

    <a href="index.php?insert_category"><i class="fa-solid fa-folder-plus"></i> Insert Category</a>
    <a href="index.php?view_category"><i class="fa-solid fa-list"></i> View Category</a>

    <a href="index.php?insert_occasions"><i class="fa-solid fa-gifts"></i> Insert Occasion</a>
    <a href="index.php?view_occasion"><i class="fa-solid fa-gift"></i> View Occasion</a>

    <a href="index.php?list_orders"><i class="fa-solid fa-truck"></i> Orders</a>
    <a href="index.php?list_users"><i class="fa-solid fa-users"></i> Users</a>

    <a href="admin_logout.php" style="background:#cc0000;">
        <i class="fa-solid fa-right-from-bracket"></i> Logout
    </a>
</div>

<!-- ================= TOP NAV ================= -->
<div class="top-nav">
    <i class="fas fa-bars sidebar-toggle" id="sidebarToggleBtn"></i>
    <h4 class="admin-name">
        Welcome <?= htmlspecialchars($_SESSION['admin_username']); ?>
    </h4>
</div>

<!-- ================= MAIN CONTENT ================= -->
<div class="main">

<?php
// 👉 SHOW DASHBOARD ONLY WHEN NO PAGE SELECTED
if (
    !isset($_GET['insert_product']) &&
    !isset($_GET['view_product']) &&
    !isset($_GET['edit_product']) &&
    !isset($_GET['delete_product']) &&
    !isset($_GET['insert_category']) &&
    !isset($_GET['view_category']) &&
    !isset($_GET['edit_gift_category']) &&
    !isset($_GET['delete_category']) &&
    !isset($_GET['insert_occasions']) &&
    !isset($_GET['view_occasion']) &&
    !isset($_GET['edit_occasion']) &&
    !isset($_GET['delete_occasion']) &&
    !isset($_GET['list_orders']) &&
    !isset($_GET['view_order_detail']) &&
    !isset($_GET['delete_order']) &&
    !isset($_GET['list_users']) &&
    !isset($_GET['view_user']) &&
    !isset($_GET['delete_user'])
) {

    // 📦 TOTAL PRODUCTS
    $res = $con->query("SELECT COUNT(*) AS total FROM products");
    $total_products = $res->fetch_assoc()['total'];

    // 👥 TOTAL USERS
    $res = $con->query("SELECT COUNT(*) AS total FROM user_table");
    $total_users = $res->fetch_assoc()['total'];

    // 🛒 TOTAL ORDERS
    $res = $con->query("SELECT COUNT(*) AS total FROM user_orders");
    $total_orders = $res->fetch_assoc()['total'];

    // 💰 TOTAL REVENUE
    $res = $con->query("SELECT SUM(amount_due) AS total FROM user_orders WHERE order_status='completed'");
    $total_revenue = $res->fetch_assoc()['total'] ?? 0;

    // 📊 CHART DATA (Last 6 Months)
    $month_labels = [];
    $month_values = [];

    $stmt = $con->query("
        SELECT DATE_FORMAT(order_date, '%b') AS month,
        SUM(amount_due) AS total
        FROM user_orders
        WHERE order_status='completed'
        GROUP BY MONTH(order_date)
        ORDER BY order_date DESC
        LIMIT 6
    ");

    while ($row = $stmt->fetch_assoc()) {
        $month_labels[] = $row['month'];
        $month_values[] = $row['total'];
    }

    $month_labels = array_reverse($month_labels);
    $month_values = array_reverse($month_values);
?>

<!-- ================= DASHBOARD ================= -->
<h3 class="mb-4"><i class="fas fa-chart-line"></i> Dashboard Overview</h3>

<div class="row g-4">

    <div class="col-md-3">
        <div class="card-box">
            <h6><i class="fas fa-box"></i> Total Products</h6>
            <h2 class="text-primary"><?= $total_products ?></h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-box">
            <h6><i class="fas fa-users"></i> Total Users</h6>
            <h2 class="text-success"><?= $total_users ?></h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-box">
            <h6><i class="fas fa-shopping-cart"></i> Total Orders</h6>
            <h2 class="text-warning"><?= $total_orders ?></h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-box">
            <h6><i class="fas fa-rupee-sign"></i> Total Revenue</h6>
            <h2 class="text-danger">₹<?= number_format($total_revenue, 2) ?></h2>
        </div>
    </div>

</div>

<hr class="my-4">

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card-box">
            <h5 class="text-danger">Users vs Orders</h5>
            <canvas id="pieChart"></canvas>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card-box">
            <h5>Monthly Revenue</h5>
            <canvas id="barChart"></canvas>
        </div>
    </div>
</div>

<?php } ?>

<!-- ================= ROUTING ================= -->
<?php
if(isset($_GET['insert_product'])) include('insert_product.php');
if(isset($_GET['view_product'])) include('view_product.php');
if(isset($_GET['edit_product'])) include('edit_product.php');
if(isset($_GET['delete_product'])) include('delete_product.php');

if(isset($_GET['insert_category'])) include('insert_category.php');
if(isset($_GET['view_category'])) include('view_category.php');
if(isset($_GET['edit_gift_category'])) include('edit_category.php');
if(isset($_GET['delete_category'])) include('delete_category.php');

if(isset($_GET['insert_occasions'])) include('insert_occasions.php');
if(isset($_GET['view_occasion'])) include('view_occasion.php');
if(isset($_GET['edit_occasion'])) include('edit_occasion.php');
if(isset($_GET['delete_occasion'])) include('delete_occasion.php');

if(isset($_GET['list_orders'])) include('list_orders.php');
if(isset($_GET['view_order_detail'])) include('view_order_detail.php');
if(isset($_GET['delete_order'])) include('delete_order.php');

if(isset($_GET['list_users'])) include('list_users.php');
if(isset($_GET['view_user'])) include('view_user.php');
if(isset($_GET['delete_user'])) include('delete_user.php');
?>

</div>

<!-- ================= SCRIPTS ================= -->
<script src="../js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Sidebar Toggle
document.getElementById("sidebarToggleBtn").onclick = function () {
    document.getElementById("adminSidebar").classList.toggle("active");
};

// Pie Chart
new Chart(document.getElementById('pieChart'), {
    type: 'pie',
    data: {
        labels: ['Users', 'Orders'],
        datasets: [{
            data: [<?= $total_users ?>, <?= $total_orders ?>],
            backgroundColor: ['#581845', '#FFD700']
        }]
    }
});

// Bar Chart
new Chart(document.getElementById('barChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($month_labels) ?>,
        datasets: [{
            label: 'Revenue',
            data: <?= json_encode($month_values) ?>,
            backgroundColor: '#581845'
        }]
    }
});
</script>

</body>
</html>