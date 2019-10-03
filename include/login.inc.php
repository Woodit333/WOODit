<?php

if(isset($_POST['login'])) {
    require 'dbh.inc.php';
    session_start();
    // grap form values
	$email = $_POST['email'];
	$password = $_POST['password'];

    $errors = array();

	// make sure form is filled properly
	if (empty($email)) {
		array_push($errors, "נא הכנס כתובת אימייל");
	}
	if (empty($password)) {
		array_push($errors, "נא הכנס סיסמ");
	}

	// attempt login if no errors on form
	if (count($errors) == 0) {
		$query = "SELECT * FROM users WHERE email='$email' AND password='$password' LIMIT 1";
		$results = mysqli_query($conn, $query);

		if (mysqli_num_rows($results) == 1) { // user found
			// check if user is admin or user
			$logged_in_user = mysqli_fetch_assoc($results);
			$_SESSION['user'] = $logged_in_user;
		}else {
            array_push($errors, "שם משתמש או סיסמא לא נכונים");
            header('Location: ../login.php');
		}
    }

    if (count($errors) == 0) {
        header('Location: ../index.php');
    } else {
        $_SESSION['errors'] = $errors;
        header('Location: ../login.php');
    }
}

?>