<?php
include('./includes/connect.php');
include('./functions/common_function.php');
include('./includes/header.php');
?>
<script>document.title = 'Giftos - Products Detail'</script>

<!-- navbar start -->
<?php include('./includes/navbar.php') ?>
<!-- navbar end -->


<div class="container my-4">
    <div class="hero-banner">Product Details</div>
</div>

<div class="container my-4">
    <div class="row">
        <?php viewDetails(); ?>
    </div>
</div>


<script>
    function changeImage(newSrc){
        const mainImage = document.querySelector('.product-main-image');
        if(mainImage){
            mainImage.src = newSrc;
        }
    }
</script>

<?php include('./includes/footer.php') ?>
<?php include('./includes/scripts_footer.php') ?>
