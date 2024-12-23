<?php

include('php/conn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['save-btn'])) {
        // Check if all required fields are filled
        $requiredFields = ['name', 'dateEmployed', 'phoneNumber', 'email', 'address', 'hoursWorked'];
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                echo "<script>alert('Please fill in all the fields.');window.location.href='Management_ManageStaff.php';</script>";
                exit();
            }
        }

        // Validate email format
        $email = $_POST['email'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Invalid email format. Please try again.');window.location.href='Management_ManageStaff.php';</script>";
            exit();
        }

        // Validate phone number format
        $phoneNumber = $_POST['phoneNumber'];
        if (!preg_match('/^01\d{8,9}$/', $phoneNumber)) {
            echo "<script>alert('Phone number must start with 01 and be followed by exactly 8 or 9 digits.');window.location.href='Management_ManageStaff.php';</script>";
            exit();
        }

        // Validate hours worked
        $hoursWorked = $_POST['hoursWorked'];
        if (!is_numeric($hoursWorked) || $hoursWorked >= 56) {
            echo "<script>alert('Hours worked must be a number less than 56.');window.location.href='Management_ManageStaff.php';</script>";
            exit();
        }

        // Assign variables from $_POST
        $name = $_POST['name'];
        $dateEmployed = $_POST['dateEmployed'];
        $address = $_POST['address'];
        $userID = $_POST['userID'];

        // Retrieve the current email from the database based on the new email
        $sql = "SELECT ID FROM user WHERE Email = '$email'";
        $result = mysqli_query($con, $sql);
        if (!$result || mysqli_num_rows($result) != 1) {
            echo "<script>alert('Error retrieving user ID.');window.location.href='Management_ManageStaff.php';</script>";
            exit();
        }
        $row = mysqli_fetch_assoc($result);
        $userID = $row['ID'];

        // Disable foreign key checks to allow the update
        mysqli_query($con, "SET FOREIGN_KEY_CHECKS=0");

        // Update user information
        $updateUserSql = "UPDATE user SET Name='$name', Email='$email', Phone_Number='$phoneNumber' WHERE ID='$userID'";
        $updateStaffSql = "UPDATE staff SET Date_Employed='$dateEmployed', Email='$email', Address='$address', Hours_Worked='$hoursWorked' WHERE Email='$email'";

        if (mysqli_query($con, $updateUserSql) && mysqli_query($con, $updateStaffSql)) {
            // Re-enable foreign key checks after the update
            mysqli_query($con, "SET FOREIGN_KEY_CHECKS=1");
            echo "<script>alert('Successfully Updated Staff!'); window.location.href='Management_ManageStaff.php';</script>";
            exit();
        } else {
            // Re-enable foreign key checks in case of an error
            mysqli_query($con, "SET FOREIGN_KEY_CHECKS=1");
            echo "<script>alert('Error updating staff information. Please try again.');window.location.href='Management_ManageStaff.php';</script>";
            exit();
        }
    } elseif (isset($_POST['delete-btn'])) {
        $staffID = $_POST['staffID'];
        $status = 'Resigned';
    
        // Prepare and bind parameters
        $stmt = $con->prepare("UPDATE staff SET Job_Status =? WHERE Staff_ID=?");
        $stmt->bind_param("si", $status, $staffID);
    
        // Execute the statement
        if ($stmt->execute()) {
            // Re-enable foreign key checks after the deletion
            mysqli_query($con, "SET FOREIGN_KEY_CHECKS=1");
            echo "<script>alert('Successfully Removed Staff'); window.location.href='Management_ManageStaff.php';</script>";
            exit();
        } else {
            // Handle error
            echo "<script>alert('Error removing staff. Please try again.');window.location.href='Management_ManageStaff.php';</script>";
            exit();
        }
    }
    
}

// Fetch all staff data for display
$staffData = [];
$sql = "SELECT u.ID as userID, u.Name, s.Staff_ID, s.Email, s.Date_Employed, u.Phone_Number, s.Hours_Worked, s.Address, s.Job_Status
FROM staff s 
JOIN user u ON s.Email = u.Email
WHERE s.Job_Status = 'Employed'";
$result = mysqli_query($con, $sql);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $staffData[] = $row;
    }
} else {
    echo "<script>alert('No staff found.');</script>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Staff</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .managestaff {
            display: flex;
            justify-content: center;
            align-items: center;
            height: max-content;
            margin: 0;
            background-color: #f8f8f8;
        }

        .container {
            width: 80%;
            max-width: 1200px;
        }

        h1 {
            text-align: center;
            font-size: 2em;
            margin-bottom: 20px;
        }

        .search-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
            position: relative;
        }

        #search {
            padding: 10px;
            width: 100%;
            max-width: 400px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-results {
            width: 100%;
            max-width: 400px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            position: absolute;
            top: 50px;
            z-index: 1;
        }

        .search-results button {
            width: 100%;
            padding: 10px;
            border: none;
            background: none;
            text-align: left;
            cursor: pointer;
        }

        .search-results button:hover {
            background-color: #f1f1f1;
        }

        .addstaff-btn {
            display: block;
            width: 120px;
            padding: 10px;
            background-color: #4c5b5c;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: -50px;
            margin-left: 750px;
            text-align: center;
        }

        .addstaff-btn:hover {
            background-color: #728284
        }

        .staff-info {
            display: none;
            background-color: white;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            position: relative;
        }

        .staff-info h2 {
            margin-top: 0;
        }

        .staff-info label {
            display: block;
            margin-top: 10px;
            font-weight: bolder;
        }

        .staff-info input {
            width: 390px;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .fa-trash {
            color: red;
            position: absolute;
            top: 13px;
            right: 65px;
            cursor: pointer;
            border: none;
            padding: 10px;
            border-radius: 5px;
        }

        .fa-pencil {
            color: #000;
            position: absolute;
            top: 14px;
            right: 20px;
            cursor: pointer;
            border: none;
            padding: 10px;
            border-radius: 5px;
            display: none;
        }

        .edit-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            border: none;
            padding: 10px;
            border-radius: 5px;
        }

        .save-btn {
            display: block;
            width: 250px;
            padding: 10px;
            background-color: #e1212a;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            margin-left: 190px;
        }

        .save-btn:hover {
            background-color: #dd4249
        }

        .btn {
            padding: 5px;
            color: white;
            background: maroon;
            border: none;
            border-radius: 5px;
            cursor: pointer;
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
            cursor: pointer;
        }

        
    </style>
