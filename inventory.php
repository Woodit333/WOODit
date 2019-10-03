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

$title = 'מלאי';
$css_links = '';
require 'header.php';
?>

<div class="container">
    <h1 class="display-4">מלאי</h1>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addNewInventory">הוסף מלאי חדש</button>
    <div class="modal fade" id="addNewInventory" tabindex="-1" role="dialog" aria-labelledby="addNewInventoryLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addNewInventoryLabel">הוספת מוצר חדש</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="include/inventory.inc.php" enctype="multipart/form-data">
                    <div class="modal-body">

                        <div class="form-label-group">
                            <input type="text" id="inputName" name="name" class="form-control" required="" autofocus="" />
                            <label for="inputName">שם סוג המלאי</label>
                        </div>
                        <div class="form-label-group">
                            <input type="text" id="inputQuantityAvailable" name="quantityNeeds" class="form-control" required="" />
                            <label for="inputQuantityAvailable">כמות נדרשת</label>
                        </div>
                        <div class="form-label-group">
                            <input type="text" id="inputQuantityNeeds" name="quantityAvailable" class="form-control" required="" />
                            <label for="inputQuantityNeeds">כמות במלאי</label>
                        </div>
                        <?php echo $display_error_add ?>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" name="addNewInventory">הוסף</button>
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
                <th scope="col">Quantity Avialble</th>
                <th scope="col">Quantity Needs</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require 'include/dbh.inc.php';
            $has_rows = true;
            if ($stmt = $conn->prepare('SELECT * FROM inventory')) {
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows != 0) {
                    while ($data = $result->fetch_assoc()) {
                        echo '<tr>
                                <td>' . $data['id'] . '</td>
                                <td>' . $data['name'] . '</td>
                                <td>' . $data['quantity_available'] . '</td>
                                <td>' . $data['quantity_needs'] . '</td>
                            </tr>';
                    }
                } else {
                   $has_rows = false;
                }
            }
            ?>
        </tbody>
    </table>
    <?php
        if(!$has_rows) {
        echo '<p>איו מלאי בחנות</p>';
        }
    ?>
</div>

<?php
$js_links = '<script src="js/jquery.tabledit.js"></script>
             <script src="js/inventory.js"></script>';
if ($display_error_add != '') {
    $js_links =  $js_links . '<script type="text/javascript">$("#addNewInventory").modal()</script>';
}

require 'footer.php';

?>