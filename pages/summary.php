<!DOCTYPE html>
<html>
<head>
    <title>Beer Summary</title>
    <link rel="stylesheet" href="styles/home.css">
</head>
<body>
    <h2>Most Liked Beers</h2>
    <div class="bc-beer-container">
        <?php 
        include("db_conn.php");

        // Query to get the most liked beers
        $sql_most_liked = "SELECT * FROM beers ORDER BY like_count DESC LIMIT 5";
        $result_most_liked = $conn->query($sql_most_liked);

        if ($result_most_liked->num_rows > 0) {
            while($row = $result_most_liked->fetch_assoc()) {
                echo "<div class='bc-bier-kaart'>";
                echo "<p class='bc-kaart-name'>" . $row["name"] . "</p>";
                echo "<p class='bc-kaart-text'>Likes: " . $row["like_count"] . "</p>";
                echo "</div>";
            }
        } else {
            echo "No data available";
        }
        ?>
    </div>

    <h2>Highest Rated Beers</h2>
    <div class="bc-beer-container">
        <?php 
        // Query to get the highest rated beers
        $sql_highest_rated = "SELECT beers.*, AVG(ratings.rating) AS avg_rating FROM beers LEFT JOIN ratings ON beers.id = ratings.beer_id GROUP BY beers.id ORDER BY avg_rating DESC LIMIT 5";
        $result_highest_rated = $conn->query($sql_highest_rated);

        if ($result_highest_rated->num_rows > 0) {
            while($row = $result_highest_rated->fetch_assoc()) {
                echo "<div class='bc-bier-kaart'>";
                echo "<p class='bc-kaart-name'>" . $row["name"] . "</p>";
                echo "<p class='bc-kaart-text'>Average Rating: " . round($row["avg_rating"], 1) . "</p>";
                echo "</div>";
            }
        } else {
            echo "No data available";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
