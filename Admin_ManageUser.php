<?php
include("session.php");
include('php/conn.php');

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    // Start a transaction
    mysqli_begin_transaction($con);

    // Fetch the email of the user to be deleted
    $emailQuery = "SELECT Email FROM user WHERE ID = '$delete_id'";
    $emailResult = mysqli_query($con, $emailQuery);

    if ($emailResult && mysqli_num_rows($emailResult) > 0) {
        $row = mysqli_fetch_assoc($emailResult);
        $email = $row['Email'];

        // Get the Customer_ID based on the email
        $customerIDQuery = "SELECT Customer_ID FROM customer WHERE Email = '$email'";
        $customerIDResult = mysqli_query($con, $customerIDQuery);

        if ($customerIDResult && mysqli_num_rows($customerIDResult) > 0) {
            $customerRow = mysqli_fetch_assoc($customerIDResult);
            $customerID = $customerRow['Customer_ID'];

            // Delete from car table
            $deleteCarQuery = "DELETE FROM car WHERE Customer_ID = '$customerID'";
            $deleteCarResult = mysqli_query($con, $deleteCarQuery);

            // Delete from customer table
            $deleteCustQuery = "DELETE FROM customer WHERE Customer_ID = '$customerID'";
            $deleteCustResult = mysqli_query($con, $deleteCustQuery);

            // Delete from user table
            $deleteUserQuery = "DELETE FROM user WHERE ID = '$delete_id'";
            $deleteUserResult = mysqli_query($con, $deleteUserQuery);

            if ($deleteCarResult && $deleteCustResult && $deleteUserResult) {
                mysqli_commit($con);
                $delete_message = "User deleted successfully.";
            } else {
                mysqli_rollback($con);
                $delete_message = "Failed to delete user.";
            }
        } else {
            $delete_message = "Customer not found.";
        }
    } else {
        $delete_message = "User not found.";
    }
}

// Fetch users from the database
$query = "SELECT ID, Name, Email, Phone_Number FROM user WHERE Role = 'Customer'";
$result = mysqli_query($con, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($con));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage User</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/scrollbar.css">
    <link rel="icon" type="image/x-icon" href="pictures/favicon.ico">
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
        }
        .fa {
            font-size: 1.5em;
            cursor: pointer;
        }
        .fa-trash {
            color: red;
            margin-left: 10px;
        }
        .fa-pencil {
            color: #000;
        }
    </style>
</head>
<body>
    <nav>
        <?php include 'navbar/Admin_Navbar.html';?>
    </nav>
    <br>
    <h1>Manage User</h1>
    <?php if (isset($delete_message)) { echo "<script>alert('User deleted Successfully!')</script>"; } ?>
    <br>
    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if (mysqli_num_rows($result) > 0){
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $no++ . "</td>";
                        echo "<td>" . $row['Name'] . "</td>";
                        echo "<td>" . $row['Email'] . "</td>";
                        echo "<td>" . $row['Phone_Number'] . "</td>";
                        echo "<td>";
                        echo "<a href='Admin_EditUser.php?id=" . $row['ID'] . "'>";
                        echo "<i class='fa fa-pencil'></i>";
                        echo "</a>";
                        echo "<form method='POST' style='display:inline;' onsubmit='return confirmDelete()'>";
                        echo "<input type='hidden' name='delete_id' value='" . $row['ID'] . "'>";
                        echo "<button type='submit' style='background:none;border:none;padding:0;cursor:pointer;'>";
                        echo "<i class='fa fa-trash'></i>";
                        echo "</button>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No users found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this user?");
        }
    </script>
</body>
</html>
