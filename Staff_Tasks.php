<?php
  include("session.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="css/scrollbar.css">
  <link rel="icon" type="image/x-icon" href="pictures/favicon.ico">
  <title>Tasks</title>
</head>
<body>
  <nav>
    <?php include 'navbar/Staff_Navbar.html';?>
  </nav>
  <br>
  <div>
    <?php include 'html/Staff/Tasks.php';?>
  </div>
  
</body>
</html>