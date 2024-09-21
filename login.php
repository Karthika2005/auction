 <?php
session_start();  // Start session at the beginning

// Database connection
$servername = "localhost";
$dbusername = "root";  // Replace with your database username
$dbpassword = "";  // Replace with your database password
$dbname = "auction_db";

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    // Retrieve form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the user exists in the bidder table
    $sql_bidder = "SELECT * FROM bidder WHERE email = '$email'";
    $result_bidder = $conn->query($sql_bidder);

    if ($result_bidder->num_rows > 0) {
        $row = $result_bidder->fetch_assoc();

        // Compare the input password with the stored password
        if ($password == $row['password']) {
            // Set session variables for the logged-in bidder
            $_SESSION['username'] = $row['username'];
            $_SESSION['loggedin'] = true;
            $_SESSION['role'] = 'bidder';  // Add role for distinguishing users

            // Redirect to homepage after successful login
            header("Location: homepage.php");
            exit();
        } else {
            echo "<script>alert('Incorrect password.');</script>";
        }
    } else {
        // Check if the user exists in the seller table
        $sql_seller = "SELECT * FROM seller WHERE email = '$email'";
        $result_seller = $conn->query($sql_seller);

        if ($result_seller->num_rows > 0) {
            $row = $result_seller->fetch_assoc();

            // Compare the input password with the stored password
            if ($password == $row['password']) {
                // Set session variables for the logged-in seller
                $_SESSION['username'] = $row['username'];
                $_SESSION['loggedin'] = true;
                $_SESSION['role'] = 'seller';  // Add role for distinguishing users

                // Redirect to homepage after successful login
                header("Location: homepage.php");
                exit();
            } else {
                echo "<script>alert('Incorrect password.');</script>";
            }
        } else {
            echo "<script>alert('User not found, please sign up.');</script>";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('background.png') no-repeat center center fixed;
            background-size: cover;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .form-container {
            width: 400px;
            margin: 0 auto;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .form-container input[type="email"],
        .form-container input[type="password"] {
            width: 90%;
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

    <h2>Login</h2>

    <div class="form-container">
        <form method="POST" action="login.php">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
            <center>
                <input type="submit" name="login" value="Login">
            </center>
        </form>
    </div>

</body>
</html>
