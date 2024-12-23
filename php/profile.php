<?php
include('conn.php');
include('../session.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateProfile'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phoneNumber'];
    $userID = $_SESSION['mySession'];

    // Retrieve the current email
    $sql = "SELECT Email FROM user WHERE ID = $userID";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $currentEmail = $row['Email'];

    // Check if the new email is already in use by another user
    $checkEmailSql = "SELECT ID FROM user WHERE Email='$email' AND ID != '$userID'";
    $checkEmailResult = mysqli_query($con, $checkEmailSql);

    if (mysqli_num_rows($checkEmailResult) > 0) {
        echo "<script>alert('Email is already in use. Please try another email.');window.location.href='../Cust_Profile.php';</script>";
        exit();
    } else {
        // Disable foreign key checks to allow the update
        mysqli_query($con, "SET FOREIGN_KEY_CHECKS=0");

        // Update user information
        $updateSql = "UPDATE user SET Name='$name', Email='$email', Phone_Number='$phoneNumber' WHERE ID='$userID'";
        $updateEmailSql = "UPDATE customer SET Email='$email' WHERE Email='$currentEmail'";

        if (mysqli_query($con, $updateSql) && mysqli_query($con, $updateEmailSql)) {
            // Re-enable foreign key checks after the update
            mysqli_query($con, "SET FOREIGN_KEY_CHECKS=1");
            echo "<script>alert('Profile updated successfully.');window.location.href='../Cust_Profile.php';</script>";
            exit();
        } else {
            // Re-enable foreign key checks in case of an error
            mysqli_query($con, "SET FOREIGN_KEY_CHECKS=1");
            echo "<script>alert('Error updating profile. Please try again.');window.location.href='../Cust_Profile.php';</script>";
            exit();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['changePassword'])) {
    if ($_POST['newPassword'] != $_POST['newConfirmPassword']) {
        echo "<script>alert('Passwords do not match. Please try again.');window.location.href='../Cust_Profile.php';</script>";
        exit();
    }

    if (strlen($_POST['newPassword']) < 6 || !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,}$/', $_POST['newPassword'])) {
        echo "<script>alert('Password must contain at least 6 characters, including a mix of upper and lower case letters and numbers.');window.location.href='../Cust_Profile.php';</script>";
        exit();
    }

    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $userID = $_SESSION['mySession'];

    // Retrieve the current password from the database
    $getPasswordSql = "SELECT Password FROM user WHERE ID = $userID";
    $getPasswordResult = mysqli_query($con, $getPasswordSql);
    $passwordRow = mysqli_fetch_assoc($getPasswordResult);
    $currentPasswordDB = $passwordRow['Password'];

    // Compare the entered current password with the stored password
    if ($currentPassword === $currentPasswordDB) {
        $updatePasswordSql = "UPDATE user SET Password='$newPassword' WHERE ID='$userID'";
        if (mysqli_query($con, $updatePasswordSql)) {
            echo "<script>alert('Password updated successfully.');window.location.href='../Cust_Profile.php';</script>";
        } else {
            echo "<script>alert('Error updating password. Please try again.');window.location.href='../Cust_Profile.php';</script>";
        }
    } else {
        echo "<script>alert('Incorrect current password.');window.location.href='../Cust_Profile.php';</script>";
    }
}
?>
