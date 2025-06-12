<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransactionController extends Controller
{
    // ... (your existing daily, weekly, monthly, quarterly methods can still exist for specific links) ...

    public function report(Request $request)
    {
        // 1. Determine the Date Range
        $period = $request->input('period', 'daily'); // Default to daily
        $startDate = null;
        $endDate = null;

        switch ($period) {
            case 'daily':
                $startDate = Carbon::today()->startOfDay();
                $endDate = Carbon::today()->endOfDay();
                break;
            case 'weekly':
                $startDate = Carbon::now()->startOfWeek(Carbon::MONDAY)->startOfDay(); // Adjust MONDAY if week starts on Sunday
                $endDate = Carbon::now()->endOfWeek(Carbon::SUNDAY)->endOfDay();
                break;
            case 'monthly':
                $startDate = Carbon::now()->startOfMonth()->startOfDay();
                $endDate = Carbon::now()->endOfMonth()->endOfDay();
                break;
            case 'quarterly':
                $startDate = Carbon::now()->startOfQuarter()->startOfDay();
                $endDate = Carbon::now()->endOfQuarter()->endOfDay();
                break;
            case 'custom':
                // Allow custom date range from input fields
                $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : null;
                $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : null;
                // If custom range is selected but dates are missing, default to daily or an error
                if (!$startDate || !$endDate) {
                    $period = 'daily'; // Fallback
                    $startDate = Carbon::today()->startOfDay();
                    $endDate = Carbon::today()->endOfDay();
                }
                break;
            default: // Fallback for invalid period
                $period = 'daily';
                $startDate = Carbon::today()->startOfDay();
                $endDate = Carbon::today()->endOfDay();
                break;
        }

        // 2. Fetch Aggregated Sales Data (Statistics)
        $salesQuery = Order::whereBetween('created_at', [$startDate, $endDate]);

        $totalSales = $salesQuery->sum('total');
        $numberOfOrders = $salesQuery->count();
        $averageOrderValue = $numberOfOrders > 0 ? $totalSales / $numberOfOrders : 0;

        // Sales by Payment Method
        $salesByPaymentMethod = Order::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('payment_method, SUM(total) as total_amount, COUNT(id) as total_count')
            ->groupBy('payment_method')
            ->get();

        // (More complex reports like Top Selling Items would go here,
        // requiring parsing the 'items' JSON from orders)

        // 3. Fetch Transaction History for the selected period
        $transactions = Order::with('user') // Eager load user relationship
                             ->whereBetween('created_at', [$startDate, $endDate])
                             ->orderBy('created_at', 'desc')
                             ->paginate(20); // Use pagination for history table

        return view('reports.index', compact(
            'transactions',
            'totalSales',
            'numberOfOrders',
            'averageOrderValue',
            'salesByPaymentMethod',
            'period',
            'startDate',
            'endDate'
        ));
    }

    // Existing show method for single order details
    public function show(Order $order)
    {
        
        return view('reports.transactions.show', compact('order'));
    }
}