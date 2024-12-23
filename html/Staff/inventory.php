<?php
include('php/conn.php');

// Handle POST request if data is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['confirm_order'])) {
    $items = $_POST['item'];
    $orderProcessedSuccessfully = true; // Flag to track order processing status

    foreach ($items as $item) {
        $id = $item['id'];
        $quantity = $item['quantity'];

        // Retrieve current quantity from database
        $sql = "SELECT Quantity FROM inventory WHERE Inventory_ID = $id";
        $result = $con->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $currentQuantity = $row['Quantity'];

            // Update quantity in database by adding the ordered quantity
            $newQuantity = $currentQuantity + $quantity;
            $updateSql = "UPDATE inventory SET Quantity = $newQuantity WHERE Inventory_ID = $id";
            if ($con->query($updateSql) === TRUE) {
                // Update successful
                continue; // Move to next item
            } else {
                echo "<script>alert('Error updating record: " . $con->error . "'); window.location.href = 'Staff_Inventory.php';</script>";
                $orderProcessedSuccessfully = false; // Mark as failed
                break;
            }
        } else {
            echo "<script>alert('Item with ID $id not found in inventory'); window.location.href = 'Staff_Inventory.php';</script>";
            $orderProcessedSuccessfully = false; // Item not found
            break;
        }
    }

    $con->close();

    if ($orderProcessedSuccessfully) {
        echo "<script>alert('Order processed successfully'); window.location.href = 'Staff_Inventory.php';</script>";
    }
    exit; // Stop further execution
}

// Fetch inventory data
$sql = "SELECT Inventory_ID, Item_Name, Price, Quantity FROM inventory";
$result = $con->query($sql);

$inventoryItems = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['image_path'] = getImagePath($row['Item_Name']); // Add image path to each item
        $inventoryItems[] = $row;
    }
}

$con->close();

// Function to return the image path based on the item name
function getImagePath($itemName) {
    $imagePaths = [
        'Engine Oil' => 'pictures/staffpics/engineoil.jpg',
        'AC Compressor' => 'pictures/staffpics/ac compressor.jpg',
        'Spark Plug' => 'pictures/staffpics/saprk plugs.jpg',
        'Gasket' => 'pictures/staffpics/Gaskets.jpg',
        'Turbo' => 'pictures/staffpics/turbo.jpg',
        'Intercooler' => 'pictures/staffpics/intercooler.jpg'
    ];
    return $imagePaths[$itemName] ?? 'pictures/default.jpg'; // Default image if not found
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
            background-color: #f4f4f4;
        }
        .container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            padding: 20px;
        }
        .item {
            border: 1px solid #ccc;
            padding: 20px;
            text-align: center;
            position: relative;
            background-color: #fff;
        }
        .controls {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 10px;
        }
        .controls button {
            margin: 0 10px;
            width: 30px;
        }
        #summary {
            display: none;
            margin: 20px auto;
            padding: 10px;
            background-color: #fff;
            border: 1px solid #ccc;
            width: 50%;
        }
        #summary table {
            width: 100%;
            border-collapse: collapse;
        }
        #summary table, #summary th, #summary td {
            border: 1px solid #ccc;
        }
        #summary th, #summary td {
            padding: 10px;
            text-align: center;
        }
        #orderButton {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: green;
            color: white;
            border: none;
            cursor: pointer;
        }

        h1 {
            font-size: 2em;
            margin-bottom: 20px;
        }

        .products {
            width: 100px;
            height: 100px;
        }
    </style>
</head>
<body>
<h1>Inventory</h1>
<div class="container">
    <?php foreach ($inventoryItems as $item) { ?>
        <div class="item" data-id="<?= $item['Inventory_ID'] ?>" data-name="<?= $item['Item_Name'] ?>" data-price="<?= $item['Price'] ?>" data-quantity="<?= $item['Quantity'] ?>">
            <img src="<?= $item['image_path'] ?>" alt="<?= $item['Item_Name'] ?>" class="products">    
            <input type="hidden" value="<?= $item['Inventory_ID'] ?>">
            <p><?= $item['Item_Name'] ?></p>
            <p>Price: RM<?= $item['Price'] ?></p>
            <p>Available: <?= $item['Quantity'] ?></p>
            <div class="controls">
                <button onclick="changeQuantity(this, -1)">-</button>
                <input type="text" value="0" readonly>
                <button onclick="changeQuantity(this, 1)">+</button>
            </div>
        </div>
    <?php } ?>
</div>
<div id="summary">
    <h2>Order Summary</h2>
    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody id="summaryBody">
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2">Total Price</th>
                <th id="totalPrice">RM0</th>
            </tr>
        </tfoot>
    </table>
    <button id="orderButton" onclick="confirmOrder()">Confirm Order</button>
</div>
<script>
    function changeQuantity(button, change) {
        const input = button.parentElement.querySelector('input[type="text"]');
        const newValue = parseInt(input.value) + change;
        if (newValue >= 0) {
            input.value = newValue;
            updateSummary();
        }
    }

    function updateSummary() {
        const items = document.querySelectorAll('.item');
        const summaryBody = document.getElementById('summaryBody');
        summaryBody.innerHTML = '';
        let showSummary = false;
        let totalPrice = 0;
        items.forEach(item => {
            const input = item.querySelector('input[type="text"]');
            const quantity = parseInt(input.value);
            if (quantity > 0) {
                showSummary = true;
                const name = item.getAttribute('data-name');
                const price = parseInt(item.getAttribute('data-price'));
                const totalItemPrice = price * quantity;
                totalPrice += totalItemPrice;
                summaryBody.innerHTML += `
                    <tr>
                        <td>${name}</td>
                        <td>${quantity}</td>
                        <td>RM${totalItemPrice}</td>
                    </tr>
                `;
            }
        });
        document.getElementById('totalPrice').innerText = 'RM' + totalPrice;
        document.getElementById('summary').style.display = showSummary ? 'block' : 'none';
    }

    function confirmOrder() {
        const items = document.querySelectorAll('.item');
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = ''; // Submit to the same page

        // Add a hidden input to indicate that this is a confirm order request
        const confirmInput = document.createElement('input');
        confirmInput.type = 'hidden';
        confirmInput.name = 'confirm_order';
        confirmInput.value = 'true';
        form.appendChild(confirmInput);

        items.forEach(item => {
            const input = item.querySelector('input[type="text"]');
            const quantity = parseInt(input.value);
            if (quantity > 0) {
                const id = item.getAttribute('data-id');
                const name = item.getAttribute('data-name');
                const price = item.getAttribute('data-price');
                
                // Create inputs for each item's details
                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = `item[${id}][id]`;
                idInput.value = id;
                form.appendChild(idInput);

                const nameInput = document.createElement('input');
                nameInput.type = 'hidden';
                nameInput.name = `item[${id}][name]`;
                nameInput.value = name;
                form.appendChild(nameInput);

                const quantityInput = document.createElement('input');
                quantityInput.type = 'hidden';
                quantityInput.name = `item[${id}][quantity]`;
                quantityInput.value = quantity;
                form.appendChild(quantityInput);

                const priceInput = document.createElement('input');
                priceInput.type = 'hidden';
                priceInput.name = `item[${id}][price]`;
                priceInput.value = price;
                form.appendChild(priceInput);
            }
        });

        document.body.appendChild(form);
        form.submit();
    }
</script>
</body>
</html>

