<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderStatusUpdatedMail;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class OrderController extends Controller
{
    /** FR5.7: Admin order management datatable. FR6.2: Low/out of stock notice */
    public function index(Request $request): View
    {
        $query = Order::with(['user', 'orderStatus', 'paymentMethod'])->orderByDesc('created_at');
        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($qry) use ($q) {
                $qry->where('transaction_id', 'like', "%{$q}%")
                    ->orWhere('shipping_name', 'like', "%{$q}%")
                    ->orWhere('shipping_contact', 'like', "%{$q}%");
            });
        }
        if ($request->filled('status')) {
            $query->whereHas('orderStatus', fn ($q) => $q->where('name', $request->status));
        }
        $orders = $query->paginate(15)->withQueryString();
        $statuses = OrderStatus::orderBy('name')->get();
        $threshold = (int) config('shop.low_stock_threshold', 5);
        $lowStockProducts = Product::withoutTrashed()
            ->where('stock_quantity', '>', 0)
            ->where('stock_quantity', '<=', $threshold)
            ->orderBy('stock_quantity')
            ->get();
        $outOfStockProducts = Product::withoutTrashed()->where('stock_quantity', '<=', 0)->orderBy('name')->get();
        return view('admin.orders.index', compact('orders', 'statuses', 'lowStockProducts', 'outOfStockProducts', 'threshold'));
    }

    /** FR5.7: Update order status. FR5.8: Email customer with PDF receipt */
    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $request->validate(['order_status_id' => 'required|exists:order_statuses,id']);
        $previousStatus = $order->orderStatus->name;
        $order->update(['order_status_id' => $request->order_status_id]);
        $order->load(['orderItems.product', 'paymentMethod', 'orderStatus', 'user']);

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
                \Log::warning('Order status update PDF generation failed: ' . $e->getMessage());
            }
        }
        
        Mail::to($order->user->email)->send(new OrderStatusUpdatedMail($order, $previousStatus, $pdfPath));

        return back()->with('success', 'Order status updated and customer notified.');
    }
}
