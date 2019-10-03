<?php
if (session_status() == PHP_SESSION_NONE) {
        session_start();
}
unset($_SESSION['user']);
unset($_SESSION['cart']);
unset($_SESSION['errors']);
unset($_SESSION['register']);
unset($_SESSION['is-add']);
unset($_SESSION['is-edit']);
unset($_SESSION['cart_summary']);
unset($_SESSION["paypal_products"]);

session_destroy();
header('Location: index.php');
exit();
?>