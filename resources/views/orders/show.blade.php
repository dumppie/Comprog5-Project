@extends('layouts.base')
@section('title', 'Order ' . $order->transaction_id)
@section('body')
    @include('layouts.flash-messages')
    <div class="container py-5">
        <div class="card card-pastry">
            <div class="card-body p-4">
                <p class="text-uppercase mb-1" style="letter-spacing: 0.15em; color: var(--pastry-caramel); font-size: 0.85rem;">Order</p>
                <h2 class="font-serif mb-4" style="color: var(--pastry-brown);">{{ $order->transaction_id }}</h2>
                <p><strong>Status:</strong> <span class="badge bg-secondary">{{ $order->orderStatus->name }}</span></p>
                <p><strong>Placed:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
                <p><strong>Payment:</strong> {{ $order->paymentMethod->name }}</p>

                <h5 class="mt-4" style="color: var(--pastry-brown);">Shipping</h5>
                <p class="mb-0">{{ $order->shipping_name }}</p>
                <p class="mb-0">{{ $order->shipping_address }}</p>
                <p class="mb-0">{{ $order->shipping_contact }}</p>

                <h5 class="mt-4" style="color: var(--pastry-brown);">Items</h5>
                <table class="table table-hover mt-2">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th class="text-end">Qty</th>
                            <th class="text-end">Unit price</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderItems as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td class="text-end">{{ $item->quantity }}</td>
                                <td class="text-end">₱{{ number_format($item->unit_price, 2) }}</td>
                                <td class="text-end">₱{{ number_format($item->line_total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <p class="mb-0">Subtotal: ₱{{ number_format($order->subtotal, 2) }}</p>
                <p class="mb-0">Tax: ₱{{ number_format($order->tax, 2) }}</p>
                <p class="fw-bold">Grand total: ₱{{ number_format($order->total, 2) }}</p>

                <div class="mt-4 d-flex gap-2">
                    <a href="{{ route('orders.receipt', $order) }}" class="btn btn-pastry">
                        <i class="fas fa-download me-1"></i> Download Receipt
                    </a>
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-pastry">Back to orders</a>
                </div>
            </div>
        </div>
    </div>
@endsection
