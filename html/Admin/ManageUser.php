<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage User</title>
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
        }
        .fa-pencil {
            color: #000;
        }
    </style>
</head>
<body>
  <h2>Manage User</h2>
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
                <tr>
                    <td>1</td>
                    <td>Mike</td>
                    <td>mikehunt@gmail.com</td>
                    <td>015-4526 4125</td>
                    <td>
                        <i class="fa fa-pencil"></i>
                        <i class="fa fa-trash" onclick="confirmDelete(this)"></i>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Harry</td>
                    <td>harrystyles@gmail.com</td>
                    <td>015-4526 4125</td>
                    <td>
                        <i class="fa fa-pencil"></i>
                        <i class="fa fa-trash" onclick="confirmDelete(this)"></i>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <script>
        function confirmDelete(element) {
            if (confirm("Are you sure you want to delete this user?")) {
                let row = element.parentElement.parentElement;
                row.remove();
            }
        }
    </script>
</body>
</html>
