<?php
    if (isset($_POST["resetpassBtn"])) {
        include('conn.php');
    
        $email = $_POST['email'];
        $phoneNum = $_POST['phone_number'];
    
        $sql = "SELECT * FROM user WHERE Email = '$email' AND Phone_Number = '$phoneNum' AND Role = 'Customer'";
        $result = mysqli_query($con, $sql);
    
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            session_start();
            $_SESSION['ID'] = $row['ID'];
            echo "<script>window.location.href='changepass.php';</script>";
            exit();
        } else {
            echo "<script>alert('Email and Phone Number does not match.'); window.location.href='Register.php';</script>";
        }
    
        mysqli_close($con);
    }

    function myPassword() {
        
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login/Register</title>
  <link rel="stylesheet" type="text/css" href="../css/register.css">
  <link rel="icon" type="image/x-icon" href="../pictures/favicon.ico">
</head>
<body>
  <div class="container">
    <div class="blueBg">
      <div class="box signin">
        <h2>Already Have an Account ?</h2>
        <button class="signinBtn">Sign in</button>
      </div>
      <div class="box signup">
        <h2>Don't Have an Account ?</h2>
        <button class="signupBtn">Sign up</button>
      </div>
    </div>
    <div class="formBx">
      <img src="../pictures/company_logo.png" alt="company_logo" style="width: 20%; margin: 0px 0px 420px 320px;">
      <div class="form signinForm">
        <form action="../login.php" method="post">
          <h3>Sign In</h3>
          <input type="email" name="email" placeholder="Email" required autocomplete="off">
          <input type="password" name="password" placeholder="Password" required>
          <input type="submit" name="loginBtn" value="Login">
          <a href="#" class="forgot">Forgot Password</a>
        </form>
      </div>

      <div class="form signupForm">
        <form action="signup.php" method="post">
          <h3>Sign Up</h3>
          <input type="text" name="full_name" placeholder="Full Name" required autocomplete="off">
          <input type="text" name="email" placeholder="Email Address" required autocomplete="off">
          <input type="text" name="phone_number" placeholder="Phone Number" required minlength="10" autocomplete="off">
          <input type="password" name="password" placeholder="Password" required minlength="6">
          <input type="password" name="confirm_password" placeholder="Confirm Password" required minlength="6">
          <input type="submit" name="signupBtn" value="Register">
        </form>
      </div>

      <div class="form forgotpassForm">
        <form method="post" action="Register.php">
          <h3>Forgot Password?</h3>
          <input type="text"name="email" placeholder="Email Address" required autocomplete="off">
          <input type="text" name="phone_number" placeholder="Phone Number" required autocomplete="off">
          <input type="submit" name="resetpassBtn" value="Reset Password">
        </form>
      </div>

      <div class="form resetpassForm">
        <form method="post">
          <h3>Reset Password</h3>
          <input type="password" name="newpassword" placeholder="New Password" required>
          <input type="password" name="confirmnewpassword" placeholder="Confirm New Password" required>
          <input type="submit" name="updatepassBtn" value="Update Password">
        </form>
      </div>

    </div>
  </div>
  <script>
    const signinBtn = document.querySelector('.signinBtn');
    const signupBtn = document.querySelector('.signupBtn');
    const forgotBtn = document.querySelector('.forgot');
    const formBx = document.querySelector('.formBx');
    const body = document.querySelector('body');

    signupBtn.onclick = function(){
      formBx.classList.add('active');
      formBx.classList.remove('forgot-active');
      formBx.classList.remove('reset-active');
      body.classList.add('active');
    }

    signinBtn.onclick = function(){
      formBx.classList.remove('active');
      formBx.classList.remove('forgot-active');
      formBx.classList.remove('reset-active');
      body.classList.remove('active');
    }

    forgotBtn.onclick = function(event){
      event.preventDefault();
      formBx.classList.add('forgot-active');
      formBx.classList.remove('active');
      formBx.classList.remove('reset-active');
    }

    const resetBtns = document.querySelectorAll('.resetBtn');
    resetBtns.forEach(btn => {
      btn.onclick = function(event){
        event.preventDefault();
        formBx.classList.add('reset-active');
        formBx.classList.remove('forgot-active');
        formBx.classList.remove('active');
      }
    });

    document.addEventListener('DOMContentLoaded', () => {
      const resetBtn = document.querySelector('.resetBtn');
      const formBx = document.querySelector('.formBx');

      resetBtn.onclick = function(event) {
        event.preventDefault();
        formBx.classList.add('reset-active');
        formBx.classList.remove('forgot-active');
        formBx.classList.remove('active');
      }
    });
  </script>
</body>
</html>