</head>
<body>
    <h1>Manage Staff</h1>
    <div class="managestaff">
        <div class="container">
            <div class="search-container">
                <input type="text" id="search" placeholder="Search staff by name">
                <div class="search-results"></div>
                <button class="addstaff-btn" onclick="window.location.href='Management_AddStaff.php'">Add Staff</button>
            </div>
            <div class="staff-list">
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Date Employed</th>
                            <th>Hours Worked</th>
                            <th>Address</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sql = "SELECT u.ID as userID, u.Name, s.Email, s.Date_Employed, u.Phone_Number, s.Hours_Worked, s.Address 
                            FROM user u 
                            JOIN staff s ON u.Email = s.Email";
                    $result = mysqli_query($con, $sql);
                    if (mysqli_num_rows($result) > 0) {
                        $i = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $i . "</td>";
                            echo "<td>" . $row['Name'] . "</td>";
                            echo "<td>" . $row['Email'] . "</td>";
                            echo "<td>" . $row['Phone_Number'] . "</td>";
                            echo "<td>" . $row['Date_Employed'] . "</td>";
                            echo "<td>" . $row['Hours_Worked'] . "</td>";
                            echo "<td>" . $row['Address'] . "</td>";
                            echo "<td><button class='btn'onclick='showStaffInfo(\"" . $row["Name"] . "\", \"" . $row["Date_Employed"] . "\", \"" . $row["Phone_Number"] . "\", \"" . $row["Email"] . "\", \"" . $row["Address"] . "\", \"" . $row["Hours_Worked"] . "\", \"" . $row["userID"] . "\")'>Edit</button></td>";
                            echo "</tr>";
                            $i++;
                        }
                    } else {
                        echo "<tr><td colspan='8'>No staff found</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <div id="staff-info" class="staff-info">
                <h2>Staff Information</h2>
                <form method="POST">
                    <input type="hidden" id="staffID" name="staffID">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                    <label for="dateEmployed">Date Employed:</label>
                    <input type="date" id="dateEmployed" name="dateEmployed" required>
                    <label for="phoneNumber">Phone Number:</label>
                    <input type="tel" id="phoneNumber" name="phoneNumber" required>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" required>
                    <label for="hoursWorked">Hours Worked:</label>
                    <input type="number" id="hoursWorked" name="hoursWorked" required>
                    <button type="submit" class="btn" name="save-btn">Save</button>
                    <button type="submit" name="delete-btn" class="fa fa-trash"></button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const searchInput = document.getElementById('search');
        const searchResults = document.querySelector('.search-results');
        const staffInfoForm = document.getElementById('staff-info');
        const staffIDInput = document.getElementById('staffID');
        const nameInput = document.getElementById('name');
        const dateEmployedInput = document.getElementById('dateEmployed');
        const phoneNumberInput = document.getElementById('phoneNumber');
        const emailInput = document.getElementById('email');
        const addressInput = document.getElementById('address');
        const hoursWorkedInput = document.getElementById('hoursWorked');

        const staffData = <?php echo json_encode($staffData); ?>;

        searchInput.addEventListener('input', function () {
            const searchValue = searchInput.value.toLowerCase();
            searchResults.innerHTML = '';

            if (searchValue === '') {
                searchResults.style.display = 'none';
                return;
            }

            const filteredStaff = staffData.filter(staff =>
                staff.Name.toLowerCase().includes(searchValue)
            );

            if (filteredStaff.length > 0) {
                searchResults.style.display = 'block';
                filteredStaff.forEach(staff => {
                    const resultButton = document.createElement('button');
                    resultButton.textContent = staff.Name;
                    resultButton.addEventListener('click', function () {
                        staffIDInput.value = staff.Staff_ID;
                        nameInput.value = staff.Name;
                        dateEmployedInput.value = staff.Date_Employed;
                        phoneNumberInput.value = staff.Phone_Number;
                        emailInput.value = staff.Email;
                        addressInput.value = staff.Address;
                        hoursWorkedInput.value = staff.Hours_Worked;

                        staffInfoForm.style.display = 'block';
                        searchResults.style.display = 'none';
                    });
                    searchResults.appendChild(resultButton);
                });
            } else {
                searchResults.style.display = 'none';
            }
        });

        function showStaffInfo(name, dateEmployed, phoneNumber, email, address, hoursWorked, userID) {
            staffIDInput.value = userID;
            nameInput.value = name;
            dateEmployedInput.value = dateEmployed;
            phoneNumberInput.value = phoneNumber;
            emailInput.value = email;
            addressInput.value = address;
            hoursWorkedInput.value = hoursWorked;
            staffInfoForm.style.display = 'block';
        }

        document.querySelector('.fa-trash').addEventListener('click', function () {
            if (confirm('Are you sure you want to remove this staff?')) {
                staffInfoForm.submit();
            }
        });

        document.querySelector('.fa-pencil').addEventListener('click', function () {
            staffInfoForm.style.display = 'block';
        });
    </script>
</body>
</html>

