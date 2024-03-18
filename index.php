<?php 
ob_start(); // Start output buffering
$page = "beerhome";
if(isset($_GET["page"])) {
    $page = $_GET["page"];
}
?>
<!DOCTYPE html>
<head>
    <title>BierGram</title>
</head>
<body>
    <?php include("beer-nav.php"); ?>
    <div class="main">
        <?php include("pages/" .$page. ".php"); ?>
    </div>
</body>
</html>