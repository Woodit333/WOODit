<?php

header('Content-Type: application/json');

// CHECK REQUEST METHOD
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $input = filter_input_array(INPUT_POST);
} else {
  $input = filter_input_array(INPUT_GET);
}

require 'dbh.inc.php';

if ($input['action'] === 'get') {
  $product_id = $input['productId'];
  $query = '
  SELECT	product as id, 
          pi.image,
          (
        CASE WHEN p.image = pi.image
          THEN 1 
          ELSE 0
        END 
      ) AS is_main
  FROM	products_images pi
  LEFT JOIN products p ON p.id = pi.product
  WHERE p.id = ' . $product_id;

  $result = $conn->query($query);
  $output = array();
  $output = $result->fetch_all(MYSQLI_ASSOC);
  echo $json = json_encode($output);
} else if ($input['action'] === 'main') {
  $product_id = $input['productId'];
  $image_id = $input['imageId'];
  $query = 'UPDATE  products
            SET     image ="'.$image_id.'"
            WHERE   id = '.$product_id;

  mysqli_query($conn, $query);
  echo json_encode($input);
 } else if ($input['action'] === 'delete') { 
  $image_id = $input['imageId'];
  $query = 'DELETE FROM products_images WHERE id = '.$image_id;
  mysqli_query($conn, $query);
  echo json_encode($input);
 }
