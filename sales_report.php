 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Overview for 2024</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        form {
            text-align: center;
            margin-bottom: 20px;
        }

        input[type="number"] {
            max-width: 900px;
            padding: 5px;
            margin-right: 10px;
        }

        button {
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <h1>Sales Overview for 2024</h1>

    <form action="" method="POST">
        <label for="month">Select Month: </label>
        <input type="number" id="month" name="month" min="1" max="12" required>
        <button type="submit">Generate Report</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "auction_db";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $month = $_POST['month'];
        $year = 2024; // Fixed year

        // SQL query to get sales data for the specified month in 2024
        $stmt = $conn->prepare("
            SELECT p.p_name, p.highest_bid, pay.amount, pay.auction_date
            FROM product p
            JOIN payment pay ON p.p_id = pay.p_id
            WHERE MONTH(pay.auction_date) = ? AND YEAR(pay.auction_date) = ?
            ORDER BY pay.auction_date
        ");
        $stmt->bind_param("ii", $month, $year);
        $stmt->execute();
        $result = $stmt->get_result();

        $totalSales = 0; // Variable to hold total sales amount

        if ($result->num_rows > 0) {
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Product Name</th>';
            echo '<th>Highest Bid</th>';
            echo '<th>Sold Amount</th>';
            echo '<th>Auction Date</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            // Output data for each row
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['p_name']) . '</td>';
                echo '<td>Rs.' . htmlspecialchars($row['highest_bid']) . '</td>';
                echo '<td>Rs.' . htmlspecialchars($row['amount']) . '</td>';
                echo '<td>' . htmlspecialchars($row['auction_date']) . '</td>';
                echo '</tr>';

                // Add to total sales amount
                $totalSales += $row['amount'];
            }

            echo '</tbody>';
            echo '</table>';

            // Display total sales amount
            echo "<h2>Total Sales Amount for Month $month, 2024: Rs." . number_format($totalSales, 2) . "</h2>";
        } else {
            echo "<p>No sales found for this month.</p>";
        }

        $stmt->close();
        $conn->close();
    }
    ?>

</body>
</html>
