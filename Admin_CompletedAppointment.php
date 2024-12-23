<?php
include("session.php");
include('php/conn.php'); // Assuming this includes your database connection

$sql = "SELECT appointment.Appointment_ID, appointment.Date, appointment.Time, appointment.Service_Type, appointment.Status,
        car.Model, car.Registration_Number, customer.Email, user.Name AS CustomerName, staff.Staff_ID, staff.Email AS StaffEmail, s.Name AS StaffName,
        appointment.Amount_Paid
        FROM appointment
        JOIN car ON appointment.Car_ID = car.Car_ID
        JOIN customer ON car.Customer_ID = customer.Customer_ID
        JOIN user ON customer.Email = user.Email
        JOIN staff ON appointment.Staff_ID = staff.Staff_ID
        JOIN user s ON staff.Email = s.Email
        WHERE appointment.Status = 'Completed'";

$result = $con->query($sql);
$staffEmailQuery = "SELECT user.Name, user.Email, staff.Staff_ID FROM staff 
                   JOIN user ON user.Email = staff.Email
                   WHERE Role = 'Staff' ";
$staffEmailResult = mysqli_query($con , $staffEmailQuery);
$staffrow = mysqli_fetch_assoc($staffEmailResult);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Management System</title>
    <link rel="stylesheet" type="text/css" href="css/scrollbar.css">
    <link rel="icon" type="image/x-icon" href="pictures/favicon.ico">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f9;
        }
        .container {
            width: 80%;
            margin: auto;
            padding-top: 20px;
        }
        h1, h2 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #fff;
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
            border-bottom: 1px solid #ddd;
        }
        td {
            border-bottom: 1px solid #ddd;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .table a {
            text-decoration: underline;
            color: #aa0000; /* Change the link color */
        }
        .table a:hover {
            color: #e92214; /* Change the link color on hover */
        }
    </style>
</head>
<body>
    <nav>
      <?php include 'navbar/Admin_Navbar.html';?>
    </nav>
    <div class="container">

        <h1>Completed Services</h1>
        <br><br>
        <table class="table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Customer</th>
                    <th>Car(Reg. Number)</th>
                    <th>Appointment Date</th>
                    <th>Service</th>
                    <th>Status</th>
                    <th>Amount Paid</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    $counter = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$counter}</td>
                                <td>{$row['CustomerName']}</td>
                                <td>{$row['Model']}<br> ({$row['Registration_Number']})</td>
                                <td>{$row['Date']} <br>{$row['Time']}</td>
                                <td>{$row['Service_Type']}</td>
                                <td>{$row['Status']}</td>";
                                
                        if (is_null($row['Amount_Paid'])) {
                            echo "<td><a href='Admin_Payment.php?id=" . $row['Appointment_ID'] . "'>Make Payment</a></td>";
                        } else {
                            echo "<td>RM {$row['Amount_Paid']}</td>";
                        }
                        
                        echo "</tr>";
                        $counter++;
                    }
                } else {
                    echo "<tr><td colspan='8'>No completed appointments found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
