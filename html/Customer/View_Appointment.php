<?php
include('php/conn.php');

// Assuming $_SESSION['mySession'] contains the user ID
$userId = $_SESSION['mySession'];

// Fetch user email
$emailQuery = "SELECT Email FROM user WHERE ID = '$userId'";
$emailResult = mysqli_query($con, $emailQuery);
if ($emailResult && mysqli_num_rows($emailResult) > 0) {
    $emailRow = mysqli_fetch_assoc($emailResult);
    $email = $emailRow['Email'];
} else {
    echo "<script>alert('User not found. Please try again.');window.location.href='Cust_View_Appointment.php';</script>";
    exit();
}

// Fetch customer ID
$customerQuery = "SELECT Customer_ID FROM customer WHERE Email = '$email'";
$customerResult = mysqli_query($con, $customerQuery);
if ($customerResult && mysqli_num_rows($customerResult) > 0) {
    $customerRow = mysqli_fetch_assoc($customerResult);
    $customer_id = $customerRow['Customer_ID'];
} else {
    echo "<script>alert('Customer not found. Please try again.');window.location.href='Cust_View_Appointment.php';</script>";
    exit();
}

// Check if cancel button was clicked
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_appointment_id'])) {
    $cancelAppointmentId = $_POST['cancel_appointment_id'];
    $updateQuery = "UPDATE appointment SET Status = 'Cancelled' WHERE Appointment_ID = '$cancelAppointmentId'";
    if (mysqli_query($con, $updateQuery)) {
        echo "<script>alert('Appointment cancelled successfully.');window.location.href='Cust_View_Appointment.php';</script>";
    } else {
        echo "<script>alert('Failed to cancel appointment. Please try again.');window.location.href='Cust_View_Appointment.php';</script>";
    }
}

// Fetch appointments
$appointmentQuery = "SELECT appointment.Appointment_ID, car.Model, appointment.Service_Type, appointment.Time, appointment.Date
                     FROM appointment
                     JOIN car ON appointment.Car_ID = car.Car_ID
                     WHERE car.Customer_ID = '$customer_id' AND appointment.Status = 'Pending'";

$appointmentResult = mysqli_query($con, $appointmentQuery);

$appointments = [];
if ($appointmentResult && mysqli_num_rows($appointmentResult) > 0) {
    while ($row = mysqli_fetch_assoc($appointmentResult)) {
        $appointments[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Booking</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap');
        * {
            font-family: "Poppins", sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background-color: #f4f4f9;
        }
        .container {
            background-color: #fff;
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .booking {
            background: #eee;
            margin: 20px 0;
            padding: 15px;
            border-radius: 10px;
            cursor: pointer;
            border: 1px solid #ccc;
        }
        .details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 10px;
        }
        .details span {
            font-weight: bold;
            color: #555;
        }
        .actions {
            display: none;
            transition: all 0.3s ease-in-out;
            margin-top: 10px;
        }
        .actions button {
            width: 49.8%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            background-color: #ff4c4c
        }
        .cancel {
            background-color: #ff4c4c;
        }
        .change button{
            background-color: #777;
        }
        @media (max-width: 600px) {
            .details {
                flex-direction: column;
                text-align: center;
            }
            .actions button, .actions a {
                width: 100%;
                margin: 5px 0;
            }
        }
        .service-list {
            margin: 0;
            padding-left: 20px;
            list-style-type: disc;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>VIEW APPOINTMENT</h1>
        <?php foreach ($appointments as $index => $appointment):?>
        <form method="POST" class="booking" onclick="toggleButtons('booking<?= $index?>-actions')">
            <div class="details">
                <span class="model"><?= htmlspecialchars($appointment['Model'])?></span> - 
                <span class="service">
                    <?php
                    $services = explode(',', $appointment['Service_Type']);
                    echo '<ul class="service-list">';
                    foreach ($services as $service) {
                        echo '<li>'. htmlspecialchars(trim($service)). '</li>';
                    }
                    echo '</ul>';
                ?>
                </span> at 
                <span class="time"><?= htmlspecialchars($appointment['Time'])?></span> on 
                <span class="date"><?= htmlspecialchars($appointment['Date'])?></span>
            </div>
            <!-- Hidden input for appointment_id -->
            <input type="hidden" name="cancel_appointment_id" value="<?= $appointment['Appointment_ID']?>">
            <div class="actions" id="booking<?= $index?>-actions">
                <!-- Cancel button now uses the hidden input for its action -->
                <button type="submit">CANCEL APPOINTMENT</button>
                <a href="Cust_ChangeAppointment.php?appointment_id=<?= $appointment['Appointment_ID']?>" class="change"><button type="button">CHANGE APPOINTMENT</button></a>
            </div>
        </form>
        <?php endforeach;?>

    </div>
    <script>
        function toggleButtons(id) {
            var allActions = document.querySelectorAll('.actions');
            allActions.forEach(function(action) {
                if (action.id !== id) {
                    action.style.display = 'none';
                }
            });
            var element = document.getElementById(id);
            element.style.display = element.style.display === 'block' ? 'none' : 'block';
        }
    </script>
</body>
</html>
