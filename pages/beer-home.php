<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles/home.css">
</head>
<body>
    <img src="img/beer-background.png" alt="" class="bc-background">
    <?php 
        include("db_conn.php");

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['like'])) {
            // Het bier ID ophalen vanuit het verborgen veld
            $beer_id = $_POST['beer_id'];
        
            // Query om het aantal likes te verhogen
            $sql = "UPDATE beers SET like_count = like_count + 1 WHERE id = $beer_id";
        
            if ($conn->query($sql) === TRUE) {
                // Voeg JavaScript code toe om de popup weer te geven
                echo '<script>
                        // Functie om de popup weer te geven
                        function showPopup(message) {
                            // Maak een div element voor de popup
                            var popup = document.createElement("div");
                            popup.setAttribute("id", "popup");
                            popup.innerHTML = "<p>" + message + "</p>";
                            document.body.appendChild(popup);
        
                            // Verwijder de popup na 10 seconden
                            setTimeout(function() {
                                document.getElementById("popup").remove();
                            }, 10000);
                        }
        
                        // Roep de functie aan om de popup weer te geven met het bericht
                        showPopup("Beer liked!");
                      </script>';
            } else {
                echo "Fout bij het toevoegen van de like: " . $conn->error;
            }
        }
        // Function to generate a unique identifier
        function generateUniqueId() {
            return uniqid('user_', true); // You can customize the prefix as needed
        }

        // Check if the user has already liked the beer
        function hasLikedBeer($conn, $beer_id, $unique_id) {
            $sql = "SELECT * FROM likes WHERE beer_id = $beer_id AND unique_id = '$unique_id'";
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
                echo "<div class='bc-bier-kaart'>";
                echo "<p class='bc-kaart-name'>" . $row["name"] . "</p>";
                echo "<p class='bc-kaart-text' style='text-align: center; margin:0;'>" . $row["brewer"] . "</p>";
                echo "<p>Type: " . $row["type"] . "</p>";
                echo "<p>Yeast: " . $row["yeast"] . "</p>";
                echo "<p>Percentage: " . $row["perc"]. "%</p>";
                echo "<p>Prijs: " . $row["purchase_price"] . "</p>";
                echo "<p>Likes: " . $row["like_count"] . "</p>";
                echo "<form method='post' action='".$_SERVER['PHP_SELF']."'>";
                echo "<input type='hidden' name='beer_id' value='" . $row["id"] . "'>";
                // Check if the user has already liked the beer
                if (!hasLikedBeer($conn, $row["id"], $unique_id)) {
                    include('partials/like-button.html');
                } else {
                    echo "You liked this beer! <br>";
                    include('partials/dislike-button.html');
                }
                echo "</form>";
                echo "</div>";
                echo "<br>";
            }
        } else {
            echo "Geen bieren gevonden";
        }

        // Handle like button click
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['like'])) {
            // Check if the user has a unique identifier
            if (empty($unique_id)) {
                $unique_id = generateUniqueId();
                setcookie('unique_id', $unique_id, time() + (365 * 24 * 3600), '/'); // Cookie valid for 1 year
            }
            // Get beer_id from the form submission
            $beer_id = $_POST['beer_id'];
            // Save like to the database
            $sql = "INSERT INTO likes (beer_id, unique_id) VALUES ($beer_id, '$unique_id')";
            if ($conn->query($sql) === TRUE) {
                // Redirect to prevent form resubmission
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            } else {
                echo "Fout bij het toevoegen van de like: " . $conn->error;
            }
        }

        $conn->close();
    ?>
</body>
</html>
