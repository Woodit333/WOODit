
<?php
require 'functions.php';
session_start();
//On add new product
if (isset($_POST['addNewImage'])) {
    require 'dbh.inc.php';
    $errors = array();

    $product = $_POST['product-id'];

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

        if ($stmt = $conn->prepare("INSERT INTO `products_images`
        (`product`, `image`)
         VALUES (?, ?)")) {
            //Bind i -> for int col & s for string col
            $stmt->bind_param("ss", $product, $image);
            if (!$stmt->execute()) {
                array_push($errors, "שגיאה: " . mysqli_error($conn));
            }

            if (count($errors) == 0) {
                if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    array_push($errors, "Sorry, there was an error uploading your file.");
                }
            }
        } else {
            array_push($errors, "שגיאה: " . mysqli_error($conn));
        }
    }

    if (count($errors) > 0) {
        $_SESSION['is-add'] = true;
        $_SESSION['errors'] = $errors;
    }

    header('Location: ../photos.php');
}
?>


