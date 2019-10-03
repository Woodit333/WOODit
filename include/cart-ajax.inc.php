<?php
header('Content-Type: application/json');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require 'functions.php';
require 'dbh.inc.php';
require 'classes.inc.php';

// CHECK REQUEST METHOD
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = filter_input_array(INPUT_POST);
} else {
    $input = filter_input_array(INPUT_GET);
}

$sourcePath = null;
$errors = array();
$image= null;
if (is_array($_FILES)&& !empty($_FILES) && $_FILES['image']['tmp_name'] != null) {
    $dirpath = realpath(dirname(getcwd()));
    $target_dir = "uploads/";
    $target_file = $dirpath . "/" . $target_dir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    //Check file
    if ($imageFileType == ''){
        array_push($errors, "image file is mandatory.");
    }
    else if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        array_push($errors, "Sorry, only JPG, JPEG PNG files are allowed.");
    }

    if (count($errors) == 0) {
        $image = getGUID() . '.' . $imageFileType;
        $target_file = $dirpath . "/" . $target_dir . $image;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    }
}

if (array_key_exists('action', $input) && $input['action'] === 'get') {
    $product_id = $input['productId'];
    $query = "SELECT * FROM products WHERE id=$product_id LIMIT 1";
    $results = mysqli_query($conn, $query);
    if (mysqli_num_rows($results) > 0) { // product exists
        $product = mysqli_fetch_assoc($results);
        $query = "SELECT * FROM products_images WHERE product=$product_id";
        $results = mysqli_query($conn, $query);
        $images = $results->fetch_all(MYSQLI_ASSOC);
        $product['images'] = $images;
        echo $json = json_encode($product);
    }
} else if ($image != null) {
    $product_id = $_POST['productId'];
    $cart = null;
    if (isset($_SESSION['cart'])) {
        $cart = unserialize($_SESSION['cart']);
    } else {
        $cart = new cart();
    }

    $query = "SELECT * FROM products WHERE id=$product_id LIMIT 1";
    $results = mysqli_query($conn, $query);
    if (mysqli_num_rows($results) > 0) { // product exists
        $product = mysqli_fetch_assoc($results);
        $discount_percentage = doubleval($product['discount_percentage']);
        $price = doubleval($product['price']);
        if ($discount_percentage > 0) {
            $price =  $price - ($price * ($discount_percentage / 100));
        }
        $cart->add_to_cart(getGUID(), $product_id, $image, $product['name'], $price);
        $_SESSION['cart'] = serialize($cart);
    }
} else if (array_key_exists('action', $input) && $input['action'] === 'remove') {
    $id = $input['id'];
    if (isset($_SESSION['cart'])) {
        $cart = unserialize($_SESSION['cart']);
        $items = $cart->cart_items;
        $cart->cart_items = array_remove_object($items, $id , 'id');
        $_SESSION['cart'] = serialize($cart);
        echo $json = json_encode($cart);
    }
}

?>