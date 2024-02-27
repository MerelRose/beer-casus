<?php 
  $page = "beer-home";
  if(isset($_GET["page"])) {
      $page = $_GET["page"];
  }
?>
<html>
    <head>
        <title>We Like Beer</title>
    </head>
    <body>
            <? include("beer-nav.php"); ?>
        <div class="main">
            <?php include("pages/" .$page. ".html"); ?>
        </div>
    </body>
</html>