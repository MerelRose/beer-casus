<!DOCTYh6E html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewh6ort" content="width=device-width, initial-scale=1.0">
  <title>Beer Site</title>
  <link rel="stylesheet" href="styles/beer.css">
  <link rel="stylesheet" href="styles/home.css">
</head>
<body>
<img src="img/beer-background.png" alt="" class="bc-background">
  <div class="container">
    <h1>Welcome to BierGram - Where Every Sip Tells a Story!</h1>
    <h5>Indulge your taste buds and explore the vast world of craft brews with us. Our platform isn't just about drinking beer; it's about experiencing the artistry and passion behind each brewer's creation.</h5>
    <h5>Discover new favorites, share your tasting adventures, and connect with fellow beer enthusiasts. From hoppy IPAs to rich stouts and crisp lagers, there's a world of flavor waiting to be explored.</h5>
    <h5>Join our community, rate the beers you've savored, and let your voice be heard. Every rating, every review adds to the collective knowledge of our vibrant beer-loving community.</h5>
    <h4>Cheers to great beer and even better company. Start your journey with us today!"</h4>
  </div>
<br>
  <section class="featured">
    <h1>Our Most Popular Beers!</h1>
    <div class="bc-beer-container">
        <?php 
        include("db_conn.php");

        // Query to get the most liked beers
        $sql_most_liked = "SELECT * FROM beers ORDER BY like_count DESC LIMIT 2";
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
                    // Display beer information
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
    </div>
  </section>
</body>
</html>
