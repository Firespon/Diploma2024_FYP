<?php
include("session.php");
include("php/conn.php");

if (isset($_GET['id'])) {
    $appointmentId = $_GET['id'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $amountPaid = $_POST['totalAmount'];

        // Update the Amount_Paid in the database
        $updateQuery = "UPDATE appointment SET Amount_Paid = ? WHERE Appointment_ID = ?";
        $stmt = mysqli_prepare($con, $updateQuery);
        mysqli_stmt_bind_param($stmt, "di", $amountPaid, $appointmentId);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Payment updated successfully.'); window.location.href='Admin_CompletedAppointment.php';</script>";
        } else {
            echo "<script>alert('Failed to update payment.');  window.location.href='Admin_CompletedAppointment.php';</script>";
        }
    }

    // Fetch user data from the database based on the ID
    $query = "SELECT appointment.Appointment_ID, appointment.Date, appointment.Time, appointment.Service_Type, appointment.Status,
        car.Model, car.Registration_Number, customer.Email, user.Name AS CustomerName, user.Phone_Number as CustomerPhone, staff.Staff_ID, staff.Email AS StaffEmail, s.Name AS StaffName, s.Phone_Number AS StaffPhone,
        appointment.Amount_Paid
        FROM appointment
        JOIN car ON appointment.Car_ID = car.Car_ID
        JOIN customer ON car.Customer_ID = customer.Customer_ID
        JOIN user ON customer.Email = user.Email
        JOIN staff ON appointment.Staff_ID = staff.Staff_ID
        JOIN user s ON staff.Email = s.Email
        WHERE appointment.Appointment_ID = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $appointmentId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $Row = mysqli_fetch_assoc($result);
    } else {
        die("Appointment not found.");
    }
} else {
    die("Invalid request.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Information</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/scrollbar.css">
    <link rel="icon" type="image/x-icon" href="pictures/favicon.ico">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .container {
            width: 50%;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        h1 {
            text-align: center;
        }
        .back-button {
            font-size: 16px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            cursor: pointer;
        }
        .back-button i {
            margin-right: 10px;
        }
        .form-group {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .form-group label {
            flex: 1;
            margin-right: 10px;
        }
        .form-group input, .form-group select {
            flex: 2;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .buttons {
            display: flex;
            justify-content: center;
        }
        .buttons button {
            padding: 10px 20px;
            font-size: 16px;
            width: 1000px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            background-color: #4caf50;
            color: white;
        }
        .form-group input[readonly] {
            background-color: #f4f4f9;
        }
    </style>
</head>
<body>
    <nav>
        <?php include 'navbar/Admin_Navbar.html';?>
    </nav>
    <br>
    <div class="container">
        <div class="back-button" onclick="goBack()">
            <i class="fa fa-arrow-left"></i> Back
        </div>
        <h1>Make Payment</h1>
        <br>
        <form id="appointmentForm" method="POST" action="">
            <input type="hidden" name="appointmentId" value="<?php echo htmlspecialchars($appointmentId); ?>">
            <div class="form-group">
                <label for="customerName">Customer Name</label>
                <input type="text" id="customerName" value="<?php echo htmlspecialchars($Row['CustomerName']); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="customerPhoneNumber">Customer Phone Number</label>
                <input type="text" id="customerPhoneNumber" value="<?php echo htmlspecialchars($Row['CustomerPhone']); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="carInService">Car in Service</label>
                <input type="text" id="carInService" value="<?php echo htmlspecialchars($Row['Model'] . ' (' . $Row['Registration_Number'] . ')'); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="appointmentDate">Appointment Date</label>
                <input type="text" id="appointmentDate" value="<?php echo htmlspecialchars($Row['Date'] . ' ' . $Row['Time']); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="staffInCharge">Staff in Charge</label>
                <input type="text" id="staffInCharge" value="<?php echo htmlspecialchars($Row['StaffName']); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="staffPhoneNumber">Staff Phone Number</label>
                <input type="text" id="staffPhoneNumber" value="<?php echo htmlspecialchars($Row['StaffPhone']); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="serviceStatus">Service Status</label>
                <input type="text" id="serviceStatus" value="<?php echo htmlspecialchars($Row['Status']); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="serviceType">Service Type</label>
                <input type="text" id="serviceType" value="<?php echo htmlspecialchars($Row['Service_Type']); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="totalAmount">Enter Total Amount to be Paid:</label>
                <input type="number" step="0.01" id="totalAmount" name="totalAmount" placeholder="RM " required>
            </div>
            <div class="buttons">
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>
    <script>
        function goBack() {
            window.location.href='Admin_CompletedAppointment.php';
        }
    </script>
</body>
</html>
