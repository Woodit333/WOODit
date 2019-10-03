<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user'])) {
    header('Location: index.php');
}

$display_error = '';
if(isset($_SESSION['errors']) && !empty($_SESSION['errors'])) {
    $display_errors_array = array();
    $errors = $_SESSION['errors']; 
    foreach ($errors as $error){
    $display_error = '<div class="alert alert-danger" role="alert">{0}</div>';
    $display_error = str_replace("{0}", $error, $display_error);
    array_push($display_errors_array, $display_error);
    }
    $display_error = implode("", $display_errors_array);
    unset($_SESSION['errors']);
}

$title = 'רישום';
$css_links = '';
require 'header.php';
?>

<form class="form-signin mt-md-5 darker-color-bg" method="post" action="include/register.inc.php">
    <div class="text-center mb-4">
        <h1 class="h3 mb-3 font-weight-normal">טופס רישום</h1>
        <p>אנא מלא את פרטי המשתמש שלך</a></p>
    </div>

    <div class="form-label-group">
        <input type="text" id="inputName" name="name" class="form-control" placeholder="שם מלא" required="" autofocus="" />
        <label for="inputName">שם מלא</label>
    </div>

    <div class="form-label-group">
        <input type="email" id="inputEmail" name="email" class="form-control" placeholder="כתובת אימייל" required="" />
        <label for="inputEmail">כתובת אימייל</label>
    </div>

    <div class="form-label-group">
        <input type="password" id="inputPassword" name="password" class="form-control" placeholder="סיסמא" required="" />
        <label for="inputPassword">סיסמא</label>
    </div>

    <div class="form-label-group">
        <input type="tel" pattern=".{10}" name="mobile" id="inputTel" class="form-control" placeholder="פלאפון" oninput="check(this)" required />
        <label for="inputTel">פלאפון</label>
    </div>

    <div class="form-label-group">
        <input type="tel" name="location-input" id="location-input" class="form-control" placeholder="כתובת מגורים" autocomplete="off" required />
        <label for="inputTel">כתובת מגורים</label>
        <input type="hidden" id="location-id" name="location-id" />
        <input type="hidden" id="location" name="location" />
    </div>

    <?php echo $display_error ?>    

    <button class="btn btn-lg bg-dark text-white btn-block" type="submit" name="register">הירשם</button>
</form>


<?php
    $js_links = '<script src="js/bootstrap-autocomplete.js"></script>
                 <script src="js/location-autocomplete.js"></script>';
    require 'footer.php';
?>