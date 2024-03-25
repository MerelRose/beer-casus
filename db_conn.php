<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "beer-casus";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Sorry, Connection failed: " . mysqli_connect_error());
}
