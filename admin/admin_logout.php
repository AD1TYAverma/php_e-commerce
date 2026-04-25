<!-- <?php
session_start();
session_destroy();
header("Location: admin_login.php");

?> -->

<?php
session_start();

// 🔥 destroy session completely
$_SESSION = [];
session_unset();
session_destroy();

// 🔒 cache clear (important)
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// ✅ redirect to login
header("Location: admin.login.php");
exit();
?>