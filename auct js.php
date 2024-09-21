 <?php
session_start(); // Start the session

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "auction_db";
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assume product ID (p_id) is passed through GET
$p_id = $_GET['p_id'] ?? 1; // Example Product ID (default 1 if not passed)

// Fetch the highest bid for this product
$query = "SELECT highest_bid, p_name, auction_end_time, highest_bidder FROM Product WHERE p_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $p_id);
$stmt->execute();
$stmt->bind_result($highest_bid, $p_name, $auction_end_time, $highest_bidder);
$stmt->fetch();
$stmt->close();

// Check if product exists
if (!$highest_bid) {
    echo "No product found.";
    exit();
}

// Set the highest bid and p_id in session for payment page
$_SESSION['p_id'] = $p_id;
$_SESSION['highest_bid'] = $highest_bid;

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Page</title>
    <style>
        /* Styling */
        body {
            font-family: Arial, sans-serif;
        }
        .product-details {
            width: 300px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            text-align: center;
        }
        .timer {
            color: red;
            font-weight: bold;
        }
        a {
            text-decoration: none;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border-radius: 5px;
            display: inline-block;
        }
        a.disabled {
            background-color: #6c757d;
            cursor: not-allowed;
            opacity: 0.6;
        }
    </style>
</head>
<body>

<div class="product-details">
    <h2>Product Details</h2>
    <p>Product Name: <?php echo htmlspecialchars($p_name); ?></p>
    <p>Product ID: <?php echo $p_id; ?></p>
    <p>Highest Bid: Rs. <?php echo $highest_bid; ?></p>
    <p class="timer" id="timer">Calculating time...</p>

    <!-- Link to the Payment Page -->
    <a href="payment.php" id="paymentLink" class="disabled">Proceed to Payment</a>
</div>

<script>
function startTimer(endTime) {
    var countDownDate = new Date(endTime).getTime();
    var paymentLink = document.getElementById('paymentLink');
    var timerElement = document.getElementById('timer');

    var x = setInterval(function() {
        var now = new Date().getTime();
        var distance = countDownDate - now;

        if (distance > 0) {
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            timerElement.innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
        } else {
            clearInterval(x);
            timerElement.innerHTML = "AUCTION ENDED";

            // Enable payment link only if the current user is the highest bidder
            if ("<?= htmlspecialchars($highest_bidder) ?>" === "<?= $_SESSION['username'] ?>") {
                paymentLink.classList.remove('disabled');
                paymentLink.style.cursor = "pointer";
            }
        }
    }, 1000);
}

// Set auction end time and start the timer
startTimer("<?= $auction_end_time ?>");
</script>

</body>
</html>
