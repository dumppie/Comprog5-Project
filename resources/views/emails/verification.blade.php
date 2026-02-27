<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification - La Petite Pâtisserie</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #d63384;
            margin-bottom: 10px;
        }
        .title {
            font-size: 20px;
            color: #495057;
            margin: 0;
        }
        .content {
            margin: 30px 0;
        }
        .button {
            display: inline-block;
            background: linear-gradient(45deg, #d63384, #e83e8c);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            text-align: center;
            margin: 20px 0;
            transition: all 0.3s ease;
        }
        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(214, 51, 132, 0.3);
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            font-size: 14px;
            color: #6c757d;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 15px;
        }
        .message {
            margin-bottom: 25px;
            line-height: 1.8;
        }
        .security-note {
            background: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #28a745;
            margin: 20px 0;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">La Petite Pâtisserie</div>
            <h1 class="title">Email Verification</h1>
        </div>
        
        <div class="content">
            <p class="greeting">Hello {{ $userName }},</p>
            
            <p class="message">
                Thank you for registering at La Petite Pâtisserie! To complete your registration and ensure the security of your account, please verify your email address by clicking the button below.
            </p>
            
            <div style="text-align: center;">
                <a href="{{ $verificationUrl }}" class="button">Verify Email Address</a>
            </div>
            
            <p class="message">
                This verification link will expire in 24 hours. If you didn't create an account with us, please disregard this email.
            </p>
            
            <div class="security-note">
                <strong>Security Note:</strong> Never share this verification link with anyone. If you suspect any unauthorized activity on your account, please contact our support team immediately.
            </div>
        </div>
        
        <div class="footer">
            <p>Warm regards,<br>The La Petite Pâtisserie Team</p>
            <p style="font-size: 12px; margin-top: 10px;">
                This is an automated message. Please do not reply to this email.
            </p>
        </div>
    </div>
</body>
</html>
