 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - Online Auction</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            background-image:url(auct.jpg) ;
            background-image:no-repeat;
            background-size: cover;
            color: #333;
        }
        
        .header {
            background-color: brown;
            color: white;
            padding: 15px 0;
            text-align: center;
            font-size: 24px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

 .container {
  background-color: rgba(255, 255, 255, 0.5); /* White with 50% opacity */
  max-width: 600px;
  margin: 20px auto;
  padding: 20px;
  color: brown;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  border-radius: 15px;
}
        

        .faq-section h2, .contact-section h2 {
            font-size: 22px;
            border-bottom: 2px solid pink;
            padding-bottom: 5px;
            margin-bottom: 20px;
        }

        .faq-item {
            margin: 10px 0;
        }

        .faq-item h3 {
            font-size: 20px;
            cursor: pointer;
            padding: 10px;
            background: grey;
            color: white;
            margin: 0;
            border-radius: 5px;
            transition: background 0.3s ease;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .faq-item h3:hover {
            background: #555;
        }

        .faq-item-content {
            max-height: 0;
            padding: 0 10px;
            background: #f1f1f1;
            border-left: 4px solid #0073e6;
            border-radius: 0 0 5px 5px;
            transition: max-height 0.3s ease-out, padding 0.3s ease-out;
            overflow: hidden;
        }

        .faq-item-content p {
            margin: 10px 0;
        }

        .faq-item[aria-expanded="true"] .faq-item-content {
            max-height: 200px; /* Adjust according to content length */
            padding: 10px;
        }

        .faq-item[aria-expanded="true"] h3::after {
            content: "▲";
        }

        .faq-item[aria-expanded="false"] h3::after {
            content: "▼";
        }
    </style>
</head>
<body>
    <div class="header">
        FAQ
    </div>
    <div class="container">
        <div class="faq-section">
            <h2>Frequently Asked Questions (FAQ)</h2>
            <div class="faq-item" aria-expanded="false">
                <h3 onclick="toggleAnswer(this)">How do I create an auction?</h3>
                <div class="faq-item-content" id="faq1">
                    <p>To create an auction, navigate to the "Sell your item" page, fill in the details of the item you wish to auction, and submit the form. Make sure to include clear photos and a detailed description to attract more bidders.</p>
                </div>
            </div>
            <div class="faq-item" aria-expanded="false">
                <h3 onclick="toggleAnswer(this)">How do I place a bid?</h3>
                <div class="faq-item-content" id="faq2">
                    <p>To place a bid, go to the auction page of the item you are interested in, enter your bid amount in the bid box, and click the "Place Bid" button. Ensure that your bid is higher than the current bid.</p>
                </div>
            </div>
            <div class="faq-item" aria-expanded="false">
                <h3 onclick="toggleAnswer(this)">Can I authorize someone else to bid on my behalf?</h3>
                <div class="faq-item-content" id="faq3">
                    <p> No, we don't recommend sharing these details for security purposes.</p>
                </div>
            </div>
            <div class="faq-item" aria-expanded="false">
                <h3 onclick="toggleAnswer(this)">How can I cancel my bid?</h3>
                <div class="faq-item-content" id="faq4">
                    <p> Once a bid is placed, the bidders cannot modify or cancel the bid.</p>
                </div>
            </div>
            <div class="faq-item" aria-expanded="false">
                <h3 onclick="toggleAnswer(this)">How do I buy a product on the site?</h3>
                <div class="faq-item-content" id="faq5">
                    <p> Login with your valid username and password enter the auction, bid and win the product of your choice.  </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleAnswer(element) {
            const parent = element.parentElement;
            const isExpanded = parent.getAttribute('aria-expanded') === 'true';
            
            // Close all other FAQs
            document.querySelectorAll('.faq-item').forEach(item => {
                item.setAttribute('aria-expanded', 'false');
            });

            // Toggle the clicked FAQ
            if (!isExpanded) {
                parent.setAttribute('aria-expanded', 'true');
            }
        }
    </script>
</body>
</html>
