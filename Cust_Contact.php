<?php
  include("session.php");

  if (isset($_POST['sendfeedbackBtn'])) {
    include('php/conn.php');

    $name = htmlspecialchars($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars($_POST['message']);
    $mood = htmlspecialchars($_POST['mood']); // Get the mood value from the hidden input field

    // Basic validation
    if(empty($name) || empty($email) || empty($message) || empty($mood)) {
      echo "<script>alert('Please fill in all the fields');window.location.href='Cust_Contact.php';</script>";
      exit;
    }

    // Validate email format
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      echo "<script>alert('Invalid email format');window.location.href='Cust_Contact.php';</script>";
      exit;
    }

    $sql = "INSERT INTO contact_us (Name, Email, Feedback, Mood) VALUES ('$name', '$email', '$message', '$mood')";

    if (!mysqli_query($con, $sql)) {
      die('Error: '. mysqli_error($con));
    } else {
      echo "<script>alert('Feedback has been sent successfully');</script>";
    }
    mysqli_close($con);
  }
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="pictures/favicon.ico">
  <title>Contact Us</title>
</head>
<body>
  <nav>
    <?php include 'navbar/Cust_Navbar.html';?>
  </nav>
  <div>
    <?php include 'html/User/Contact.php';?>
  </div>
</body>
</html>