<?php
  include("session.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile Page</title>
  <link rel="stylesheet" type="text/css" href="css/scrollbar.css">
  <link rel="icon" type="image/x-icon" href="pictures/favicon.ico">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap');

    * {
      font-family: "Poppins", sans-serif;
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    .tab {
      float: left;
      margin-left: 100px;
      border: 2px solid #ccc;
      background-color: #f1f1f1;
      width: 18%;
      border-radius: 13px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .tab-button {
      background-color: inherit;
      color: black;
      padding: 22px 16px;
      width: 100%;
      border: none;
      outline: none;
      text-align: left;
      cursor: pointer;
      font-size: 1em;
      font-weight: bold;
      border-radius: 0;
      transition: background-color 0.3s;
    }

    .tab-button:hover,
    .tab-button.active {
      background-color: maroon;
      color: white;
    }

    .tab-button:first-child {
      border-top-left-radius: 13px;
      border-top-right-radius: 13px;
    }

    .tab-button:last-child {
      border-bottom-left-radius: 13px;
      border-bottom-right-radius: 13px;
    }

    .tabcontent {
      float: left;
      padding: 0px 12px;
      width: 60%;
      height: 100%;
      display: none;
      margin-top: -60px;
      margin-left: 70px;
      margin-bottom: 10px;
    }

    @media (max-width: 784px) {
      .tab {
        margin-left: 0;
        width: auto;
        flex-direction: row;
        overflow-x: auto;
      }

      .tab-button {
        width: 100%;
      }
    }
  </style>
</head>
<body>
  <nav>
    <?php include 'navbar/Cust_Navbar.html' ?>
  </nav>
  <br><br><br><br>
  <div class="tab">
    <button class="tab-button" onclick="openTab(event,'Profile')">Profile</button>
    <button class="tab-button" onclick="openTab(event,'My_Cars')">My Cars</button>
  </div>

  <div id="Profile" class="tabcontent">
    <div>
      <?php include 'html/Customer/Profile_Page.php' ?>
    </div>
  </div>
  
  <div id="My_Cars" class="tabcontent">
    <div>
      <?php include 'html/Customer/My_Car.php' ?>
    </div>
  </div>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      openTab(null, 'Profile');
    });

    function openTab(evt, tabName) {
      var i, tabcontent, tablinks;

      tabcontent = document.getElementsByClassName("tabcontent");
      for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
      }

      tablinks = document.getElementsByClassName("tab-button");
      for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
      }

      document.getElementById(tabName).style.display = "block";
      if (evt) {
        evt.currentTarget.className += " active";
      }
    }
  </script>
</body>
</html>
