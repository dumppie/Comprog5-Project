<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Successful - La Petite Pâtisserie</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            text-align: center;
            max-width: 500px;
            margin: 20px;
        }
        .success-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, #28a745, #20c997);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            font-size: 40px;
            color: white;
        }
        .title {
            font-size: 28px;
            color: #28a745;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .message {
            font-size: 16px;
            color: #495057;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        .button {
            display: inline-block;
            background: linear-gradient(45deg, #d63384, #e83e8c);
            color: white;
            padding: 15px 35px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            margin: 10px;
        }
        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(214, 51, 132, 0.3);
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #d63384;
            margin-bottom: 20px;
        }
        .features {
            text-align: left;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
        .features h3 {
            color: #495057;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .features ul {
            list-style: none;
            padding: 0;
        }
        .features li {
            padding: 8px 0;
            border-bottom: 1px solid #dee2e6;
            color: #6c757d;
        }
        .features li:last-child {
            border-bottom: none;
        }
        .features li:before {
            content: "✓ ";
            color: #28a745;
            font-weight: bold;
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">La Petite Pâtisserie</div>
        
        <div class="success-icon">
            ✓
        </div>
        
        <h1 class="title">Email Verified Successfully!</h1>
        
        <p class="message">
            Thank you for verifying your email address. Your account is now fully activated and you can enjoy all the features of La Petite Pâtisserie.
        </p>
        
        <div class="features">
            <h3>What's Next?</h3>
            <ul>
                <li>Browse our exquisite collection of pastries and cakes</li>
                <li>Place orders and enjoy our delivery service</li>
                <li>Receive exclusive offers and promotions</li>
                <li>Manage your orders and preferences</li>
            </ul>
        </div>
        
        <div>
            <a href="{{ route('home') }}" class="button">Go to Homepage</a>
            <a href="{{ route('login') }}" class="button">Login to Your Account</a>
        </div>
        
        <p style="margin-top: 30px; font-size: 14px; color: #6c757d;">
            If you have any questions, please don't hesitate to contact our support team.
        </p>
    </div>
</body>
</html>
