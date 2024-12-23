<?php
include ('session.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['saveChanges'])) {
    include('php/conn.php');

    // Check if all required fields are filled
    if (empty($_POST['firstName']) || empty($_POST['lastName']) || empty($_POST['dateEmployed']) || 
        empty($_POST['phoneNumber']) || empty($_POST['email']) || empty($_POST['address']) || 
        empty($_POST['hoursWorked'])) {
        
        echo "<script>alert('Please fill in all the fields.');window.location.href='Management_AddStaff.php';</script>";
        exit();
    }

    // Validate email format
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format. Please try again.');window.location.href='Management_AddStaff.php';</script>";
        exit();
    }

    // Validate phone number format
    $phoneNumber = $_POST['phoneNumber'];
    if (!preg_match('/^01\d{8,9}$/', $phoneNumber)) {
        echo "<script>alert('Phone number must start with 01 and be followed by exactly 8 or 9 digits.');window.location.href='Management_AddStaff.php';</script>";
        exit();
    }

    // Validate hours worked
    $hoursWorked = $_POST['hoursWorked'];
    if (!is_numeric($hoursWorked) || $hoursWorked >= 56) {
        echo "<script>alert('Hours worked must be a number less than 56.');window.location.href='Management_AddStaff.php';</script>";
        exit();
    }

    // Combine first name and last name
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $name = $firstName . ' ' . $lastName;
    $dateEmployed = $_POST['dateEmployed'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    // Check if email already exists in the User table
    $checkEmailSql = "SELECT * FROM user WHERE Email='$email'";
    $result = mysqli_query($con, $checkEmailSql);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Email already exists. Please choose another one.');window.location.href='Management_AddStaff.php';</script>";
        exit();
    }

    // Insert user details into the User table
    $sqlUser = "INSERT INTO user (Name, Email, Phone_Number, Password, Role) VALUES ('$name', '$email', '$phoneNumber', 'Staff123', 'Staff')";

    // Execute the query
    if (!mysqli_query($con, $sqlUser)) {
        die('Error: ' . mysqli_error($con));
    } else {
        

        // Insert user details into the Staff table
        $sqlStaff = "INSERT INTO staff ( Date_Employed, Email, Address, Hours_Worked) VALUES ( '$dateEmployed', '$email', '$address', '$hoursWorked')";

        // Execute the query
        if (!mysqli_query($con, $sqlStaff)) {
            // Rollback the User table insert if the Staff table insert fails
            mysqli_query($con, "DELETE FROM user WHERE Email='$email'");
            die('Error: ' . mysqli_error($con));
        } else {
            echo '<script>alert("Successfully Registered Staff!"); window.location.href = "Management_AddStaff.php";</script>';
        }
    }

    mysqli_close($con);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Staff</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/scrollbar.css">
    <link rel="icon" type="image/x-icon" href="pictures/favicon.ico">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            height: max-content;
        }
        .container {
            width: 50%;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            height: 650px;
        }
        h1 {
            text-align: center;
        }
        .back-button {
            font-size: 16px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            cursor: pointer;
        }
        .back-button i {
            margin-right: 10px;
        }
        .form-group {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .form-group label {
            flex: 1;
            margin-right: 10px;
        }
        .form-group input {
            flex: 2;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .buttons {
            display: block;
            justify-content: center;
        }
        .buttons button {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            background-color: #4caf50;
            color: white;
            float: right;
        }
    </style>
</head>
<body>
    <nav>
      <?php include 'navbar/Management_Navbar.html'; ?>
    </nav>
    <br>
    <div class="container">
        <div class="back-button" onclick="goBack()">
            <i class="fa fa-arrow-left"></i> Back
        </div>
        <h1>Add Staff</h1>
        <br>
        <form id="addUserForm" method="POST" action="">
            <div class="form-group">
                <label for="firstName">First Name</label>
                <input type="text" id="firstName" name="firstName" value="" required autocomplete="off">
            </div>
            <div class="form-group">
                <label for="lastName">Last Name</label>
                <input type="text" id="lastName" name="lastName" value="" required autocomplete="off">
            </div>
            <div class="form-group">
                <label for="dateEmployed">Date Employed</label>
                <input type="date" id="dateEmployed" name="dateEmployed" value="" required autocomplete="off">
            </div>
            <div class="form-group">
                <label for="phoneNumber">Phone Number</label>
                <input type="text" id="phoneNumber" name="phoneNumber" value=""required autocomplete="off">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value=""required autocomplete="off">
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" value=""required autocomplete="off">
            </div>
            <div class="form-group">
                <label for="hoursWorked">Hours Worked (Weekly)</label>
                <input type="text" id="hoursWorked" name="hoursWorked" value=""required autocomplete="off">
            </div>
            <div class="buttons">
                <button type="submit" name="saveChanges">Create Staff</button>
            </div>
        </form>
    </div><br><br>
    <script>
        function goBack() {
            window.location.href = "Management_ManageStaff.php";
        }
    </script>
    
    
</body>
</html>
