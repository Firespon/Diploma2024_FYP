<?php
include('conn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $car_id = $_POST['car_id'];

    $deleteQuery = "DELETE FROM car WHERE Car_ID='$car_id'";

    if (mysqli_query($con, $deleteQuery)) {
        echo "<script>alert('Car deleted successfully'); window.location.href='../Cust_Profile.php'</script>";
    } else {
        echo "Error deleting car: " . mysqli_error($con);
    }

    mysqli_close($con);
}
?>
