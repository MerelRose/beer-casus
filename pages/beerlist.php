<html>
<head>
    <link rel="stylesheet" href="styles/home.css">
    <style>
.checked {
    color: #ffc700; 
}

.star {
    font-size: 24px;
    color: #ccc; 
}

.colored-star {
    color: #ffc700; 
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
   session_start();
   include("db_conn.php");

   // Check if user is logged in
   if (!isset($_SESSION['user_id'])) {
       // Redirect user to the login page or show an error message
       header("Location: pages/loginform.php");
       exit(); // Stop executing the script
   }

   // Retrieve the user ID from the session
   $user_id = $_SESSION['user_id'];

// Function to check if the user has rated a particular beer
   function hasRatedBeer($conn, $beer_id, $user_id) {
       $sql = "SELECT * FROM ratings WHERE beer_id = ? AND user_id = ?";
       $stmt = $conn->prepare($sql);
       $stmt->bind_param("ii", $beer_id, $user_id);
       $stmt->execute();
       $result = $stmt->get_result();
       return $result->num_rows > 0;
   }
   
// Fetch beers from the API or database
$api_url = 'http://localhost:3000/api/beers';
$api_response = file_get_contents($api_url);
$beers = json_decode($api_response, true);
    

    foreach ($beers as $beer) {
        $beer_id = $beer["id"];
        $sql_avg_rating = "SELECT AVG(rating) AS avg_rating FROM ratings WHERE beer_id = $beer_id";
        $result_avg_rating = $conn->query($sql_avg_rating);
        $row_avg_rating = $result_avg_rating->fetch_assoc();
        $average_rating = $row_avg_rating['avg_rating'];

        echo "<div class='courses-container'>";
        echo "<div class='course'>";
        echo "<div class='course-preview'>";
        echo "<h6>Beer</h6>";
        echo "<h2>" . $beer["name"] . "</h2>";
        echo "</div>";
        echo "<div class='course-info'>";
        echo "<h6>Likes: " . $beer["like_count"] . " Average Rating: " . $average_rating .  "</h6>";
        echo "<h2>" . $beer["brewer"] . "</h2>";
        echo "<h6>Type: " . $beer["type"] . "</h6>";
        echo "<h6>Yeast: " . $beer["yeast"] . "</h6>";
        echo "<h6>Percentage: " . ($beer["perc"] * 100) . "%</h6>";
        echo "<h6>Prijs: &euro;" . $beer["purchase_price"] . "</h6>";
        echo "<form method='post' action='".$_SERVER['PHP_SELF']."'>";
        echo "<input type='hidden' name='beer_id' value='" . $beer["id"] . "'>";
        echo "<div class='bc-star-rating rate'>";
        if (isset($_SESSION['email'])) {
            for ($i = 5; $i >= 1; $i--) {
                $checked = (hasRatedBeer($conn, $beer["id"], $user_id) && $i == $average_rating) ? 'checked' : '';
                $checkedClass = ($checked == 'checked') ? 'checked' : '';
                echo "<label title='text' for='rating_" . $beer["id"] . "_" . $i . "' class='$checkedClass'>";
                echo "<input type='radio' id='rating_" . $beer["id"] . "_" . $i . "' name='rating' value='" . $i . "' " . $checked . " " . (hasRatedBeer($conn, $beer["id"], $user_id) ? "disabled" : "") . ">";
                echo "</label>";
            }
        }
        echo "</div>";
        echo "<button class='btn' type='submit' name='rate' " . (hasRatedBeer($conn, $beer["id"], $user_id) ? "disabled" : "") . ">Rate</button>";
        echo "</form>"; 
        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "<br>";
    }

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['rate'])) {
    $beer_id = $_POST['beer_id'];
    $rating = $_POST['rating'];

    // Check if the user has already rated the beer
        if (hasRatedBeer($conn, $beer_id, $user_id)) {
            // Update the existing rating
            $sql_update = "UPDATE ratings SET rating = ? WHERE beer_id = ? AND user_id = ?";
            $stmt = $conn->prepare($sql_update);
            $stmt->bind_param("iii", $rating, $beer_id, $user_id);
            if ($stmt->execute()) {
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            } else {
                echo "Error updating rating: " . $conn->error;
            }
        } else {
            // Insert a new rating
            $sql_insert = "INSERT INTO ratings (beer_id, user_id, rating) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql_insert);
            $stmt->bind_param("iii", $beer_id, $user_id, $rating);
            if ($stmt->execute()) {
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