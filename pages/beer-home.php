<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles/home.css">
    <style>
.checked {
    color: #ffc700; /* Color for checked stars */
}

.star {
    font-size: 24px; /* Adjust font size as needed */
    color: #ccc; /* Default color for uncolored stars */
}

.colored-star {
    color: #ffc700; /* Color for colored stars */
}
.rate {
    float: left;
    height: 46px;
    padding: 0 10px;
}
.rate:not(:checked) > input {
    position:absolute;
    top:-9999px;
}
.rate:not(:checked) > label {
    float:right;
    width:1em;
    overflow:hidden;
    white-space:nowrap;
    cursor:pointer;
    font-size:30px;
    color:#ccc;
}
.rate:not(:checked) > label:before {
    content: '★ ';
}
.rate > input:checked ~ label {
    color: #ffc700;    
}
.rate:not(:checked) > label:hover,
.rate:not(:checked) > label:hover ~ label {
    color: #deb217;  
}
.rate > input:checked + label:hover,
.rate > input:checked + label:hover ~ label,
.rate > input:checked ~ label:hover,
.rate > input:checked ~ label:hover ~ label,
.rate > label:hover ~ input:checked ~ label {
    color: #c59b08;
}
    </style>
</head>
<body>
    <img src="img/beer-background.png" alt="" class="bc-background">
    <div class="bc-beer-container">
    <?php 
        include("db_conn.php");

        function generateUniqueId() {
            return uniqid('user_', true);
        }

        function hasLikedBeer($conn, $beer_id, $unique_id) {
            $sql = "SELECT * FROM likes WHERE beer_id = $beer_id AND unique_id = '$unique_id'";
            $result = $conn->query($sql);
            return $result->num_rows > 0;
        }

        function hasRatedBeer($conn, $beer_id, $unique_id) {
            $sql = "SELECT * FROM ratings WHERE beer_id = $beer_id AND unique_id = '$unique_id'";
            $result = $conn->query($sql);
            return $result->num_rows > 0;
        }

        // Check if the user has a unique identifier cookie
        $unique_id = isset($_COOKIE['unique_id']) ? $_COOKIE['unique_id'] : '';

        // Query om alle bieren op te halen
        $sql = "SELECT * FROM beers";
        $result = $conn->query($sql);

        // Controleren of er resultaten zijn
        if ($result->num_rows > 0) {
            // Output van de gegevens van elk bier
            while($row = $result->fetch_assoc()) {
                // Calculate the average rating and total number of ratings
                $beer_id = $row["id"];
                $sql_rating = "SELECT AVG(rating) AS average_rating, COUNT(*) AS total_ratings FROM ratings WHERE beer_id = $beer_id";
                $result_rating = $conn->query($sql_rating);
                $row_rating = $result_rating->fetch_assoc();
                $average_rating = isset($row_rating["average_rating"]) ? round($row_rating["average_rating"], 1) : 0.0;
                $total_ratings = $row_rating["total_ratings"];

                echo "<div class='bc-bier-kaart'>";
                echo "<p class='bc-kaart-name'>" . $row["name"] . "</p>";
                echo "<p class='bc-kaart-text' style='margin:0;'>" . $row["brewer"] . "</p>";
                echo "<p class='bc-kaart-text2'>Type: " . $row["type"] . "</p>";
                echo "<p class='bc-kaart-text2'>Yeast: " . $row["yeast"] . "</p>";
                echo "<p class='bc-kaart-text2'>Percentage: " . $row["perc"]. "%</p>";
                echo "<p class='bc-kaart-text2'>Prijs: " . $row["purchase_price"] . "</p>";
                echo "<p class='bc-kaart-likes'>Likes: " . $row["like_count"] . "</p>";


                // Display star rating system
                echo "<form method='post' action='".$_SERVER['PHP_SELF']."'>";
                echo "<input type='hidden' name='beer_id' value='" . $row["id"] . "'>";
                echo "<div class='bc-star-rating rate'>";
                for ($i = 5; $i >= 1; $i--) {
                    $checked = (hasRatedBeer($conn, $row["id"], $unique_id) && $i == $average_rating) ? 'checked' : '';
                    $checkedClass = ($checked == 'checked') ? 'checked' : ''; // Add 'checked' class if the radio button is checked
                    echo "<label title='text' for='rating_" . $row["id"] . "_" . $i . "' class='$checkedClass'>";
                    echo "<input type='radio' id='rating_" . $row["id"] . "_" . $i . "' name='rating' value='" . $i . "' " . $checked . " " . (hasRatedBeer($conn, $row["id"], $unique_id) ? "disabled" : "") . ">";
                    echo "</label>";
                }
                echo "</div>";
                echo "<button class='bc-rate' type='submit' name='rate' " . (hasRatedBeer($conn, $row["id"], $unique_id) ? "disabled" : "") . ">Rate</button>";
                echo "</form>";

                
                // Display like button
                echo "<form method='post' action='".$_SERVER['PHP_SELF']."'>";
                echo "<input type='hidden' name='beer_id' value='" . $row["id"] . "'>";
                if (!hasLikedBeer($conn, $row["id"], $unique_id)) {
                    include('partials/like-button.html');
                } else {
                    echo "<p class='bc-like-msg'>You liked this beer!</p> <br>";
                    include('partials/dislike-button.html');
                }
                echo "</form>";
                echo "</div>";
                echo "<br>";
            }
        } else {
            echo "Geen bieren gevonden";
        }
        
// Handle rating submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['rate'])) {
    $beer_id = $_POST['beer_id'];
    $rating = $_POST['rating'];

    // Check if the user has a unique identifier
    if (empty($unique_id)) {
        $unique_id = generateUniqueId();
        setcookie('unique_id', $unique_id, time() + (365 * 24 * 3600), '/'); // Cookie valid for 1 year
    }

    // Check if the user has already rated the beer
    if (hasRatedBeer($conn, $beer_id, $unique_id)) {
        // Update the existing rating in the database
        $sql_update = "UPDATE ratings SET rating = $rating WHERE beer_id = $beer_id AND unique_id = '$unique_id'";
        if ($conn->query($sql_update) === TRUE) {
            // Refresh the page to update the ratings
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Error updating rating: " . $conn->error;
        }
    } else {
        // Insert the new rating into the database
        $sql_insert = "INSERT INTO ratings (beer_id, unique_id, rating) VALUES ($beer_id, '$unique_id', $rating)";
        if ($conn->query($sql_insert) === TRUE) {
            // Refresh the page to update the ratings
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Error inserting rating: " . $conn->error;
        }
    }
}


        $conn->close();
    ?>
    </div>
</body>
</html>
