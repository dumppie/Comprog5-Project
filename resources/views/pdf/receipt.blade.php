<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; color: #333; font-size: 12px; }
        h2 { color: #5C4033; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background: #FDF8F3; }
        .right { text-align: right; }
        .totals { margin-top: 10px; }
    </style>
</head>
<body>
    <h2>La Petite Pâtisserie — Receipt</h2>
    <p><strong>Transaction ID:</strong> {{ $order->transaction_id }}</p>
    <p><strong>Date:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
    <p><strong>Customer / Shipping</strong></p>
    <p>Name: {{ $order->shipping_name }}</p>
    <p>Address: {{ $order->shipping_address }}</p>
    <p>Contact: {{ $order->shipping_contact }}</p>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th class="right">Qty</th>
                <th class="right">Unit Price</th>
                <th class="right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td class="right">{{ $item->quantity }}</td>
                <td class="right">₱{{ number_format($item->unit_price, 2) }}</td>
                <td class="right">₱{{ number_format($item->line_total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="totals">
        <p>Subtotal: ₱{{ number_format($order->subtotal, 2) }}</p>
        <p>Tax: ₱{{ number_format($order->tax, 2) }}</p>
        <p><strong>Grand Total: ₱{{ number_format($order->total, 2) }}</strong></p>
    </div>
</body>
</html>
