    <?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];
?>

   
 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Auction Homepage</title>
     
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            scroll-behavior: smooth;  
            background-color: #f5f5f5;
            display: flex;
        }
 
        .marquee-container {
            position: fixed;
            left: 0;
            top: 0;
            width: 220px;
            height: 100vh;
            background-color: gold;
            color: white;
            overflow: hidden;
            z-index: 1001;
            padding: 15px;
        }

        #winnerMarquee {
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            height: 100%;
        }

        .marquee-item {
            display: flex;
            align-items: center;
            margin: 15px 0;
        }

        .marquee-item img {
            width: 50px;
            height: 50px;
            margin-right: 10px;
            border-radius: 50%;
        }

        .marquee-item p {
            font-size: 14px;
            margin: 0;
        }

         
        .main-content {
            margin-left: 240px;
            width: calc(100% - 240px);
        }

        header {
            background-color: cream;
            color: red;
            padding: 15px;
             height: 30px;
            position: sticky;
            top: 0;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header .logo {
            font-size: 24px;
            font-weight: bold;     
        }

        header nav ul {
            list-style: none;
            margin: 0;
            
            padding: 0;
            display: flex;
        }

        header nav ul li {
            margin-left: 20px;
             
        }

        header nav ul li a {
            color: red;
            text-decoration: none;
            font-weight: 500;
        }

        .hero {
            color: white;
            background-image: url('shape.png');
            background-repeat: no-repeat;
            background-size: cover;
            text-align: center;
            padding: 150px 20px;
            position: relative;
        }

        .hero h1 {
            font-size: 48px;
            margin-bottom: 20px;
              color: white;
            position: relative;
            z-index: 2;
        }

        .hero p {
            font-size: 18px;
            margin-bottom: 30px;
            position: relative;
             
            z-index: 2;
        }

        .hero .cta-buttons {
            display: flex;
            justify-content: center;
            position: relative;
            z-index: 2;
        }

        .hero .cta-buttons a {
            background-color: #f39c12;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            margin: 0 10px;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .hero .cta-buttons a:hover {
            background-color: #e67e22;
        }

        section {
            padding: 60px 20px;
            text-align: center;
        }
    </style>
</head>
<body>
     
    <div class="marquee-container">
        <?php
        
        $servername = "localhost";  
        $username = "root";         
        $password = "";            
        $dbname = "auction_db";    

         
        $conn = new mysqli($servername, $username, $password, $dbname);

         
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

         
        $product_sql = "SELECT p_name, highest_bid, image FROM product";
        $product_result = $conn->query($product_sql);

        
        if ($product_result->num_rows > 0) {
            echo "<div id='winnerMarquee'>";
            while ($product_row = $product_result->fetch_assoc()) {
                echo "<div class='marquee-item'>";
                
                $imageSrc = !empty($product_row["image"]) ? htmlspecialchars($product_row["image"]) : 'placeholder.png';
                echo "<img src='$imageSrc' alt='Product Image'>";
                echo "<p>Product: " . htmlspecialchars($product_row["p_name"]) . "<br>Highest Bid: Rs." . htmlspecialchars($product_row["highest_bid"]) . "</p>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<p>No products available!</p>";
        }

        $conn->close();
        ?>
    </div>

    
    <div class="main-content">
        <header>
            <div class="logo"> 
                 <?php
 
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            text-align: center;
        }
        h1 {
            color: #333;
        }
    </style>
</head>
<body>

<h3 color: red>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h3>
 
</body>
</html>
            </div>
            <nav>
                <ul>
                    <li><a href="#home">HOME</a></li>
                    <li><a href="about.php">ABOUT</a></li>
                    <li><a href="admin dashboard.php">ADMIN DASHBOARD</a></li>
                     <li><a href="FAQ.php">FAQ</a></li>
                    <li><a href="contact.php">CONTACT</a></li>
                    <li><a href="logout.php">LOGOUT</a></li>
                     </ul>    
            </nav>
        </header>

        <section class="hero" id="home">
            <h1>Bid on Your Favorite Items Today!</h1>
            <p>Join our community of passionate bidders and sellers.</p>
            <div class="cta-buttons">
                <a href="sells product.php">Sell Your Item</a>
                <a href="product.php">Start Bidding</a>
            </div>
        </section>

 

    </div>

   
    <script>
         
        function startVerticalMarquee() {
            const marquee = document.getElementById('winnerMarquee');
            let top = 100;  
            const marqueeHeight = marquee.scrollHeight;
             

            function scroll() {
                top--;
                if (top < -marqueeHeight) {
                    top = 100;  
                }
                marquee.style.transform = "translateY(" + top + "px)";
            }

            setInterval(scroll, 30);  
        }

        window.onload = startVerticalMarquee;
    </script>
</body>
</html>