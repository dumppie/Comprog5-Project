<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; color: #333; line-height: 1.5; }
        .header { color: #5C4033; margin-bottom: 1.5rem; }
    </style>
</head>
<body>
    <h2 class="header">La Petite Pâtisserie — Order Status Update</h2>
    <p>Hello {{ $order->user->name }},</p>
    <p>Your order <strong>#{{ $order->transaction_id }}</strong> status has been updated.</p>
    <p><strong>Previous status:</strong> {{ $previousStatus }}</p>
    <p><strong>New status:</strong> {{ $order->orderStatus->name }}</p>
    <p>Please find your receipt attached as a PDF.</p>
    <p>Thank you.</p>
</body>
</html>
