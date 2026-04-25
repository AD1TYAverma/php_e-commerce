<?php
$con = mysqli_connect('localhost', 'root', '', 'e-commerce_php');

if(!$con){
   die("Connection Failed: " . mysqli_connect_error($con));
}
?>