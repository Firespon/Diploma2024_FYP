<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <link href='https://fonts.googleapis.com/css?family=Allura' rel='stylesheet'>
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <link rel="stylesheet" type="text/css" href="css/scrollbar.css">
    <style>
      @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

      * {
          font-family: "Poppins", sans-serif;
          margin: 0;
          padding: 0;
          box-sizing: border-box;
      }

      html, body {
        overflow-x: hidden;
      }

      .page {
        width: 100%;
        overflow-x: hidden;
        overflow-y: scroll;
      }

      input[type=submit] {
        background-color: #E12A2A;
        color: white;
        padding-top: 15px;
        padding-bottom: 15px;
        border: none;
        border-radius: 30px;
        cursor: pointer;
        position: relative;
        width: 200px;
        font-size: 20px;
        font-weight: bold;
        float: right;
        text-align: center;
        right: 120px;
      }

      input[type=submit]:hover {
        background-color: #e12a2ad1;
      }

      .feedback-form {
          background-image: url("pictures/contactpics/dodgehellcat.jpg");
          position: relative; 
          top: 14%;
          left: 0px;
          right: 0px;
          height: 100%;
          bottom: 14%;
          background-size: cover;
          overflow: auto;
      }

      label[for=title] {
          font-size: 60px;
          position: relative;   
          left: 80px;
          top: 30px;
          font-weight: bold;
          color: white;
          text-align: center;
      }

      text {
          font-size: 30px;
          font-size: 18px;
          position: right;
          top: 0px;
          margin-left: 250px;
          line-height: 25px;
          color: white;
          font-style: italic;
          text-align: center;
      }

      .open-button {
        background-color: black;
        color: white;
        padding: 16px 20px;
        border: none;
        cursor: pointer;
        position: fixed;
        top: 150px;
        right: 185px;
        width: 150px;
        border-radius: 50px;
        font-size: 20px;
        font-weight: bold;
      }

      .form-popup {
        display: block;
        position: relative;
        bottom: 100px;
        right: 40px;
        z-index: 9;
        width: 450px;
        height: 560px;  
        float: right;
      }

      .form-popup h1 {
          font-size: 35px;
          color: #f1f1f1;
          text-align: center;
      }

      .contact-input {
          width: 400px;
          margin-left: 25px; 
          margin-top: 20px;
          height: 50px;
          padding: 12px 20px;
          box-sizing: border-box;
          border: 2px solid #ccc;
          border-radius: 10px;
          background-color: #e7e5e5;
          font-size: 16px;
          resize: none;
      }

      .contact-feedback {
          width: 400px;
          margin-left: 25px; 
          margin-top: 20px;
          height: 130px;
          padding: 12px 20px;
          box-sizing: border-box;
          border: 2px solid #ccc;
          border-radius: 10px;
          background-color: #e7e5e5;
          font-size: 16px;
          resize: none;
      }

      .zoom {
        padding: 0px;
        transition: transform 0.2s; 
        width: 50px;
        height: 50px;
        margin: 0 auto;
      }

      .zoom:hover {
        transform: scale(1.1);
      }

      .zoom-button {
          background-color: transparent;
          border: none;
          padding: 0;
          cursor: pointer;
      }

      .map {
        color: #f1f1f1;
        text-align: center;
        font-size: 27px;
        background: url('pictures/contactpics/aipic.jpg');
        position: inline-flex;
        float: left;
        padding-right: 70px; 
        height: auto;
        width: 10000px;
        border-radius: 20px;
        overflow-x: hidden;
      }

      .iframe {
        border-radius: 7px;
        margin-right: 670px;  
      }

      #map-title {
      margin-left: 70px;
      opacity: 0;
      transform: translateX(-100%);
      transition: all 1s ease;
      float: left;
      }

      #map-title.animate {
      transform: translateX(0%);
      opacity: 1;
      }

      .bottom-section {
        display: flex;
        justify-content: space-between;
        width: 100%;
        padding: 20px;
      }

    </style>
