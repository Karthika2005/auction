<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Define database credentials
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "auction_db";

    // Retrieve the Seller ID from the form input
    $s_id = $_POST['s_id'];

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to retrieve products where the s_id matches the seller's ID
    $sql = "SELECT p_id, p_name, description, starting_bid, highest_bid, auction_end_time FROM product WHERE s_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $s_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if any products were found
    if ($result->num_rows > 0) {
        // Store the products in an array
        $products = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $alert_message = "No products found for Seller ID: $s_id";
        $products = [];
    }

    // Close the connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Seller Products Report</title>
    <style>
        .container {
            background-color: rgba(255, 255, 255, 0.5); /* White with 50% opacity */
            max-width: 500px;
            margin: 20px auto;
            padding: 20px;
            color: black;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
        }
        button {
            padding: 7px 25px;
            border-radius: 9px;
            font-size: 15px;
            background: pink;
        }
        button:hover {
            box-shadow: 0 0 5px pink, 0 0 25px pink, 0 0 50px pink, 0 0 100px pink, 0 0 200px pink;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid black;
            text-align: center;
        }
        th {
            background-color: lightgray;
        }
    </style>
</head>
<center>
<body style="background: url('bg.jpg') no-repeat center center; background-size: cover;">
    <div class="container">
        <h2 style="text-align: center;">Seller Products Report</h2>
         
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="s_id">Enter Seller ID:</label>
            <input type="number" id="s_id" name="s_id" required><br><br>
            <center>
                <button type="submit">Show Products</button>
            </center>
        </form>

        <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
            <?php if (!empty($products)): ?>
                <h3>Products sold by Seller ID: <?php echo htmlspecialchars($s_id); ?></h3>
                <table>
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Description</th>
                            <th>Starting Bid</th>
                            <th>Highest Bid</th>
                            <th>Auction End Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($product['p_id']); ?></td>
                                <td><?php echo htmlspecialchars($product['p_name']); ?></td>
                                <td><?php echo htmlspecialchars($product['description']); ?></td>
                                <td><?php echo htmlspecialchars($product['starting_bid']); ?></td>
                                <td><?php echo htmlspecialchars($product['highest_bid']); ?></td>
                                <td><?php echo htmlspecialchars($product['auction_end_time']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p><?php echo $alert_message; ?></p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
            </center>
</body>
</html>
