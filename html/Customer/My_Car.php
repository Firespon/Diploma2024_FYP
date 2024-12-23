<?php
include('php/conn.php');

// Get the email from the session
$id = $_SESSION['mySession'];

// Fetch the email using the user ID
$emailQuery = "SELECT Email FROM user WHERE ID = '$id'";
$emailResult = mysqli_query($con, $emailQuery);

if (mysqli_num_rows($emailResult) > 0) {
    $emailRow = mysqli_fetch_assoc($emailResult);
    $email = $emailRow['Email'];
} else {
    echo "<script>alert('User not found. Please try again.');window.location.href='../Cust_Profile.php';</script>";
    exit();
}

$customerQuery = "SELECT Customer_ID FROM customer WHERE Email = '$email'";
$customerResult = mysqli_query($con, $customerQuery);

if (mysqli_num_rows($customerResult) > 0) {
    $customerRow = mysqli_fetch_assoc($customerResult);
    $customer_id = $customerRow['Customer_ID'];
} else {
    echo "<script>alert('Customer not found. Please try again.');window.location.href='../Cust_Profile.php';</script>";
    exit();
}

$carQuery = "SELECT Car_ID, Brand, Model, Registration_Number, Year FROM car WHERE Customer_ID = '$customer_id'";
$carResult = mysqli_query($con, $carQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Car</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins&display=swap">
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
            padding-top: 20px;
        }
        .mycar-body h1, .mycar-body h2 {
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
        }
        .fa-pencil {
            color: #000;
        }
        .delete-btn, .edit-btn {
            background: none;
            border: none;
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
            max-width: 300px; 
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

        .edit-popup {
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
            max-width: 400px;
            border-radius: 10px;
        }
        .edit-popup .close-btn {
            display: block;
            text-align: right;
            cursor: pointer;
            margin-bottom: 5px;
        }
        .edit-popup .edit-message {
            margin-bottom: 20px;
            font-size: 1.2em;
            text-align: center;
            font-weight: bold;
        }
        .edit-popup .edit-form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .edit-popup .edit-form label {
            font-weight: normal;
            margin-bottom: 5px;
        }
        .edit-popup .edit-form input, 
        .edit-popup .edit-form select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #d7d7d7;
        }
        .edit-popup .buttons {
            display: flex;
            justify-content: space-between;
        }
        .edit-popup button {
            width: 48%;
            padding: 10px;
            border: none;
            cursor: pointer;
            font-size: 1em;
        }
        .edit-popup .save-btn {
            background-color: #4CAF50;
            color: white;
        }
        .edit-popup .cancel-btn {
            background-color: #f44336;
            color: white;
        }
        .add-car-link {
            padding: 10px 20px; 
            margin: 10px; 
            background-color: white;
            color: maroon;
            border: solid 1px maroon;
            text-align: center; 
            text-decoration: none; 
            font-size: 18px;
            border-radius: 5px; 
            transition: background-color 0.3s ease; 
            }

        .add-car-link:hover {
            background-color: maroon;
            color: white;
        }

    </style>
