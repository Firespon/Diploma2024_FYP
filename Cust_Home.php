<?php
  include("session.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="pictures/favicon.ico">
  <link rel="stylesheet" type="text/css" href="css/scrollbar.css">
  <title>Home</title>
</head>
<body>
  <nav>
    <?php include 'navbar/Cust_Navbar.html';?>
  </nav>
  <div>
    <?php include 'html/User/Home_Slideshow.html';?>
  </div>

  <br><br><br>

  <div>
    <?php include 'html/Customer/Cust_Home.html';?>
  </div>
</body>
</html>