
<?php
require 'functions.php';
session_start();
//On add new product
if (isset($_POST['addNewInventory'])) {
    require 'dbh.inc.php';
    $errors = array();

    $name = $_POST['name'];
    if(!is_numeric($_POST['quantityAvailable'])){
        array_push($errors, "Quantity available must be valid number.");
    }
    $quantityAvailable = $_POST['quantityAvailable'];
    if(!is_numeric($_POST['quantityNeeds'])){
        array_push($errors, "Quantity needs must be valid number.");
    }
    $quantityNeeds =  $_POST['quantityNeeds'];

    if (count($errors) == 0) {
        $image = getGUID() . '.' . $imageFileType;
        $target_file = $dirpath . "/" . $target_dir . $image;

        if ($stmt = $conn->prepare("INSERT INTO `inventory`
        (`name`,
        `quantity_available`,
        `quantity_needs`)
         VALUES (?, ?, ?)")) {
            //Bind i -> for int col & s for string col
            $stmt->bind_param("sii", $name, $quantityAvailable, $quantityNeeds);
            if (!$stmt->execute()) {
                array_push($errors, "שגיאה: " . mysqli_error($conn));
            }
        } else {
            array_push($errors, "שגיאה: " . mysqli_error($conn));
        }
    }

    if (count($errors) > 0) {
        $_SESSION['is-add'] = true;
        $_SESSION['errors'] = $errors;
    }

    header('Location: ../inventory.php');
}
?>


