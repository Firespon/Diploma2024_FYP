<?php
include("php/conn.php");

// Assuming you have a session variable storing the logged-in user's ID
$id = $_SESSION['mySession'];

// Fetch the email of the logged-in user
$emailQuery = "SELECT Email FROM user WHERE ID = ?";
$emailStmt = mysqli_prepare($con, $emailQuery);
mysqli_stmt_bind_param($emailStmt, "i", $id);
mysqli_stmt_execute($emailStmt);
$emailResult = mysqli_stmt_get_result($emailStmt);
$emailRow = mysqli_fetch_assoc($emailResult);
$userEmail = $emailRow['Email'];

// Fetch transactions from the database
$query = "SELECT appointment.Appointment_ID, appointment.Date, appointment.Time, appointment.Amount_Paid, car.Model, car.Registration_Number, staffUser.Name AS StaffName, appointment.Service_Type, appointment.Feedback_Status, customer.Email AS CustID
          FROM appointment
          JOIN car ON appointment.Car_ID = car.Car_ID
          JOIN staff ON appointment.Staff_ID = staff.Staff_ID
          JOIN user AS staffUser ON staff.Email = staffUser.Email
          JOIN customer ON car.Customer_ID = customer.Customer_ID
          WHERE customer.Email = ? AND appointment.Status = 'Completed'";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "s", $userEmail);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Handle feedback submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $appointmentId = $_POST['appointmentId'];
    $feedback = $_POST['feedback'];
    $rating = $_POST['rating'];

    $insertFeedbackQuery = "INSERT INTO servicefeedback (Appointment_ID, Feedback, Rating) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($con, $insertFeedbackQuery);
    mysqli_stmt_bind_param($stmt, "isi", $appointmentId, $feedback, $rating);

    if (mysqli_stmt_execute($stmt)) {
        // Update Feedback_Status to 'Done'
        $updateFeedbackStatusQuery = "UPDATE appointment SET Feedback_Status = 'Done' WHERE Appointment_ID = ?";
        $updateStmt = mysqli_prepare($con, $updateFeedbackStatusQuery);
        mysqli_stmt_bind_param($updateStmt, "i", $appointmentId);
        mysqli_stmt_execute($updateStmt);

        echo "<script>alert('Feedback submitted successfully.'); window.location.href='Cust_History.php';</script>";
    } else {
        echo "<script>alert('Failed to submit feedback.'); window.location.href='Cust_History.php';</script>";
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
    <title>Previous Transactions</title>
    <style>
        * {
            font-family: "Poppins", sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .container {
            padding: 20px;
        }

        h1 {
            font-size: 2em;
            margin-bottom: 20px;
            text-align: center;
        }

        .transaction-summary, .transaction-details {
            background-color: #e0e0e0;
            border-radius: 5px;
            margin: 20px auto;
            width: 80%;
            max-width: 600px;
            padding: 10px;
            cursor: pointer;
        }

        .transaction-summary .transaction-header, .transaction-details {
            display: flex;
            justify-content: space-between;
            background-color: #4d4d4d;
            color: white;
            padding: 10px;
            border-radius: 5px 5px 0 0;
        }

        .transaction-header {
            margin-top: -10px;
            width: 600px;
            margin-left: -10px;  
        }

        .transaction-info {
            padding: 10px;
        }

        .transaction-info p {
            text-align: left;
            margin-left: 0px; 
        }

        .amount-main {
            color: rgb(249, 36, 36);
            float: right;
            margin-top: -30px;
            font-weight: bolder;
        }

        .close-btn {
            font-size: 15px;
            color: #ccc;
            cursor: pointer;
            padding: 10px 9px;
            background-color: #4d4d4d;
            border-radius: 5px;
            width: 40px;
            margin-left: 260px;
            height: 20px;
        }

        .transaction-details {
            display: none;
        }

        .form-group {
            margin: 10px 0;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input[type="text"] {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }

        .form-group input[type="submit"] {
            padding: 10px 20px;
            background-color: #4caf50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
        }

        .rating input {
            display: none;
        }

        .rating label {
            position: relative;
            width: 1em;
            font-size: 3vw;
            color: #FFD600;
            cursor: pointer;
        }

        .rating label::before {
            content: "★";
            position: absolute;
            opacity: 0;
        }

        .rating label:hover:before,
        .rating label:hover ~ label:before {
            opacity: 1 !important;
        }

        .rating input:checked ~ label:before {
            opacity: 1;
        }

        .rating input:checked ~ label:hover:before,
        .rating input:checked ~ label:hover ~ label:before,
        .rating label:hover ~ input:checked ~ label:before {
            opacity: 0.4;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Service History</h1>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <div class="transaction-summary" onclick="toggleDetails(<?php echo $row['Appointment_ID']; ?>)">
                <div class="transaction-header">
                    <span><?php echo date('jS F Y', strtotime($row['Date'])); ?></span>
                </div>
                <div class="transaction-info">
                    <p><strong>Model: </strong><?php echo htmlspecialchars($row['Model'] . ' (' . $row['Registration_Number'] . ')'); ?></p>
                    <span class="amount-main">-RM <?php echo number_format($row['Amount_Paid'], 2); ?></span>
                    <p><strong>Staff in Charge: </strong><?php echo htmlspecialchars($row['StaffName']); ?></p>
                </div>
            </div>
            
            <div class="transaction-details" id="transaction-details-<?php echo $row['Appointment_ID']; ?>">
                <div class="transaction-info">
                    <p><strong>Model:</strong> <?php echo htmlspecialchars($row['Model'] . ' (' . $row['Registration_Number'] . ')'); ?></p>
                    <table>
                        <thead>
                            <tr>
                                <th>Services:</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $services = explode(',', $row['Service_Type']);
                            foreach ($services as $index => $service) {
                                echo "<tr>";
                                echo "<td>" . ($index + 1) . ". " . htmlspecialchars($service) . "</td>";
                                echo "</tr>";
                            }
                            ?>
                            <tr>
                                <td><strong>Total:</strong></td>
                                <td><strong>RM <?php echo number_format($row['Amount_Paid'], 2); ?></strong></td>
                            </tr>
                        </tbody>
                    </table>
                    <?php if ($row['Feedback_Status'] == NULL) { ?>
                        <form method="POST" action="">
                            <input type="hidden" name="appointmentId" value="<?php echo $row['Appointment_ID']; ?>" autocomplete="off">
                            <div class="form-group">
                                <label for="feedback">Feedback:</label>
                                <input type="text" name="feedback" required autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="rating">Rating:</label>
                                <div class="rating">
                                    <input type="radio" name="rating" id="rating-5-<?php echo $row['Appointment_ID']; ?>" value="5">
                                    <label for="rating-5-<?php echo $row['Appointment_ID']; ?>">☆</label>
                                    <input type="radio" name="rating" id="rating-4-<?php echo $row['Appointment_ID']; ?>" value="4">
                                    <label for="rating-4-<?php echo $row['Appointment_ID']; ?>">☆</label>
                                    <input type="radio" name="rating" id="rating-3-<?php echo $row['Appointment_ID']; ?>" value="3">
                                    <label for="rating-3-<?php echo $row['Appointment_ID']; ?>">☆</label>
                                    <input type="radio" name="rating" id="rating-2-<?php echo $row['Appointment_ID']; ?>" value="2">
                                    <label for="rating-2-<?php echo $row['Appointment_ID']; ?>">☆</label>
                                    <input type="radio" name="rating" id="rating-1-<?php echo $row['Appointment_ID']; ?>" value="1">
                                    <label for="rating-1-<?php echo $row['Appointment_ID']; ?>">☆</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="submit" value="Submit Feedback">
                            </div>
                        </form>
                    <?php } else { ?>
                        <p><strong>Feedback already submitted.</strong></p>
                    <?php } ?>
                </div>
                <div class="close-btn" onclick="toggleDetails(<?php echo $row['Appointment_ID']; ?>)">&#x25B2;</div>
            </div>
        <?php } ?>
    </div>
    
    <script>
        function toggleDetails(id) {
            var details = document.getElementById('transaction-details-' + id);
            if (details.style.display === 'block') {
                details.style.display = 'none';
            } else {
                details.style.display = 'block';
            }
        }
    </script>
</body>
</html>
