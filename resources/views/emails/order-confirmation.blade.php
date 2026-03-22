<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; color: #333; line-height: 1.5; }
        .header { color: #5C4033; margin-bottom: 1.5rem; }
        table { width: 100%; border-collapse: collapse; margin: 1rem 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #FDF8F3; }
        .total { font-weight: bold; margin-top: 1rem; }
        .delivery { margin-top: 1rem; padding: 1rem; background: #f5f5f5; border-radius: 6px; }
    </style>
</head>
<body>
    <h2 class="header">La Petite Pâtisserie — Order Confirmation</h2>
    <p>Hello {{ $order->user->name }},</p>
    <p>Your order has been placed successfully.</p>
    <p><strong>Order / Transaction ID:</strong> {{ $order->transaction_id }}</p>
    <p><strong>Status:</strong> {{ $order->orderStatus->name }}</p>

    <h3>Items</h3>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>₱{{ number_format($item->unit_price, 2) }}</td>
                <td>₱{{ number_format($item->line_total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <p>Subtotal: ₱{{ number_format($order->subtotal, 2) }}</p>
    <p>Tax: ₱{{ number_format($order->tax, 2) }}</p>
    <p class="total">Grand Total: ₱{{ number_format($order->total, 2) }}</p>

    <div class="delivery">
        <h3>Delivery information</h3>
        <p><strong>Name:</strong> {{ $order->shipping_name }}</p>
        <p><strong>Address:</strong> {{ $order->shipping_address }}</p>
        <p><strong>Contact:</strong> {{ $order->shipping_contact }}</p>
        <p><strong>Payment:</strong> {{ $order->paymentMethod->name }}</p>
    </div>

    <div style="margin-top: 2rem; padding: 1rem; background: #e8f5e8; border-radius: 6px; border-left: 4px solid #28a745;">
        <h3 style="margin-top: 0; color: #155724;">📎 Your Receipt</h3>
        <p>Your order receipt has been attached as a PDF file for your records. You can also download it anytime from your order history.</p>
        <p><strong>File:</strong> receipt-{{ $order->transaction_id }}.pdf</p>
        <p style="margin: 0;">
            <a href="{{ route('orders.receipt', $order) }}" style="display: inline-block; padding: 8px 16px; background: #28a745; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">
                📥 Download Receipt
            </a>
        </p>
    </div>

    <p style="margin-top: 2rem;">Thank you for your order.</p>
</body>
</html>
