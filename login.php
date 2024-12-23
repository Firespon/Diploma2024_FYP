<?php
    session_start();
    include 'php/conn.php';
    if (isset($_POST['loginBtn'])) {     
      $email = $_POST['email'];
      $password = $_POST['password'];
   
      $sql = "SELECT * FROM user WHERE Email = '$email' AND Password = '$password'";
      $result = mysqli_query($con, $sql);
   
      $row = mysqli_fetch_assoc($result);
      $rowcount = mysqli_num_rows($result);

      if ($rowcount ==   1){
          $_SESSION["mySession"] = $row['ID'];
         
          if ($row['Role'] === 'Customer') {
              header("location: ../SDP/Cust_Home.php");
              exit();
          } elseif ($row['Role'] === 'Admin') {
              header("location: ../SDP/Admin_PendingAppointment.php");
              exit();
          } elseif ($row['Role'] === 'Staff') {
            header("location: ../SDP/Staff_Tasks.php");
            exit();
          } elseif ($row['Role'] === 'Management') {
            header("location: ../SDP/Management_ManageStaff.php");
            exit();}
            
          
      } else {
        echo "<script>alert('Email and Password does not match.'); window.location.href='php/Register.php';</script>";
      }
   
      mysqli_close($con);
    }   
?>