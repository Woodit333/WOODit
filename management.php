<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) || !boolval($_SESSION['user']['is_admin'])) {
    header('Location: index.php');
}

$title = 'ניהול';
$css_links = '';
require 'header.php';
?>

<div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
    <h1 class="display-4">אדמיניסטרציה</h1>
    <p class="lead">בחר את אחד מהאפשרויות הבאות לצורך ניהול האתר</p>
</div>

<div class="container">
    <div class="card-group">
        <div class="card shadow-sm darker-color-bg">
            <div class="card-header">
                <h4 class="my-0 font-weight-normal">מוצרים</h4>
            </div>
            <div class="card-body">
                <h3 class="card-title">הוסף, ערוך ומחק מוצרים במערכת</h3>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-lg btn-block btn-outline-light"><a class="text-dark" href="products.php">ניהול מוצרים</a></button>
            </div>
        </div>
        <div class="card shadow-sm darker-color-bg">
            <div class="card-header">
                <h4 class="my-0 font-weight-normal">תמונות</h4>
            </div>
            <div class="card-body">
                <h3 class="card-title">הוסף, ערוך ומחק תמונות של מוצרים</h3>
            </div>
            <div class="card-footer">
            <button type="button" class="btn btn-lg btn-block btn-outline-light"><a class="text-dark" href="photos.php">ניהול תמונות</a></button>
            </div>
        </div>
        <div class="card shadow-sm darker-color-bg">
            <div class="card-header">
                <h4 class="my-0 font-weight-normal">מלאי</h4>
            </div>
            <div class="card-body">
                <h3 class="card-title">ניהול מלאי החנות</h3>
            </div>
            <div class="card-footer">
            <button type="button" class="btn btn-lg btn-block btn-outline-light"><a class="text-dark" href="inventory.php">ניהול מלאי</a></button>
            </div>
        </div>
        <div class="card shadow-sm darker-color-bg">
            <div class="card-header">
                <h4 class="my-0 font-weight-normal">הזמנות</h4>
            </div>
            <div class="card-body">
                <h3 class="card-title">ניהול הזמנות לקוחות</h3>
            </div>
            <div class="card-footer">
            <button type="button" class="btn btn-lg btn-block btn-outline-light"><a class="text-dark" href="orders.php">ניהול הזמנות</a></button>
            </div>
        </div>
    </div>
</div>

<?php
$js_links = '';
require 'footer.php';
?>