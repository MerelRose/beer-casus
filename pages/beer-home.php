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
            // Refresh de pagina om de nieuwe like count te zien
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Fout bij het toevoegen van de like: " . $conn->error;
        }
    }
    
    // Controleren of er resultaten zijn
    if ($result->num_rows > 0) {
        // Output van de gegevens van elk bier
        while($row = $result->fetch_assoc()) {
            echo "Naam: " . $row["name"]. " - Brouwer: " . $row["brewer"]. " - Type: " . $row["type"]. " - Yeast: " . $row["yeast"]. " - Percentage: " . $row["perc"]. "% - Prijs: " . $row["purchase_price"]. " - Likes: " . $row["like_count"];
            echo "<form method='post' action='".$_SERVER['PHP_SELF']."'>";
            echo "<input type='hidden' name='beer_id' value='" . $row["id"] . "'>";
            echo "<input type='submit' name='like' value='Like'>";
            include("partials/like-button.html");
            echo "</form>";
            echo "<br>";
        }
    } else {
        echo "Geen bieren gevonden";
    }
    
    $conn->close();
    
    ob_end_flush();
?>