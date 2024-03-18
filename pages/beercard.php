<style>
.course {
	background-color: #fff;
	border-radius: 10px;
	box-shadow: 0 10px 10px rgba(0, 0, 0, 0.2);
	display: flex;
	max-width: 100%;
	margin: 20px;
	overflow: hidden;
	width: 700px;
}

.course h6 {
	opacity: 0.6;
	margin: 0;
	letter-spacing: 1px;
	text-transform: uppercase;
}

.course h2 {
	letter-spacing: 1px;
	margin: 10px 0;
}

.course-preview {
	background-color: #2A265F;
	color: #fff;
	padding: 30px;
	max-width: 250px;
}

.course-info {
	padding: 30px;
	position: relative;
	width: 100%;
}

.btn {
	background-color: #2A265F;
	border: 0;
	border-radius: 50px;
	box-shadow: 0 10px 10px rgba(0, 0, 0, 0.2);
	color: #fff;
	font-size: 16px;
	padding: 12px 25px;
	position: absolute;
	bottom: 30px;
	right: 30px;
	letter-spacing: 1px;
}
</style>
<div class="courses-container">
	<div class="course">
		<div class="course-preview">
			<h6>Beer</h6>
			<h2>Beer Name</h2>
		</div>
		<div class="course-info">
			<h6>Likes:</h6>
			<h2>Brewer Name</h2>
			<h6>Type:</h6>
			<h6>Yeast:</h6>
			<h6>Percentage:</h6>
			<h6>Price:</h6>
			<button class="btn">rate</button>
		</div>
	</div>
</div>
<?php
        include("db_conn.php");
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
                            echo "<button class='btn'>rate</button>";
                        echo "</div>";
                    echo "</div>";
                echo "</div>";

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