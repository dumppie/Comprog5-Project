<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    private function ensureCustomer(): void
    {
        if (auth()->user()?->isAdmin()) {
            abort(403, 'Orders are for customers only.');
        }
    }

    /** FR5.6: Customer order history and current order status */
    public function index(): View
    {
        $this->ensureCustomer();
        $orders = auth()->user()
            ->orders()
            ->with(['orderStatus', 'paymentMethod'])
            ->orderByDesc('created_at')
            ->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order): View|\Illuminate\Http\RedirectResponse
    {
        $this->ensureCustomer();
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }
        $order->load(['orderItems.product', 'orderStatus', 'paymentMethod']);
        return view('orders.show', compact('order'));
    }
}
