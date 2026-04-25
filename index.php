<?php
session_start();
include('./includes/connect.php');
include('./functions/common_function.php');
cart();
include('./includes/header.php');
?>
<script>document.title = 'Giftos - Home'</script>

<!-- navbar start -->
<?php include('./includes/navbar.php') ?>
<!-- navbar end -->

<!-- home page Start -->
<div class="container my-4">
    <div class="hero-banner">Find The Perfect Gift For Every Occasion!</div>
</div>

<div class="container">
    <div class="row">
        <div class="col-lg-3 mb-4 sidebar">
            <h4>Occations</h4>
            <ul class="navbar-nav">
                <?php get_occasion(); ?>
            </ul>
            <h4 class="mt-4">Gift Categories</h4>
            <ul class="navbar-nav">
                <?php get_gift_categories(); ?>
            </ul>
        </div>
        <div class="col-lg-9">
            <div class="row g-4">
                <?php get_products();
                    get_unique_gift_categories();
                    get_unique_occasion();
                ?>
            </div>
        </div>
    </div>
</div>

<!-- home page end -->


<?php include('./includes/footer.php') ?>
<?php include('./includes/notification.php') ?>
<?php include('./includes/scripts_footer.php') ?>