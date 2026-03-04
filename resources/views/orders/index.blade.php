@extends('layouts.base')
@section('title', 'My Orders')
@section('body')
    @include('layouts.flash-messages')
    <div class="container py-5">
        <div class="card card-pastry">
            <div class="card-body p-4">
                <p class="text-uppercase mb-1" style="letter-spacing: 0.15em; color: var(--pastry-caramel); font-size: 0.85rem;">Orders</p>
                <h2 class="font-serif mb-4" style="color: var(--pastry-brown);">Order History</h2>

                @if($orders->isEmpty())
                    <p class="text-muted">You have no orders yet.</p>
                    <a href="{{ route('shop.index') }}" class="btn btn-pastry">Browse shop</a>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr style="color: var(--pastry-brown);">
                                    <th>Transaction ID</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th class="text-end">Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td><strong>{{ $order->transaction_id }}</strong></td>
                                        <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                                        <td><span class="badge bg-secondary">{{ $order->orderStatus->name }}</span></td>
                                        <td class="text-end">₱{{ number_format($order->total, 2) }}</td>
                                        <td><a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-pastry">View</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
