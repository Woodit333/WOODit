<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) || !boolval($_SESSION['user']['is_admin'])) {
    header('Location: index.php');
}

require 'include/functions.php';
$display_error_edit = '';
if (isset($_SESSION['is-edit']) && !empty($_SESSION['is-edit']) && $_SESSION['is-edit']) {
    $display_error_edit = getErrorLabels();
    unset($_SESSION['is-edit']);
}

$title = 'הזמנות';
$css_links = '';
require 'header.php';
?>

<!-- Modal -->
<div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-labelledby="orderModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="orderModalTitle">Modal title</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="" enctype="multipart/form-data">
                <input id="productId" type="hidden" />
                <div class="modal-body">
                    <h4>פרטי לקוח</h4>
                    <div class="user-wrapper">
                    </div>
                    <h4 class="mt-2">מוצרי ההזמנה</h4>
                    <div class="container">
                        <div class="row order-products">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">סגור</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container">
    <h1 class="display-4">הזמנות</h1>

    <table id="my-table" class="table">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">לקוח</th>
                <th scope="col">תאריך</th>
                <th scope="col">סטטוס</th>
                <th scope="col">עסקה</th>
                <th scope="col">פרטים</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require 'include/dbh.inc.php';
            $has_rows = true;
            if ($stmt = $conn->prepare('SELECT 	o.*, u.name as user_name FROM orders o INNER JOIN users u ON o.user = u.id')) {
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows != 0) {
                    while ($data = $result->fetch_assoc()) {
                        echo '<tr class="order-row">
                                <td>' . $data['id'] . '</td>
                                <td>שם: ' . $data['user_name'] . ', מספר לקוח: ' . $data['user'] .'</td>
                                <td>' . $data['create_date'] . '</td>
                                <td>
                                    <select class="custom-select selectpicker">
                                        <option value="1" ' . ((intval($data['status']) == 1) ? "selected" : "") . '>חדשה</option>
                                        <option value="2" ' . ((intval($data['status']) == 2) ? "selected" : "") . '>בתהליך</option>
                                        <option value="3" ' . ((intval($data['status']) == 3) ? "selected" : "") . '>נשלחה</option>
                                        <option value="4" ' . ((intval($data['status']) == 4) ? "selected" : "") . '>בוטלה</option>
                                    </select>
                                    <button id="' . $data['id'] . '" type="button" class="btn btn-secondary update-order">עדכן הזמנה</button>
                                </td>
                                <td>' . $data['transaction'] . '</td>
                                <td><button id="'. $data['id'] .'" type="button" class="btn btn-light" data-toggle="modal" data-target="#orderModal">פרטי הזמנה</button></td>
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
        echo '<p>אין הזמנות בחנות</p>';
        }
    ?>
</div>

<?php
$js_links = '<script src="js/orders.js"></script>';

require 'footer.php';

?>