 <?php
session_start();  // Start the session

// Check if the necessary session variables exist
if (!isset($_SESSION['p_id']) || !isset($_SESSION['highest_bid'])) {
    echo "Session data missing. Please go back to the product page.";
    exit();
}

// Retrieve values from the session
$p_id = $_SESSION['p_id'];
$highest_bid = $_SESSION['highest_bid'];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Define database credentials
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "auction_db";

    // Retrieve form data
    $b_id = $_POST['b_id'];
    $upi_id = $_POST['Upi_id'];
    $amount = $_POST['amount'];
    $auction_date = $_POST['Auction_date']; // Retrieve the auction date

    // Create a connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind SQL statement to insert payment data into the payment table
    $stmt = $conn->prepare("INSERT INTO payment (p_id, b_id, upi_id, amount, auction_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisds", $p_id, $b_id, $upi_id, $amount, $auction_date); // Binding the auction date

    // Execute the statement and check for success
    if ($stmt->execute()) {
        echo "<script>alert('Payment successful!');</script>";
    } else {
        echo "<script>alert('Payment failed: " . $stmt->error . "');</script>";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Page</title>
    <style>
        body {
            background: linear-gradient(135deg, #f5a623, #f06595);
            font-family: 'Arial', sans-serif;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        h2 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #fff;
        }
        input[type="text"], input[type="number"], input[type="date"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 10px;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            font-size: 16px;
        }
        .readonly-input {
            background-color: rgba(255, 255, 255, 0.7);
        }
        button {
            background-color: #e91e63;
            border: none;
            padding: 12px 20px;
            font-size: 18px;
            border-radius: 50px;
            color: white;
            cursor: pointer;
            transition: 0.3s ease;
        }
        button:hover {
            background-color: #c2185b;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Payment Page</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="p_id">Product ID (p_id):</label>
        <input type="text" id="p_id" name="p_id" value="<?php echo $p_id; ?>" class="readonly-input" readonly>

        <label for="b_id">Bidder ID (b_id):</label>
        <input type="text" id="b_id" name="b_id" required>

        <label for="Upi_id">UPI ID:</label>
        <input type="text" id="Upi_id" name="Upi_id" required>

        <label for="amount">Amount (Highest Bid):</label>
        <input type="number" id="amount" name="amount" value="<?php echo $highest_bid; ?>" class="readonly-input" readonly>

        <label for="Auction_date">Auction Date:</label>
        <input type="date" id="Auction_date" name="Auction_date" required>

        <button type="submit">Pay Now</button>
    </form>
</div>

</body>
</html>
