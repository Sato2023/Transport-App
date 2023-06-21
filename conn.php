<?php
$dbhost = "localhost";
$dbuser = "Adam";
$dbpass = "Adam1";
$dbname = "adamdb";

$conn  = mysqli_connect($dbhost,$dbuser, $dbpass, $dbname);


if($conn -> connect_error) {
    die('Could not connect: ' . $conn -> connect_error);
}

?>
