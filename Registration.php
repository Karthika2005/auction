 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .form-container {
            width: 400px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .form-container input[type="text"],
        .form-container input[type="email"],
        .form-container input[type="password"],
        .form-container input[type="tel"],
        .form-container textarea {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .form-container select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .form-container input[type="submit"] {
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-container input[type="submit"]:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

    <h2>Signup Form</h2>

    <div class="form-container">
        <form method="POST" action="">

            <label for="username">Username</label>
            <input type="text" name="username" id="username" required>

            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>

            <label for="phone">Phone Number</label>
            <input type="tel" name="phone" id="phone" required>

            <label for="address">Address</label>
            <textarea name="address" id="address" rows="3" required></textarea>

            <label for="role">User is:</label>
            <select name="role" id="role" required>
                <option value="bidder">Bidder</option>
                <option value="seller">Seller</option>
            </select>
<center>
            <input type="submit" name="submit" value="Sign Up">
    </center>

        </form>
    </div>

    <?php
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "auction_db";
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
        // Retrieve form data
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $role = $_POST['role'];

        if ($role == 'seller') {
            // Insert into seller table
            $sql = "INSERT INTO seller (username, email, password, phone_no, address) VALUES ('$username', '$email', '$password', '$phone', '$address')";
        } else {
            // Insert into bidder table
            $sql = "INSERT INTO bidder (username, email, password, phone_no, address) VALUES ('$username', '$email', '$password', '$phone', '$address')";
        }

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Signup successful as a $role!');</script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "');</script>";
        }
    }

    $conn->close();
    ?>

</body>
</html>