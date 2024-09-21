 <?php
// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "auction_db";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables to hold query results
$product_info = [];
$selected_option = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selected_option = $_POST['option'];

    // If user selects "Highest Bid", fetch highest bid product
    if ($selected_option == "highest") {
        $sql_highest = "SELECT p_name, description, highest_bid, auction_end_time FROM product ORDER BY highest_bid DESC LIMIT 1";
        $result_highest = $conn->query($sql_highest);
        if ($result_highest->num_rows > 0) {
            $product_info = $result_highest->fetch_assoc();
        }
    }
    // If user selects "Lowest Bid", fetch lowest bid product
    elseif ($selected_option == "lowest") {
        $sql_lowest = "SELECT p_name, description, highest_bid, auction_end_time FROM product ORDER BY highest_bid ASC LIMIT 1";
        $result_lowest = $conn->query($sql_lowest);
        if ($result_lowest->num_rows > 0) {
            $product_info = $result_lowest->fetch_assoc();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Auction Report: Highest and Lowest Bids</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .report-container {
            width: 80%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .option-form {
            text-align: center;
            margin-bottom: 20px;
        }
        .option-form select {
            padding: 10px;
            font-size: 16px;
            margin-right: 10px;
        }
        .option-form button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #f4b41a;
            color: #fff;
        }
    </style>
</head>
<body>

<div class="report-container">
    <h2>Product Auction Report</h2>

    <!-- Option Form to Select Highest or Lowest Bid -->
    <form class="option-form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="option">Select Bid Type:</label>
        <select name="option" id="option" required>
            <option value="highest" <?php if ($selected_option == "highest") echo "selected"; ?>>Highest Bid</option>
            <option value="lowest" <?php if ($selected_option == "lowest") echo "selected"; ?>>Lowest Bid</option>
        </select>
        <button type="submit">Show Product</button>
    </form>

    <!-- Display the selected product details -->
    <?php if (!empty($product_info)): ?>
    <table>
        <tr>
            <th>Product Name</th>
            <th>Description</th>
            <th><?php echo $selected_option == "highest" ? "Highest" : "Lowest"; ?> Bid</th>
            <th>Auction End Time</th>
        </tr>
        <tr>
            <td><?php echo htmlspecialchars($product_info['p_name']); ?></td>
            <td><?php echo htmlspecialchars($product_info['description']); ?></td>
            <td>Rs.<?php echo htmlspecialchars($product_info['highest_bid']); ?></td>
            <td><?php echo htmlspecialchars($product_info['auction_end_time']); ?></td>
        </tr>
    </table>
    <?php else: ?>
        <p>No product found for the selected option.</p>
    <?php endif; ?>
</div>

</body>
</html>

<?php
// Close the connection
$conn->close();
?>