</head>
<body>
  <div class="page">
    <div class="feedback-form">
      <label for="title">We'd love to hear <br>your thoughts</label>
    
      <form method="post" action="">
        <div class="form-popup" id="myForm">
          <h1>How are you feeling?</h1>
  
          <div style="display: flex; justify-content: first baseline;">
            <div class="zoom">
              <img src="pictures/contactpics/Very Dissatisfied.png" value="Very Dissatisfied" data-original-src="pictures/contactpics/Very Dissatisfied.png" data-src="pictures/contactpics/red.png" style="width: 50px; height: 50px;cursor: pointer;margin: 30px -40px; margin-left: 2px;">
            </div>
            <div class="zoom">
              <img src="pictures/contactpics/Dissatisfied.png" value="Dissatisfied" data-original-src="pictures/contactpics/Dissatisfied.png" data-src="pictures/contactpics/orange.png" style="width: 50px; height: 50px;cursor: pointer;margin: 30px -40px; margin-left: 2px;">
            </div>
            <div class="zoom">
              <img src="pictures/contactpics/Okay.png" value="Okay" data-original-src="pictures/contactpics/Okay.png" data-src="pictures/contactpics/yellow.png" style="width: 50px; height: 50px;cursor: pointer;margin: 30px -40px; margin-left: 2px;">
            </div>
            <div class="zoom">
              <img src="pictures/contactpics/Satisfied.png" value="Satisfied" data-original-src="pictures/contactpics/Very Satisfied.png" data-src="pictures/contactpics/kiwi.png" style="width: 50px; height: 50px;cursor: pointer;margin: 30px -40px; margin-left: 2px;">
            </div>
            <div class="zoom">
              <img src="pictures/contactpics/Very Satisfied.png" value="Very Satisfied" data-original-src="pictures/contactpics/Very Satisfied.png" data-src="pictures/contactpics/green.png" style="width: 50px; height: 50px;cursor: pointer;margin: 30px -40px; margin-left: 2px;">
            </div>
          </div>
          <br><br>
          <input class="contact-input" type="text" id="name" name="name" placeholder="Name" required>
          <br>
          <input class="contact-input" type="text" id="email" name="email" placeholder="Email" required>
          <br>
          <textarea class="contact-feedback" name="message" rows="7" cols="65" placeholder="Type your feedback here..." required></textarea><br><br>
          <input type="hidden" name="mood" id="moodValue">
          <input type="submit" name="sendfeedbackBtn" value="Submit">
        </div>
      </form>

      <div class="bottom-section">
        <div class="map">
          <h1 id="map-title">Locate Our Workshop</h1>
          <br>
          <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15937.615178196946!2d101.6200061!3d2.9853167!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cdb479dff585b7%3A0xe1e4c5a70fa60072!2stheWorkshop%20Malaysia%20-%20Auto%20Service%20Center!5e0!3m2!1sen!2ssg!4v1715964606640!5m2!1sen!2ssg" class="iframe" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" height="410px" width="600px"></iframe>
        </div>
      </div>
    </div>
  </div>
  
  <script>
    document.querySelectorAll('.zoom img').forEach((img) => {
      img.addEventListener('click', function() {
        // Assuming the value attribute contains the mood value you want to insert
        var moodValue = this.getAttribute('value');
        document.getElementById('moodValue').value = moodValue; // Update the hidden input field with the mood value
      });
    });

    document.addEventListener('DOMContentLoaded', function() {
      const mapTitle = document.getElementById('map-title');
      const mapTitleOffset = mapTitle.offsetTop;
      const mapTitleHeight = mapTitle.offsetHeight;

      window.addEventListener('scroll', function() {
          const pageOffset = window.pageYOffset + window.innerHeight;

          if (pageOffset > mapTitleOffset + mapTitleHeight / 2 && window.pageYOffset < mapTitleOffset + mapTitleHeight) {
              mapTitle.classList.add('animate');
          } else {
              mapTitle.classList.remove('animate');
          }
      });
    });

    document.addEventListener('DOMContentLoaded', function() {
      // Select all images with the class 'zoom'
      var zoomImages = document.querySelectorAll('.zoom img');

      // Initialize the last clicked image variable
      var lastClickedImage = null;

      // Function to revert the last clicked image to its original state
      function revertLastImage() {
        if (lastClickedImage !== null) {
          lastClickedImage.src = lastClickedImage.dataset.originalSrc;
        }
      }

      // Attach a click event listener to each image
      zoomImages.forEach(function(img) {
        img.addEventListener('click', function() {
          // Store the current last clicked image in a temporary variable
          var tempLastClickedImage = lastClickedImage;

          // Revert the last clicked image if it exists
          revertLastImage();

          // Change the current image
          var src = img.src;
          var dataSrc = img.getAttribute('data-src');
          img.src = dataSrc;

          // Update the last clicked image variable with the new image
          lastClickedImage = img;

          // Store the original source of the current image
          img.dataset.originalSrc = src;
        });
      });
    });
  </script>
</body>
</html>
