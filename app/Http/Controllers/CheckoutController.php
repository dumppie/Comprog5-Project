<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlaceOrderRequest;
use App\Mail\OrderConfirmationMail;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\PaymentMethod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    private function ensureCustomer(): void
    {
        if (auth()->user()?->isAdmin()) {
            abort(403, 'Checkout is for customers only.');
        }
    }

    /** FR5.1: Checkout from cart. FR5.2, FR5.3: Shipping details + payment method form */
    public function index(): View|RedirectResponse
    {
        $this->ensureCustomer();
        $items = auth()->user()->cartItems()->with('product')->orderBy('created_at')->get();
        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }
        $totalAmount = $items->sum(fn (CartItem $item) => $item->line_total);
        $taxRate = (float) config('shop.tax_rate', 0);
        $tax = $totalAmount * $taxRate;
        $grandTotal = $totalAmount + $tax;
        $paymentMethods = PaymentMethod::orderBy('name')->get();
        $user = auth()->user();
        return view('checkout.index', compact('items', 'totalAmount', 'tax', 'grandTotal', 'paymentMethods', 'user'));
    }

    /** FR5.4: Unique transaction ID, store order. FR5.5: Email confirmation. FR6.1: Decrease stock */
    public function store(PlaceOrderRequest $request): RedirectResponse
    {
        $this->ensureCustomer();
        $user = auth()->user();
        $items = $user->cartItems()->with('product')->get();
        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        foreach ($items as $item) {
            $product = $item->product;
            if ($product->stock_quantity < $item->quantity) {
                return redirect()->route('checkout.index')->with('error', "Insufficient stock for {$product->name}. Available: {$product->stock_quantity}.");
            }
        }

        $taxRate = (float) config('shop.tax_rate', 0);
        $subtotal = $items->sum(fn (CartItem $item) => $item->line_total);
        $tax = $subtotal * $taxRate;
        $total = $subtotal + $tax;
        $pendingStatus = OrderStatus::where('name', 'Pending')->firstOrFail();
        $transactionId = 'TXN-' . strtoupper(Str::random(8)) . '-' . time();

        DB::beginTransaction();
        try {
            $order = Order::create([
                'transaction_id' => $transactionId,
                'user_id' => $user->id,
                'shipping_name' => $request->shipping_name,
                'shipping_address' => $request->shipping_address,
                'shipping_contact' => $request->shipping_contact,
                'payment_method_id' => $request->payment_method_id,
                'order_status_id' => $pendingStatus->id,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
            ]);

            foreach ($items as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->product->price,
                ]);
                $cartItem->product->decrement('stock_quantity', $cartItem->quantity);
            }

            $user->cartItems()->delete();
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->route('checkout.index')->with('error', 'Order failed. Please try again.');
        }

        $order->load(['orderItems.product', 'paymentMethod', 'orderStatus']);

        // Generate PDF receipt
        $pdfPath = null;
        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            try {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.receipt', compact('order'));
                $pdfPath = storage_path('app/public/receipts/receipt-' . $order->transaction_id . '-' . time() . '.pdf');
                
                // Ensure directory exists
                $directory = dirname($pdfPath);
                if (!is_dir($directory)) {
                    mkdir($directory, 0755, true);
                }
                
                $pdf->save($pdfPath);
            } catch (\Throwable $e) {
                \Log::warning('Order confirmation PDF generation failed: ' . $e->getMessage());
            }
        }

        \Illuminate\Support\Facades\Mail::to($user->email)->send(new OrderConfirmationMail($order, $pdfPath));

        return redirect()->route('orders.show', $order)->with('success', 'Order placed successfully. Confirmation email sent.');
    }
}
