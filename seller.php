 <html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>Online Auction System</title>
</head>   
<style>
    /* Navigation bar styling */
    .navbar {
        width: 100%;
        background-color: darkblue;
        overflow: hidden;
        padding: 5px;
    }

    .navbar a {
        float: left;
        display: block;
        color: red;
        text-align: center;
        padding: 14px 120px;
        text-decoration: none;
        font-size: 17px;
    }

    .navbar a:hover {
        background-color: lightgreen;
        color: black;
    }

    .content {
        padding-top: 20px;
        text-align: center;
    }

    body {
        background: url('bid.jpeg') no-repeat;
        background-size: cover;
        margin: 0;
        padding: 0;
    }
</style>

<body>
    <div class="navbar">
        <a href="demo.php">Home</a>
        <a href="sells product.php">Sell</a>
        <a href="product.php">Auctions</a>     
        <a href="logout.php">Logout</a>
    </div>

    <div class="content">
        <center>
            <h1 style="color:Red;">Start selling</h1>
        </center>
    </div>
</body>
</html>
