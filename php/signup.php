<?php
if (isset($_POST['signupBtn'])) {
    include('conn.php');

    // Validate email format
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format. Please try again.');window.location.href='Register.php';</script>";
        exit();
    }

    // Check if passwords match
    if ($_POST['password']!= $_POST['confirm_password']) {
        echo "<script>alert('Passwords do not match. Please try again.');window.location.href='Register.php';</script>";
        exit();
    }

    // Check for minimum password length and complexity
    if (strlen($_POST['password']) < 6 ||!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,}$/', $_POST['password'])) {
        echo "<script>alert('Password must contain at least 6 characters, including a mix of upper and lower case letters and numbers.');window.location.href='Register.php';</script>";
        exit();
    }

    // Validate phone number format
    $phoneNumber = $_POST['phone_number'];
    if (!preg_match('/^01\d{8,9}$/', $phoneNumber)) {
        echo "<script>alert('Phone number must start with 01 and be followed by exactly 8 or 9 digits.');window.location.href='Register.php';</script>";
        exit();
    }
    

    // Prepare variables for database insertion
    $name = $_POST['full_name'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phone_number'];
    $password = $_POST['password'];

    // Check if email already exists in the database
    $checkEmailSql = "SELECT * FROM user WHERE Email='$email'";
    $result = mysqli_query($con, $checkEmailSql);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Email already exists. Please choose another one.');window.location.href='Register.php';</script>";
        exit();
    }

    // Insert user details into the user table
    $sql = "INSERT INTO user (Name, Email, Phone_Number, Password, Role) VALUES ('$name', '$email', '$phoneNumber', '$password', 'Customer')";

    // Execute the query
    if (!mysqli_query($con, $sql)) {
        die('Error: '. mysqli_error($con));
    } else {
        // Assuming you want to insert the email into the customer table after successful registration
        $insertCustomerSql = "INSERT INTO customer (Email) VALUES ('$email')";
        if (!mysqli_query($con, $insertCustomerSql)) {
            echo "Failed to add customer.";
        } else {
            echo '<script>alert("Successfully Signed Up!"); window.location.href = "Register.php";</script>';
        }
    }
    mysqli_close($con);
} else {
    echo "<script>alert('Please fill in all the fields');window.location.href='Register.php';</script>";
}
