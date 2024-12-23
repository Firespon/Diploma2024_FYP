<?php
include('session.php');
include('php/conn.php');

$id = $_SESSION['mySession'];

// Fetch the email using the user ID
$emailQuery = "SELECT Email FROM user WHERE ID = '$id'";
$emailResult = mysqli_query($con, $emailQuery);

if (mysqli_num_rows($emailResult) > 0) {
    $emailRow = mysqli_fetch_assoc($emailResult);
    $email = $emailRow['Email'];
} else {
    echo "<script>alert('User not found. Please try again.');window.location.href='Cust_Appointment.php';</script>";
    exit();
}

$customerQuery = "SELECT Customer_ID FROM customer WHERE Email = '$email'";
$customerResult = mysqli_query($con, $customerQuery);

if (mysqli_num_rows($customerResult) > 0) {
    $customerRow = mysqli_fetch_assoc($customerResult);
    $customer_id = $customerRow['Customer_ID'];
} else {
    echo "<script>alert('Customer not found. Please try again.');window.location.href='Cust_Appointment.php';</script>";
    exit();
}

$modelsQuery = "SELECT Car_ID, Model FROM car WHERE Customer_ID = '$customer_id' ORDER BY Model ASC";
$modelsResult = mysqli_query($con, $modelsQuery);

$optionsContent = '';
while ($modelRow = mysqli_fetch_assoc($modelsResult)) {
    $optionsContent .= '<option value="' . htmlspecialchars($modelRow['Car_ID']) . '">' . htmlspecialchars($modelRow['Model']) . '</option>';
}

// Handle form submission
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $carId = $_POST['selectedCarId'];
  $selectedDate = $_POST['selectedDate'];
  $selectedTime = $_POST['selectedTime'];
  $status = 'Pending';

  $allServices = ['Wheel Alignment', 'Engine Remap', 'Turbocharge', 'Engine Overhaul', 'Vehicle Computer Diagnostic', 'Aircond Service'];
  $selectedServices = array_filter($allServices, function($service) {
      return in_array($service, $_POST['services'] ?? []);
  });

  if (!empty($carId) && !empty($selectedDate) && !empty($selectedTime) && count($selectedServices) > 0) {
      $servicesString = implode(", ", $selectedServices);

      $stmt = $con->prepare("INSERT INTO appointment (Car_ID, Service_Type, Date, Time, Status) VALUES (?,?,?,?,?)");
      $stmt->bind_param("issss", $carId, $servicesString, $selectedDate, $selectedTime, $status);

      if ($stmt->execute()) {
          echo "<script>alert('Appointment saved successfully');</script>";
      } else {
          echo "<script>alert('Failed to save appointment');</script>";
      }
  } else {
      echo "<script>alert('Please fill in all fields');</script>";
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Appointment</title>
  <link rel="stylesheet" type="text/css" href="css/scrollbar.css">
  <link rel="icon" type="image/x-icon" href="pictures/favicon.ico">
  <style>
    body {
        font-family: 'Poppins', sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f4f9;
    }

    .dropbtn {
        background-color: #d9534f;
        color: white;
        padding: 14px;
        font-size: 16px;
        border: none;
        cursor: pointer;
        border-radius: 10px;
        width: 250px;
        text-align: left;
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
    }

    .dropbtn:hover {
        background-color: #c9302c;
    }

    .dropdown {
        position: relative;
        display: inline-block;
        margin-left: 300px;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #ffffff;
        min-width: 250px;
        overflow: auto;
        border: 1px solid #ddd;
        border-radius: 10px;
        box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        z-index: 1000;
        top: 100%; 
        left: 0;
    }

    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        text-align: left;
    }

    .dropdown-content a:hover {
        background-color: #f1f1f1;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .dropdown:focus-within .dropdown-content {
        display: block;
    }

    #carSelect {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-color: #d9534f;
        color: white;
        padding: 14px;
        font-size: 16px;
        border: none;
        cursor: pointer;
        border-radius: 10px;
        width: 250px;
        text-align: left;
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
    }

    #carSelect:hover {
        background-color: #c9302c;
    }

    #carSelect:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(217, 83, 79, 0.4);
    }

    #carSelect option {
        background-color: #ffffff;
        color: black;
        padding: 12px;
    }


    #myInput {
      box-sizing: border-box;
      background-image: url('pictures/bookingpics/search.png');
      background-position: 14px 12px;
      background-repeat: no-repeat;
      background-size: 20px 20px;
      font-size: 16px;
      padding: 14px 20px 12px 20px;
      border: none;
      border-bottom: 1px solid #ddd;
      width: 100%; /* Full width input */
    }

    #myInput:focus {
      outline: 3px solid #ddd;
    }

    .show {display:block;}

    .checker {
      display: block;
      position: relative;
      background-color:#d9d9d9; ;
      margin-left: 300px;
      margin-bottom: 15px;
      padding-left: 30px;
      padding-top: 20px;
      cursor: pointer;
      font-size: 20px;
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
      color: #7e121b;
      font-weight: bold;
      width: 450px;
      height: 80px;
      align-items: left;
      border-radius: 15px;
    }

    
    .checker input{
      position: absolute;
      opacity: 0;
      cursor: pointer;
      height: 0;
      width: 0;
    }

    .checkmark {
      position: absolute;
      top: 25px;
      left: 400px;
      height: 25px;
      width: 25px;
      background-color: #ffffff;
      border-radius: 40px;
      border: 3px solid maroon;
      transition: background-color 0.3s ease;
      
    }

    .checker input:checked ~.checkmark {
      background-color: maroon ;
      
    }
    
    .checkmark::after {
      content: "";
      position: absolute;
      display: none;
    }

    .checker input:checked ~ .checkmark::after {
      display: block;
      
    }

    .textbox {
      display: block;
      position: relative;
      background-color:#d9d9d9; ;
      margin-left: 300px;
      margin-bottom: 20px;
      padding-left: 30px;
      padding-top: 20px;
      cursor: auto;
      font-size: 20px;
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
      color: #190568 ;
      font-weight: bold;
      width: 450px;
      height: 80px;
      align-items: left;
      border-radius: 15px;
    }

    .textbox input[type="text"]{
      margin-left: 30px;;
      height: 50px;;
      width: 250px;
      border-radius: 10px;
      border: none;
      margin-top: -6px;

    }

    .textbox input[type="text"]::placeholder {
      padding-left: 75px;
    }

    .submit {
    border-radius: 9px;
    background-color: #E12A2A;
    border: none;
    color: #FFFFFF;
    text-align: center;
    font-size: 28px;
    padding: 20px;
    width: 450px;
    height: 60px;
    transition: all 0.5s;
    cursor: pointer;
    margin: 5px;
    padding-top: 8px;
    margin-left: 540px;
    font-weight: bold;
    
    }

    .submit span {
      cursor: pointer;
      display: inline-block;
      position: relative;
      transition: 0.5s;
    }

    .submit span:after {
      content: '\00bb';
      position: absolute;
      opacity: 0;
      top: 0;
      right: -20px;
      transition: 0.5s;
    }

    .submit:hover span {
      padding-right: 25px;
    }

    .submit:hover span:after {
      opacity: 1;
      right: 0;
    }
      
  </style>  
