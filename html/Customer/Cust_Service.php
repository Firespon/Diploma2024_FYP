<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>View Service Status</title>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

    * {
        font-family: "Poppins", sans-serif;
        margin: 0;
        padding: 0;
    }
    body {
        background-color: #f4f4f9;
    }
    .container {
        background-color: #fff;
        width: 80%;
        margin: 0 auto;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    h1 {
        text-align: center;
        color: #333;
        margin-bottom: 20px;
    }
    .service-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        background-color: #f9f9f9;
        border-radius: 5px;
    }
    .service-name {
        font-weight: bold;
    }
    .service-status {
        text-align: center;
        padding: 3px;
        width: 128px;
        color: #fff;
        border-radius: 5px;
    }
    .completed {
        background-color: #4CAF50;
    }
    .under-review {
        background-color: #FF9800;
    }
    .accepted {
        background-color: #2196F3;
    }
    .on-going {
        background-color: #FF5722;
    }
</style>
</head>
<body>
    <div class="container">
        <h1>VIEW APPOINTMENT STATUS</h1>
        <?php
        include('php/conn.php');

        $sql = "SELECT appointment.Appointment_ID, appointment.Date, appointment.Time, appointment.Service_Type, appointment.Status,
        car.Model, car.Registration_Number, customer.Email, user.Name 
        FROM appointment
        JOIN car ON appointment.Car_ID = car.Car_ID
        JOIN customer ON car.Customer_ID = customer.Customer_ID
        JOIN user ON customer.Email = user.Email
        WHERE appointment.Status != 'Pending' 
        AND appointment.Status != 'Cancelled'
        AND appointment.Amount_Paid IS NULL";
 
        $result = $con->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $status_class = "";
                $serviceTypes = explode(", ", $row["Service_Type"]);
                $groupedServiceTypes = array_chunk($serviceTypes, 6);
                switch ($row["Status"]) {
                    case "Accepted":
                        $status_class = "accepted";
                        break;
                    case "Under Review":
                        $status_class = "under-review";
                        break;
                    case "On Going":
                        $status_class = "on-going";
                        break;
                    case "Completed":
                        $status_class = "completed";
                        break;
                }
                echo "<div class='service-item'>
                        <p><span style='font-weight: bold;'>{$row['Model']}</span><br><span class='service-name'>";
                foreach ($groupedServiceTypes as $types) {
                    echo "<ul>";
                    foreach ($types as $type) {
                        echo "<li>$type</li>";
                    }
                    echo "</ul></span>";
                }
                echo "</p>
                        <p>{$row['Date']} at {$row['Time']}</p>
                        <span class='service-status $status_class'>{$row['Status']}</span>
                      </div>";
            }
        } else {
            echo "No records found.";
        }
        ?>
    </div>
</body>
</html>
