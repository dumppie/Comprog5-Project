<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.reports.index');
    }

    public function yearlySales(Request $request): View
    {
        // FR11.1: Yearly sales data - SQLite compatible
        $yearlySales = Order::select(
                DB::raw("strftime('%Y', created_at) as year"),
                DB::raw('SUM(total) as total_sales'),
                DB::raw('COUNT(*) as order_count')
            )
            ->where('status', 'completed')
            ->groupBy(DB::raw("strftime('%Y', created_at)"))
            ->orderBy('year', 'desc')
            ->get();

        return view('admin.reports.yearly-sales', compact('yearlySales'));
    }

    public function monthlySales(Request $request): View
    {
        // FR11.2: Monthly sales data - SQLite compatible
        $year = $request->get('year', date('Y'));
        
        $monthlySales = Order::select(
                DB::raw("strftime('%m', created_at) as month"),
                DB::raw('SUM(total) as total_sales'),
                DB::raw('COUNT(*) as order_count')
            )
            ->where('status', 'completed')
            ->where(DB::raw("strftime('%Y', created_at)"), $year)
            ->groupBy(DB::raw("strftime('%m', created_at)"))
            ->orderBy('month')
            ->get();

        // Fill missing months with zero
        $allMonths = collect(range(1, 12))->map(function ($month) use ($monthlySales) {
            $data = $monthlySales->where('month', (string)$month)->first();
            return [
                'month' => $month,
                'month_name' => date('F', mktime(0, 0, 0, $month, 1)),
                'total_sales' => $data ? $data->total_sales : 0,
                'order_count' => $data ? $data->order_count : 0,
            ];
        });

        return view('admin.reports.monthly-sales', [
            'monthlySales' => $allMonths,
            'selectedYear' => $year,
            'availableYears' => Order::select(DB::raw("strftime('%Y', created_at) as year"))
                ->distinct()
                ->orderBy('year', 'desc')
                ->pluck('year')
        ]);
    }

    public function dateRangeSales(Request $request): View
    {
        // FR11.3: Sales with date range filter - SQLite compatible
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $query = Order::where('status', 'completed');

        if ($startDate) {
            $query->where(DB::raw("date(created_at)"), '>=', $startDate);
        }

        if ($endDate) {
            $query->where(DB::raw("date(created_at)"), '<=', $endDate);
        }

        $salesData = $query->select(
                DB::raw("date(created_at) as date"),
                DB::raw('SUM(total) as total_sales'),
                DB::raw('COUNT(*) as order_count')
            )
            ->groupBy(DB::raw("date(created_at)"))
            ->orderBy('date')
            ->get();

        return view('admin.reports.date-range-sales', [
            'salesData' => $salesData,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }

    public function productSales(Request $request): View
    {
        // FR11.4: Pie chart showing sales by product
        $productSales = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->select(
                'products.name',
                DB::raw('SUM(order_items.quantity * order_items.price) as total_sales'),
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('COUNT(DISTINCT order_items.order_id) as order_count')
            )
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sales', 'desc')
            ->get();

        $totalSales = $productSales->sum('total_sales');

        // Calculate percentage for each product
        $productSales = $productSales->map(function ($item) use ($totalSales) {
            $item->percentage = $totalSales > 0 ? ($item->total_sales / $totalSales) * 100 : 0;
            return $item;
        });

        return view('admin.reports.product-sales', compact('productSales', 'totalSales'));
    }

    public function dashboard(): View
    {
        // Overview dashboard with key metrics - SQLite compatible
        $todaySales = Order::where(DB::raw("date(created_at)"), DB::raw("date('now')"))
            ->where('status', 'completed')
            ->sum('total');

        $monthSales = Order::where(DB::raw("strftime('%Y-%m', created_at)"), DB::raw("strftime('%Y-%m', 'now')"))
            ->where('status', 'completed')
            ->sum('total');

        $yearSales = Order::where(DB::raw("strftime('%Y', created_at)"), DB::raw("strftime('%Y', 'now')"))
            ->where('status', 'completed')
            ->sum('total');

        $totalOrders = Order::where('status', 'completed')->count();
        $pendingOrders = Order::where('status', 'pending')->count();

        $topProducts = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        return view('admin.reports.dashboard', compact(
            'todaySales',
            'monthSales', 
            'yearSales',
            'totalOrders',
            'pendingOrders',
            'topProducts'
        ));
    }
}
