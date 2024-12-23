<?php
include('php/conn.php');

function getMonthlyEarnings($con, $year, $month) {
    $year = (int)$year;
    $month = (int)$month;
    $sql = "SELECT SUM(Amount_Paid) as total_earnings 
            FROM appointment 
            WHERE YEAR(Date) = $year AND MONTH(Date) = $month";
    $result = mysqli_query($con, $sql);
    if (!$result) {
        die('Error in SQL query: ' . mysqli_error($con));
    }
    $row = mysqli_fetch_assoc($result);
    return $row['total_earnings'] ? $row['total_earnings'] : 0;
}

function getMonthlyEarningsSummary($con) {
    $sql = "SELECT MONTH(Date) as month, SUM(Amount_Paid) as total_earnings
            FROM appointment
            GROUP BY MONTH(Date)";
    $result = mysqli_query($con, $sql);
    if (!$result) {
        die('Error in SQL query: ' . mysqli_error($con));
    }

    $earnings = array_fill(1, 12, 0); // Initialize all months with 0 earnings
    while ($row = mysqli_fetch_assoc($result)) {
        $earnings[(int)$row['month']] = $row['total_earnings'];
    }
    return $earnings;
}

$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('m');

$monthlyEarnings = getMonthlyEarnings($con, $year, $month);
$monthlyEarningsSummary = getMonthlyEarningsSummary($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Earnings Summary with Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .container {
            width: 80%;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .summary-container {
            margin-top: 40px;
        }
        h1 {
            font-size: 3em;
            margin-bottom: 20px;
        }
        .controls {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }
        .controls .icon {
            font-size: 2em;
            cursor: pointer;
            margin: 0 20px;
        }
        .total-earnings {
            font-size: 2em;
            margin-bottom: 40px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        #earningsChartContainer {
            margin-top: 40px;
            max-width: 800px;
            margin: auto;
        }

        #current-month-year{
          margin-bottom: -2px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="controls">
        <span class="icon" onclick="navigateMonth(-1)">&#9664;</span>
        <h1 id="current-month-year"><?php echo date('F Y', mktime(0, 0, 0, $month, 10, $year)); ?></h1>
        <span class="icon" onclick="navigateMonth(1)">&#9654;</span>
    </div>

    <div class="total-earnings">
        Total Earnings for <?php echo date('F Y', mktime(0, 0, 0, $month, 10, $year)); ?>: RM <?php echo number_format($monthlyEarnings, 2); ?>
    </div>
</div>

<div class="container summary-container">
    <h1>Yearly Earnings Summary</h1>
    <table>
        <thead>
            <tr>
                <th>Month</th>
                <th>Total Earnings (RM)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $months = [
                1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 
                5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 
                9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
            ];
            foreach ($months as $num => $name) {
                $earnings = isset($monthlyEarningsSummary[$num]) ? number_format($monthlyEarningsSummary[$num], 2) : '0.00';
                echo "<tr><td>$name</td><td>RM $earnings</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<div id="earningsChartContainer" class="container">
    <h1>Yearly Earnings Chart</h1>
    <canvas id="earningsChart"></canvas>
</div>

<script>
// PHP to JS conversion of chartData
var chartData = <?php echo json_encode($monthlyEarningsSummary); ?>;

// Chart.js initialization
document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('earningsChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                'January', 'February', 'March', 'April',
                'May', 'June', 'July', 'August',
                'September', 'October', 'November', 'December'
            ],
            datasets: [{
                label: 'Monthly Earnings (RM)',
                data: [
                    chartData[1] ?? 0, chartData[2] ?? 0, chartData[3] ?? 0, chartData[4] ?? 0,
                    chartData[5] ?? 0, chartData[6] ?? 0, chartData[7] ?? 0, chartData[8] ?? 0,
                    chartData[9] ?? 0, chartData[10] ?? 0, chartData[11] ?? 0, chartData[12] ?? 0
                ],
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Total Earnings (RM)'
                    }
                }
            }
        }
    });
});

function navigateMonth(direction) {
    const currentMonth = <?php echo $month; ?>;
    const currentYear = <?php echo $year; ?>;
    let newMonth = currentMonth + direction;
    let newYear = currentYear;

    if (newMonth < 1) {
        newMonth = 12;
        newYear--;
    } else if (newMonth > 12) {
        newMonth = 1;
        newYear++;
    }

    window.location.href = `?year=${newYear}&month=${newMonth}`;
}
</script>

</body>
</html>
