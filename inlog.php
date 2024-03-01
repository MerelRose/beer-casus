<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "beer-casus";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize login result message
$loginResult = "";

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST["email"]) ? $_POST["email"] : ""; // Updated to use email instead of username
    $password = $_POST["password"];

    // Perform a simple query (ensure to use prepared statements in production)
    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Login successful, redirect or perform other actions
        $loginResult = "Login successful!";
    } else {
        // Login failed, handle accordingly
        $loginResult = "Invalid email or password";
    }
}

// Close the connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            background-image: url("img/beer-background.png");
            background-repeat: no-repeat;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-size: 100%;
            margin: 0;
        }
        #achterkantvlak{
           
            background-position: center;
            overflow: hidden;
            opacity: 0.5;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            width: 100%;
            margin: 0;
            z-index: 1;
            position: absolute;
        }
        .bouwvlak {
            height: 25vh;
            width: 20vw;
            border-radius: 25px;
            background-color: rgb(rgb(255, 255, 97));
            text-align: center;
            padding-top: 5px;
            z-index: 10;
        }

        .inlogvlak {
            background-color: rgb(rgb(255, 255, 97));
            height: 22vh;
            width: 18vw;
            z-index: 10;
            border-radius: 25px;
            text-align: center;
            padding-top: 5px;
            margin: auto;

        }

        .login-result {
            margin-top: 10px;
            color: red;
        }
    </style>
</head>

<body>
    <div id="achterkantvlak"> </div>
    <div class="bouwvlak">
        INLOGGEN
        <div class="inlogvlak">
            <form method="post">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required />
                <br>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required />
                <br>
                <input type="submit" value="Submit" />
            </form>
            <div class="login-result">
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    echo $loginResult;
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>