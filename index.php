<?php 
  $page = "beer-home";
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
            <?php include("pages/" .$page. ".html"); ?>
        </div>
    </body>
</html>