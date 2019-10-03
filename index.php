<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$title = 'עמוד הבית';
$css_links = '<link rel="stylesheet" href="css/index.css">';
require 'header.php';
?>

<div class="container">
    <div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">רישום לאתר</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>הרישום לאתר בוצע בהצלחה, אנא התחבר.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">סגור</button>
                </div>
            </div>
        </div>
    </div>

    <div class="jumbotron row p-4 p-md-5 text-white rounded bg-dark mt-md-3">
        <div class="col-md-8">
            <h1 class="display-4 font-italic">WOODit</h1>
            <p class="lead my-3">מחפשים מתנה מקורית ומיוחדת?</p>
            <p class="lead my-3">אנחנו מכינים תמונות על עץ מלא - בעבודת יד מקצועית ואיכותית.</p>
            <p class="lead my-3">רק תבחרו את הגדול הרצוי ואת התמונה הכי יפה ואנחנו נעשה את השאר.</p>
        </div>
        <div class="col-md-4">
            <img src="images/woodit.png" class="img-fluid" />
        </div>
    </div>

    <div class="row justify-content-center mb-4 darker-color-bg p-2">
        <a href="sale.php" class="btn bg-dark btn-lg text-white">בואו נתחיל</a>
    </div>

    <section class="features-area">
        <div class="row features-inner">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="single-features">
                    <div class="f-icon">
                        <img src="images/f-icon1.png" alt="">
                    </div>
                    <h6>שילוח חינם</h6>
                    <p>הובלה חינם לכל ההזמנות</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="single-features">
                    <div class="f-icon">
                        <img src="images/f-icon2.png" alt="">
                    </div>
                    <h6>ביטול הזמנות</h6>
                    <p>על פי חוק</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="single-features">
                    <div class="f-icon">
                        <img src="images/f-icon3.png" alt="">
                    </div>
                    <h6>תמיכה 24/7</h6>
                    <p>אנחנו זמינים עבורכם  כל הזמן</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="single-features">
                    <div class="f-icon">
                        <img src="images/f-icon4.png" alt="">
                    </div>
                    <h6>תשלום בטוח</h6>
                    <p>תשלום דרך PayPal</p>
                </div>
            </div>
        </div>
    </section>
</div>


<?php
$js_links = '';
if (isset($_SESSION['register']) && !empty($_SESSION['register']) && $_SESSION['register']) {
    $js_links = '<script type="text/javascript">$("#registerModal").modal()</script>';
    unset($_SESSION['register']);
}

require 'footer.php';
?>