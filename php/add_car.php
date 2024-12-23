<?php
if (isset($_POST['registerCarBtn'])) {
    include('conn.php');
    include('../session.php');

    // Get the email from the session
    $id = $_SESSION['mySession'];

    // Fetch the email using the user ID
    $emailQuery = "SELECT Email FROM user WHERE ID = '$id'";
    $emailResult = mysqli_query($con, $emailQuery);
    
    if (mysqli_num_rows($emailResult) > 0) {
        $emailRow = mysqli_fetch_assoc($emailResult);
        $email = $emailRow['Email'];
    } else {
        echo "<script>alert('User not found. Please try again.');window.location.href='../Cust_Profile.php';</script>";
        exit();
    }

    // Fetch the Customer_ID using the email
    $customerQuery = "SELECT Customer_ID FROM customer WHERE Email = '$email'";
    $customerResult = mysqli_query($con, $customerQuery);
    
    if (mysqli_num_rows($customerResult) > 0) {
        $customerRow = mysqli_fetch_assoc($customerResult);
        $customer_id = $customerRow['Customer_ID'];
    } else {
        echo "<script>alert('Customer not found. Please try again.');window.location.href='../Cust_Profile.php';</script>";
        exit();
    }

    // Validate form fields
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $registration_number = $_POST['registration_number'];
    $manufactured_year = $_POST['manufactured_year'];

    if (empty($brand) || empty($model) || empty($registration_number) || empty($manufactured_year)) {
        echo "<script>alert('Please fill in all the fields');window.location.href='../Cust_Profile.php';</script>";
        exit();
    }

    // Check if registration number already exists in the database
    $checkRegNumberSql = "SELECT * FROM car WHERE Registration_Number='$registration_number'";
    $result = mysqli_query($con, $checkRegNumberSql);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Registration number already exists. Please choose another one.');window.location.href='../Cust_Profile.php';</script>";
        exit();
    }

    // Insert car details into the car table
    $sql = "INSERT INTO car (Customer_ID, Brand, Model, Registration_Number, Year) VALUES ('$customer_id', '$brand', '$model', '$registration_number', '$manufactured_year')";

    // Execute the query
    if (!mysqli_query($con, $sql)) {
        die('Error: '. mysqli_error($con));
    } else {
        echo '<script>alert("Car successfully registered!"); window.location.href = "../Cust_Profile.php";</script>';
    }
    mysqli_close($con);
} else {
    echo "<script>alert('Please fill in all the fields');window.location.href='../Cust_Profile.php';</script>";
}
?>
