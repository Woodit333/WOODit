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

$title = 'Woodit עמוד הבית';
$css_links = '';
require 'header.php';
?>

<form class="form-signin mt-md-5 darker-color-bg" method="post" action="include/login.inc.php">
  <div class="text-center mb-4">
    <h1 class="h3 mb-3 font-weight-normal">התחבר</h1>
    <p>כדי להזמין באתר יש להתחבר</a></p>
  </div>

  <div class="form-label-group">
    <input type="email" id="inputEmail" class="form-control" name="email" placeholder="כתובת אימייל" required="" autofocus="">
    <label for="inputEmail">כתובת אימייל</label>
  </div>

  <div class="form-label-group">
    <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required="">
    <label for="inputPassword">סיסמא</label>
  </div>

  <button class="btn btn-lg bg-dark text-white btn-block" type="submit" name="login">התחבר</button>

  <?php echo $display_error ?>

  <div class="mt-4">
    <span>עדיין לא רשום?</span>
    <a class="btn btn-lg bg-dark text-white btn-block" href="register.php">הירשם</a>
</div>
</form>


<?php
$js_links = '';
require 'footer.php';
?>