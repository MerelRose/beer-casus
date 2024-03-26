<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    ini_set("session.gc_maxlifetime", 45 * 60);  // 45 min
    if (session_status() === PHP_SESSION_DISABLED) {
        throw new Exception("Sessions disabled!");
    } else {
        // session_id() om het juiste id op te halen 
        if (isset($_COOKIE[ucfirst(str_replace(".", "_", $_SERVER['SERVER_NAME']))])) {
            session_id($_COOKIE[ucfirst(str_replace(".", "_", $_SERVER['SERVER_NAME']))]);
        } else {
            session_name(ucfirst($_SERVER['SERVER_NAME']));
            session_set_cookie_params(0, "/", $_SERVER['SERVER_NAME'], true);
        }
        session_start();
    }
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "beer-casus";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Sorry, Connection failed: " . mysqli_connect_error());
}
