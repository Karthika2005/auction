 <?php
session_start(); // Start the session to manage auction data

// Initialize the auction data if not already set
if (!isset($_SESSION['highestBid'])) {
    $_SESSION['highestBid'] = 100; // Starting price
    $_SESSION['highestBidder'] = "No bids yet";
}

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bidderName = htmlspecialchars($_POST['bidderName']);
    $bidAmount = floatval($_POST['bidAmount']);

    // Check if the new bid is higher than the current highest bid
    if ($bidAmount > $_SESSION['highestBid']) {
        $_SESSION['highestBid'] = $bidAmount;
        $_SESSION['highestBidder'] = $bidderName;
        echo "Congratulations, $bidderName! You are the highest bidder with a bid of $$bidAmount.";
    } else {
        echo "Your bid must be higher than the current highest bid of $" . $_SESSION['highestBid'] . ".";
    }
}

// Display the current highest bid
echo "<h2>Current Highest Bid: $" . $_SESSION['highestBid'] . " by " . $_SESSION['highestBidder'] . "</h2>";

echo '<br><a href="index.html">Go back to Auction Page</a>';
?>
