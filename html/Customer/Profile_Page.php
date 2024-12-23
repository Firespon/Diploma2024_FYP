<?php
include("php/conn.php");
$sql = "SELECT Name, Email, Phone_Number FROM user WHERE ID =" . $_SESSION['mySession'];
$result = mysqli_query($con, $sql);
while ($row = mysqli_fetch_array($result)) {
    $name = $row['Name'];
    $email = $row['Email'];
    $phoneNumber = $row['Phone_Number'];
}
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile Page</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
    body .profile {
      font-family: 'Poppins', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      background-color: #f5f5f5;
    }
    .profile-container {
      background: white;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      padding: 20px;
      max-width:500px;
      text-align: left;
      position: relative;
    }
    .profile-container h1 {
      font-size: 24px;
      margin-bottom: 10px;
      font-weight: 600;
    }
    .profile-container p {
      margin: 5px 0;
      font-weight: 400;
      font-size: large;
    }
    .profile-container strong {
      margin: 5px 0;
      font-size: larger;
      font-weight: bold;
    }
    .edit-button {
      position: absolute;
      bottom: 10px;
      right: 10px;
      background: none;
      border: none;
      cursor: pointer;
    }
    .edit-button .fa {
      font-size: 20px;
    }
    .changepassBtn {
      font-size: 20px;
    }
    .popup, .changepassPopup {
      display: none;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: white;
      padding: 20px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      border-radius: 10px;
      z-index: 1000;
    }
    .popup input[type="email"], .popup input[type="text"], .changepassPopup input[type="password"], .changepassPopup input[type="text"] {
      display: block;
      margin-bottom: 10px;
      padding: 10px;
      width: 200px;
      border: 1px solid #c8c8c8;
      background-color: rgb(225, 42, 42);
      color: white;
      border-radius: 5px;
      font-family: 'Poppins', sans-serif;
    }
    .changepassBtn, .popup button, .changepassPopup button {
      padding: 10px 20px;
      background-color: white;
      color: #b00a0a;
      border: 2px solid #b00a0a;
      border-radius: 5px;
      cursor: pointer;
      font-family: 'Poppins', sans-serif;
      transition: background-color 0.15s, color 0.15s;
    }
    .changepassBtn:hover, .popup button:hover, .changepassPopup button:hover {
      background-color: #b00a0a;
      color: white;
    }
    .changepassPopup input::placeholder {
      color: white;
    }
    .changepassPopup input:focus::placeholder {
      visibility: hidden;
    }
    .close-btn {
      display: block;
      text-align: right;
      cursor: pointer;
      margin-bottom: 5px;
    }
    .overlay, .changepassoverlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      z-index: 999;
    }
  </style>
</head>
<body class="profile">
  <h1>Profile</h1>
  <div class="profile-container">
    <p>Name<br><strong><?php echo htmlspecialchars($name); ?></strong></p>
    <p>Email<br><strong><?php echo htmlspecialchars($email); ?></strong></p>
    <p>Phone Number<br><strong><?php echo htmlspecialchars($phoneNumber); ?></strong></p>
    <a class="edit-button" onclick="showPopup()">
      <i class="fa fa-pencil"></i>
    </a>
  </div>
  <br>
  <button class="changepassBtn" onclick="showChangePassPopup()">Change Password</button>

  <div class="overlay" onclick="closePopup()"></div>
  <div class="changepassoverlay" onclick="closeChangePassPopup()"></div>

  <div class="popup">
    <span class="close-btn" onclick="closePopup()">✖️</span>
    <h2>Edit Profile</h2>
    <br>
    <form method="POST" action="php/profile.php">
      <input type="text" name="name" placeholder="Name" value="<?php echo htmlspecialchars($name); ?>" class="input-field" required autocomplete="off">
      <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>" class="input-field" required autocomplete="off">
      <input type="text" name="phoneNumber" placeholder="Phone Number" value="<?php echo htmlspecialchars($phoneNumber); ?>" class="input-field" required autocomplete="off">
      <button type="submit" name="updateProfile">Save</button>
    </form>
  </div>

  <div class="changepassPopup">
    <span class="close-btn" onclick="closeChangePassPopup()">✖️</span>
    <h2>Change Password</h2>
    <br>
    <form method="POST" action="php/profile.php">
      <input type="hidden" name="changePassword" value="true">
      <input type="password" name="currentPassword" placeholder="Current Password" required>
      <input type="password" name="newPassword" placeholder="New Password" required minlength="6">
      <input type="password" name="newConfirmPassword" placeholder="Confirm New Password" required minlength="6">
      <button type="submit" name="changePasswordForm">Save</button>
    </form>
  </div>


  <script>
    function showPopup() {
      document.querySelector('.popup').style.display = 'block';
      document.querySelector('.overlay').style.display = 'block';
    }

    function closePopup() {
      document.querySelector('.popup').style.display = 'none';
      document.querySelector('.overlay').style.display = 'none';
    }

    function showChangePassPopup() {
      document.querySelector('.changepassPopup').style.display = 'block';
      document.querySelector('.changepassoverlay').style.display = 'block';
    }

    function closeChangePassPopup() {
      document.querySelector('.changepassPopup').style.display = 'none';
      document.querySelector('.changepassoverlay').style.display = 'none';
    }
  </script>
</body>
</html>
