@extends('layouts.base')
@section('title', 'Order Management')
@section('body')
    @include('layouts.flash-messages')
    <div class="container py-5">
        <div class="card card-pastry">
            <div class="card-body p-4">
                <p class="text-uppercase mb-1" style="letter-spacing: 0.15em; color: var(--pastry-caramel); font-size: 0.85rem;">Admin</p>
                <h2 class="font-serif mb-4" style="color: var(--pastry-brown);">Order management</h2>

                @if($lowStockProducts->isNotEmpty() || $outOfStockProducts->isNotEmpty())
                    <div class="alert alert-warning mb-4" role="alert">
                        <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Inventory alert</h6>
                        @if($outOfStockProducts->isNotEmpty())
                            <p class="mb-1"><strong>Out of stock:</strong>
                                @foreach($outOfStockProducts as $p)
                                    <span class="badge bg-danger me-1">{{ $p->name }}</span>
                                @endforeach
                            </p>
                        @endif
                        @if($lowStockProducts->isNotEmpty())
                            <p class="mb-0"><strong>Low stock (≤{{ $threshold }}):</strong>
                                @foreach($lowStockProducts as $p)
                                    <span class="badge bg-warning text-dark me-1">{{ $p->name }} ({{ $p->stock_quantity }})</span>
                                @endforeach
                            </p>
                        @endif
                        <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-warning mt-2">Manage products</a>
                    </div>
                @endif

                <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-2 mb-4">
                    <div class="col-auto">
                        <input type="text" name="search" class="form-control" placeholder="Transaction ID, name, contact..." value="{{ request('search') }}">
                    </div>
                    <div class="col-auto">
                        <select name="status" class="form-select">
                            <option value="">All statuses</option>
                            @foreach($statuses as $s)
                                <option value="{{ $s->name }}" {{ request('status') == $s->name ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-pastry">Search</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr style="color: var(--pastry-brown);">
                                <th>Transaction ID</th>
                                <th>Customer</th>
                                <th>Shipping</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th class="text-end">Total</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td><strong>{{ $order->transaction_id }}</strong></td>
                                    <td>{{ $order->user->name ?? '-' }}<br><small class="text-muted">{{ $order->user->email ?? '' }}</small></td>
                                    <td><small>{{ $order->shipping_name }}<br>{{ Str::limit($order->shipping_address, 30) }}</small></td>
                                    <td>{{ $order->paymentMethod->name ?? '-' }}</td>
                                    <td>
                                        <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="d-inline">
                                            @csrf
                                            <select name="order_status_id" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                                                @foreach($statuses as $s)
                                                    <option value="{{ $s->id }}" {{ $order->order_status_id == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                                                @endforeach
                                            </select>
                                        </form>
                                    </td>
                                    <td class="text-end">₱{{ number_format($order->total, 2) }}</td>
                                    <td><small>{{ $order->created_at->format('M d, Y H:i') }}</small></td>
                                    <td></td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-muted text-center">No orders found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
