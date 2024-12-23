<?php
include('php/conn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);

    $sql = "DELETE FROM servicefeedback WHERE ServiceFeedback_ID = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        echo "<script>alert('Feedback deleted successfully'); window.location.href='Admin_ViewAppointmentFeedback.php';</script>";
    } else {
        echo "<script>alert('Error deleting feedback'); window.location.href='Admin_ViewAppointmentFeedback.php';</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Feedback</title>
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
        .delete-btn {
            background: none;
            border: none;
            color: #ff4d4d;
            cursor: pointer;
        }
        .delete-btn i {
            font-size: 1.5em;
        }
        .confirm-popup {
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

        .confirm-popup .close-btn {
            display: block;
            text-align: right;
            cursor: pointer;
            margin-bottom: 5px;
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Appointment Feedback</h1>
        <br>
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Appointment Date</th>
                    <th>Customer</th>
                    <th>Service</th>
                    <th>Rating</th>
                    <th>Remarks</th>
                    <th>Staff Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="feedback-table">
                <?php
                $sql = "SELECT 
                            sf.ServiceFeedback_ID,
                            a.Appointment_ID,
                            sf.Feedback,
                            sf.Rating,
                            a.Date,
                            a.Time,
                            a.Service_Type,
                            u.Name AS Customer_Name,
                            u2.Name AS Staff_Name
                        FROM 
                            servicefeedback sf
                        JOIN 
                            appointment a ON sf.Appointment_ID = a.Appointment_ID
                        JOIN 
                            car c ON a.Car_ID = c.Car_ID
                        JOIN 
                            customer cust ON c.Customer_ID = cust.Customer_ID
                        JOIN 
                            user u ON cust.Email = u.Email
                        JOIN 
                            staff s ON a.Staff_ID = s.Staff_ID
                        JOIN 
                            user u2 ON s.Email = u2.Email";

                $result = $con->query($sql);

                if ($result->num_rows > 0) {
                    $counter = 1;
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $counter . "</td>";
                        echo "<td>" . $row["Date"] . "</td>";
                        echo "<td>" . $row["Customer_Name"] . "</td>";
                        echo "<td>";
                        foreach(explode(',', $row["Service_Type"]) as $serviceType) {
                            echo "$serviceType <br>";
                        }
                        echo "</td>";
                        echo "<td>" . $row["Rating"] . "</td>";
                        echo "<td>" . $row["Feedback"] . "</td>";
                        echo "<td>" . $row["Staff_Name"] . "</td>";
                        echo "<td><button class='delete-btn' data-id='" . $row["ServiceFeedback_ID"] . "'><i class='fa fa-trash'></i></button></td>";
                        echo "</tr>";
                        $counter++;
                    }
                } else {
                    echo "<tr><td colspan='8'>No feedback available</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div id="confirm-popup" class="confirm-popup">
        <span class="close-btn" onclick="closeConfirmPopup()">✖️</span>
        <div class="confirm-message">Are you sure you want to delete this feedback?</div>
        <div class="buttons">
            <button class="confirm-yes" onclick="confirmDelete()">Yes</button>
            <button class="confirm-no" onclick="closeConfirmPopup()">No</button>
        </div>
    </div>

    <form id="delete-form" method="POST" action="" style="display:none;">
        <input type="hidden" name="delete_id" id="delete-id">
    </form>

    <script>
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const feedbackId = this.getAttribute('data-id');
                showConfirmPopup(feedbackId);
            });
        });

        function showConfirmPopup(id) {
            document.getElementById('confirm-popup').style.display = 'block';
            document.getElementById('confirm-popup').setAttribute('data-id', id);
        }

        function closeConfirmPopup() {
            document.getElementById('confirm-popup').style.display = 'none';
        }

        function confirmDelete() {
            const id = document.getElementById('confirm-popup').getAttribute('data-id');
            document.getElementById('delete-id').value = id;
            document.getElementById('delete-form').submit();
        }
    </script>
</body>
</html>
