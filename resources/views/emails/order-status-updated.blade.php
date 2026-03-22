<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; color: #333; line-height: 1.5; }
        .header { color: #5C4033; margin-bottom: 1.5rem; }
        .status-update { padding: 1rem; background: #f8f9fa; border-radius: 6px; margin: 1rem 0; }
        .pdf-notice { padding: 1rem; background: #e8f5e8; border-radius: 6px; border-left: 4px solid #28a745; margin: 1rem 0; }
    </style>
</head>
<body>
    <h2 class="header">La Petite Pâtisserie — Order Status Update</h2>
    <p>Hello {{ $order->user->name }},</p>
    <p>Your order <strong>#{{ $order->transaction_id }}</strong> status has been updated.</p>
    
    <div class="status-update">
        <p><strong>Previous status:</strong> {{ $previousStatus }}</p>
        <p><strong>New status:</strong> {{ $order->orderStatus->name }}</p>
    </div>
    
    <div class="pdf-notice">
        <h3 style="margin-top: 0; color: #155724;">📎 Updated Receipt</h3>
        <p>An updated receipt with the current order status has been attached as a PDF file for your records.</p>
        <p><strong>File:</strong> receipt-{{ $order->transaction_id }}.pdf</p>
        <p style="margin: 0;">
            <a href="{{ route('orders.receipt', $order) }}" style="display: inline-block; padding: 8px 16px; background: #28a745; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">
                📥 Download Updated Receipt
            </a>
        </p>
    </div>
    
    <p>Thank you for your patience and understanding.</p>
</body>
</html>
