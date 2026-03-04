<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Low Stock Alert</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }
        .content {
            background: white;
            padding: 30px;
            border: 1px solid #e9ecef;
            border-top: none;
        }
        .alert {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .alert-warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
        }
        .alert-danger {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        .product-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📦 Inventory Alert</h1>
    </div>
    
    <div class="content">
        @if($stockStatus === 'Out of Stock')
            <div class="alert alert-danger">
                <strong>⚠️ Out of Stock Alert!</strong> A product has run out of inventory.
            </div>
        @else
            <div class="alert alert-warning">
                <strong>⚠️ Low Stock Alert!</strong> A product is running low on inventory.
            </div>
        @endif
        
        <h2>Product Details</h2>
        
        <div class="product-info">
            <p><strong>Product:</strong> {{ $productName }}</p>
            <p><strong>Category:</strong> {{ $category }}</p>
            <p><strong>Price:</strong> {{ $price }}</p>
            <p><strong>Current Stock:</strong> {{ $remainingStock }} units</p>
            <p><strong>Status:</strong> {{ $stockStatus }}</p>
        </div>
        
        <p><strong>Action Required:</strong></p>
        <ul>
            @if($stockStatus === 'Out of Stock')
                <li>Restock the product as soon as possible</li>
                <li>Consider updating the product status to "inactive" if restocking will take time</li>
            @else
                <li>Reorder inventory soon to avoid stockouts</li>
                <li>Monitor sales trends for this product</li>
            @endif
        </ul>
        
        <a href="{{ route('admin.products.edit', $productId) }}" class="btn">
            Manage Product
        </a>
    </div>
    
    <div class="footer">
        <p>This is an automated message from the Shop Inventory Management System.</p>
        <p>If you believe this is an error, please contact your system administrator.</p>
    </div>
</body>
</html>
