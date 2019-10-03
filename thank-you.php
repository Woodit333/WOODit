<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require 'include/dbh.inc.php';
require 'include/classes.inc.php';
require 'include/functions.php';

$title = 'תודה שרכשת';
$css_links = '';
require 'header.php';
?>

<div class="jumbotron text-center">
  <h1 class="display-3">תודה!</h1>
  <p class="lead"><strong>Please check your email</strong> for further instructions on how to complete your account setup.</p>
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