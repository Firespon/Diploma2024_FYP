<?php
include("session.php");
include('php/conn.php');

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Fetch user data from the database based on the ID
    $query = "SELECT ID, Name, Email, Phone_Number FROM user WHERE ID = '$userId'";
    $result = mysqli_query($con, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $userRow = mysqli_fetch_assoc($result);
    } else {
        die("User not found.");
    }
} else {
    die("Invalid request.");
}

// Handle form submission to update user data
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_user'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phoneNumber'];
    $userID = $_GET['id'];

    // Retrieve the current email
    $sql = "SELECT Email FROM user WHERE ID = $userID";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $currentEmail = $row['Email'];

    // Check if the new email is already in use by another user
    $checkEmailSql = "SELECT ID FROM user WHERE Email='$email' AND ID != '$userID'";
    $checkEmailResult = mysqli_query($con, $checkEmailSql);

    if (mysqli_num_rows($checkEmailResult) > 0) {
        echo "<script>alert('Email is already in use. Please try another email.');window.location.href='Admin_EditUser.php?id=$userID';</script>";
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
            echo "<script>alert('User updated successfully.');window.location.href='Admin_ManageUser.php';</script>";
            exit();
        } else {
            // Re-enable foreign key checks in case of an error
            mysqli_query($con, "SET FOREIGN_KEY_CHECKS=1");
            echo "<script>alert('Error updating User. Please try again.');window.location.href='Admin_EditUser.php?id=$userID';</script>";
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
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
        }
        .container {
            width: 50%;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
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
            display: flex;
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
        }
    </style>
</head>
<body>
    <nav>
      <?php include 'navbar/Admin_Navbar.html';?>
    </nav>
    <br>
    <div class="container">
        <div class="back-button" onclick="goBack()">
            <i class="fa fa-arrow-left"></i> Back
        </div>
        <h1>Edit User</h1>
        <?php if (isset($update_message)): ?>
            <p><script>alert("<?php echo $update_message; ?>")</script></p>
        <?php endif; ?>
        <br>
        <form id="editUserForm" method="POST">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($userRow['Name']); ?>">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($userRow['Email']); ?>">
            </div>
            <div class="form-group">
                <label for="phoneNumber">Phone Number</label>
                <input type="text" id="phoneNumber" name="phoneNumber" value="<?php echo htmlspecialchars($userRow['Phone_Number']); ?>">
            </div>
            <div class="buttons">
                <button type="submit" name="update_user">Save Changes</button>
            </div>
        </form>
    </div>
    <script>
        function goBack() {
            window.location.href = 'Admin_ManageUser.php';
        }
    </script>
</body>
</html>
