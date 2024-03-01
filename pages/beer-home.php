<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles/home.css">
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

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['like'])) {

            if (empty($unique_id)) {
                $unique_id = generateUniqueId();
                setcookie('unique_id', $unique_id, time() + (365 * 24 * 3600), '/'); // Cookie valid for 1 year
            }

            $beer_id = $_POST['beer_id'];

            $sql = "INSERT INTO likes (beer_id, unique_id) VALUES ($beer_id, '$unique_id')";
            if ($conn->query($sql) === TRUE) {
                $sql_update = "UPDATE beers SET like_count = like_count + 1 WHERE id = $beer_id";
                $conn->query($sql_update);
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            } else {
                echo "Fout bij het toevoegen van de like: " . $conn->error;
            }
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['dislike'])) {
            $beer_id = $_POST['beer_id'];

            $unique_id = isset($_COOKIE['unique_id']) ? $_COOKIE['unique_id'] : '';

            $sql_delete = "DELETE FROM likes WHERE beer_id = $beer_id AND unique_id = '$unique_id'";
            if ($conn->query($sql_delete) === TRUE) {

                $sql_update = "UPDATE beers SET like_count = like_count - 1 WHERE id = $beer_id";
                $conn->query($sql_update);
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            } else {
                echo "Fout bij het verwijderen van de like: " . $conn->error;
            }
        }

        $conn->close();
    ?>
    </div>
</body>
</html>
