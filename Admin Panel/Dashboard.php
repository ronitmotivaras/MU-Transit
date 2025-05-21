<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MU Transit - Admin Panel</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .stats-section {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }
        .stat-card {
            text-align: center;
            position: relative;
            width: 220px;
            height: 250px;
            background-color: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .chart-container {
            position: relative;
            width: 180px;
            height: 180px;
        }
        .chart-label {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 22px;
            font-weight: bold;
            color: #333;
        }
        h6 {
            font-size: 18px;
            font-weight: bold;
        }
        h2 {
            font-size: 32px;
            font-weight: bolder;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <?php include('sidenavbar.php')?>
    
    <!-- Main Content -->
    <div class="content">
        <h2 class="mb-4">Hello, Admin</h2>
        <div class="stats-section">
            <div class="stat-card">
                <div class="chart-container">
                    <canvas id="totalBusesChart"></canvas>
                    <div class="chart-label" id="totalBusesLabel">50</div>
                </div>
                <h6>Total Buses</h6>
            </div>
            <div class="stat-card">
                <div class="chart-container">
                    <canvas id="activeBusesChart"></canvas>
                    <div class="chart-label" id="activeBusesLabel">40</div>
                </div>
                <h6>Active Buses</h6>
            </div>
            <div class="stat-card">
                <div class="chart-container">
                    <canvas id="studentsChart"></canvas>
                    <div class="chart-label" id="studentsLabel">5000</div>
                </div>
                <h6>Students</h6>
            </div>
            <div class="stat-card">
                <div class="chart-container">
                    <canvas id="driversChart"></canvas>
                    <div class="chart-label" id="driversLabel">30</div>
                </div>
                <h6>Drivers</h6>
            </div>
        </div>

<!-- Add this section after the charts -->
<div class="mt-5">
    <h3 class="text-center mb-3">Next Bus Schedule</h3>
    <table class="table table-bordered table-striped text-center">
        <thead class="table-dark">
            <tr>
                <th>Bus No</th>
                <th>Bus Route</th>
                <th>Bus Time</th>
                <th>Bus Shift</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>101</td>
                <td>North Campus - Main Gate</td>
                <td>08:30 AM</td>
                <td>Morning</td>
            </tr>
            <tr>
                <td>102</td>
                <td>South Campus - Library</td>
                <td>09:00 AM</td>
                <td>Morning</td>
            </tr>
            <tr>
                <td>201</td>
                <td>West Campus - Sports Complex</td>
                <td>02:30 PM</td>
                <td>Afternoon</td>
            </tr>
            <tr>
                <td>202</td>
                <td>East Campus - Hostel</td>
                <td>05:00 PM</td>
                <td>Evening</td>
            </tr>
        </tbody>
    </table>
</div>


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            function createDoughnutChart(chartId, labelId, data, colors) {
                let ctx = document.getElementById(chartId).getContext("2d");
                new Chart(ctx, {
                    type: 'doughnut',
                    data: { datasets: [{ data: data, backgroundColor: colors, borderWidth: 5 }] },
                    options: { responsive: true, maintainAspectRatio: false, cutout: '70%', plugins: { legend: { display: false }, tooltip: { enabled: false } } }
                });
                document.getElementById(labelId).innerText = data[0];
            }
            window.onload = function () {
                createDoughnutChart("totalBusesChart", "totalBusesLabel", [50, 10], ['#ff5733', '#ffc107']);
                createDoughnutChart("activeBusesChart", "activeBusesLabel", [40, 10], ['#28a745', '#17a2b8']);
                createDoughnutChart("studentsChart", "studentsLabel", [5000, 500], ['#6610f2', '#ff851b']);
                createDoughnutChart("driversChart", "driversLabel", [30, 5], ['#e83e8c', '#007bff']);
            };
        </script>
    </div>
</body>
</html>