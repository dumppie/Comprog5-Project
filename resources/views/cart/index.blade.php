@extends('layouts.base')
@section('title', 'Shopping Cart')
@section('body')
    @include('layouts.flash-messages')
    <div class="container py-5">
        <div class="card card-pastry">
            <div class="card-body p-4">
                <p class="text-uppercase mb-1" style="letter-spacing: 0.15em; color: var(--pastry-caramel); font-size: 0.85rem;">Cart</p>
                <h2 class="font-serif mb-4" style="color: var(--pastry-brown);">Your cart</h2>

                @if($items->isEmpty())
                    <p class="text-muted mb-4">Your cart is empty.</p>
                    <a href="{{ route('shop.index') }}" class="btn btn-pastry">Continue shopping</a>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr style="color: var(--pastry-brown);">
                                    <th>Product</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-end">Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    <tr>
                                        <td>
                                            <strong>{{ $item->product->name }}</strong>
                                        </td>
                                        <td class="text-end">{{ $item->product->formatted_price ?? ('₱' . number_format($item->product->price, 2)) }}</td>
                                        <td class="text-center">
                                            <form action="{{ route('cart.update', $item) }}" method="POST" class="d-inline-flex align-items-center gap-1">
                                                @csrf
                                                @method('PUT')
                                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-control form-control-sm" style="width: 4rem;">
                                                <button type="submit" class="btn btn-sm btn-outline-pastry">Update</button>
                                            </form>
                                        </td>
                                        <td class="text-end"><strong>₱{{ number_format($item->line_total, 2) }}</strong></td>
                                        <td>
                                            <form action="{{ route('cart.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this item?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash-alt"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-4 pt-3 border-top">
                        <a href="{{ route('shop.index') }}" class="btn btn-outline-pastry">Continue shopping</a>
                        <div class="d-flex align-items-center gap-3">
                            <div class="fs-5" style="color: var(--pastry-brown);">
                                <strong>Total: ₱{{ number_format($totalAmount, 2) }}</strong>
                            </div>
                            <a href="{{ route('checkout.index') }}" class="btn btn-pastry">Proceed to checkout</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
