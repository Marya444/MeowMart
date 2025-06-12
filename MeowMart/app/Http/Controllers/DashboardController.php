<?php

namespace App\Http\Controllers;

use App\Models\Order; // Assuming Order model exists for transactions
use App\Models\Product; // Make sure to import your Product model
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function index()
    {
        // Sales Data
        $todaySales = Order::whereDate('created_at', Carbon::today())->sum('total');
        $todayOrders = Order::whereDate('created_at', Carbon::today())->count();
        $avgOrderValue = $todayOrders > 0 ? $todaySales / $todayOrders : 0;
        $monthlySales = Order::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total');

        // Recent Transactions
        $recentTransactions = Order::orderBy('created_at', 'desc')->take(5)->get();

        // Daily Sales Data for Chart
        $dailySalesData = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total) as total')
        )
            ->where('created_at', '>=', Carbon::now()->subDays(6)) // Last 7 days including today
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Payment Methods Data for Chart
        $paymentMethodsData = Order::select(
            'payment_method',
            DB::raw('SUM(total) as amount')
        )
            ->groupBy('payment_method')
            ->get();

        // Hourly Sales Data for Chart (Today)
        $hourlySalesData = Order::select(
            DB::raw('DATE_FORMAT(created_at, "%H:00") as hour'),
            DB::raw('SUM(total) as sales')
        )
            ->whereDate('created_at', Carbon::today())
            ->groupBy('hour')
            ->orderBy('hour', 'asc')
            ->get();

        // Monthly Comparison Data (Last 6 months)
        $monthlyComparisonData = Order::select(
            DB::raw('DATE_FORMAT(created_at, "%b") as month'),
            DB::raw('SUM(total) as sales')
        )
            ->where('created_at', '>=', Carbon::now()->subMonths(5)->startOfMonth()) // Last 6 months including current
            ->groupBy('month')
            ->orderBy(DB::raw('MIN(created_at)'), 'asc') // Order by actual date to ensure correct month sequence
            ->get();

        // Fetch low stock products for the alert
        $lowStockProducts = Product::whereColumn('stock', '<=', 'low_stock_threshold')
            ->orderBy('stock', 'asc')
            ->get();

        // **NEW: Calculate Stock Statistics for Charts**
        $stockStats = [
            'in_stock' => Product::where('stock', '>', DB::raw('low_stock_threshold'))->count(),
            'low_stock' => Product::whereColumn('stock', '<=', 'low_stock_threshold')
                ->where('stock', '>', 0)
                ->count(),
            'out_of_stock' => Product::where('stock', '=', 0)->count(),
            'total' => Product::count()
        ];

        return view('dashboard', compact(
            'todaySales',
            'todayOrders',
            'avgOrderValue',
            'monthlySales',
            'recentTransactions',
            'dailySalesData',
            'paymentMethodsData',
            'hourlySalesData',
            'monthlyComparisonData',
            'lowStockProducts',
            'stockStats' // **NEW: Pass stock statistics to the view**
        ));
    }

    public function cashierDashboard()
    {
        // Logic for Cashier Dashboard
        return view('cashier-dashboard');
    }

    /**
     * Fetch low stock products (for AJAX requests).
     * This will be called periodically by the frontend.
     */
    public function getLowStockProducts()
    {
        $lowStockProducts = Product::whereColumn('stock', '<=', 'low_stock_threshold')
                                    ->orderBy('stock', 'asc')
                                    ->get(['id', 'name', 'stock', 'low_stock_threshold']); // Select only necessary fields
        return response()->json($lowStockProducts);
    }

    /**
     * Refresh stock data (for AJAX requests).
     * This method is called by the JavaScript refresh button.
     */
    public function refreshStock()
    {
        // Calculate current stock statistics
        $stockStats = [
            'in_stock' => Product::where('stock', '>', DB::raw('low_stock_threshold'))->count(),
            'low_stock' => Product::whereColumn('stock', '<=', 'low_stock_threshold')
                ->where('stock', '>', 0)
                ->count(),
            'out_of_stock' => Product::where('stock', '=', 0)->count(),
            'total' => Product::count()
        ];

        // Get new low stock items (items that just went below threshold)
        $newLowStockItems = Product::whereColumn('stock', '<=', 'low_stock_threshold')
            ->where('stock', '>', 0)
            ->orderBy('stock', 'asc')
            ->get(['id', 'name', 'stock', 'low_stock_threshold']);

        return response()->json([
            'stockStats' => $stockStats,
            'newLowStockItems' => $newLowStockItems,
            'timestamp' => now()->format('h:i A')
        ]);
    }
}
