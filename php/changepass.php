<?php
session_start();
include('conn.php');

// Check if the user is logged in
if (!isset($_SESSION['ID'])) {
    echo "<script>alert('Unauthorized access.'); window.location.href='Register.php';</script>";
    exit();
}

// Get the user's name from the database
$id = $_SESSION['ID'];
$nameQuery = "SELECT Name FROM user WHERE ID = '$id'";
$result = mysqli_query($con, $nameQuery);

if (!$result) {
    die("Query failed: " . mysqli_error($con));
}

$userRow = mysqli_fetch_assoc($result);

if (isset($_POST['updatepassBtn'])) {
    $new_password = $_POST['newpassword'];
    $confirm_new_password = $_POST['confirmnewpassword'];

    if ($new_password !== $confirm_new_password) {
        echo "<script>alert('Passwords do not match.'); window.location.href='changepass.php';</script>";
    } 
    if (strlen($_POST['newpassword']) < 6 ||!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,}$/', $_POST['newpassword'])) {
      echo "<script>alert('Password must contain at least 6 characters, including a mix of upper and lower case letters and numbers.');window.location.href='changepass.php';</script>";
      exit();
    }
    else {;
        $sql = "UPDATE user SET Password = '$new_password' WHERE ID = '$id'";
        $result = mysqli_query($con, $sql);

        if ($result) {
            echo "<script>alert('Password updated successfully.'); window.location.href='Register.php';</script>";
        } else {
            echo "<script>alert('Failed to update password.'); window.location.href='resetpassword.php';</script>";
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
  <title>Reset Password</title>
  <link rel="icon" type="image/x-icon" href="../pictures/favicon.ico">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap');

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      transition: 0.5s;
      background-image: url("../pictures/registerpics/register.jpg");
      background-size: cover;
      background-repeat: no-repeat;
    }

    .container {
      position: relative;
      width: 400px;
      padding: 20px;
      background: #fff;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
      border-radius: 10px;
    }

    .formBx {
      width: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
    }

    .formBx .form form {
      width: 100%;
      display: flex;
      flex-direction: column;
    }

    .formBx .form form h3 {
      font-size: 1.5em;
      color: #333;
      margin-bottom: 20px;
      font-weight: 500;
      text-align: center;
    }

    .formBx .form form input {
      width: 100%;
      margin-bottom: 20px;
      padding: 10px;
      outline: none;
      font-size: 16px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .formBx .form form input[type="submit"] {
      background: #efae0b;
      border: none;
      color: #fff;
      cursor: pointer;
      border-radius: 7px;
      font-weight: 600;
      padding: 10px;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="formBx">
      <div class="form resetpassForm">
        <form method="post">
          <h3>Welcome, <?php echo htmlspecialchars($userRow['Name'] ?? ''); ?></h3>
          <h3>Reset Password</h3>
          <input type="password" name="newpassword" placeholder="New Password" required>
          <input type="password" name="confirmnewpassword" placeholder="Confirm New Password" required>
          <input type="submit" name="updatepassBtn" value="Update Password">
        </form>
      </div>
    </div>
  </div>
</body>
</html>
