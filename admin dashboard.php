  <?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch data if requested
if (isset($_GET['action']) && $_GET['action'] === 'fetch_data') {
    header('Content-Type: application/json'); // Set header for JSON response

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "auction_db";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        echo json_encode(['error' => 'Database connection failed']);
        exit;
    }

    // Fetch product data
    $query = "SELECT p_name, starting_bid, highest_bid FROM Product";
    $result = $conn->query($query);

    if (!$result) {
        echo json_encode(['error' => 'Query failed: ' . $conn->error]);
        exit;
    }

    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    // Output JSON
    echo json_encode($products);

    $conn->close();
    exit; // Exit to prevent additional output
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Product Bid Comparison</title>
    <style>
        /* Reset some default browser styles */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }

        /* Sidebar styling */
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: blue;
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            padding: 20px;
            overflow-y: auto;
        }

        .sidebar h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: red;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            margin-bottom: 10px;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
            border-radius: 4px;
        }

        .sidebar ul li a:hover {
            background-color: pink;
        }

        /* Main content styling */
        .main-content {
            margin-left: 250px;
            padding: 20px;
            background-color: #f4f4f4;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: white;
            padding: 20px;
            border-bottom: 1px solid #ddd;
        }

        header h1 {
            margin: 0;
            font-size: 28px;
        }

        .header-right input {
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        /* Widget box styling */
        .widgets {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .widget {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .widget h3 {
            margin-bottom: 20px;
        }

         .chart {
            max-width: 1000px; /* Maximum width for laptop screen size */
            max-height: 400px; /* Adjust height */
            margin: 0 auto;
        }
        /* Dropdown styling */
        .dropdown {
            position: relative;
            display: inline-block;
            margin-top: 20px;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: blue;
            min-width: 160px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: white;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: pink;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown:hover .dropbtn {
            background-color: pink;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Admin Dashboard</h2>
        <ul>
            <li><a href="#">Dashboard</a></li><br>
            <li><a href="bidder_details.php">Bidder Info</a></li><br>
            <li><a href="seller_details.php">Seller Info</a></li><br>
            <li><a href="pro_status.php">Products</a></li>

            <!-- Dropdown for Reports -->
            <li class="dropdown">
                <a href="#" class="dropbtn">Reports</a>
                <div class="dropdown-content">
                    <a href="comparing_report.php">Comparison Report</a>
                    <a href="sales_report.php">Sales Report</a>
                    <a href="bid_status.php">Bid Status Report</a>
                    <a href="seller_report.php">Seller Report</a>
                </div>
            </li>
        </ul>
    </div>

    <div class="main-content">
        <header>
            <h1>Dashboard</h1>
            <div class="header-right">
                <input type="text" placeholder="Search...">
            </div>
        </header>

        <div class="widgets">
            <div class="widget">
                <h3>Product Bid Comparison</h3>
                <canvas id="bidComparisonChart" class="chart"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('?action=fetch_data') // Fetch data from the same script
                .then(response => response.json())
                .then(data => {
                    if (Array.isArray(data)) {
                        const labels = data.map(product => product.p_name);
                        const startingBids = data.map(product => parseFloat(product.starting_bid));
                        const highestBids = data.map(product => parseFloat(product.highest_bid));

                        const ctx = document.getElementById('bidComparisonChart').getContext('2d');
                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [
                                    {
                                        label: 'Starting Bid',
                                        data: startingBids,
                                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                                        borderColor: 'rgba(54, 162, 235, 1)',
                                        borderWidth: 1
                                    },
                                    {
                                        label: 'Highest Bid',
                                        data: highestBids,
                                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                                        borderColor: 'rgba(255, 99, 132, 1)',
                                        borderWidth: 1
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    x: {
                                        beginAtZero: true
                                    },
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    } else {
                        alert('Failed to load data.');
                    }
                })
                .catch(error => {
                    alert('Failed to fetch data.');
                });
        });
    </script>
</body>
</html>
