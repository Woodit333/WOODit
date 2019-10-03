<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$user_exists = false;
$is_admin = false;
if (isset($_SESSION['user'])) {
    $user_exists = true;
    if (boolval($_SESSION['user']['is_admin'])){
        $is_admin = true;
    }
}

require 'include/dbh.inc.php';

$title = 'מוצרים למכירה';
$css_links = '';
require 'header.php';
?>

<!-- Modal -->
<div class="modal fade" id="saleModal" tabindex="-1" role="dialog" aria-labelledby="saleModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="saleModalTitle">Modal title</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="" enctype="multipart/form-data">
                <input id="productId" type="hidden" />
                <div class="modal-body">
                    <h4>מחיר</h4>
                    <div class="price-wrapper">
                    </div>
                    <!--Carousel Wrapper-->
                    <div id="carousel-thumb" class="carousel slide carousel-fade carousel-thumbnails" data-ride="carousel">
                        <!--Slides-->
                        <div class="carousel-inner" role="listbox">
                        </div>
                        <!--/.Slides-->
                        <!--Controls-->
                        <a class="carousel-control-prev" href="#carousel-thumb" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carousel-thumb" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                        <!--/.Controls-->
                        <ol class="carousel-indicators">
                        </ol>
                    </div>
                    <!--/.Carousel Wrapper-->
                    <?php 
                    if($user_exists  && !$is_admin) {
                        echo '
                    <h4 class="mt-2">העלאה תמונה להדפסה</h4>
                    <div name="userImage" class="custom-file">
                        <input type="file" name="image" class="custom-file-input" id="customFile" onchange="loadFile(event)" require>
                        <label class="custom-file-label" for="customFile">Choose file</label>
                    </div>
                    <img class="mt-2 img-fluid mx-auto d-block" id="output" />';
                        }
                    ?>
                </div>
                <div class="modal-footer">
                    <?php 
                        if($user_exists  && !$is_admin) {
                            echo '<button type="submit" class="btn btn-success add-to-cart"><span class="fa fa-cart-plus"></span> הוסף לעגלה</button>';
                        }
                    ?>
                    <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">סגור</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container">
    <div class="row justify-content-center mb-4 darker-color-bg p-2">
        <h3>הוסף פריטים לעגלת קניות שלך
            <?php
                if(!$user_exists) {
                    echo '- למשתמשים רשומים בלבד';
                }
            ?>
        </h3>
    </div>
    <div id="polite" aria-live="polite" aria-atomic="true" style="position: relative; min-height: 200px; display: none;">
        <div id="saleToast" class="toast" style="position: absolute; top: 0; left: 0;" data-delay="5000">
            <div class="toast-header">
              <strong>פריט הוסף לעגלה</strong>
              <button type="button" class="ml-2 mb-1 close mr-auto" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="toast-body">
             לחץ על סמל העגלה כדי לעבור למסך עגלת קניות
            </div>
        </div>
    </div>

    <?php
    if ($stmt = $conn->prepare('SELECT * FROM products WHERE is_deleted = 0')) {
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows != 0) {
            echo '<div class="row">';
            while ($data = $result->fetch_assoc()) {
                echo '<div class="col-md-4">
                        <div id="' . $data['id'] . '" class="card mb-4 darker-color-bg" data-toggle="modal" data-target="#saleModal">
                            <div class="heffect">
                                <img class="card-img-top" src="uploads\\' . $data['image'] . '">
                            </div>
                            <div class="card-body text-center">
                                <h5 class="card-title text-center">' . $data['name'] . '</h5>
                            </div>
                        </div>
                </div>';
            }
            echo '</div>';
        } else {
            echo '<h4>אין מוצרים במערכת</h4>';
        }
    }
    ?>
</div>

<?php
$js_links = '<script src="js/sale.js"></script>';
require 'footer.php';
?>