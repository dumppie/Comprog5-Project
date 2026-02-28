<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    /** Block admin from using cart (they manage products, not shop). */
    private function ensureCustomer(): void
    {
        if (auth()->user()?->isAdmin()) {
            abort(403, 'Cart is for customers only. Admins manage products from the dashboard.');
        }
    }

    /** FR4.2: Display all cart items (name, price, quantity, total); FR4.4: total recalculated from items */
    public function index(): View
    {
        $this->ensureCustomer();
        $items = auth()->user()
            ->cartItems()
            ->with('product')
            ->orderBy('created_at')
            ->get();

        $totalAmount = $items->sum(fn (CartItem $item) => $item->line_total);

        return view('cart.index', compact('items', 'totalAmount'));
    }

    /** FR4.1: Add product to cart (authenticated customers) */
    public function store(AddToCartRequest $request): RedirectResponse
    {
        $this->ensureCustomer();
        $product = Product::findOrFail($request->product_id);
        $cartItem = auth()->user()->cartItems()->where('product_id', $product->id)->first();

        if ($cartItem) {
            $cartItem->update(['quantity' => $cartItem->quantity + $request->quantity]);
        } else {
            auth()->user()->cartItems()->create([
                'product_id' => $product->id,
                'quantity'   => $request->quantity,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Added to cart.');
    }

    /** FR4.3 & FR4.4: Update quantity; total recalculates on next view */
    public function update(UpdateCartRequest $request, CartItem $cart_item): RedirectResponse
    {
        $this->ensureCustomer();
        $cart_item->update(['quantity' => $request->quantity]);
        return redirect()->route('cart.index')->with('success', 'Cart updated.');
    }

    /** FR4.3: Remove item from cart */
    public function destroy(CartItem $cart_item): RedirectResponse
    {
        $this->ensureCustomer();
        if ($cart_item->user_id !== auth()->id()) {
            abort(403);
        }
        $cart_item->delete();
        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }
}
