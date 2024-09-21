
<?php
$alert_message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Define database credentials
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "auction_db";

    $name = $_POST['name'];
    $email = $_POST['email'];
    $Queries = $_POST['Queries'];

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO contact_queirs (name, email, Queries) VALUES (?, ?, ?)");

    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sss", $name, $email, $Queries);

    // Execute the statement and set a success flag
    $success = $stmt->execute();

    // Close connection
    $stmt->close();
    $conn->close();

 
    if ($success) {
        $alert_message = "Thank you for your feedback!";
    } else {
        $alert_message = "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
     <link rel="stylesheet" href="style.css">

    <meta charset="UTF-8">
    <title>CONTACT</title>
     <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
             

            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

         

        input[type="text"], input[type="email"], input[type="text"] {
            width: 90%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 15px;
        }

         
          button{
            padding: 7px 25px;
            border-radius:9px;
            font-size: 17px;
            background: brown;
             
        }

        button:hover{
            box-shadow: 0 0 5px brown,
            0 0 25px brown,0 0 50px brown,
            0 0 100px brown,0 0 200px brown;
        }
         

         
        

         
    </style>
    
</head>
 <body style="background: url('c.jpeg') no-repeat  ; background-size: cover;">
    <div class="container">
        <h2 style="text-align: center;">CONTACT PAGE</h2>
        <form id="loginForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br><br>

            <label for="Queries">How can we help you?:</label>
            <input type="text" id="Queries" name="Queries" required><br><br>
<center>
            <button type="submit">SUBMIT</button>
    </center>
        </form>
         
    </div>

    <?php if ($alert_message): ?>
        <script>
            // Show alert message and redirect after a short delay
            alert("<?php echo $alert_message; ?>");
            <?php if ($alert_message == "Thank you for your feedback!"): ?>
                setTimeout(function() {
                    window.location.href = 'homepage.php';
                }, 1000); // Redirect after 1 second
            <?php endif; ?>
        </script>
    <?php endif; ?>
</body>
</html>