</head>
<body class="mycar-body">
    <h1>My Cars</h1>
    <br>
    <a href="Cust_AddCar.php" class="add-car-link">Add Car</a>
    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>Brand</th>
                    <th>Model</th>
                    <th>Registration Number</th>
                    <th>Year</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if (mysqli_num_rows($carResult) > 0) {
                        while ($carRow = mysqli_fetch_assoc($carResult)) {
                            $carId = $carRow['Car_ID'];
                            $brand = $carRow['Brand'];
                            $model = $carRow['Model'];
                            $regNumber = $carRow['Registration_Number'];
                            $year = $carRow['Year'];

                            echo "<tr>";
                            echo "<td>$brand</td>";
                            echo "<td>$model</td>";
                            echo "<td>$regNumber</td>";
                            echo "<td>$year</td>";
                            echo "<td>
                                <button class='edit-btn' onclick=\"openEditPopup('$carId', '$brand', '$model', '$regNumber', '$year')\">
                                    <i class='fa fa-pencil'></i>
                                </button>
                                <button class='delete-btn' onclick=\"openConfirmPopupWithId('$carId')\">
                                    <i class='fa fa-trash'></i>
                                </button>
                            </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No cars found</td></tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>

    <div id="edit-popup" class="edit-popup">
        <span class="close-btn" onclick="closeEditPopup()">✖️</span>
        <div class="edit-message">Edit Car Details</div>
        <form id="edit-form" class="edit-form" method="post" action="php/edit_car.php">
            <input type="hidden" id="car-id" name="car_id">
            <label for="car-brand">Car Brand</label>
            <select id="car-brand" name="car-brand" onchange="filterModels()">
                <option value="">Select Brand</option>
                <option value="BMW">BMW</option>
                <option value="Honda">Honda</option>
                <option value="Mercedes">Mercedes</option>
                <option value="Perodua">Perodua</option>
                <option value="Porsche">Porsche</option>
                <option value="Toyota">Toyota</option>
            </select>

            <label for="car-model">Car Model</label>
            <select id="car-model" name="car-model">
                <option value="">Select Model</option>
            </select>

            <label for="edit-reg-number">Reg. Number</label>
            <input type="text" id="edit-reg-number" name="edit-reg-number" placeholder="Registration Number">

            <label for="manufactured-year">Manufactured Year</label>
            <select id="manufactured-year" name="manufactured-year">
                <option value="">Select Year</option>
                <script>
                    const currentYear = new Date().getFullYear();
                    for (let year = currentYear; year >= 1980; year--) {
                        document.write(`<option value="${year}">${year}</option>`);
                    }
                </script>
            </select>
        </form>
        <br>
        <div class="buttons">
            <button class="save-btn" onclick="document.getElementById('edit-form').submit()">Save</button>
            <button class="cancel-btn" onclick="closeEditPopup()">Cancel</button>
        </div>
    </div>

    <div id="confirm-popup" class="confirm-popup">
        <span class="close-btn" onclick="closeConfirmPopup()">✖️</span>
        <div class="confirm-message">Are you sure you want to delete this car?</div>
        <div class="buttons">
            <button class="confirm-yes" id="confirm-yes" onclick="confirmDelete(event)">Yes</button>
            <button class="confirm-no" onclick="closeConfirmPopup()">No</button>
        </div>
    </div>

    <script>
        function openEditPopup(carId, brand, model, regNumber, year) {
            document.getElementById('edit-popup').style.display = 'block';
            document.getElementById('car-id').value = carId;
            document.getElementById('car-brand').value = brand;
            filterModels();
            document.getElementById('car-model').value = model;
            document.getElementById('edit-reg-number').value = regNumber;
            document.getElementById('manufactured-year').value = year;
        }

        function openConfirmPopup() {
            document.getElementById('confirm-popup').style.display = 'block';
        }

        function closeEditPopup() {
            document.getElementById('edit-popup').style.display = 'none';
        }

        function closeConfirmPopup() {
            document.getElementById('confirm-popup').style.display = 'none';
        }

        function openConfirmPopupWithId(carId) {
            document.getElementById('confirm-popup').style.display = 'block';
            document.getElementById('confirm-yes').setAttribute('data-car-id', carId);
        }

        function confirmDelete(event) {
            let carId = event.target.getAttribute('data-car-id'); // Get carId from data attribute
            let formData = new FormData();
            formData.append('car_id', carId);

            fetch('php/delete_car.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                console.log(data);
                alert('Car deleted Successfully!');
                closeConfirmPopup(); // Close the confirmation popup after deletion
                location.reload(); // Refresh the page to show the updated car list
            })
            .catch(error => console.error('Error:', error));
        }



        function filterModels() {
            const brandSelect = document.getElementById('car-brand');
            const modelSelect = document.getElementById('car-model');
            const selectedBrand = brandSelect.value;

            // Clear existing options in the model dropdown
            modelSelect.options.length = 1;

            // Populate model options based on the selected brand
            switch (selectedBrand) {
                case 'BMW':
                    addModelOption(modelSelect, 'BMW 3 Series');
                    addModelOption(modelSelect, 'BMW 5 Series');
                    addModelOption(modelSelect, 'BMW X5');
                    break;
                case 'Honda':
                    addModelOption(modelSelect, 'Honda Civic');
                    addModelOption(modelSelect, 'Honda Accord');
                    addModelOption(modelSelect, 'Honda CR-V');
                    break;
                case 'Perodua':
                    addModelOption(modelSelect, 'Perodua Myvi');
                    addModelOption(modelSelect, 'Perodua Axia');
                    addModelOption(modelSelect, 'Perodua Bezza');
                    break;
                case 'Toyota':
                    addModelOption(modelSelect, 'Toyota Corolla');
                    addModelOption(modelSelect, 'Toyota Camry');
                    addModelOption(modelSelect, 'Toyota Vios');
                    break;
                case 'Porsche':
                    addModelOption(modelSelect, 'Porsche 911');
                    addModelOption(modelSelect, 'Porsche Cayenne');
                    addModelOption(modelSelect, 'Porsche Macan');
                    break;
                case 'Mercedes':
                    addModelOption(modelSelect, 'Mercedes-Benz C-Class');
                    addModelOption(modelSelect, 'Mercedes-Benz E-Class');
                    addModelOption(modelSelect, 'Mercedes-Benz S-Class');
                    break;
                default:
                    break;
            }
        }

        function addModelOption(select, modelName) {
            const option = document.createElement('option');
            option.value = modelName;
            option.text = modelName;
            select.add(option);
        }
    </script>
</body>
</html>