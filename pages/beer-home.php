<html>
<head>
    <link rel="stylesheet" href="styles/home.css">
</head>
    <img src="img/beer-background.png" alt="" class="bc-background">
</html>
<?php 
    include("db_conn.php");

    // Query om alle bieren op te halen
    $sql = "SELECT * FROM beers";
    $result = $conn->query($sql);
    
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
    
    // Controleren of er resultaten zijn
    if ($result->num_rows > 0) {
        // Output van de gegevens van elk bier
        while($row = $result->fetch_assoc()) {
            echo "<div>";
            echo "Naam: " . $row["name"] . "<br>";
            echo "Brouwer: " . $row["brewer"] . "<br>";
            echo "Type: " . $row["type"] . "<br>";
            echo "Yeast: " . $row["yeast"] . "<br>";
            echo "Percentage: " . $row["perc"]. "%<br>";
            echo "Prijs: " . $row["purchase_price"] . "<br>";
            echo "Likes: " . $row["like_count"] . "<br>";
            echo "<form method='post' action='".$_SERVER['PHP_SELF']."'>";
            echo "<input type='hidden' name='beer_id' value='" . $row["id"] . "'>";
            // echo "<input type='submit' name='like' value='Like'>";
            include("partials/like-button.html");
            echo "</form>";
            echo "</div>";
            echo "<br>";
        }
    } else {
        echo "Geen bieren gevonden";
    }
    
    $conn->close();
    
    //ob_end_flush();
?>