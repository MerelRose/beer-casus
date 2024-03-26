<?php
include("db_conn.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function generate_token() {
    return bin2hex(random_bytes(16));
}

function hash_token($token) {
    return hash('sha256', $token);
}

function authenticate_user($conn, $token) {
    $hashed_token = hash_token($token);
    $sql = "SELECT username FROM remember_tokens WHERE token = '$hashed_token'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['username'];
    }
    return false;
}

function login($conn, $username, $password, $remember_me) {
    $hashed_password = hash('sha256', $password); 
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$hashed_password'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        var_dump($result);
        if ($remember_me) {
            $token = generate_token();
            $hashed_token = hash_token($token);
            $sql = "INSERT INTO remember_tokens (username, token) VALUES ('$username', '$hashed_token')";
            $conn->query($sql);
            setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60));
        } else {
            $_SESSION['username'] = $username;
        }
        return true;
    }
    return false;
}

function logout($conn) {
    unset($_SESSION['username']);
    if (isset($_COOKIE['remember_token'])) {
        $token = $_COOKIE['remember_token'];
        $hashed_token = hash_token($token);
        $sql = "DELETE FROM remember_tokens WHERE token = '$hashed_token'";
        $conn->query($sql);
        setcookie('remember_token', '', time() - 3600); 
    }
}

function protected_page() {
    global $conn;
    if (isset($_SESSION['username']) || isset($_COOKIE['remember_token'])) {
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];
        } elseif (isset($_COOKIE['remember_token'])) {
            $token = $_COOKIE['remember_token'];
            $username = authenticate_user($conn, $token);
            if (!$username) {
                http_response_code(401);
                die('Unauthorized');
            }
        }
        echo "Welcome, $username!";
    } else {
        http_response_code(401);
        die('Unauthorized');
    }
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember_me = isset($_POST['remember_me']) && $_POST['remember_me'] === 'on';

    if (login($conn, $username, $password, $remember_me)) {
        echo "Logged in";
    } else {
        echo "Invalid credentials";
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    logout($conn);
    echo "Logged out";
}

    echo "hhhhhhhhhhhhh";

$conn->close();