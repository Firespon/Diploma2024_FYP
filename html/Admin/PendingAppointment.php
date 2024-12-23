<?php
include('php/conn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancel_appointment'])) {
    $appointmentId = $_POST['appointment_id'];
    $cancelSql = "UPDATE appointment SET Status='Cancelled' WHERE Appointment_ID='$appointmentId'";
    if (mysqli_query($con, $cancelSql)) {
        echo "<script>alert('Appointment cancelled successfully.');window.location.href='Admin_PendingAppointment.php';</script>";
    } else {
        echo "<script>alert('Error cancelling appointment. Please try again.');window.location.href='Admin_PendingAppointment.php';</script>";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['assign_staff'])) {
    $appointmentId = $_POST['appointment_id'];
    $staffId = $_POST['staff_id'];
    $assignSql = "UPDATE appointment SET Staff_ID='$staffId', Status='Accepted' WHERE Appointment_ID='$appointmentId'";
    if (mysqli_query($con, $assignSql)) {
        echo "<script>alert('Staff assigned and appointment accepted successfully.');window.location.href='Admin_PendingAppointment.php';</script>";
    } else {
        echo "<script>alert('Error assigning staff. Please try again.');window.location.href='Admin_PendingAppointment.php';</script>";
    }
}

$staffEmailQuery = "SELECT user.Name, user.Email, staff.Staff_ID, staff.Job_Status FROM staff 
JOIN user ON user.Email = staff.Email
WHERE user.Role = 'Staff' AND staff.Job_Status != 'Resigned'";
$staffEmailResult = mysqli_query($con, $staffEmailQuery);

$sql = "SELECT appointment.Appointment_ID, appointment.Date, appointment.Time, appointment.Service_Type, appointment.Status,
car.Model, car.Registration_Number, customer.Email, user.Name FROM appointment
JOIN car ON appointment.Car_ID = car.Car_ID
JOIN customer ON car.Customer_ID = customer.Customer_ID
JOIN user ON customer.Email = user.Email
WHERE appointment.Status = 'Pending'";

