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
  $quantity_available = mysqli_real_escape_string($conn, $input['quantity_available']);;
  $quantity_needs = mysqli_real_escape_string($conn, $input['quantity_needs']);;

  $query = 'UPDATE  inventory
            SET     name ="'.$name.'",
                    quantity_available ="'.$quantity_available.'",
                    quantity_needs ="'.$quantity_needs.'"
            WHERE   id = '.$id;

  mysqli_query($conn, $query);
} else if ($input['action'] === 'delete') {
  $query = 'DELETE FROM  inventory
            WHERE   id = '.$id;

  mysqli_query($conn, $query);
} 

// RETURN OUTPUT
echo json_encode($input);
