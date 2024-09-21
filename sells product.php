 <?php
session_start();  // Start session at the beginning
$alert_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Define database credentials
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "auction_db";

    // Retrieve form inputs
    $p_id = $_POST['p_id'];
    $p_name = $_POST['p_name'];
    $image = $_POST['image']; // Note: For file uploads, handling will be different
    $description = $_POST['description'];
    $starting_bid = $_POST['starting_bid'];
    $highest_bid = $_POST['highest_bid'];
    $auction_end_time = $_POST['auction_end_time']; // Expected format 'Y-m-d\TH:i'
    $s_id = $_POST['s_id'];  // Retrieve seller ID from the form

    // Convert auction_end_time to a format MySQL accepts (Y-m-d H:i:s)
    $auction_end_time = date('Y-m-d H:i:s', strtotime($auction_end_time));

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO product (p_id, p_name, image, description, starting_bid, highest_bid, auction_end_time, s_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $p_id, $p_name, $image, $description, $starting_bid, $highest_bid, $auction_end_time, $s_id);

    // Execute the statement and set a success flag
    $success = $stmt->execute();

    // Close connection
    $stmt->close();
    $conn->close();

    // If successful, set the product ID in the session and alert message
    if ($success) {
        $_SESSION['p_id'] = $p_id;  // Store the product ID in the session
        $alert_message = "Product uploaded successfully!";
    } else {
        $alert_message = "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Product</title>
    <!-- Style omitted for brevity -->
    <style>
         .container {
             background-color: rgba(255, 255, 255, 0.5); /* White with 50% opacity */
             max-width: 360px;
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
    </style>
</head>
<body style="background: url('sell.jpg') no-repeat center center; background-size: cover;">
    <div class="container">
        <h2 style="text-align: center;">PRODUCT PAGE</h2>
        <form id="productForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="p_id">Product id:</label>
            <input type="number" id="p_id" name="p_id" required><br><br>

            <label for="s_id">Seller ID:</label>
            <input type="number" id="s_id" name="s_id" required><br><br>

            <label for="p_name">Product Name:</label>
            <input type="text" id="p_name" name="p_name" required><br><br>

            <label for="image">Product Image:</label>
            <input type="file" id="image" name="image" accept="image/*" required><br><br>

            <label for="description">Description:</label>
            <input type="text" id="description" name="description" required><br><br>

            <label for="starting_bid">Starting Bid:</label>
            <input type="number" id="starting_bid" name="starting_bid" required><br><br>

            <label for="highest_bid">Highest Bid:</label>
            <input type="number" id="highest_bid" name="highest_bid" required><br><br>

            <!-- Use datetime-local for better date and time input -->
            <label for="auction_end_time">End time:</label>
            <input type="datetime-local" id="auction_end_time" name="auction_end_time" required><br><br> 

            

            <center>
                <button type="submit">UPLOAD PRODUCT</button>
            </center>
        </form>
    </div>

    <?php if ($alert_message): ?>
        <script>
            alert("<?php echo $alert_message; ?>");
            <?php if ($alert_message == "Product uploaded successfully!"): ?>
                // Redirect to the details page to show the product info
                setTimeout(function() {
                    window.location.href = 'demo.php';  // Redirect to details page
                }, 1000);
            <?php endif; ?>
        </script>
    <?php endif; ?>
</body>
</html>
