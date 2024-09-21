<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Get the username from the session
$username = $_SESSION['username'];

// Database connection
$conn = new mysqli("localhost", "root", "", "auction_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle bid submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_bid'])) {
    $p_id = $_POST['p_id'];
    $new_bid = $_POST['bid'];

    // Fetch the current highest bid and auction end time for the product
    $check_bid_sql = "SELECT highest_bid, auction_end_time, highest_bidder, p_name FROM product WHERE p_id = $p_id";
    $check_bid_result = $conn->query($check_bid_sql);
    if ($check_bid_result->num_rows > 0) {
        $row = $check_bid_result->fetch_assoc();
        $current_highest_bid = $row['highest_bid'];
        $auction_end_time = strtotime($row['auction_end_time']);
        $current_time = time();

        // Update the bid if the new bid is higher and auction has not ended
        if ($new_bid > $current_highest_bid && $current_time < $auction_end_time) {
            $update_bid_sql = "UPDATE product SET highest_bid = $new_bid, highest_bidder = '$username' WHERE p_id = $p_id";
            if ($conn->query($update_bid_sql) === TRUE) {
                echo "<script>alert('Bid placed successfully!');</script>";
            } else {
                echo "<script>alert('Error placing bid. Please try again.');</script>";
            }
        } elseif ($current_time >= $auction_end_time) {
            echo "<script>alert('Auction has ended. You cannot place a bid anymore.');</script>";
        } else {
            echo "<script>alert('Your bid must be higher than the current highest bid!');</script>";
        }
    }
}

// Fetch products from the database
$sql = "SELECT p_id, p_name, image, description, starting_bid, highest_bid, highest_bidder, auction_end_time FROM product";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auction Products</title>
    <style>
        /* Styling for the page */
        body {
            font-family: Arial, sans-serif;
        }
        .products-container {
            display: flex;
            flex-wrap: wrap;
        }
        .product-card {
            width: 250px;
            margin: 15px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #fff;
            text-align: center;
        }
        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: contain;
            margin-bottom: 10px;
        }
        .timer {
            color: red;
            font-weight: bold;
        }
        .bid-form input[type="number"] {
            padding: 5px;
            margin-bottom: 10px;
        }
        .bid-form input[type="submit"] {
            padding: 10px 20px;
            background-color: #28a745; /* Green color for active button */
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .bid-form input[type="submit"]:disabled {
            background-color: #6c757d; /* Gray color for disabled button */
            cursor: not-allowed;
            opacity: 0.6;
            border: 1px solid #ccc;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 300px;
            text-align: center;
            border-radius: 10px;
        }
        .modal-content h2 {
            color: green;
        }
        .modal-content button {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            margin-top: 20px;
        }
        .modal-content button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<header>
    <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
</header>

<div class="products-container">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $auction_end_time = strtotime($row["auction_end_time"]);
            $current_time = time();
            $disabled = ($current_time >= $auction_end_time) ? "disabled" : "";
            $auction_end_time_js = date('Y-m-d H:i:s', $auction_end_time);
    ?>
        <div class="product-card">
            <?php if (!empty($row["image"])) { ?>
                <img src="<?= htmlspecialchars($row["image"]) ?>" alt="<?= htmlspecialchars($row["p_name"]) ?>">
            <?php } else { ?>
                <div>No image available</div>
            <?php } ?>

            <h3><?= htmlspecialchars($row["p_name"]) ?></h3>
            <p><?= htmlspecialchars($row["description"]) ?></p>
            <p>Starting Bid: Rs. <?= htmlspecialchars($row["starting_bid"]) ?></p>
            <p>Highest Bid: Rs. <?= htmlspecialchars($row["highest_bid"]) ?></p>
            <p class="timer" id="timer<?= $row['p_id'] ?>">Calculating time...</p>

            <form method="post" action="" class="bid-form">
                <input type="hidden" name="p_id" value="<?= $row['p_id'] ?>">
                <input type="number" name="bid" min="<?= $row['highest_bid'] + 1 ?>" placeholder="Enter your bid" required>
                <input type="submit" name="submit_bid" id="bid_button<?= $row['p_id'] ?>" value="Place Bid" <?= $disabled ?>>
            </form>
        </div>

        <script>
        function startTimer(timerId, bidButtonId, endTime, productName, highestBidder) {
            var countDownDate = new Date(endTime).getTime();
            var x = setInterval(function() {
                var now = new Date().getTime();
                var distance = countDownDate - now;

                if (distance >= 0) {
                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    
                    document.getElementById(timerId).innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
                } else {
                    clearInterval(x);
                    document.getElementById(timerId).innerHTML = "AUCTION ENDED";
                    document.getElementById(bidButtonId).disabled = true; 

                    // If the current user is the highest bidder, show a winning modal
                    if (highestBidder === '<?= $username ?>') {
                        showWinnerModal(productName);
                    }
                }
            }, 1000);
        }

        function showWinnerModal(productName) {
            var modal = document.getElementById("winnerModal");
            modal.style.display = "block";
            document.getElementById("productWon").innerText = productName;
        }

        startTimer("timer<?= $row['p_id'] ?>", "bid_button<?= $row['p_id'] ?>", "<?= $auction_end_time_js ?>", "<?= htmlspecialchars($row['p_name']) ?>", "<?= htmlspecialchars($row['highest_bidder']) ?>");
        </script>

    <?php
        }
    } else {
        echo "No products found.";
    }
    $conn->close();
    ?>
</div>

<!-- Modal -->
<div id="winnerModal" class="modal">
    <div class="modal-content">
        <h2>Congratulations!</h2>
        <p>You have won the auction for <span id="productWon"></span>!</p>
        <button onclick="location.href='payment.php'">Continue to Payment</button>
    </div>
</div>

<script>
    // Close modal on window click
    window.onclick = function(event) {
        var modal = document.getElementById("winnerModal");
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>
</body>
</html>
