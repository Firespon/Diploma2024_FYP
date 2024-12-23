<?php
include('php/conn.php');


$staffEmailQuery = "SELECT user.Name, user.Email, staff.Staff_ID FROM staff 
JOIN user ON user.Email = staff.Email
WHERE Role = 'Staff' ";
$staffEmailResult = mysqli_query($con , $staffEmailQuery);

$sql = "SELECT appointment.Appointment_ID, appointment.Date, appointment.Time, appointment.Service_Type, appointment.Status,
car.Model, car.Registration_Number, customer.Email, user.Name AS CustomerName, staff.Staff_ID, staff.Email AS StaffEmail, s.Name AS StaffName
FROM appointment
JOIN car ON appointment.Car_ID = car.Car_ID
JOIN customer ON car.Customer_ID = customer.Customer_ID
JOIN user ON customer.Email = user.Email
JOIN staff ON appointment.Staff_ID = staff.Staff_ID
JOIN user s ON staff.Email = s.Email
WHERE appointment.Status = 'Accepted'";

$result = mysqli_query($con, $sql);

if (!$result) {
    echo "Error: " . mysqli_error($con);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Appointment</title>
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
        .filter-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .filter-container input[type="date"] {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
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
        <h1>Upcoming Appointment</h1><br>
        <div class="filter-container">
            <input type="date" id="filterDate">
            <button onclick="filterTable()">DATE</button>
        </div>
        <table id="appointmentTable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Customer</th>
                    <th>Car(Reg. Number)</th>
                    <th>Appointment Date</th>
                    <th>Service Type</th>
                    <th>Staff</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $no = 1;
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $serviceTypes = explode(", ", $row["Service_Type"]);
                            $groupedServiceTypes = array_chunk($serviceTypes, 6);

                            echo "<tr>";
                            echo "<td>" . $no++ . "</td>";
                            echo "<td>". $row["CustomerName"]. "</td>";
                            echo "<td>". $row["Model"]. " <br> (". $row["Registration_Number"]. ")</td>";
                            echo "<td>". $row["Date"]. " <br> ". $row["Time"]. "</td>";

                            echo "<td class='service-types-cell'>";
                            foreach ($groupedServiceTypes as $types) {
                                echo "<ul>";
                                foreach ($types as $type) {
                                    echo "<li>$type</li>";
                                }
                                echo "</ul>";
                            }
                            echo "</td>";
                            echo "<td>". $row["StaffName"]. "</td>";

                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>0 results</td></tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>
    <script>
        function filterTable() {
            const filterDate = document.getElementById('filterDate').value;
            const table = document.getElementById('appointmentTable');
            const trs = table.getElementsByTagName('tr');
            
            for (let i = 1; i < trs.length; i++) {
                const td = trs[i].getElementsByTagName('td')[3]; 
                if (td) {
                    const dateText = td.textContent || td.innerText;
                    const [datePart] = dateText.split(' '); 
                    const appointmentDate = new Date(datePart);
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
