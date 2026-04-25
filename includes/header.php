<?php
if(!isset($page_title)){
    $page_title = 'Giftos';
}
$path_prefix = "";
if(strpos($_SERVER['PHP_SELF'], 'user')!==false){
    $path_prefix ="../";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars( $page_title )?></title>
    <link rel="icon" href="./images/favicon.png">
    
    <link rel="stylesheet" href="<?php echo $path_prefix; ?>style/style.css">
    <link rel="stylesheet" href="<?php echo $path_prefix; ?>style/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <?php
      if(strpos($_SERVER['PHP_SELF'], 'user')!==false): 
    ?>
    <link rel="stylesheet" href="<?php echo $path_prefix ?>user/user_style.css">
    <?php endif; ?>

</head>
<body>