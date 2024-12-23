<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Car Registration</title>
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap');

    * {
      font-family: "Poppins", sans-serif;
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    .carreg-body {
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 0;
      font-family: "Poppins", sans-serif;
    }

    .form-container {
      max-width: 800px;
      padding: 10px;
      border: 2px solid black;
      border-radius: 20px;
      padding-left: 15px;
      background-color: white; /* Optional: Adds a background color to the form container */
    }


    .form-group {
      margin-bottom: 20px;
    }

    label {
      font-weight: normal;
      margin-bottom: 5px;
    }

    input[type="text"],
    input[type="number"],
    select {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      background-color: #d7d7d7;
    }

    input[type="radio"] {
      margin-right: 5px;
      display: inline;
      padding: 10px;
    }

    .dropdown {
      position: relative;
      display: inline-block;
    }

    .dropdown-content {
      display: none;
      position: absolute;
      background-color: #f6f6f6;
      min-width: 200px;
      overflow: auto;
      border: 1px solid #ddd;
      z-index: 1;
    }

    .dropdown-content a {
      color: black;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
    }

    .dropdown-content a:hover {
      background-color: #ddd;
    }

    .show {
      display: block;
    }

    #submitBtn {
      background-color: #e12a2a;
      border: none;
      color: #fff;
      width: 760px;
      max-width: 100%;
      height: 42px;
      cursor: pointer;
      border-radius: 7px;
      font-weight: 600;
    }

    .back-icon {
      margin-left: 300px;
      font-size: 24px; 
      transition: transform 0.3s ease; 
    }

    .back-icon:hover {
      transform: scale(1.2);
      transition: transform 0.3s ease;
    }

    .back-icon:active {
      transform: scale(1.05); 
      transition: transform 0.3s ease; 
    }

  </style>
</head>
<body>
    <a href="Cust_Profile.php"><i class="fa fa-arrow-left back-icon"></i></a>
    <h1 style="text-align: center;">Car Registration</h1>
    <br>
    <div class="carreg-body">
      <div class="form-container">
        <form action="php/add_car.php" method="POST">
          <div class="form-group">
            <label for="brand">Car Brand</label>
            <select id="brand" name="brand" onchange="filterModels()">
              <option value="">Select Brand</option>
              <script>
                const brands = {
                  BMW: ["BMW 3 Series", "BMW 5 Series", "BMW X5"],
                  Honda: ["Honda Civic", "Honda Accord", "Honda CR-V"],
                  Mercedes: ["Mercedes-Benz C-Class", "Mercedes-Benz E-Class", "Mercedes-Benz S-Class"],
                  Perodua: ["Perodua Myvi", "Perodua Axia", "Perodua Bezza"],
                  Porsche: ["Porsche 911", "Porsche Cayenne", "Porsche Macan"],
                  Toyota: ["Toyota Corolla", "Toyota Camry", "Toyota Vios"]
                };

                for (const brand in brands) {
                  document.write(`<option value="${brand}">${brand}</option>`);
                }
              </script>
            </select>
          </div>

          <div class="form-group">
            <label for="model">Car Model</label>
            <select id="model" name="model">
              <option value="">Select Model</option>
            </select>
          </div>

          <div class="form-group">
            <label for="registration-number">Registration Number</label>
            <input type="text" id="registration-number" name="registration_number" required autocomplete="off">
          </div>

          <div class="form-group">
            <label for="manufactured-year">Manufactured Year</label>
            <select id="manufactured-year" name="manufactured_year">
              <option value="">Select Year</option>
              <script>
                const currentYear = new Date().getFullYear();
                for (let year = currentYear; year >= 1980; year--) {
                  document.write(`<option value="${year}">${year}</option>`);
                }
              </script>
            </select>
          </div>

          <button id="submitBtn" type="submit" name="registerCarBtn">Submit</button>
        </form>
      </div>

  </div>
  <script>
    function filterModels() {
      const brandSelect = document.getElementById('brand');
      const modelSelect = document.getElementById('model');
      const selectedBrand = brandSelect.value;

      // Clear existing options in the model dropdown
      modelSelect.options.length = 1;

      // Populate model options based on the selected brand
      if (selectedBrand && brands[selectedBrand]) {
        brands[selectedBrand].forEach(model => {
          const option = document.createElement('option');
          option.value = model;
          option.textContent = model;
          modelSelect.appendChild(option);
        });
      }
    }
  </script>
</body>
</html>
