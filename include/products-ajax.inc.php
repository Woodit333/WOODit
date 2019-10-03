<?php

header('Content-Type: application/json');

// CHECK REQUEST METHOD
if ($_SERVER['REQUEST_METHOD']=='POST') {
  $input = filter_input_array(INPUT_POST);
} else {
  $input = filter_input_array(INPUT_GET);
}

require 'dbh.inc.php';
$id = $input['id'];

// Php question
if ($input['action'] === 'edit') {
  $name = mysqli_real_escape_string($conn, $input['name']);
  $description = mysqli_real_escape_string($conn, $input['description']);
  $price = mysqli_real_escape_string($conn, $input['price']);;
  $discount_percentage = mysqli_real_escape_string($conn, $input['discount_percentage']);;

  $query = 'UPDATE  products
            SET     name ="'.$name.'",
                    description ="'.$description.'",
                    price ="'.$price.'",
                    discount_percentage ="'.$discount_percentage.'"
            WHERE   id = '.$id;

  mysqli_query($conn, $query);
} else if ($input['action'] === 'delete') {
  $query = 'UPDATE  products
            SET     is_deleted = 1
            WHERE   id = '.$id;

  mysqli_query($conn, $query);
} else if ($input['action'] === 'restore') {
  $query = 'UPDATE  products
  SET     is_deleted = 0
  WHERE   id = '.$id;

  mysqli_query($conn, $query);
}

// RETURN OUTPUT
echo json_encode($input);
