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
    <title>Product Bid Comparison</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 20px;
        }
        canvas {
            max-width: 600px; /* Adjusted width */
            max-height: 400px; /* Adjusted height */
            margin: 20px auto;
        }
    </style>
</head>
<body>
    <h1>Product Bid Comparison</h1>
    <canvas id="bidComparisonChart"></canvas>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('?action=fetch_data') // Fetch data from the same script
                .then(response => response.json())
                .then(data => {
                    console.log('Fetched Data:', data); // Log the data

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
                        console.error('Expected array of data but received:', data);
                        alert('Failed to load data. Check console for details.');
                    }
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    alert('Failed to fetch data. Check console for details.');
                });
        });
    </script>
</body>
</html>
