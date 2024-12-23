<?php

include('php/conn.php');

$id = $_SESSION['mySession'];

// SQL query to get user's email based on userID
$idQuery = "SELECT Email FROM user WHERE ID = '$id'";
$idResult = mysqli_query($con, $idQuery);

if (!$idResult) {
    echo "<script>alert('Error fetching user information.'); window.location.href='Staff_Tasks.php';</script>";
    exit();
}

$row = mysqli_fetch_assoc($idResult);
if (!$row) {
    echo "<script>alert('No user information found.'); window.location.href='Staff_Tasks.php';</script>";
    exit();
}
$email = $row['Email'];

// Query to get Staff_ID from staff table using email
$staffQuery = "SELECT Staff_ID FROM staff WHERE Email = '$email'";
$staffResult = mysqli_query($con, $staffQuery);

if (!$staffResult) {
    echo "<script>alert('Error fetching staff information.'); window.location.href='Staff_Tasks.php';</script>";
    exit();
}

$row = mysqli_fetch_assoc($staffResult);
if (!$row) {
    echo "<script>alert('No staff information found.'); window.location.href='Staff_Tasks.php';</script>";
    exit();
}
$staffID = $row['Staff_ID'];

// SQL query to fetch appointments related to the logged-in staff member
$sql = "SELECT a.Appointment_ID, u.Name AS Customer, a.Date, a.Service_Type, c.Registration_Number AS Car_Reg_Num, a.Status
        FROM appointment a
        INNER JOIN car c ON a.Car_ID = c.Car_ID
        INNER JOIN customer cu ON c.Customer_ID = cu.Customer_ID
        INNER JOIN user u ON cu.Email = u.Email
        WHERE a.Staff_ID = '$staffID'";

$result = mysqli_query($con, $sql);

if (!$result) {
    echo "<script>alert('Error fetching appointments.'); window.location.href='Staff_Tasks.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tasks</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
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
        h1 {
            text-align: center;
            font-size: 2em;
            margin-bottom: 20px;
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
        .filter-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            cursor: pointer;
        }
        .filter-container input[type="date"] {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            cursor: pointer;
        }
        .filter-container button {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            background-color: #888;
            color: white;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Tasks</h1>
        <br>
        <div class="filter-container">
            <input type="date" id="filterDate">
            <button onclick="filterTable()">DATE</button>
        </div>
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Customer</th>
                    <th>Appointment Date</th>
                    <th>Service Type</th>
                    <th>Car (Reg. Num)</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="appointment-table">
                <?php
                // Display fetched data in table rows
                $i = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $i . "</td>";
                    echo "<td>" . $row['Customer'] . "</td>";
                    echo "<td>" . $row['Date'] . "</td>";
                    echo "<td>" . $row['Service_Type'] . "</td>";
                    echo "<td>" . $row['Car_Reg_Num'] . "</td>";
                    echo "<td>" . $row['Status'] . "</td>";
                    echo "</tr>";
                    $i++;
                }if ($i == 1) {
                    echo "<tr><td colspan='6'>No tasks found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script>
    function filterTable() {
        const filterDate = document.getElementById('filterDate').value;
        const table = document.getElementById('appointment-table');
        const trs = table.getElementsByTagName('tr');
        
        for (let i = 0; i < trs.length; i++) { // Loop from 0 to include all rows
            const td = trs[i].getElementsByTagName('td')[2]; // Use index 2 for the date column
            if (td) {
                const dateText = td.textContent || td.innerText;
                const appointmentDate = new Date(dateText);
                const selectedDate = new Date(filterDate);
                
                if (selectedDate.toDateString() === appointmentDate.toDateString()) {
                    trs[i].style.display = '';
                } else {
                    trs[i].style.display = 'none';
                }
            }
        }
    }
</script>
</body>
</html>

<?php
mysqli_close($con); // Close the database connection
?>
    
