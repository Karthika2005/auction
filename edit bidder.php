 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Bidder</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .form-group button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        .form-group button:hover {
            background-color: #218838;
        }
        .success {
            color: green;
            text-align: center;
        }
        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Edit Bidder</h1>

    <div id="response">
        <?php
        // Database connection settings
        $servername = "localhost";
        $username = "root"; // Replace with your database username
        $password = ""; // Replace with your database password
        $dbname = "auction_db"; // Replace with your database name

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            echo "<p class='error'>Connection failed: " . $conn->connect_error . "</p>";
            exit();
        }

        // Update bidder details if form is submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $b_id = $_POST['b_id'] ?? '';
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $phone_no = $_POST['phone_no'] ?? '';
            $address = $_POST['address'] ?? '';

            if (empty($b_id)) {
                echo "<p class='error'>Error: Bidder ID is required.</p>";
            } else {
                // Prepare the SQL UPDATE query
                $sql = "UPDATE bidder SET username = ?, email = ?, password = ?, phone_no = ?, address = ? WHERE b_id = ?";
                $stmt = $conn->prepare($sql);
                if ($stmt === false) {
                    echo "<p class='error'>Prepare failed: " . $conn->error . "</p>";
                } else {
                    // Bind the parameters: use the correct types (s = string, i = integer, etc.)
                    $stmt->bind_param("sssssi", $username, $email, $password, $phone_no, $address, $b_id);

                    // Execute the statement and check for success
                    if ($stmt->execute()) {
                        if ($stmt->affected_rows > 0) {
                         echo "<script>alert('Bidder modified successfully.');</script>";                     
                           } 
                         else {
                         echo "<script>alert('No bidder found with the ID: " . htmlspecialchars($b_id) . "');</script>";                        }
                    } else {
                                            echo "<script>alert('Error modifying bidder: " . $stmt->error . "');</script>";
                     }

                    // Close the statement
                    $stmt->close();
                }
            }
        }

        // Check if b_id is set and is valid
        if (isset($_GET['b_id']) && is_numeric($_GET['b_id'])) {
            $b_id = intval($_GET['b_id']);
        } else {
            echo "<p class='error'>Invalid bidder ID.</p>";
            exit();
        }

        // Fetch current bidder details
        $sql = "SELECT * FROM bidder WHERE b_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $b_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $bidder = $result->fetch_assoc();
        ?>
            <form method="post">
                <input type="hidden" name="b_id" value="<?php echo htmlspecialchars($b_id); ?>">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($bidder['username']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($bidder['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="phone_no">Phone Number:</label>
                    <input type="text" id="phone_no" name="phone_no" value="<?php echo htmlspecialchars($bidder['phone_no']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($bidder['address']); ?>" required>
                </div>
                <div class="form-group">
                    <button type="submit">Update Bidder</button>
                </div>
            </form>
        <?php
        } else {
            echo "<p class='error'>Bidder not found.</p>";
        }

        $stmt->close();
        $conn->close();
        ?>
    </div>
</div>

</body>
</html>