</head>
<body>
    <nav>
        <?php include 'navbar/Cust_Navbar.html';?>
    </nav>
    <br>
    <div>
        <h1 style="font-size:50px; text-align: center;">E-Appointment</h1>
        <br><br>
    </div>

    <form action="Cust_Appointment.php" method="POST" id="appointmentForm">
        <div class="dropdown">
            <select id="carSelect" class="dropbtn" onchange="updateCarId()">
                <option value="">Select Your Car</option>
                <?php echo $optionsContent; ?>
            </select>
        </div>

        <!-- Hidden input fields to store selected Car_ID, date, and time -->
        <input type="hidden" id="selectedCarId" name="selectedCarId">
        <input type="hidden" id="selectedDate" name="selectedDate">
        <input type="hidden" id="selectedTime" name="selectedTime">

        <div style="float: right; margin-right: 150px;">
            <h1 style="font-size: 25px;margin-left: 300px;">WHEN?</h1>
            <br><br>
            <?php include 'html/Customer/calendar.html';?>
        </div>
        <br><br><br>

        <div>
            <h1 style="font-size: 25px;margin-left: 300px;">SELECT SERVICE:</h1><br>
            <div>
                <label class="checker">WHEEL ALIGNMENT
                    <input type="checkbox" name="services[]" value="Wheel Alignment">
                    <span class="checkmark"></span>
                </label>
                <label class="checker">ENGINE REMAP
                    <input type="checkbox" name="services[]" value="Engine Remap">
                    <span class="checkmark"></span>
                </label>
                <label class="checker">TURBOCHARGE
                    <input type="checkbox" name="services[]" value="Turbocharge">
                    <span class="checkmark"></span>
                </label>
                <label class="checker">ENGINE OVERHAUL
                    <input type="checkbox" name="services[]" value="Engine Overhaul">
                    <span class="checkmark"></span>
                </label>
                <label class="checker">VEHICLE COMPUTER DIAGNOSTIC
                    <input type="checkbox" name="services[]" value="Vehicle Computer Diagnostic">
                    <span class="checkmark"></span>
                </label>
                <label class="checker">AIRCOND SERVICE
                    <input type="checkbox" name="services[]" value="Aircond Service">
                    <span class="checkmark"></span>
                </label>
            </div>
            <br><br><br><br><br>

            <div>
                <button type="submit" class="submit" "><span>REQUEST</span></button>
                <br>
            </div>
            <br><br>
        </div>
    </form>

    <br><br>

    <script>
        function updateCarId() {
            var carSelect = document.getElementById("carSelect");
            var selectedCarId = carSelect.options[carSelect.selectedIndex].value;
            document.getElementById("selectedCarId").value = selectedCarId;
            console.log("Selected Car ID: " + selectedCarId); // For debugging purposes
        }
    </script>
</body>
</html>
