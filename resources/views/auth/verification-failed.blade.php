<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Failed - La Petite Pâtisserie</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
        .error-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, #dc3545, #e74c3c);
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
            color: #dc3545;
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
        .button-secondary {
            background: linear-gradient(45deg, #6c757d, #5a6268);
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #d63384;
            margin-bottom: 20px;
        }
        .help-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            text-align: left;
        }
        .help-section h3 {
            color: #495057;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .help-section ul {
            list-style: none;
            padding: 0;
        }
        .help-section li {
            padding: 8px 0;
            color: #6c757d;
        }
        .help-section li:before {
            content: "• ";
            color: #d63384;
            font-weight: bold;
            margin-right: 8px;
        }
        .alert {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">La Petite Pâtisserie</div>
        
        <div class="error-icon">
            ✕
        </div>
        
        <h1 class="title">Verification Failed</h1>
        
        <p class="message">
            We couldn't verify your email address. The verification link may have expired or is invalid.
        </p>
        
        @if(session('error'))
            <div class="alert">
                {{ session('error') }}
            </div>
        @endif
        
        <div class="help-section">
            <h3>What could have happened?</h3>
            <ul>
                <li>The verification link has expired (valid for 24 hours)</li>
                <li>The verification link was already used</li>
                <li>The link was copied incorrectly</li>
                <li>You may have already verified your email</li>
            </ul>
        </div>
        
        <div>
            <a href="{{ route('verification.notice') }}" class="button">Request New Verification</a>
            <a href="{{ route('home') }}" class="button button-secondary">Return to Homepage</a>
        </div>
        
        <p style="margin-top: 30px; font-size: 14px; color: #6c757d;">
            If you continue to experience issues, please contact our support team for assistance.
        </p>
    </div>
</body>
</html>
