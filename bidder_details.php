 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bidder</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
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
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .edit-btn, .delete-btn {
            padding: 6px 12px;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }
        .edit-btn {
            background-color: #007bff;
        }
        .edit-btn:hover {
            background-color: #0056b3;
        }
        .delete-btn {
            background-color: #dc3545;
        }
        .delete-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Bidder</h1>

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
        die("<p>Error connecting to database: " . $conn->connect_error . "</p>");
    }

    // Delete bidder if delete request is made
    if (isset($_GET['delete'])) {
        $b_id = intval($_GET['delete']);
        $deleteSql = "DELETE FROM Bidder WHERE b_id = $b_id";

        if ($conn->query($deleteSql) === TRUE) {
            echo "<p>Bidder deleted successfully.</p>";
        } else {
            echo "<p>Error deleting bidder: " . $conn->error . "</p>";
        }
    }

    // Fetch bidder data
    $sql = "SELECT * FROM Bidder";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>b_id</th>";
        echo "<th>username</th>";
        echo "<th>email</th>";
        echo "<th>phone_no</th>";
        echo "<th>password</th>";
        echo "<th>address</th>";
        echo "<th>Edit</th>";  // New column for edit option
        echo "<th>Delete</th>"; // New column for delete option
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['b_id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td>" . htmlspecialchars($row['phone_no']) . "</td>";
            echo "<td>" . htmlspecialchars($row['password']) . "</td>";
            echo "<td>" . htmlspecialchars($row['address']) . "</td>";
            echo "<td><a href='edit bidder.php?b_id=" . $row['b_id'] . "' class='edit-btn'>Edit</a></td>";  // Add edit button with link
            echo "<td>
                    <a href='?delete=" . $row['b_id'] . "' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this bidder?\");'>Delete</a>
                  </td>"; // Add delete button with confirmation
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "<p>No bidders found.</p>";
    }

    $conn->close();
    ?>

</div>

</body>
</html>
