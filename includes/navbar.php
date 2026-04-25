<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
$project_folder = 'php_project/e-commerce';

include_once('connect.php');

$common_function_path = $_SERVER['DOCUMENT_ROOT'].'/'.$project_folder.'/functions/common_function.php';
if(file_exists($common_function_path)){

    include_once($common_function_path);
}else{
    error_log("CRITICAL ERROR: common_function.php not found at".$common_function_path);
}
if(!isset($base_url)){
$base_url = "/php_project/e-commerce/";
}
function get_absolute_link($path, $base_url){
  $clean_path = ltrim($path, './');
  return rtrim($base_url, '/').'/'.$clean_path;  
  
}
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top px-4">
    <div class="container-fluid">
        <a class="navbar-brand logo-link" href="<?php echo get_absolute_link('index.php', $base_url)?>">
            <img src="<?php echo get_absolute_link('./images/favicon.png', $base_url); ?>" alt="" class="d-inline-block align-text-top glram-top img-fluid" style="height: 30px;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link"  href="<?php echo get_absolute_link('index.php', $base_url)?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo get_absolute_link('display_all.php', $base_url)?>">Products</a>
                </li>
                <?php
                    if(isset($_SESSION['username'])){
                        echo "<li class='nav-item'>
                                <a class='nav-link' href='".get_absolute_link('user/profile.php', $base_url)."'>My Account</a>
                            </li>";
                    }else{
                        echo "<li class='nav-item'>
                                <a class='nav-link' href='".get_absolute_link('user/user_registration.php', $base_url)."'>Register</a>
                            </li>";
                    }
                ?>
                <?php
                    if(!isset($_SESSION['username'])){
                        echo "<li class='nav-item'>
                                <a class='nav-link' href='".get_absolute_link('user/user_login.php', $base_url)."'>Login</a>
                            </li>";
                    }else{
                        echo "<li class='nav-item'>
                            <a class='nav-link' 
                            href='".get_absolute_link('user/logout.php', $base_url)."' 
                            onclick=\"return confirm('Are You Sure?')\">
                            Logout
                            </a>
                        </li>";
                    }
                ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo get_absolute_link('card.php', $base_url)?>"><i class="fa-solid fa-cart-shopping"></i>
                        <sup><?php cart_item();?></sup>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Total: &#8377;<?php total_price(); ?></a>
                </li>
            </ul>
            <form class="d-flex" role="search" action="<?php echo get_absolute_link('search_product.php', $base_url)?>" method="GET">
                <input class="form-control me-2" type="search" placeholder="Search Products..." style="min-width: 200px;" name="search_data"/>
                <button class="btn btn-custom" type="submit" name="search_data_product">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>
</nav>