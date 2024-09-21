 <?php
session_start();  // Start the session to access the product ID

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the product ID is available in the session
if (!isset($_SESSION['p_id'])) {
    echo "No product ID found. Please upload a product first.";
    exit();
}

// Retrieve the product ID from the session
$p_id = $_SESSION['p_id'];

// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "auction_db";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch product details based on the stored product ID
$sql = "SELECT p_name, description, starting_bid, highest_bid, auction_end_time, image FROM product WHERE p_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $p_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if the product exists
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Get current time and auction end time
    $current_time = date("Y-m-d H:i:s");
    $auction_end_time = $row['auction_end_time'];

    // Check if the auction is live or ended
    $auction_status = (strtotime($auction_end_time) > strtotime($current_time)) ? "Live" : "Ended";

    // Prepare the image data (assuming image is stored as BLOB)
    if (!empty($row['image'])) {
        $image_data = base64_encode($row['image']);
        $image_src = 'data:image/jpeg;base64,' . $image_data;
    } else {
        $image_src = '';  // Handle if no image exists
        echo "No image data found for this product.<br>";
    }

    // Display product details
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Product Details</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f5f5f5;
                margin: 0;
                padding: 0;
            }
            .product-container {
                display: flex;
                flex-direction: row;
                justify-content: center;
                align-items: center;
                padding: 20px;
                background-color: #fff;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                margin: 50px auto;
                width: 80%;
                border-radius: 10px;
            }
            .product-image {
                flex: 1;
                max-width: 300px;
                margin-right: 20px;
            }
            .product-image img {
                width: 100%;
                height: auto;
                border-radius: 10px;
            }
            .product-info {
                flex: 2;
            }
            h3 {
                color: #333;
                font-size: 24px;
                margin-bottom: 15px;
            }
            .product-info p {
                margin: 10px 0;
                font-size: 18px;
                color: #555;
            }
            .product-info span {
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <div class='product-container'>
            <div class='product-image'>
                <?php
                if ($image_src) {
                    echo "<img src='" . $image_src . "' alt='Product Image'>";
                } else {
                    echo "<p>No image available</p>";  // Fallback if image data is not available
                }
                ?>
            </div>
            <div class='product-info'>
                <h3>Product Details</h3>
                Product Name: <?php echo $row['p_name']; ?><br>
                Description: <?php echo $row['description']; ?><br>
                Starting Bid: $<?php echo $row['starting_bid']; ?><br>
                Highest Bid: $<?php echo $row['highest_bid']; ?><br>
                Auction End Time: <?php echo $auction_end_time; ?><br>
                Auction Status: <?php echo $auction_status; ?><br>
            </div>
        </div>
    </body>
    </html>
    <?php
} else {
    echo "No product found with the given ID.";
}

// Close connection
$stmt->close();
$conn->close();
?>
