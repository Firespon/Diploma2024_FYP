<?php
include("session.php");
include("conn.php");

if (isset($_GET['id']) && isset($_POST['totalAmount'])) {
    $appointmentId = $_GET['id'];
    $totalAmount = $_POST['totalAmount'];

    $query = "UPDATE appointment SET Amount_Paid = ? WHERE Appointment_ID = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("di", $totalAmount, $appointmentId); // Assuming Amount_Paid is a decimal (d) or integer (i) type

    if ($stmt->execute()) {
        echo "<script>alert('Payment updated successfully.'); window.location.href = '../Admin_CompletedAppointment.php';</script>";
    } else {
        echo "<script>alert('Failed to update payment.'); window.location.href = '../Admin_CompletedAppointment.php';</script>";
    }

    $stmt->close();
    $con->close();
} else {
    // Redirect or handle invalid requests
    header("Location: ../Admin_CompletedAppointment.php");
    exit();
}
?>
