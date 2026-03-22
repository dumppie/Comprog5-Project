<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\Response;

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

    /** Download PDF receipt for the order */
    public function downloadReceipt(Order $order): Response
    {
        $this->ensureCustomer();
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // Try to find existing PDF
        $pdfPath = storage_path('app/public/receipts/receipt-' . $order->transaction_id . '.pdf');
        
        // If not found, try with timestamp pattern
        if (!file_exists($pdfPath)) {
            $files = glob(storage_path('app/public/receipts/receipt-' . $order->transaction_id . '-*.pdf'));
            if (!empty($files)) {
                $pdfPath = $files[0]; // Get the most recent one
            }
        }

        // If still not found, generate new one
        if (!file_exists($pdfPath)) {
            if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
                try {
                    $order->load(['orderItems.product', 'paymentMethod', 'orderStatus']);
                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.receipt', compact('order'));
                    $pdfPath = storage_path('app/public/receipts/receipt-' . $order->transaction_id . '-' . time() . '.pdf');
                    
                    // Ensure directory exists
                    $directory = dirname($pdfPath);
                    if (!is_dir($directory)) {
                        mkdir($directory, 0755, true);
                    }
                    
                    $pdf->save($pdfPath);
                } catch (\Throwable $e) {
                    \Log::warning('PDF generation failed for receipt download: ' . $e->getMessage());
                    abort(500, 'Unable to generate receipt PDF.');
                }
            } else {
                abort(500, 'PDF generation not available.');
            }
        }

        return response()->download($pdfPath, 'receipt-' . $order->transaction_id . '.pdf');
    }
}
