 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Table Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
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
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .error {
            color: red;
            text-align: center;
        }
        .btn-edit, .btn-delete {
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-edit:hover {
            background-color: #0056b3;
        }
        .btn-delete {
            background-color: #dc3545; /* Red for delete */
        }
        .btn-delete:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Seller Table Data</h1>

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

    // Delete seller if delete request is made
    if (isset($_GET['delete'])) {
        $s_id = intval($_GET['delete']);
        $deleteSql = "DELETE FROM seller WHERE s_id = $s_id";

        if ($conn->query($deleteSql) === TRUE) {
            echo "<p>Seller deleted successfully.</p>";
        } else {
            echo "<p class='error'>Error deleting seller: " . $conn->error . "</p>";
        }
    }

    // Fetch seller data
    $sql = "SELECT * FROM seller";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<table>';
        echo '<tr><th>ID</th><th>Username</th><th>Email</th><th>Password</th><th>Phone Number</th><th>Address</th><th>Action</th><th>Delete</th></tr>';

        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['s_id']) . '</td>';
            echo '<td>' . htmlspecialchars($row['username']) . '</td>';
            echo '<td>' . htmlspecialchars($row['email']) . '</td>';
            echo '<td>' . htmlspecialchars($row['password']) . '</td>';
            echo '<td>' . htmlspecialchars($row['phone_no']) . '</td>';
            echo '<td>' . htmlspecialchars($row['address']) . '</td>';
            echo '<td><a href="edit seller.php?s_id=' . htmlspecialchars($row['s_id']) . '" class="btn-edit">Edit</a></td>';
            echo '<td>
                    <a href="?delete=' . $row['s_id'] . '" class="btn-delete" onclick="return confirm(\'Are you sure you want to delete this seller?\');">Delete</a>
                  </td>'; // Add delete button with confirmation
            echo '</tr>';
        }

        echo '</table>';
    } else {
        echo "<p class='error'>No sellers found.</p>";
    }

    $conn->close();
    ?>
</div>

</body>
</html>
