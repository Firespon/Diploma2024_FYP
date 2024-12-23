<?php 
include ('session.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Service Management System</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="css/scrollbar.css">
  <link rel="icon" type="image/x-icon" href="pictures/favicon.ico">
</head>
<style>
    body {
        font-family: 'Poppins', sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f4f9;
        height: max-content;      
    }
  
  
</style>
<body>
    <nav>
      <?php include 'navbar/Management_Navbar.html';?>
    </nav>
    <br>
    <div>
      <?php include 'html/Management/ManageStaff.php';?>
    </div>
    
  
</body>
</html>