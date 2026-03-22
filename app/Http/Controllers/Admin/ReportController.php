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
        // FR11.1: Yearly sales data - Database agnostic
        $driver = DB::getDriverName();
        
        if ($driver === 'sqlite') {
            $yearlySales = Order::select(
                    DB::raw("strftime('%Y', created_at) as year"),
                    DB::raw('SUM(total) as total_sales'),
                    DB::raw('COUNT(*) as order_count')
                )
                ->whereHas('orderStatus', function($query) {
                    $query->where('name', 'completed');
                })
                ->groupBy(DB::raw("strftime('%Y', created_at)"))
                ->orderBy('year', 'desc')
                ->get();
        } else {
            // MySQL/MariaDB
            $yearlySales = Order::select(
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('SUM(total) as total_sales'),
                    DB::raw('COUNT(*) as order_count')
                )
                ->whereHas('orderStatus', function($query) {
                    $query->where('name', 'completed');
                })
                ->groupBy(DB::raw('YEAR(created_at)'))
                ->orderBy('year', 'desc')
                ->get();
        }

        return view('admin.reports.yearly-sales', compact('yearlySales'));
    }

    public function monthlySales(Request $request): View
    {
        // FR11.2: Monthly sales data - Database agnostic
        $year = $request->get('year', date('Y'));
        $driver = DB::getDriverName();
        
        if ($driver === 'sqlite') {
            $monthlySales = Order::select(
                    DB::raw("strftime('%m', created_at) as month"),
                    DB::raw('SUM(total) as total_sales'),
                    DB::raw('COUNT(*) as order_count')
                )
                ->whereHas('orderStatus', function($query) {
                    $query->where('name', 'completed');
                })
                ->where(DB::raw("strftime('%Y', created_at)"), $year)
                ->groupBy(DB::raw("strftime('%m', created_at)"))
                ->orderBy('month')
                ->get();
        } else {
            // MySQL/MariaDB
            $monthlySales = Order::select(
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('SUM(total) as total_sales'),
                    DB::raw('COUNT(*) as order_count')
                )
                ->whereHas('orderStatus', function($query) {
                    $query->where('name', 'completed');
                })
                ->whereYear('created_at', $year)
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->orderBy('month')
                ->get();
        }

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
            'availableYears' => $driver === 'sqlite' 
                ? Order::select(DB::raw("strftime('%Y', created_at) as year"))
                    ->distinct()
                    ->orderBy('year', 'desc')
                    ->pluck('year')
                : Order::select(DB::raw('YEAR(created_at) as year'))
                    ->distinct()
                    ->orderBy('year', 'desc')
                    ->pluck('year')
        ]);
    }

    public function dateRangeSales(Request $request): View
    {
        // FR11.3: Sales with date range filter - Database agnostic
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $driver = DB::getDriverName();

        $query = Order::whereHas('orderStatus', function($query) {
            $query->where('name', 'completed');
        });

        if ($startDate) {
            if ($driver === 'sqlite') {
                $query->where(DB::raw("date(created_at)"), '>=', $startDate);
            } else {
                $query->whereDate('created_at', '>=', $startDate);
            }
        }

        if ($endDate) {
            if ($driver === 'sqlite') {
                $query->where(DB::raw("date(created_at)"), '<=', $endDate);
            } else {
                $query->whereDate('created_at', '<=', $endDate);
            }
        }

        if ($driver === 'sqlite') {
            $salesData = $query->select(
                    DB::raw("date(created_at) as date"),
                    DB::raw('SUM(total) as total_sales'),
                    DB::raw('COUNT(*) as order_count')
                )
                ->groupBy(DB::raw("date(created_at)"))
                ->orderBy('date')
                ->get();
        } else {
            $salesData = $query->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(total) as total_sales'),
                    DB::raw('COUNT(*) as order_count')
                )
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy('date')
                ->get();
        }

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
            ->join('order_statuses', 'orders.order_status_id', '=', 'order_statuses.id')
            ->where('order_statuses.name', 'completed')
            ->select(
                'products.name',
                DB::raw('SUM(order_items.quantity * order_items.unit_price) as total_sales'),
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
        // Overview dashboard with key metrics - Database agnostic
        $driver = DB::getDriverName();
        
        if ($driver === 'sqlite') {
            $todaySales = Order::where(DB::raw("date(created_at)"), DB::raw("date('now')"))
                ->whereHas('orderStatus', function($query) {
                    $query->where('name', 'completed');
                })
                ->sum('total');

            $monthSales = Order::where(DB::raw("strftime('%Y-%m', created_at)"), DB::raw("strftime('%Y-%m', 'now')"))
                ->whereHas('orderStatus', function($query) {
                    $query->where('name', 'completed');
                })
                ->sum('total');

            $yearSales = Order::where(DB::raw("strftime('%Y', created_at)"), DB::raw("strftime('%Y', 'now')"))
                ->whereHas('orderStatus', function($query) {
                    $query->where('name', 'completed');
                })
                ->sum('total');
        } else {
            // MySQL/MariaDB
            $todaySales = Order::whereDate('created_at', now()->toDateString())
                ->whereHas('orderStatus', function($query) {
                    $query->where('name', 'completed');
                })
                ->sum('total');

            $monthSales = Order::whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->whereHas('orderStatus', function($query) {
                    $query->where('name', 'completed');
                })
                ->sum('total');

            $yearSales = Order::whereYear('created_at', now()->year)
                ->whereHas('orderStatus', function($query) {
                    $query->where('name', 'completed');
                })
                ->sum('total');
        }

        $totalOrders = Order::whereHas('orderStatus', function($query) {
                $query->where('name', 'completed');
            })->count();
        $pendingOrders = Order::whereHas('orderStatus', function($query) {
                $query->where('name', 'pending');
            })->count();

        $topProducts = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('order_statuses', 'orders.order_status_id', '=', 'order_statuses.id')
            ->where('order_statuses.name', 'completed')
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
