 <!DOCTYPE html>
<html lang="en">
 
    
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Online Auction</title>
         
       
 </head>
 <body style="background: url('bid.jpeg') no-repeat   center; background-size: cover;">
    <div class="header">
        About Us
    </div>
    <style>
        .header {
  background-color: royalblue;
  color: white;
  padding: 15px 0;
  text-align: center;
  font-size: 24px;
}
        .container {
  background-color: rgba(255, 255, 255, 0.5); /* White with 50% opacity */
  max-width: 700px;
  margin: 20px auto;
  padding: 20px;
  color: brown;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  border-radius: 15px;
}
.footer {
  background-color: royalblue;
  color: white;
  padding: 15px;
  text-align: center;
  width: 100%;
  position: fixed;
  bottom: 0;
  left: 0;
}
        </style>
   

    <div class="container">
         
    
        <h4 id="welcome-message"><b>We provide a seamless and dynamic online auction experience where buyers and sellers can connect and engage in exciting bidding activities. Our platform is designed to offer a wide range of items, from antiques and collectibles to electronics and more.</b></h4>

        <h2 id="mission-heading">Our Mission</h2>
        <p id="mission-message" class="message">Our mission is to revolutionize the auction industry by providing a user-friendly platform that ensures transparency, fairness, and accessibility for everyone. We are committed to delivering a secure and enjoyable experience for all our users, whether you're here to sell a prized item or find the perfect deal.</p>

        <h2 id="howitworks-heading">How It Works</h2>
        <p id="howitworks-message" class="message">Our auction platform is designed to be intuitive and easy to use. Sellers can list their items with detailed descriptions and high-quality images, while buyers can browse through the listings, place bids, and win items in real-time. We provide robust tools to manage bids, monitor auctions, and ensure a smooth transaction process.</p>
          

        <h2 id="whychooseus-heading">Why Choose Us?</h2>
        <p id="whychooseus-message">We stand out from the competition due to our commitment to excellent customer service, cutting-edge technology, and a user-centric approach. Our team of dedicated professionals is here to assist you every step of the way, ensuring that your auction experience is as enjoyable and efficient as possible.</p>

        <h2 id="contact-heading">Contact Us</h2>
        <p id="contact-message">If you have any questions or need assistance, feel free to <a href="contact.php">contact</a> us. We’re here to help and look forward to hearing from you!</p>
    </div>

    <div class="footer">
        &copy; 2024 Online Auction. All rights reserved.
    </div>

    <script>
        // Function to toggle the visibility of the message
        function toggleMessage(event) {
            const messageId = event.target.id.replace('-heading', '-message');
            const messageElement = document.getElementById(messageId);
            if (messageElement) {
                // Toggle display between 'none' and 'block'
                if (messageElement.style.display === 'none' || messageElement.style.display === '') {
                    messageElement.style.display = 'block';
                } else {
                    messageElement.style.display = 'none';
                }
            }
        }

        // Attach click event listeners to all headings
        document.querySelectorAll('h2').forEach(heading => {
            heading.addEventListener('click', toggleMessage);
        });
    </script>
</body>
</html>
