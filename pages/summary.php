<!DOCTYPE html>
<html>
<head>
    <title>Beer Summary</title>
    <link rel="stylesheet" href="styles/home.css">
</head>
<body>
<img src="img/beer-background.png" alt="" class="bc-background">
    <h2 class="bc-top5">Top 5 Liked Beers</h2>
    <div class="bc-beer-container">
        <?php 
        include("db_conn.php");

        $sql_most_liked = "SELECT * FROM beers ORDER BY like_count DESC LIMIT 10";
        $result_most_liked = $conn->query($sql_most_liked);

        if ($result_most_liked->num_rows > 0) {
            while($row = $result_most_liked->fetch_assoc()) {
                echo "<div class='courses-container'>";
                echo "<div class='course'>";
                    echo "<div class='course-preview'>";
                    echo "<h6>Beer</h6>";
                    echo "<h2>" . $row["name"] . "</h2>";
                    echo "</div>";
                echo "<div class='course-info'>";
                    echo "<h6>Likes:" . $row["like_count"] ."</h6>";
                    echo "<h2>" . $row["brewer"] . "</h2>";
                    echo "<h6>Type: " . $row["type"] . "</h6>";
                    echo "<h6>Yeast: " . $row["yeast"] . "</h6>";
                    echo "<h6>Percentage:" . $row["perc"]. "%</h6>";
                    echo "<h6>Prijs: " . $row["purchase_price"] . "</p>";
                echo "</div>";
            echo "</div>";
        echo "</div>";
            }
        } else {
            echo "No data available";
        }
        ?>
    </div>

    <h2 class="bc-top5">Top 5 Rated Beers</h2>
    <div class="bc-beer-container">
        <?php 
        $sql_highest_rated = "SELECT beers.*, AVG(ratings.rating) AS avg_rating FROM beers LEFT JOIN ratings ON beers.id = ratings.beer_id GROUP BY beers.id ORDER BY avg_rating DESC LIMIT 10";
        $result_highest_rated = $conn->query($sql_highest_rated);

        if ($result_highest_rated->num_rows > 0) {
            while($row = $result_highest_rated->fetch_assoc()) {
                echo "<div class='courses-container'>";
                echo "<div class='course'>";
                    echo "<div class='course-preview'>";
                    echo "<h6>Beer</h6>";
                    echo "<h2>" . $row["name"] . "</h2>";
                    echo "</div>";
                echo "<div class='course-info'>";
                    echo "<h6>Likes:" . $row["like_count"] ."</h6>";
                    echo "<h2>" . $row["brewer"] . "</h2>";
                    echo "<h6>Type: " . $row["type"] . "</h6>";
                    echo "<h6>Yeast: " . $row["yeast"] . "</h6>";
                    echo "<h6>Percentage:" . $row["perc"]. "%</h6>";
                    echo "<h6>Prijs: " . $row["purchase_price"] . "</p>";
                    echo "<h6 class='bc-kaart-text'>Average Rating: " . round($row["avg_rating"], 1) . "</h6>";
                echo "</div>";
            echo "</div>";
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
