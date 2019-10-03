<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) || !boolval($_SESSION['user']['is_admin'])) {
    header('Location: index.php');
}

require 'include/functions.php';
$display_error_add = '';
$display_error_edit = '';
if (isset($_SESSION['is-add']) && !empty($_SESSION['is-add']) && $_SESSION['is-add']) {
    $display_error_add = getErrorLabels();
    unset($_SESSION['is-add']);
} else if (isset($_SESSION['is-edit']) && !empty($_SESSION['is-edit']) && $_SESSION['is-edit']) {
    $display_error_edit = getErrorLabels();
    unset($_SESSION['is-edit']);
}


$title = 'מוצרים';
$css_links = '';
require 'header.php';
?>

<div class="container">
    <h1 class="display-4">מוצרים</h1>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addNewProduct">הוסף מוצר חדש</button>
    <div class="modal fade" id="addNewProduct" tabindex="-1" role="dialog" aria-labelledby="addNewProductLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addNewProductLabel">הוספת מוצר חדש</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="include/products.inc.php" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-label-group">
                            <input type="text" id="inputName" name="name" class="form-control" required="" autofocus="" />
                            <label for="inputName">שם המוצר</label>
                        </div>
                        <div class="form-label-group">
                            <input type="text" id="inputDescription" name="description" class="form-control" required="" autofocus="" />
                            <label for="inputName">תיאור המוצר</label>
                        </div>
                        <div class="form-label-group">
                            <input type="text" id="inputPrice" name="price" class="form-control" required="" />
                            <label for="inputPrice">מחיר</label>
                        </div>
                        <div class="form-label-group">
                            <input type="text" id="inputDiscount" name="discount" class="form-control" required="" />
                            <label for="inputDiscount">הנחה (0-99)</label>
                        </div>
                        <p>תמונה ראשית:</p>
                        <div class="custom-file">
                            <input type="file" name="image" class="custom-file-input" id="customFile" require>
                            <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>
                        <?php echo $display_error_add ?>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" name="addNewProduct">הוסף</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <table id="my-table" class="table">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Name</th>
                <th scope="col">Description</th>
                <th scope="col">Price</th>
                <th scope="col">Discount (%)</th>
                <th scope="col">Active</th>
                <th scope="col">Image</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require 'include/dbh.inc.php';
            if ($stmt = $conn->prepare('SELECT * FROM products')) {
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows != 0) {
                    while ($data = $result->fetch_assoc()) {
                        $active = '';
                        if ($data['is_deleted']) {
                            //$active = '<i class="fa fa-times-circle fa-2x"></i>';
                            $active = '<button type="button" class="tabledit-not-active btn btn-sm btn-default" style="float: none;" data-toggle="popover" data-trigger="hover" data-placement="right" data-content="active this product">
                                            <span class="fa fa-times-circle fa-2x"></span>
                                        </button>';
                        } else {
                            //$active = '<i class="fa fa-check-circle fa-2x"></i>';
                            $active = '<button type="button" class="tabledit-active btn btn-sm btn-default" style="float: none;" data-toggle="popover" data-trigger="hover" data-placement="right" data-content="disable this product">
                                        <span class="fa fa-check-circle fa-2x"></span>
                                        </button>';
                        }
                        echo '<tr>
                        <td>' . $data['id'] . '</td>
                        <td>' . $data['name'] . '</td>
                        <td>' . $data['description'] . '</td>
                        <td>' . $data['price'] . '</td>
                        <td>' . $data['discount_percentage'] . '</td>
                        <td>' . $active . '</td>
                        <td><button type="button" class="btn btn-light" data-toggle="modal" data-target="#modalImage" src="uploads/' . $data['image'] . '">הצג תמונה</button></td>
                    </tr>';
                    }
                } else {
                    echo '<tr><td colspan="5">אין מוצרים במערכת</td></tr>';
                }
            }
            ?>
        </tbody>
    </table>
</div>

<?php
$js_links = '<script src="js/jquery.tabledit.js"></script>
             <script src="js/products.js"></script>';
if ($display_error_add != '') {
    $js_links =  $js_links . '<script type="text/javascript">$("#addNewProduct").modal()</script>';
}

require 'footer.php';

?>