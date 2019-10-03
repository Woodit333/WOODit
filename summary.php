<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['cart_summary'])) {
    header('Location: index.php');
}

require 'include/classes.inc.php';

$cart_summary = unserialize($_SESSION['cart_summary']);
unset($_SESSION['cart_summary']);

$title = 'סיכום קניה';
$css_links = '';
require 'header.php';
?>

<div class="jumbotron text-center">
  <h1 class="display-3"><?php echo $cart_summary->is_complete ? 'תודה שרכשת!' : 'שגיאה!' ?></h1>
  <p class="lead"></p>
  <hr>
  <div class="alert alert-success <?php echo $cart_summary->is_complete ? '' : 'd-none' ?>" role="alert">
        <?php echo $cart_summary->complete_message ?>
  </div>
   <div class="alert alert-danger <?php echo $cart_summary->is_error ? '' : 'd-none' ?>" role="alert">
        <?php echo $cart_summary->error_message ?>
  </div>
  <hr>
  <p>
    ישנה בעיה? <a href="contact.php">צור קשר</a>
  </p>
  <p class="lead">
    <a class="btn btn-primary btn-sm" href="index.php" role="button">המשך לעמוד הבית</a>
  </p>
</div>

<?php

$js_links = '';
require 'footer.php';

?>