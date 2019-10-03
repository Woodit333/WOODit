<?php
$servername = "localhost";
$dBUsername = "maysh";
$dBPassword = "7LyuP@6!Rl";
$dBName = "maysh_woodi";

$conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);

if (!$conn){
    die("Connection failed: ".mysqli_connect_error());
}

mysqli_query($conn,"SET NAMES utf8");

?>