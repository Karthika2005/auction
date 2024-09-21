 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auction Products Report</title>
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

        .status-live {
            color: green;
            font-weight: bold;
        }

        .status-ended {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <h1>Auction Product Report</h1>

    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "auction_db";
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT p_id, p_name, description, starting_bid, highest_bid, auction_end_time FROM product";
    $result = $conn->query($sql);
    ?>

    <table>
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Description</th>
                <th>Starting Bid</th>
                <th>Highest Bid</th>
                <th>Auction End Time</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Check if auction_end_time is valid
                    if (!empty($row["auction_end_time"])) {
                        // Convert the auction_end_time to a UNIX timestamp
                        $auction_end_time = strtotime($row["auction_end_time"]);
                    } else {
                        $auction_end_time = 0; // If no auction end time, consider it ended
                    }
                    
                    // Get the current time
                    $current_time = time();
                    
                    // Determine status based on comparison
                    if ($current_time >= $auction_end_time) {
                        $status = "Ended";
                        $status_class = "status-ended";
                    } else {
                        $status = "Live";
                        $status_class = "status-live";
                    }
            ?>
                <tr>
                    <td><?= htmlspecialchars($row["p_id"]) ?></td>
                    <td><?= htmlspecialchars($row["p_name"]) ?></td>
                    <td><?= htmlspecialchars($row["description"]) ?></td>
                    <td>Rs.<?= htmlspecialchars($row["starting_bid"]) ?></td>
                    <td>Rs.<?= htmlspecialchars($row["highest_bid"]) ?></td>
                    <td><?= htmlspecialchars($row["auction_end_time"]) ?></td>
                    <td class="<?= $status_class ?>"><?= $status ?></td>
                </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='7'>No products found.</td></tr>";
            }
            $conn->close();
            ?>
        </tbody>
    </table>

</body>
</html>
