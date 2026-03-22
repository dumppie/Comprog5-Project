<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get sales data by month (works for both MySQL and SQLite)
        $driver = DB::getDriverName();
        $monthExpr = $driver === 'sqlite'
            ? "CAST(strftime('%m', orders.created_at) AS INTEGER)"
            : 'MONTH(orders.created_at)';

        $monthlySales = Order::join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->selectRaw("{$monthExpr} as month, SUM(order_items.unit_price * order_items.quantity) as total")
            ->whereYear('orders.created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Get top selling products
        $topProducts = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
            ->selectRaw('products.name, SUM(order_items.quantity) as total_sold')
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        // Get recent orders
        $recentOrders = Order::with(['user', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get low stock products
        $lowStockProducts = Product::where('stock_quantity', '<=', 5)
            ->where('stock_quantity', '>', 0)
            ->orderBy('stock_quantity', 'asc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'monthlySales',
            'topProducts', 
            'recentOrders',
            'lowStockProducts'
        ));
    }
}
