<?php
require 'include/functions.php';
require 'include/dbh.inc.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) || !boolval($_SESSION['user']['is_admin'])) {
    header('Location: index.php');
}

$display_error_add_image = '';
if (isset($_SESSION['is-add']) && !empty($_SESSION['is-add']) && $_SESSION['is-add']) {
    $display_error_add_image = getErrorLabels();
    unset($_SESSION['is-add']);
}

$title = 'תמונות';
$css_links = '';
require 'header.php';
?>

<div class="container">
    <h1 class="display-4">תמונות</h1>
    <div class="btn-group dropleft">
        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            בחר מוצר
        </button>
        <div class="dropdown-menu">
            <?php
            if ($stmt = $conn->prepare('SELECT * FROM products')) {
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows != 0) {
                    while ($data = $result->fetch_assoc()) {
                        echo '<a id="' . $data['id'] . '" class="dropdown-item" href="#">' . $data['name'] . '</a>';
                    }
                } else {
                    echo '<a class="dropdown-item disabled" href="#">אין מוצרים במערכת</a>';
                }
            }
            ?>
        </div>
        
    </div>
    <p class="h3">תמונה ראשית למוצר</p>
    <div id="mainIamge" class="row">

    </div>
    <p class="h3">תמונות למוצר בגלריה</p>
    <form method="post" action="include/photo.inc.php" enctype="multipart/form-data" class="invisible">
        <input id="productId" name="product-id" type="hidden" value="" />
        <div class="custom-file">
            <input type="file" name="image" class="custom-file-input" id="customFile" require>
            <label class="custom-file-label" for="customFile">Choose file</label>
        </div>
        <button type="submit" class="btn btn-primary" name="addNewImage">הוסף תמונה לגלריה</button>
    </form>
    <?php echo $display_error_add_image ?>
    <div id="images" class="card-columns">

    </div>
</div>

<?php

$js_links = '<script src="js/photos.js"></script>';
require 'footer.php';

?>