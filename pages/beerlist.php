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


button.dislike {
    width: 30px;
    height: 30px;
    margin: 0 auto;
    line-height: 30px;
    border-radius: 50%;
    color: rgba(255, 82, 82, 1);
    background-color: rgba(255, 138, 128, 0.3);
    border-color: rgba(255, 82, 82, 1);
    border-width: 1px;
    font-size: 15px;
    position: relative;
    float: right;
    margin-right: 10px;
    cursor: pointer;
}

button.like {
    width: 30px;
    height: 30px;
    margin: 0 auto;
    line-height: 30px;
    border-radius: 50%;
    color: rgba(0, 150, 136, 1);
    background-color: rgba(38, 166, 154, 0.3);
    border-color: rgba(0, 150, 136, 1);
    border-width: 1px;
    font-size: 15px;
    position: relative;
    float: right;
    margin-right: 10px;
    cursor: pointer;
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


        function likeBeer($conn, $beer_id, $unique_id) {
            // Increment like count in the beers table
            $sql_increment = "UPDATE beers SET like_count = like_count + 1 WHERE id = $beer_id";
            $result_increment = $conn->query($sql_increment);
            if (!$result_increment) {
                echo "Error updating like count: " . $conn->error;
                return false;
            }
    
            // Insert like record into the likes table
            $sql_insert = "INSERT INTO likes (beer_id, unique_id) VALUES ($beer_id, '$unique_id')";
            $result_insert = $conn->query($sql_insert);
            if (!$result_insert) {
                echo "Error liking the beer: " . $conn->error;
                return false;
            }
    
            return true;
        }
    
        function dislikeBeer($conn, $beer_id, $unique_id) {
            // Decrement like count in the beers table
            $sql_decrement = "UPDATE beers SET like_count = like_count - 1 WHERE id = $beer_id";
            $result_decrement = $conn->query($sql_decrement);
            if (!$result_decrement) {
                echo "Error updating like count: " . $conn->error;
                return false;
            }
    
            // Delete like record from the likes table
            $sql_delete = "DELETE FROM likes WHERE beer_id = $beer_id AND unique_id = '$unique_id'";
            $result_delete = $conn->query($sql_delete);
            if (!$result_delete) {
                echo "Error disliking the beer: " . $conn->error;
                return false;
            }
    
            return true;
        }
    
        // Check if the user has a unique identifier cookie
        $unique_id = isset($_COOKIE['unique_id']) ? $_COOKIE['unique_id'] : '';
    
        // Handle like/dislike actions
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['like'])) {
                $beer_id = $_POST['beer_id'];
                if (!hasLikedBeer($conn, $beer_id, $unique_id)) {
                    if (likeBeer($conn, $beer_id, $unique_id)) {
                        // Successfully liked the beer
                        header("Location: ".$_SERVER['PHP_SELF']);
                        exit();
                    }
                }
            } elseif (isset($_POST['dislike'])) {
                $beer_id = $_POST['beer_id'];
                if (hasLikedBeer($conn, $beer_id, $unique_id)) {
                    if (dislikeBeer($conn, $beer_id, $unique_id)) {
                        // Successfully disliked the beer
                        header("Location: ".$_SERVER['PHP_SELF']);
                        exit();
                    }
                }
            }
        }


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

                echo "<div class='courses-container'>";
                        echo "<div class='course'>";
                            echo "<div class='course-preview'>";
                            echo "<h6>Beer</h6>";
                            echo "<h2>" . $row["name"] . "</h2>";
                            echo "</div>";
                        echo "<div class='course-info'>";
                            // Display like button
                            echo "<form method='post' action='".$_SERVER['PHP_SELF']."'>";
                            echo "<input type='hidden' name='beer_id' value='" . $row["id"] . "'>";
                            if (!hasLikedBeer($conn, $row["id"], $unique_id)) {
                                echo "<button class='like' type='submit' name='like' value='Like'> 
                                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-hand-thumbs-up' viewBox='0 0 16 16'>
                                        <path d='M8.864.046C7.908-.193 7.02.53 6.956 1.466c-.072 1.051-.23 2.016-.428 2.59-.125.36-.479 1.013-1.04 1.639-.557.623-1.282 1.178-2.131 1.41C2.685 7.288 2 7.87 2 8.72v4.001c0 .845.682 1.464 1.448 1.545 1.07.114 1.564.415 2.068.723l.048.03c.272.165.578.348.97.484.397.136.861.217 1.466.217h3.5c.937 0 1.599-.477 1.934-1.064a1.86 1.86 0 0 0 .254-.912c0-.152-.023-.312-.077-.464.201-.263.38-.578.488-.901.11-.33.172-.762.004-1.149.069-.13.12-.269.159-.403.077-.27.113-.568.113-.857 0-.288-.036-.585-.113-.856a2 2 0 0 0-.138-.362 1.9 1.9 0 0 0 .234-1.734c-.206-.592-.682-1.1-1.2-1.272-.847-.282-1.803-.276-2.516-.211a10 10 0 0 0-.443.05 9.4 9.4 0 0 0-.062-4.509A1.38 1.38 0 0 0 9.125.111zM11.5 14.721H8c-.51 0-.863-.069-1.14-.164-.281-.097-.506-.228-.776-.393l-.04-.024c-.555-.339-1.198-.731-2.49-.868-.333-.036-.554-.29-.554-.55V8.72c0-.254.226-.543.62-.65 1.095-.3 1.977-.996 2.614-1.708.635-.71 1.064-1.475 1.238-1.978.243-.7.407-1.768.482-2.85.025-.362.36-.594.667-.518l.262.066c.16.04.258.143.288.255a8.34 8.34 0 0 1-.145 4.725.5.5 0 0 0 .595.644l.003-.001.014-.003.058-.014a9 9 0 0 1 1.036-.157c.663-.06 1.457-.054 2.11.164.175.058.45.3.57.65.107.308.087.67-.266 1.022l-.353.353.353.354c.043.043.105.141.154.315.048.167.075.37.075.581 0 .212-.027.414-.075.582-.05.174-.111.272-.154.315l-.353.353.353.354c.047.047.109.177.005.488a2.2 2.2 0 0 1-.505.805l-.353.353.353.354c.006.005.041.05.041.17a.9.9 0 0 1-.121.416c-.165.288-.503.56-1.066.56z'/>
                                    </svg>
                                    </button>";
                            } else {
                                //echo "<p class='bc-like-msg'>You liked this beer!</p> <br>";
                                echo "<button class='dislike' type='submit' name='dislike'>
                                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-hand-thumbs-down' viewBox='0 0 16 16'>
                                            <path d='M8.864 15.674c-.956.24-1.843-.484-1.908-1.42-.072-1.05-.23-2.015-.428-2.59-.125-.36-.479-1.012-1.04-1.638-.557-.624-1.282-1.179-2.131-1.41C2.685 8.432 2 7.85 2 7V3c0-.845.682-1.464 1.448-1.546 1.07-.113 1.564-.415 2.068-.723l.048-.029c.272-.166.578-.349.97-.484C6.931.08 7.395 0 8 0h3.5c.937 0 1.599.478 1.934 1.064.164.287.254.607.254.913 0 .152-.023.312-.077.464.201.262.38.577.488.9.11.33.172.762.004 1.15.069.13.12.268.159.403.077.27.113.567.113.856s-.036.586-.113.856c-.035.12-.08.244-.138.363.394.571.418 1.2.234 1.733-.206.592-.682 1.1-1.2 1.272-.847.283-1.803.276-2.516.211a10 10 0 0 1-.443-.05 9.36 9.36 0 0 1-.062 4.51c-.138.508-.55.848-1.012.964zM11.5 1H8c-.51 0-.863.068-1.14.163-.281.097-.506.229-.776.393l-.04.025c-.555.338-1.198.73-2.49.868-.333.035-.554.29-.554.55V7c0 .255.226.543.62.65 1.095.3 1.977.997 2.614 1.709.635.71 1.064 1.475 1.238 1.977.243.7.407 1.768.482 2.85.025.362.36.595.667.518l.262-.065c.16-.04.258-.144.288-.255a8.34 8.34 0 0 0-.145-4.726.5.5 0 0 1 .595-.643h.003l.014.004.058.013a9 9 0 0 0 1.036.157c.663.06 1.457.054 2.11-.163.175-.059.45-.301.57-.651.107-.308.087-.67-.266-1.021L12.793 7l.353-.354c.043-.042.105-.14.154-.315.048-.167.075-.37.075-.581s-.027-.414-.075-.581c-.05-.174-.111-.273-.154-.315l-.353-.354.353-.354c.047-.047.109-.176.005-.488a2.2 2.2 0 0 0-.505-.804l-.353-.354.353-.354c.006-.005.041-.05.041-.17a.9.9 0 0 0-.121-.415C12.4 1.272 12.063 1 11.5 1'/>
                                        </svg>
                                    </button>";
                            }
                            echo "</form>";
                            // Display beer information
                            echo "<h6>Likes:" . $row["like_count"] ."</h6>";
                            echo "<h2>" . $row["brewer"] . "</h2>";
                            echo "<h6>Type: " . $row["type"] . "</h6>";
                            echo "<h6>Yeast: " . $row["yeast"] . "</h6>";
                            echo "<h6>Percentage:" . $row["perc"]. "%</h6>";
                            echo "<h6>Prijs: " . $row["purchase_price"] . "</p>";
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
                            echo "<button class='btn' type='submit' name='rate' " . (hasRatedBeer($conn, $row["id"], $unique_id) ? "disabled" : "") . ">Rate</button>";
                            echo "</form>"; 
                        echo "</div>";
                    echo "</div>";
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
