<?php
session_start();

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

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    // Query the database to fetch the user ID based on the email
    $sql = "SELECT user_id FROM users WHERE email = '$email'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // Fetch the user ID from the query result
        $row = $result->fetch_assoc();
        $userId = $row['user_id'];
        echo "Logged in user ID from database: " . $userId;
    } else {
        echo "User not found in database";
    }
} else {
    echo "User not logged in";
}

function generate_token() {
    return bin2hex(random_bytes(16));
}

function hash_token($token) {
    return hash('sha256', $token);
}

// // login met hash
// function login($conn, $email, $password, $remember_me) {
//     $hashed_password = hash('sha256', $password); 
//     $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$hashed_password'";
//     $result = $conn->query($sql);
//     if ($result && $result->num_rows > 0) {
//         // Login successful
//         if ($remember_me) {
//             $token = generate_token();
//             $hashed_token = hash_token($token);
//             $sql = "INSERT INTO remember_tokens (email, token) VALUES ('$email', '$hashed_token')";
//             $conn->query($sql);
//             setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60));
//         } else {
//             $_SESSION['email'] = $email;
//         }
//         return true;
//     } else {
//         // Debugging: Print SQL error if any
//         if ($result === false) {
//             echo "Error: " . $conn->error;
//         }
//         return false;
//     }
// }

// login zonder hash
function login($conn, $email, $password, $remember_me) {
    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        // Login successful
        if ($remember_me) {
            $token = generate_token();
            $hashed_token = hash_token($token);
            $sql = "INSERT INTO remember_tokens (email, token) VALUES ('$email', '$hashed_token')";
            $conn->query($sql);
            setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60));
        } else {
            $_SESSION['email'] = $email;
        }
        return true;
    } else {
        // Debugging: Print SQL error if any
        if ($result === false) {
            echo "Error: " . $conn->error;
        }
        return false;
    }
}


function logout($conn) {
    unset($_SESSION['email']);
    if (isset($_COOKIE['remember_token'])) {
        $token = $_COOKIE['remember_token'];
        $hashed_token = hash_token($token);
        $sql = "DELETE FROM remember_tokens WHERE token = '$hashed_token'";
        $conn->query($sql);
        setcookie('remember_token', '', time() - 3600); 
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $remember_me = isset($_POST['remember_me']) && $_POST['remember_me'] === 'on';

    if (login($conn, $email, $password, $remember_me)) {
        echo "Logged in";
    } else {
        echo "Invalid credentials";
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    logout($conn);
    echo "Logged out";
}

$conn->close();
?>

<link rel="stylesheet" href="styles/login.css">
<div class="container" id="container">
    <div class="form-container sign-up-container">
        <form method="post">
            <h1>Create Account</h1>
            <div class="social-container">
                <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
            </div>
            <span>or use your email for registration</span>
            <input type="text" placeholder="Name" />
            <input type="email" placeholder="Email" />
            <input type="password" placeholder="Password" />
            <button>Sign Up</button>
        </form>
    </div>
    <div class="form-container sign-in-container">
        <form method="post">
            <h1>Sign in</h1>
            <div class="social-container">
                <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
            </div>
            <span>or use your account</span>
            <input type="email" name="email" id="email" placeholder="Email" />
            <input type="password" name="password" id="password" placeholder="Password" />
            <a href="#">Forgot your password?</a>
            <input type="submit" name="signin" value="Sign In">
        </form>
    </div>
    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-left">
                <h1>Welcome Back!</h1>
                <p>To keep connected with us please login with your personal info</p>
                <button class="ghost" id="signIn">Sign In</button>
            </div>
            <div class="overlay-panel overlay-right">
                <h1>Hello, Friend!</h1>
                <p>Enter your personal details and start journey with us</p>
                <button class="ghost" id="signUp">Sign Up</button>
            </div>
        </div>
    </div>
</div>
<script>
    const signUpButton = document.getElementById('signUp');
    const signInButton = document.getElementById('signIn');
    const container = document.getElementById('container');

    signUpButton.addEventListener('click', () => {
        container.classList.add("right-panel-active");
    });

    signInButton.addEventListener('click', () => {
        container.classList.remove("right-panel-active");
    });
</script>

<p><a href="?action=logout">Logout</a></p>
