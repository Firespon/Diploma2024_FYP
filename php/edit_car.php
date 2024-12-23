<?php
include('conn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $car_id = $_POST['car_id'];
    $brand = $_POST['car-brand'];
    $model = $_POST['car-model'];
    $regNumber = $_POST['edit-reg-number'];
    $year = $_POST['manufactured-year'];

    $updateQuery = "UPDATE car SET Brand='$brand', Model='$model', Registration_Number='$regNumber', Year='$year' WHERE Car_ID='$car_id'";

    if (mysqli_query($con, $updateQuery)) {
        echo "<script>alert('Car updated successfully'); window.location.href='../Cust_Profile.php'</script>.";
    } else {
        echo "Error updating car: " . mysqli_error($con);
    }

    mysqli_close($con);
}
?>
