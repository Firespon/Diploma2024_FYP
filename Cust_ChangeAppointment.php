<?php
include('session.php');
include('php/conn.php');

// Get the appointment ID from the URL or form
$appointmentId = isset($_GET['appointment_id']) ? $_GET['appointment_id'] : (isset($_POST['appointment_id']) ? $_POST['appointment_id'] : null);

if (!$appointmentId) {
    echo "<script>alert('Invalid appointment ID.');window.location.href='Cust_View_Appointment.php';</script>";
    exit();
}

// Fetch the appointment details
$appointmentQuery = "SELECT * FROM appointment WHERE Appointment_ID = '$appointmentId'";
$appointmentResult = mysqli_query($con, $appointmentQuery);

if (mysqli_num_rows($appointmentResult) > 0) {
    $appointmentRow = mysqli_fetch_assoc($appointmentResult);
    $carId = $appointmentRow['Car_ID'];
    $serviceType = $appointmentRow['Service_Type'];
    $date = $appointmentRow['Date'];
    $time = $appointmentRow['Time'];
} else {
    echo "<script>alert('Appointment not found.');window.location.href='Cust_View_Appointment.php';</script>";
    exit();
}

// Fetch customer ID
$id = $_SESSION['mySession'];
$emailQuery = "SELECT Email FROM user WHERE ID = '$id'";
$emailResult = mysqli_query($con, $emailQuery);

if (mysqli_num_rows($emailResult) > 0) {
    $emailRow = mysqli_fetch_assoc($emailResult);
    $email = $emailRow['Email'];
} else {
    echo "<script>alert('User not found.');window.location.href='Cust_Appointment.php';</script>";
    exit();
}

$customerQuery = "SELECT Customer_ID FROM customer WHERE Email = '$email'";
$customerResult = mysqli_query($con, $customerQuery);

if (mysqli_num_rows($customerResult) > 0) {
    $customerRow = mysqli_fetch_assoc($customerResult);
    $customer_id = $customerRow['Customer_ID'];
} else {
    echo "<script>alert('Customer not found.');window.location.href='Cust_Appointment.php';</script>";
    exit();
}

// Fetch the car models
$modelsQuery = "SELECT Car_ID, Model FROM car WHERE Customer_ID = '$customer_id' ORDER BY Model ASC";
$modelsResult = mysqli_query($con, $modelsQuery);

$optionsContent = '';
while ($modelRow = mysqli_fetch_assoc($modelsResult)) {
    $selected = $modelRow['Car_ID'] == $carId ? 'selected' : '';
    $optionsContent .= '<option value="' . htmlspecialchars($modelRow['Car_ID']) . '" ' . $selected . '>' . htmlspecialchars($modelRow['Model']) . '</option>';
}

// Define the services array
$allServices = ['Wheel Alignment', 'Engine Remap', 'Turbocharge', 'Engine Overhaul', 'Vehicle Computer Diagnostic', 'Aircond Service'];


// Handle form submission to update appointment
// Handle form submission to update appointment
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Validate input here (e.g., check if dates are valid, car ID exists)
  
  $carId = $_POST['selectedCarId'];
  $selectedDate = $_POST['selectedDate'];
  $selectedTime = $_POST['selectedTime'];
  $status = 'Pending'; // Assuming you want to set the status to Pending

  // Collect selected services
  $selectedServices = array_filter($allServices, function($service) {
      return in_array($service, $_POST['services']?? []);
  });

  // Construct services string
  $servicesString = implode(", ", $selectedServices);

  // Prepare and bind parameters for the update statement
  $stmt = $con->prepare("UPDATE appointment SET Car_ID=?, Service_Type=?, Date=?, Time=?, Status=? WHERE Appointment_ID=?");
  $stmt->bind_param("issssi", $carId, $servicesString, $selectedDate, $selectedTime, $status, $appointmentId);

  // Execute the update statement
  if ($stmt->execute()) {
      echo "<script>alert('Appointment updated successfully');window.location.href='Cust_View_Appointment.php';</script>";
  } else {
      echo "<script>alert('Failed to update appointment');</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Appointment</title>
    <link rel="stylesheet" type="text/css" href="css/scrollbar.css">
    <link rel="icon" type="image/x-icon" href="pictures/favicon.ico">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .dropbtn, #carSelect, .checker, .submit, .clear {
            margin-left: 300px;
            border-radius: 10px;
        }
        .dropbtn, #carSelect {
            background-color: #d9534f;
            color: white;
            padding: 14px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            width: 250px;
            text-align: left;
        }
        .dropbtn:hover, #carSelect:hover {
            background-color: #c9302c;
        }
        .dropbtn:focus, #carSelect:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(217, 83, 79, 0.4);
        }
        .checker {
            display: block;
            position: relative;
            background-color: #d9d9d9;
            margin-bottom: 15px;
            padding-left: 30px;
            padding-top: 20px;
            cursor: pointer;
            font-size: 20px;
            user-select: none;
            color: #7e121b;
            font-weight: bold;
            width: 450px;
            height: 80px;
            border-radius: 15px;
        }
        .checker input {
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
            background-color: maroon;
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
            background-color: #d9d9d9;
            margin-bottom: 20px;
            padding-left: 30px;
            padding-top: 20px;
            font-size: 20px;
            user-select: none;
            color: #190568;
            font-weight: bold;
            width: 450px;
            height: 80px;
            border-radius: 15px;
        }
        .textbox input[type="text"] {
            margin-left: 30px;
            height: 50px;
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

          .date-time-container {
            float: right;
            margin-right: 150px;
          }
    </style>
</head>
<body>
    <nav>
      <?php include("navbar/Cust_Navbar.html")?>
    </nav>
    <br>
    <div class="dropdown">
    <form action="Cust_ChangeAppointment.php" method="post">
        <select id="carSelect" name="selectedCarId">
            <?php echo $optionsContent; ?>
        </select>
        <div class="date-time-container">
            <?php include("html/Customer/calendar.html")?>
        </div>
        <br><br>
        <?php foreach ($allServices as $service): ?>
            <label class="checker">
                <input type="checkbox" name="services[]" value="<?php echo htmlspecialchars($service); ?>" <?php echo in_array($service, explode(", ", $serviceType)) ? 'checked' : ''; ?>>
                <?php echo htmlspecialchars($service); ?>
                <span class="checkmark"></span>
            </label>
        <?php endforeach; ?>
        <input type="hidden" name="appointment_id" value="<?= htmlspecialchars($appointmentId)?>">
        <input type="hidden" id="selectedDate" name="selectedDate">
        <input type="hidden" id="selectedTime" name="selectedTime">
        <br><br><br>
        <button type="submit" class="submit"><span>Update Appointment</span></button>
    </form>
    </div>
</body>
</html>
