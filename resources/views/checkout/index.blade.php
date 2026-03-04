@extends('layouts.base')
@section('title', 'Checkout')
@section('body')
    @include('layouts.flash-messages')
    <div class="container py-5">
        <div class="card card-pastry">
            <div class="card-body p-4">
                <p class="text-uppercase mb-1" style="letter-spacing: 0.15em; color: var(--pastry-caramel); font-size: 0.85rem;">Checkout</p>
                <h2 class="font-serif mb-4" style="color: var(--pastry-brown);">Shipping &amp; payment</h2>

                <div class="row">
                    <div class="col-lg-8">
                        <form action="{{ route('checkout.store') }}" method="POST">
                            @csrf
                            <h5 class="mb-3" style="color: var(--pastry-brown);">Shipping details (FR5.2)</h5>
                            <div class="mb-3">
                                <label for="shipping_name" class="form-label">Name *</label>
                                <input type="text" id="shipping_name" name="shipping_name" class="form-control" value="{{ old('shipping_name', $user->full_name) }}" required>
                                @error('shipping_name') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="shipping_address" class="form-label">Address *</label>
                                <textarea id="shipping_address" name="shipping_address" class="form-control" rows="3" required>{{ old('shipping_address', $user->address) }}</textarea>
                                @error('shipping_address') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="shipping_contact" class="form-label">Contact number *</label>
                                <input type="text" id="shipping_contact" name="shipping_contact" class="form-control" value="{{ old('shipping_contact', $user->contact_number) }}" required>
                                @error('shipping_contact') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <h5 class="mb-3 mt-4" style="color: var(--pastry-brown);">Payment method (FR5.3)</h5>
                            <div class="mb-4">
                                @foreach($paymentMethods as $pm)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method_id" id="pm{{ $pm->id }}" value="{{ $pm->id }}" {{ (old('payment_method_id') ?: $paymentMethods->first()?->id) == $pm->id ? 'checked' : '' }}>
                                        <label class="form-check-label" for="pm{{ $pm->id }}">{{ $pm->name }}</label>
                                    </div>
                                @endforeach
                                @error('payment_method_id') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <button type="submit" class="btn btn-pastry btn-lg">Place order</button>
                            <a href="{{ route('cart.index') }}" class="btn btn-outline-pastry btn-lg ms-2">Back to cart</a>
                        </form>
                    </div>
                    <div class="col-lg-4 mt-4 mt-lg-0">
                        <div class="border rounded p-3" style="background: var(--pastry-cream);">
                            <h5 style="color: var(--pastry-brown);">Order summary</h5>
                            <table class="table table-sm mb-0">
                                @foreach($items as $item)
                                    <tr>
                                        <td>{{ $item->product->name }} × {{ $item->quantity }}</td>
                                        <td class="text-end">₱{{ number_format($item->line_total, 2) }}</td>
                                    </tr>
                                @endforeach
                            </table>
                            <hr>
                            <p class="mb-1">Subtotal: ₱{{ number_format($totalAmount, 2) }}</p>
                            <p class="mb-1">Tax: ₱{{ number_format($tax, 2) }}</p>
                            <p class="fw-bold mb-0">Total: ₱{{ number_format($grandTotal, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
