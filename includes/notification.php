<?php

if(function_exists('cart')){
    cart();
}
if(isset($_SESSION['toast_message'])){
    $toast_message = htmlspecialchars($_SESSION['toast_message']);
    $toast_title = $_SESSION['toast_title']?? 'Notification';
    $toast_icon = $_SESSION['toast_icon']?? "fa-check-circle";

    unset($_SESSION['toast_message']);
    unset($_SESSION['toast_title']);
    unset($_SESSION['toast_icon']);


    echo "<script>
            document.addEventListener('DOMContentLoaded', 
            function(){
                showToast('{$toast_message}','{$toast_title}', '{$toast_icon}');    
            });
        </script>";
}
?>
<div class="toast-notification" id="toast">
    <i class="fas toast-icon" id="toast-icon"></i>
    <div class="toast-content">
        <div class="toast-title" id="toast-title">Notification</div>
        <div class="toast-message" id="toast-message"></div>
        <button class="toast-close" onclick="hideToast()">&times;</button>
    </div>
</div>