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

    // Check if product ID is provided
    if (isset($_GET['p_id'])) {
        $p_id = intval($_GET['p_id']);
        // Fetch product data for the specific product ID
        $query = "SELECT p_name, starting_bid, highest_bid FROM Product WHERE p_id = $p_id";
    } else {
        echo json_encode(['error' => 'Product ID not provided']);
        exit;
    }

    $result = $conn->query($query);

    if (!$result) {
        echo json_encode(['error' => 'Query failed: ' . $conn->error]);
        exit;
    }

    $product = $result->fetch_assoc();

    if (!$product) {
        echo json_encode(['error' => 'No product found with the provided ID']);
        exit;
    }

    // Output JSON
    echo json_encode($product);

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
            max-width: 400px; /* Reduced chart width */
            max-height: 400px; /* Reduced chart height */
            margin: 20px auto;
        }
        input, button {
            margin: 10px;
            padding: 8px;
            font-size: 14px; /* Slightly smaller font size */
        }
    </style>
</head>
<body>
    <h1>Product Bid Comparison</h1>
    <label for="productId">Enter Product ID:</label>
    <input type="number" id="productId" name="productId" min="1">
    <button id="fetchButton">Show Bid Comparison</button>
    <canvas id="bidComparisonChart" style="display:none;"></canvas>
    <script>
        document.getElementById('fetchButton').addEventListener('click', function() {
            const productId = document.getElementById('productId').value;

            if (productId) {
                fetch(`?action=fetch_data&p_id=${productId}`) // Fetch data for the specified product ID
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Fetched Data:', data); // Log the data

                        if (data && data.p_name) {
                            const ctx = document.getElementById('bidComparisonChart').getContext('2d');
                            document.getElementById('bidComparisonChart').style.display = 'block'; // Show the chart

                            // Clear the canvas before creating a new chart
                            ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);

                            new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: [data.p_name],
                                    datasets: [
                                        {
                                            label: 'Starting Bid',
                                            data: [parseFloat(data.starting_bid)],
                                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                                            borderColor: 'rgba(54, 162, 235, 1)',
                                            borderWidth: 1
                                        },
                                        {
                                            label: 'Highest Bid',
                                            data: [parseFloat(data.highest_bid)],
                                            backgroundColor: 'rgba(255, 99, 132, 0.5)',
                                            borderColor: 'rgba(255, 99, 132, 1)',
                                            borderWidth: 1
                                        }
                                    ]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: true, // Ensure aspect ratio is maintained
                                    plugins: {
                                        legend: {
                                            display: true,
                                            position: 'top'
                                        },
                                        tooltip: {
                                            callbacks: {
                                                label: function(context) {
                                                    let label = context.dataset.label || '';
                                                    if (label) {
                                                        label += ': ';
                                                    }
                                                    if (context.parsed.y !== null) {
                                                        label += new Intl.NumberFormat().format(context.parsed.y);
                                                    }
                                                    return label;
                                                }
                                            }
                                        }
                                    },
                                    scales: {
                                        x: {
                                            beginAtZero: true,
                                            ticks: {
                                                font: {
                                                    size: 12
                                                }
                                            }
                                        },
                                        y: {
                                            beginAtZero: true,
                                            ticks: {
                                                font: {
                                                    size: 12
                                                }
                                            }
                                        }
                                    }
                                }
                            });
                        } else {
                            console.error('No data returned for the specified product ID:', data);
                            alert('No data found for the provided product ID.');
                            document.getElementById('bidComparisonChart').style.display = 'none'; // Hide the chart
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                        alert('Failed to fetch data. Check console for details.');
                        document.getElementById('bidComparisonChart').style.display = 'none'; // Hide the chart
                    });
            } else {
                alert('Please enter a product ID.');
            }
        });
    </script>
</body>
</html>