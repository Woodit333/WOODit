<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

unset($_SESSION["paypal_products"]);

$user_disply = '<li class="nav-item active"><a class="nav-link" href="login.php">התחבר \ הירשם</a></li>';
if (isset($_SESSION['user'])) {
    $user_disply = '<span class="navbar-text">שלום,</span>';
    $user_name = $_SESSION['user']['name'];
    if (boolval($_SESSION['user']['is_admin'])) {
        $user_disply .= '<li class="nav-item active"><a class="nav-link" href="management.php">' . $user_name . '</a></li>';
    } else {
        $user_disply .= '<li class="nav-item active"><a class="nav-link" href="edit-user.php">' . $user_name . '</a></li>'
            . '<li class="nav-item"><a class="nav-link" href="my-orders.php">ההזמנות שלי</a></li>'
            . '<li class="nav-item"><a class="nav-link" href="cart.php">עגלת קניות <i class="fa fa-shopping-cart"></i></a></li>';
    }

    $user_disply .= '<li class="nav-item"><a class="nav-link" href="logout.php">התנתק</a></li>';
}
?>

<!DOCTYPE html>
<html lang="he">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?php echo $title; ?></title>
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet">
    <link href="css/general.css" rel="stylesheet">
    <link rel="stylesheet" href="css/form.css">
    <?php echo $css_links; ?>
    <script
        src="https://www.paypal.com/sdk/js?client-id=ASzKXk-KvyZwfrPAkP9DzK_F2hkc5NgSFgEI2TS15FFdCVCj6jIxK9JYF6wtWxhupoTv4U1A_AFP1DX0&currency=ILS">
    </script>
</head>

<body class="d-flex flex-column" data-gr-c-s-loaded="true">
    <header>
        <!-- Fixed navbar -->
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <a class="navbar-brand" href="index.php">WOODit</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
                aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav mr-auto">
                    <?php echo $user_disply ?>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Begin page content -->
    <main role="main" class="flex-shrink-0">

        <div class="modal fade" id="modalImage" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-body">
                    <img id="modalImageElement" src="http://i3.ytimg.com/vi/vr0qNXmkUJ8/maxresdefault.jpg"
                        class="img-fluid" />
                </div>
            </div>
        </div>