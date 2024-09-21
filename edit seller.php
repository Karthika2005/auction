 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Seller</title>
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
    <h1>Edit Seller</h1>

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

        // Update seller details if form is submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $s_id = $_POST['s_id'] ?? '';
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $phone_no = $_POST['phone_no'] ?? '';
            $address = $_POST['address'] ?? '';

            if (empty($s_id)) {
                echo "<p class='error'>Error: Seller ID is required.</p>";
            } else {
                // Prepare the SQL UPDATE query
                $sql = "UPDATE seller SET username = ?, email = ?, password = ?, phone_no = ?, address = ? WHERE s_id = ?";
                $stmt = $conn->prepare($sql);
                if ($stmt === false) {
                    echo "<p class='error'>Prepare failed: " . $conn->error . "</p>";
                } else {
                    // Bind the parameters: use the correct types (s = string, i = integer, etc.)
                    $stmt->bind_param("sssssi", $username, $email, $password, $phone_no, $address, $s_id);

                    // Execute the statement and check for success
                    if ($stmt->execute()) {
                        if ($stmt->affected_rows > 0) {
                            echo "<script>alert('Seller modified successfully.');</script>";
                        } else {
                            echo "<script>alert('No seller found with the ID: " . htmlspecialchars($s_id) . "');</script>";
                        }
                    } else {
                        echo "<script>alert('Error modifying seller: " . $stmt->error . "');</script>";
                    }

                    // Close the statement
                    $stmt->close();
                }
            }
        }

        // Check if s_id is set and is valid
        if (isset($_GET['s_id']) && is_numeric($_GET['s_id'])) {
            $s_id = intval($_GET['s_id']);
        } else {
            echo "<p class='error'>Invalid seller ID.</p>";
            exit();
        }

        // Fetch current seller details
        $sql = "SELECT * FROM seller WHERE s_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $s_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $seller = $result->fetch_assoc();
        ?>
            <form method="post">
                <input type="hidden" name="s_id" value="<?php echo htmlspecialchars($s_id); ?>">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($seller['username']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($seller['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="phone_no">Phone Number:</label>
                    <input type="text" id="phone_no" name="phone_no" value="<?php echo htmlspecialchars($seller['phone_no']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($seller['address']); ?>" required>
                </div>
                <div class="form-group">
                    <button type="submit">Update Seller</button>
                </div>
            </form>
        <?php
        } else {
            echo "<p class='error'>Seller not found.</p>";
        }

        $stmt->close();
        $conn->close();
        ?>
    </div>
</div>

</body>
</html>
