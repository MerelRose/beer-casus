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
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Perform a simple query (ensure to use prepared statements in production)
    $sql = "SELECT * FROM users WHERE name = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Login successful, redirect or perform other actions
        $loginResult = "Login successful!";
    } else {
        // Login failed, handle accordingly
        $loginResult = "Invalid username or password";
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
</head>

<body>
    <style>
        body {
            overflow: hidden;
            background-color: lightgrey;
        }

        .bouwvlak {
            height: 55vh;
            width: 40vw;
            border-radius: 25px;
            background-color: rgb(94, 94, 94);
            margin-top: 10vh;
            margin-left: 30vw;
            text-align: center;
            padding-top: 5px;
        }

        .inlogvlak {
            height: 50vh;
            width: 38vw;
            border-radius: 25px;
            background-color: rgb(192, 190, 190);
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
        <div class="bouwvlak">
            INLOGGEN!!!!!!!!!!!!!!!!!!!!
            <div class="inlogvlak">
                <form method="post">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required />
                    <br>
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required />
                    <br>
                    <input type="submit" value="Submit" />
                </form>
                <div class="login-result">
                    <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        if ($result->num_rows > 0) {
                            // Login successful, redirect or perform other actions
                            echo "Login successful!";
                        } else {
                            // Login failed, handle accordingly
                            echo "Invalid username or password";
                        }
                    }
                    ?>
                </div>
            </div>
        </div>

    </body>

</html>
