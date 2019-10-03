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

$errors = array();
if (array_key_exists('action', $input) && $input['action'] === 'get') {
    $order_id = $input['orderId'];
    $query = "SELECT * FROM orders WHERE id=$order_id LIMIT 1";
    $results = mysqli_query($conn, $query);
    if (mysqli_num_rows($results) == 0) { // order exists
        return;
    }
    
    $order = mysqli_fetch_assoc($results);
    
    $user_id = $order['user'];
    $query = "SELECT * FROM users WHERE id=$user_id LIMIT 1";
    $results = mysqli_query($conn, $query);
    if (mysqli_num_rows($results) == 0) { // order exists
        return;
    }
    
    $user = mysqli_fetch_assoc($results);
    $order['user'] = $user;
    
    $query = "SELECT p.id, p.name, op.price, op.image FROM order_products op INNER JOIN products p ON op.product_id = p.id WHERE op.order_id=$order_id";
    $results = mysqli_query($conn, $query);
    $order_products = $results->fetch_all(MYSQLI_ASSOC);
    $order['orderProducts'] = $order_products;
    echo $json = json_encode($order);
} elseif (array_key_exists('action', $input) && $input['action'] === 'set') {
    $order_id = $input['orderId'];
    $status = $input['status'];
    $query = 
        'UPDATE  orders
        SET     status ='.$status.'
        WHERE   id = '.$order_id;

    mysqli_query($conn, $query);
    
    //if order cancel
    if($status == 4) {
        // the message
        $msg = '<html lang="he-IL">';
        $msg .= '<head><meta charset="utf-8"></head>';
        $msg .= '<body dir="rtl">';
        $msg .= "שלום " . $_SESSION['user']['name'] . ",";
        $msg .= "<br/><br/>";
        $msg .= "הזמנתך באתר WOODit בוטלה";
        $msg .= "<br/>";
        $msg .= "מספר הזמנה: " . $order_id;
        $msg .= "<br/><br/>";
        $msg .= "בברכה,";
        $msg .= "<br/>";
        $msg .= "WOODit";
        $msg .= '</body></html>';
        // To send HTML mail, the Content-type header must be set
        $headers = array(
          "Content-Type: text/html; charset=utf-8",
          "MIME-Version: 1.0",
          "X-Mailer: PHP/" . PHP_VERSION
        );
        $headers = implode("\r\n", $headers);       
        // send email
        mail($_SESSION['user']['email'],"Woodit - הזמנתך בוטלה",$msg, $headers);
    }
}

?>