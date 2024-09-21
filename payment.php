 <?php
session_start();  // Start the session

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

// Check if `p_id` is stored in session
if (!isset($_SESSION['p_id'])) {
    if (isset($_POST['b_id'])) {
        $b_id = $_POST['b_id'];

        // Retrieve `p_id` based on `b_id`
        $stmt = $conn->prepare("SELECT p_id FROM products WHERE b_id = ?");
        $stmt->bind_param("i", $b_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $_SESSION['p_id'] = $row['p_id'];  // Store `p_id` in session
        } else {
            echo "No auction data found. Please go back to the product page.";
            exit();
        }
        $stmt->close();
    } else {
        echo "Session data missing. Please go back to the product page.";
        exit();
    }
}

// Retrieve the value of `p_id` from session
$p_id = $_SESSION['p_id'];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $b_id = $_POST['b_id'];
    $upi_id = $_POST['Upi_id'];
    $amount = $_POST['amount'];
    $auction_date = $_POST['Auction_date'];

    // Prepare and bind the SQL statement to insert payment data into the payment table
    $stmt = $conn->prepare("INSERT INTO payment (p_id, b_id, upi_id, amount, auction_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisds", $p_id, $b_id, $upi_id, $amount, $auction_date);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        // Payment successful, clear `p_id` from session
        unset($_SESSION['p_id']);
        echo "<script>alert('Payment successful!');</script>";
        // Redirect to homepage after a short delay
        echo "<script>setTimeout(function() { window.location.href = 'homepage.php'; }, 2000);</script>";
    } else {
        echo "<script>alert('Payment failed: " . $stmt->error . "');</script>";
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
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
        <input type="text" id="p_id" name="p_id" value="<?php echo htmlspecialchars($p_id); ?>" class="readonly-input" readonly>

        <label for="b_id">Bidder ID (b_id):</label>
        <input type="text" id="b_id" name="b_id" required>

        <label for="Upi_id">UPI ID:</label>
        <input type="text" id="Upi_id" name="Upi_id" required>

        <label for="amount">Amount (Highest Bid):</label>
        <input type="number" id="amount" name="amount" required>
        
        <label for="Auction_date">Auction Date:</label>
        <input type="date" id="Auction_date" name="Auction_date" required>

        <button type="submit">Pay Now</button>
    </form>
</div>

</body>
</html>