$result = mysqli_query($con, $sql);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Appointment</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
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
        .checkbox-btn {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .checkbox-btn input {
            display: none;
        }
        .checkbox-btn label {
            cursor: pointer;
            padding: 5px;
            font-size: 1.5em;
        }
        .checkbox-btn .fa-check {
            color: green;
        }
        .checkbox-btn .fa-check:hover {
            color: rgb(25, 214, 25);
        }
        .checkbox-btn .fa-times {
            color: red;
        }
        .checkbox-btn .fa-times:hover {
            color: rgb(163, 8, 8);
        }
        .popup, .confirm-popup {
            display: none; 
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background-color: white;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            width: auto; 
            border-radius: 10px; 
        }

        .popup .close-btn, .confirm-popup .close-btn {
            display: block;
            text-align: right;
            cursor: pointer;
            margin-bottom: 5px;
        }

        .assign-staff {
            margin-bottom: 20px;
        }

        .assign-staff input[type="text"] {
            width: calc(100% - 42px); /* Adjusted width to account for search icon space */
            padding: 10px;
            box-sizing: border-box;
            margin-bottom: 10px;
            font-size: 1em;
            border-radius: 5px; /* Added border-radius for rounded corners */
        }

        .staff-list button {
            width: 100%;
            padding: 10px;
            background-color: #D32F2F; /* Adjusted color shade of red */
            color: white;
            border: none;
            cursor: pointer;
            margin-bottom: 5px;
            font-size: 1em;
        }

        .confirm-popup .confirm-message {
            margin-bottom: 20px;
            font-size: 1.1em;
            text-align: center;
        }
        .confirm-popup .buttons {
            display: flex;
            justify-content: space-between;
        }
        .confirm-popup button {
            width: 48%;
            padding: 10px;
            border: none;
            cursor: pointer;
            font-size: 1em;
        }
        .confirm-popup .confirm-yes {
            background-color: #4CAF50;
            color: white;
        }
        .confirm-popup .confirm-no {
            background-color: #f44336;
            color: white;
        }
        .service-types-cell {
            max-width: 200px; /* Adjust based on your needs */
            word-wrap: break-word; /* Allow text to wrap */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Pending Appointments</h1>
        <br>
        <br>
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Appointment Date</th>
                    <th>Customer</th>
                    <th>Car<br>(Reg. Number)</th>
                    <th>Service Type</th>
                    <th>Staff</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $serviceTypes = explode(", ", $row["Service_Type"]);
                        $groupedServiceTypes = array_chunk($serviceTypes, 6);

                        echo "<tr>";
                        echo "<td>". $row["Appointment_ID"]. "</td>";
                        echo "<td>". $row["Date"]. " <br> ". $row["Time"]. "</td>";
                        echo "<td>". $row["Name"]. "</td>";
                        echo "<td>". $row["Model"]. " <br> (". $row["Registration_Number"]. ")</td>";

                        echo "<td class='service-types-cell'>";
                        foreach ($groupedServiceTypes as $types) {
                            echo "<ul>";
                            foreach ($types as $type) {
                                echo "<li>$type</li>";
                            }
                            echo "</ul>";
                        }
                        echo "</td>";

                        echo "<td>N/A</td>";
                        echo "<td class=\"checkbox-btn\">";
                        echo "<input type=\"checkbox\" id=\"check\">";
                        echo "<label for=\"check\" class=\"checkmark\"><i class=\"fa fa-check\"></i></label>";
                        echo "<label for=\"check\" class=\"xmark\" data-id=\"". $row["Appointment_ID"]. "\"><i class=\"fa fa-times\"></i></label>";
                        echo "</td>";

                        echo "</tr>";
                    }
                } else {
                    echo "0 results";
                }
                ?>
            </tbody>
        </table>

        <div id="popup" class="popup">
            <span class="close-btn" onclick="closePopup()">✖️</span>
            <div class="assign-staff">
                <input type="text" id="staff-search" placeholder="Search staff...">
            </div>
            <div id="staff-list" class="staff-list">
                <?php
                if ($staffEmailResult->num_rows > 0) {
                    while($row = $staffEmailResult->fetch_assoc()) {
                        echo "<button name=\"assignBtn\" onclick=\"assignStaff(". $row['Staff_ID'] .")\">". $row['Name']."</button>";
                    }
                } else {
                    echo "No available Staff";
                }
                ?>
            </div>
        </div>


        <div id="confirm-popup" class="confirm-popup">
            <span class="close-btn" onclick="closeConfirmPopup()">✖️</span>
            <div class="confirm-message">Are you sure you want to cancel the appointment?</div>
            <div class="buttons">
                <button class="confirm-yes" onclick="confirmCancel()">Yes</button>
                <button class="confirm-no" onclick="closeConfirmPopup()">No</button>
            </div>
        </div>
    </div>

    <script>
        function assignStaff(staffId) {
            const appointmentId = document.getElementById('popup').getAttribute('data-appointment-id');
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '';

            const appointmentInput = document.createElement('input');
            appointmentInput.type = 'hidden';
            appointmentInput.name = 'appointment_id';
            appointmentInput.value = appointmentId;
            form.appendChild(appointmentInput);

            const staffInput = document.createElement('input');
            staffInput.type = 'hidden';
            staffInput.name = 'staff_id';
            staffInput.value = staffId;
            form.appendChild(staffInput);

            const assignInput = document.createElement('input');
            assignInput.type = 'hidden';
            assignInput.name = 'assign_staff';
            assignInput.value = 'true';
            form.appendChild(assignInput);

            document.body.appendChild(form);
            form.submit();
        }

        document.querySelectorAll('.checkmark').forEach((checkMark) => {
            checkMark.addEventListener('click', function(event) {
                event.stopPropagation();
                event.preventDefault();
                const appointmentId = this.closest('tr').querySelector('.xmark').getAttribute('data-id');
                showPopup(appointmentId);
            });
        });

        document.querySelectorAll('.xmark').forEach((cancelMark) => {
            cancelMark.addEventListener('click', function(event) {
                const appointmentId = this.getAttribute('data-id');
                event.stopPropagation();
                event.preventDefault();
                showConfirmPopup(appointmentId);
            });
        });


        function showPopup(appointmentId) {
            const popup = document.getElementById('popup');
            popup.style.display = 'block';
            popup.setAttribute('data-appointment-id', appointmentId);
        }

        function closePopup() {
            const popup = document.getElementById('popup');
            popup.style.display = 'none';
            popup.removeAttribute('data-appointment-id');
            document.getElementById('staff-search').value = '';
            document.querySelectorAll('#staff-list button').forEach((button) => {
                button.style.display = '';
            });
            document.querySelectorAll('.checkbox-btn input').forEach((checkbox) => {
                checkbox.checked = false;
            });
        }

        function showConfirmPopup(id) {
            document.getElementById('confirm-popup').style.display = 'block';
            document.getElementById('confirm-popup').setAttribute('data-id', id);
        }

        function closeConfirmPopup() {
            document.getElementById('confirm-popup').style.display = 'none';
        }

        function confirmCancel() {
            const id = document.getElementById('confirm-popup').getAttribute('data-id');
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '';

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'appointment_id';
            input.value = id;
            form.appendChild(input);

            const cancelInput = document.createElement('input');
            cancelInput.type = 'hidden';
            cancelInput.name = 'cancel_appointment';
            cancelInput.value = 'true';
            form.appendChild(cancelInput);

            document.body.appendChild(form);
            form.submit();
        }

        function assignStaff(staffId) {
            const appointmentId = document.getElementById('popup').getAttribute('data-appointment-id');
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '';

            const appointmentInput = document.createElement('input');
            appointmentInput.type = 'hidden';
            appointmentInput.name = 'appointment_id';
            appointmentInput.value = appointmentId;
            form.appendChild(appointmentInput);

            const staffInput = document.createElement('input');
            staffInput.type = 'hidden';
            staffInput.name = 'staff_id';
            staffInput.value = staffId;
            form.appendChild(staffInput);

            const assignInput = document.createElement('input');
            assignInput.type = 'hidden';
            assignInput.name = 'assign_staff';
            assignInput.value = 'true';
            form.appendChild(assignInput);

            document.body.appendChild(form);
            form.submit();
        }


        document.getElementById('staff-search').addEventListener('input', function() {
            let filter = this.value.toUpperCase();
            let buttons = document.querySelectorAll('#staff-list button');
            buttons.forEach((button) => {
                let text = button.textContent || button.innerText;
                if (text.toUpperCase().indexOf(filter) > -1) {
                    button.style.display = '';
                } else {
                    button.style.display = 'none';
                }
            });
        });

        document.querySelector('table tbody').addEventListener('click', function(event) {
            var target = event.target;
            while (target && target !== this) {
                if (target.matches('.service-types-cell')) {
                    showPopup();
                    return;
                }
                target = target.parentNode;
            }
        });
    </script>
</body>
</html>