<?php
include('session.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="pictures/favicon.ico">
  <link rel="stylesheet" type="text/css" href="css/scrollbar.css">
  <title>View Appointment</title>
</head>
<body>
  <nav>
    <?php include 'navbar/Cust_Navbar.html';?>
  </nav>
  <br>
  <section>
    <?php include 'html/Customer/View_Appointment.php';?>
  </section>
</body>
</html>