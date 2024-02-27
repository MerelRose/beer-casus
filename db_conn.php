<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "beer-casus";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Sorry, Connection failed: " . mysqli_connect_error());
}
