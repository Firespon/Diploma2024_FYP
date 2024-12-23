<?php
include("session.php");
include('php/conn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id']) && isset($_POST['status'])) {
        $appointmentId = $_POST['id'];
        $status = $_POST['status'];

        $sql = "UPDATE appointment SET Status = ? WHERE Appointment_ID = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("si", $status, $appointmentId);

        if ($stmt->execute()) {
            echo "<script>alert('Status Updated Successfully!');window.location.href='Admin_IncompletedAppointment.php';</script>";
        } else {
            echo "<script>alert('Error Updating Status!');window.location.href='Admin_IncompletedAppointment.php';</script>";
        }

        $stmt->close();
    } else {
        echo "Invalid request.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/scrollbar.css">
    <link rel="icon" type="image/x-icon" href="pictures/favicon.ico">
    <title>Service Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
        .status-select {
            padding: 10px;
            font-size: 1em;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .status-accepted {
            color: white;
            background-color: #2196F3;
        }
        .status-under-review {
            color: white;
            background-color: #FF9800;
        }
        .status-on-going {
            color: white;
            background-color: #FF5722;
        }
        .status-completed {
            color: white;
            background-color: #4CAF50;
        }
        .status-cancel {
            color: white;
            background-color: maroon;
        }
        .delete-btn {
            background: none;
            border: none;
            color: #ff4d4d;
            cursor: pointer;
        }
        .delete-btn i {
            font-size: 1.5em;
        }
    </style>
</head>
<body>
    <nav>
      <?php include 'navbar/Admin_Navbar.html';?>
    </nav>
    <div class="container">
        <h1>Incomplete Services</h1>
        <br><br>
        <table class="table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Customer</th>
                    <th>Car<br>(Reg. Number)</th>
                    <th>Appointment Date</th>
                    <th>Staff</th>
                    <th>Service Type</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php

                $sql = "SELECT appointment.Appointment_ID, appointment.Date, appointment.Time, appointment.Service_Type, appointment.Status,
                car.Model, car.Registration_Number, customer.Email, user.Name AS CustomerName, staff.Staff_ID, staff.Email AS StaffEmail, s.Name AS StaffName
                FROM appointment
                JOIN car ON appointment.Car_ID = car.Car_ID
                JOIN customer ON car.Customer_ID = customer.Customer_ID
                JOIN user ON customer.Email = user.Email
                JOIN staff ON appointment.Staff_ID = staff.Staff_ID
                JOIN user s ON staff.Email = s.Email
                WHERE appointment.Status != 'Pending' AND
                appointment.Status != 'Cancelled' AND
                appointment.Status != 'Completed' ";

                $result = $con->query($sql);

                $staffEmailQuery = "SELECT user.Name, user.Email, staff.Staff_ID FROM staff 
                JOIN user ON user.Email = staff.Email
                WHERE Role = 'Staff' ";
                $staffEmailResult = mysqli_query($con , $staffEmailQuery);
                $staffrow = mysqli_fetch_assoc($staffEmailResult);

                if ($result->num_rows > 0) {
                    $counter = 1;
                    while ($row = $result->fetch_assoc()) {
                        $status_class = strtolower(str_replace(" ", "-", $row["Status"]));
                        echo "<tr>
                                <td>{$counter}</td>
                                <td>{$row['CustomerName']}</td>
                                <td>{$row['Model']}<br>({$row['Registration_Number']})</td>
                                <td>{$row['Date']}<br>{$row['Time']}</td>
                                <td>{$row['StaffName']}</td>
                                <td>{$row['Service_Type']}</td>
                                <td>
                                    <form method='POST' style='display:inline;'>
                                        <select class='status-select status-{$status_class}' name='status' onchange='this.form.submit()'>
                                            <option value='Accepted' class='status-accepted'" . ($row['Status'] == 'Accepted' ? ' selected' : '') . ">Accepted</option>
                                            <option value='Under Review' class='status-under-review'" . ($row['Status'] == 'Under Review' ? ' selected' : '') . ">Under Review</option>
                                            <option value='On Going' class='status-on-going'" . ($row['Status'] == 'On Going' ? ' selected' : '') . ">On Going</option>
                                            <option value='Completed' class='status-completed'" . ($row['Status'] == 'Completed' ? ' selected' : '') . ">Completed</option>
                                            <option value='Cancelled' class='status-cancel'" . ($row['Status'] == 'Cancelled' ? ' selected' : '') . ">Cancel</option>
                                        </select>
                                        <input type='hidden' name='id' value='{$row['Appointment_ID']}'>
                                    </form>
                                </td>
                              </tr>";
                        $counter++;
                    }
                } else {
                    echo "<tr><td colspan='8'>No records found.</td></tr>";
                }

                $con->close();
                ?>
            </tbody>
        </table>
    </div>
    <script>
        document.querySelectorAll('.status-select').forEach(select => {
            select.addEventListener('change', function() {
                updateSelectColor(this);
            });
            updateSelectColor(select);
        });

        function updateSelectColor(selectElement) {
            selectElement.classList.remove('status-accepted', 'status-under-review', 'status-on-going', 'status-completed', 'status-cancel');
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            selectElement.classList.add(`status-${selectedOption.value.toLowerCase().replace(" ", "-")}`);
        }
    </script>
</body>
</html>
