@extends('layouts.app')
@section('content')
<div class="max-w-7xl mx-auto mt-8 p-6 bg-white shadow rounded">
    <h1 class="text-3xl font-bold mb-8 text-gray-800">Sales Dashboard</h1>

    {{-- Stock Alerts Section --}}
    @if(isset($lowStockProducts) && $lowStockProducts->count() > 0)
    <div class="mb-8">
        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg shadow-md">
            <div class="flex items-center mb-3">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-red-800">Low Stock Alert</h3>
                    <p class="text-sm text-red-700">{{ $lowStockProducts->count() }} product(s) are running low on stock</p>
                </div>
                <div class="ml-auto">
                    <button onclick="toggleStockAlert()" class="text-red-600 hover:text-red-800">
                        <span id="alert-toggle-text">Show Details</span>
                        <svg id="alert-toggle-icon" class="inline h-4 w-4 ml-1 transform transition-transform" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div id="stock-alert-details" class="hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                    @foreach($lowStockProducts as $product)
                    <div class="bg-white p-4 rounded-lg border border-red-200">
                        <div class="flex items-center space-x-3">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-12 w-12 object-cover rounded">
                            @else
                                <div class="h-12 w-12 bg-gray-200 rounded flex items-center justify-center">
                                    <svg class="h-6 w-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $product->name }}</p>
                                <p class="text-xs text-gray-500">SKU: {{ $product->sku }}</p>
                                <div class="flex items-center mt-1">
                                    <span class="text-sm font-semibold text-red-600">{{ $product->stock }} left</span>
                                    <span class="text-xs text-gray-500 ml-2">(Threshold: {{ $product->low_stock_threshold }})</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('admin.products.edit', $product) }}" class="text-xs bg-blue-100 text-blue-800 hover:bg-blue-200 px-2 py-1 rounded transition-colors">
                                Update Stock
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 rounded-lg shadow-lg text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Today's Sales</p>
                    <p class="text-3xl font-bold">₱{{ number_format($todaySales ?? 0, 2) }}</p>
                </div>
                <div class="text-blue-200">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 rounded-lg shadow-lg text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Today's Orders</p>
                    <p class="text-3xl font-bold">{{ $todayOrders ?? 0 }}</p>
                </div>
                <div class="text-green-200">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2L3 7v11a2 2 0 002 2h10a2 2 0 002-2V7l-7-5zM8 15a1 1 0 01-1-1v-2a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1H8z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-6 rounded-lg shadow-lg text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Avg Order Value</p>
                    <p class="text-3xl font-bold">₱{{ number_format($avgOrderValue ?? 0, 2) }}</p>
                </div>
                <div class="text-purple-200">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-6 rounded-lg shadow-lg text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Monthly Sales</p>
                    <p class="text-3xl font-bold">₱{{ number_format($monthlySales ?? 0, 2) }}</p>
                </div>
                <div class="text-orange-200">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Stock Monitoring Dashboard --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        {{-- Stock Status Overview --}}
        <div class="bg-white p-6 rounded-lg shadow-lg border">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Stock Status Overview DIRI</h2>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <span class="text-sm font-medium text-gray-700">In Stock</span>
                    </div>
                    <span class="text-lg font-bold text-green-600" id="in-stock-count">{{ $stockStats['in_stock'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                        <span class="text-sm font-medium text-gray-700">Low Stock</span>
                    </div>
                    <span class="text-lg font-bold text-yellow-600" id="low-stock-count">{{ $stockStats['low_stock'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                        <span class="text-sm font-medium text-gray-700">Out of Stock</span>
                    </div>
                    <span class="text-lg font-bold text-red-600" id="out-of-stock-count">{{ $stockStats['out_of_stock'] ?? 0 }}</span>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Total Products</span>
                    <span class="text-lg font-semibold text-gray-800" id="total-products">{{ $stockStats['total'] ?? 0 }}</span>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.products.index') }}" class="w-full bg-blue-600 text-white text-center py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors inline-block">
                    Manage Inventory
                </a>
            </div>
        </div>

        {{-- Stock Level Chart --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-lg border">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Stock Levels Distribution DIRA</h2>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500">Last updated:</span>
                    <span class="text-sm font-medium text-gray-700" id="last-updated">{{ now()->format('h:i A') }}</span>
                    <button onclick="refreshStockData()" class="text-blue-600 hover:text-blue-800 ml-2">
                        <svg id="refresh-icon" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="relative h-64">
                <canvas id="stockLevelsChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        {{-- Daily Sales Trend Chart --}}
        <div class="bg-white p-6 rounded-lg shadow-lg border">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Daily Sales Trend (Last 7 Days)</h2>
            <div class="relative h-64">
                <canvas id="dailySalesChart"></canvas>
            </div>
        </div>
        {{-- Payment Methods Chart --}}
        <div class="bg-white p-6 rounded-lg shadow-lg border">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Payment Methods Distribution</h2>
            <div class="relative h-64">
                <canvas id="paymentMethodsChart"></canvas>
            </div>
        </div>
    </div>
    {{-- Additional Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        {{-- Hourly Sales Chart --}}
        <div class="bg-white p-6 rounded-lg shadow-lg border">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Today's Hourly Sales</h2>
            <div class="relative h-64">
                <canvas id="hourlySalesChart"></canvas>
            </div>
        </div>
        {{-- Monthly Comparison Chart --}}
        <div class="bg-white p-6 rounded-lg shadow-lg border">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Monthly Sales Comparison</h2>
            <div class="relative h-64">
                <canvas id="monthlyComparisonChart"></canvas>
            </div>
        </div>
    </div>
    {{-- Recent Transactions Summary --}}
    <div class="bg-white p-6 rounded-lg shadow-lg border">
        <h2 class="text-xl font-semibold mb-4 text-gray-800">Recent Transactions</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentTransactions ?? [] as $transaction)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $transaction->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction->created_at->format('h:i A') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">₱{{ number_format($transaction->total, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucfirst($transaction->payment_method) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Completed
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">No recent transactions</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Chart.js CDN --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sample data - Replace with actual data from your controller
            const dailySalesData = {!! json_encode($dailySalesData ?? [
                ['date' => '2024-06-04', 'total' => 5200],
                ['date' => '2024-06-05', 'total' => 6800],
                ['date' => '2024-06-06', 'total' => 4500],
                ['date' => '2024-06-07', 'total' => 7200],
                ['date' => '2024-06-08', 'total' => 5900],
                ['date' => '2024-06-09', 'total' => 8100],
                ['date' => '2024-06-10', 'total' => 6300]
            ]) !!};
            const paymentMethodsData = {!! json_encode($paymentMethodsData ?? [
                ['method' => 'Cash', 'amount' => 15000],
                ['method' => 'Card', 'amount' => 25000],
                ['method' => 'GCash', 'amount' => 8000],
                ['method' => 'Maya', 'amount' => 5000]
            ]) !!};
            const hourlySalesData = {!! json_encode($hourlySalesData ?? [
                ['hour' => '8:00', 'sales' => 300],
                ['hour' => '9:00', 'sales' => 450],
                ['hour' => '10:00', 'sales' => 600],
                ['hour' => '11:00', 'sales' => 800],
                ['hour' => '12:00', 'sales' => 1200],
                ['hour' => '13:00', 'sales' => 950],
                ['hour' => '14:00', 'sales' => 750],
                ['hour' => '15:00', 'sales' => 850],
                ['hour' => '16:00', 'sales' => 900],
                ['hour' => '17:00', 'sales' => 700]
            ]) !!};
            const monthlyComparisonData = {!! json_encode($monthlyComparisonData ?? [
                ['month' => 'Jan', 'sales' => 85000],
                ['month' => 'Feb', 'sales' => 78000],
                ['month' => 'Mar', 'sales' => 92000],
                ['month' => 'Apr', 'sales' => 88000],
                ['month' => 'May', 'sales' => 95000],
                ['month' => 'Jun', 'sales' => 102000]
            ]) !!};

            const stockLevelsData = {!! json_encode($stockStats) !!};
       
            // Chart configuration options
            const commonOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            };

            // Stock Levels Chart
            const stockLevelsCtx = document.getElementById('stockLevelsChart').getContext('2d');
            new Chart(stockLevelsCtx, {
                type: 'doughnut',
                data: {
                    labels: ['In Stock', 'Low Stock', 'Out of Stock'],
                    datasets: [{
                        data: [stockLevelsData.in_stock, stockLevelsData.low_stock, stockLevelsData.out_of_stock],
                        backgroundColor: [
                            'rgba(34, 197, 94, 0.8)',   // Green
                            'rgba(245, 158, 11, 0.8)',  // Yellow
                            'rgba(239, 68, 68, 0.8)'    // Red
                        ],
                        borderColor: [
                            'rgb(34, 197, 94)',
                            'rgb(245, 158, 11)',
                            'rgb(239, 68, 68)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    ...commonOptions,
                    plugins: {
                        ...commonOptions.plugins,
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return `${label}: ${value} products (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });

            // 1. Daily Sales Trend Chart
            const dailySalesCtx = document.getElementById('dailySalesChart').getContext('2d');
            new Chart(dailySalesCtx, {
                type: 'line',
                data: {
                    labels: dailySalesData.map(item => {
                        const date = new Date(item.date);
                        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                    }),
                    datasets: [{
                        label: 'Daily Sales (₱)',
                        data: dailySalesData.map(item => item.total),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: 'rgb(59, 130, 246)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5
                    }]
                },
                options: {
                    ...commonOptions,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '₱' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });

            // 2. Payment Methods Pie Chart
            const paymentMethodsCtx = document.getElementById('paymentMethodsChart').getContext('2d');
            new Chart(paymentMethodsCtx, {
                type: 'doughnut',
                data: {
                    labels: paymentMethodsData.map(item => item.method),
                    datasets: [{
                        data: paymentMethodsData.map(item => item.amount),
                        backgroundColor: [
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(168, 85, 247, 0.8)',
                            'rgba(249, 115, 22, 0.8)'
                        ],
                        borderColor: [
                            'rgb(34, 197, 94)',
                            'rgb(59, 130, 246)',
                            'rgb(168, 85, 247)',
                            'rgb(249, 115, 22)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    ...commonOptions,
                    plugins: {
                        ...commonOptions.plugins,
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = '₱' + context.parsed.toLocaleString();
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((context.parsed / total) * 100).toFixed(1);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });

            // 3. Hourly Sales Bar Chart
            const hourlySalesCtx = document.getElementById('hourlySalesChart').getContext('2d');
            new Chart(hourlySalesCtx, {
                type: 'bar',
                data: {
                    labels: hourlySalesData.map(item => item.hour),
                    datasets: [{
                        label: 'Sales (₱)',
                        data: hourlySalesData.map(item => item.sales),
                        backgroundColor: 'rgba(168, 85, 247, 0.8)',
                        borderColor: 'rgb(168, 85, 247)',
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    ...commonOptions,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '₱' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });

            // 4. Monthly Comparison Chart
            const monthlyComparisonCtx = document.getElementById('monthlyComparisonChart').getContext('2d');
            new Chart(monthlyComparisonCtx, {
                type: 'bar',
                data: {
                    labels: monthlyComparisonData.map(item => item.month),
                    datasets: [{
                        label: 'Monthly Sales (₱)',
                        data: monthlyComparisonData.map(item => item.sales),
                        backgroundColor: 'rgba(249, 115, 22, 0.8)',
                        borderColor: 'rgb(249, 115, 22)',
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    ...commonOptions,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '₱' + (value / 1000) + 'k';
                                }
                            }
                        }
                    }
                }
            });

            // Auto-refresh stock data every 5 minutes
            setInterval(refreshStockData, 300000);
        });

        // Toggle stock alert details
        function toggleStockAlert() {
            const details = document.getElementById('stock-alert-details');
            const toggleText = document.getElementById('alert-toggle-text');
            const toggleIcon = document.getElementById('alert-toggle-icon');

            if (details.classList.contains('hidden')) {
                details.classList.remove('hidden');
                toggleText.textContent = 'Hide Details';
                toggleIcon.classList.add('rotate-180');
            } else {
                details.classList.add('hidden');
                toggleText.textContent = 'Show Details';
                toggleIcon.classList.remove('rotate-180');
            }
        }

        // Refresh stock data function
        function refreshStockData() {
            const refreshIcon = document.getElementById('refresh-icon');
            const lastUpdated = document.getElementById('last-updated');

            // Add spinning animation
            refreshIcon.classList.add('animate-spin');

            // Simulate API call - replace with actual AJAX call
            fetch('/dashboard/refresh-stock', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                // Update stock counts
                document.getElementById('in-stock-count').textContent = data.stockStats.in_stock;
                document.getElementById('low-stock-count').textContent = data.stockStats.low_stock;
                document.getElementById('out-of-stock-count').textContent = data.stockStats.out_of_stock;
                document.getElementById('total-products').textContent = data.stockStats.total;

                // Update last updated time
                lastUpdated.textContent = new Date().toLocaleTimeString('en-US', {
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                });

                // Show notification if there are new low stock items
                if (data.newLowStockItems && data.newLowStockItems.length > 0) {
                    showStockAlert(data.newLowStockItems);
                }
            })
            .catch(error => {
                console.error('Error refreshing stock data:', error);
                showNotification('Error refreshing stock data', 'error');
            })
            .finally(() => {
                // Remove spinning animation
                refreshIcon.classList.remove('animate-spin');
            });
        }

        // Show stock alert notification
        function showStockAlert(items) {
            const alertHtml = `
                <div id="stock-notification" class="fixed top-4 right-4 bg-red-500 text-white p-4 rounded-lg shadow-lg z-50 max-w-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="font-medium">Low Stock Alert!</p>
                            <p class="text-sm">${items.length} item(s) need restocking</p>
                        </div>
                        <button onclick="closeNotification()" class="ml-2 text-white hover:text-gray-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', alertHtml);

            // Auto-hide after 10 seconds
            setTimeout(() => {
                closeNotification();
            }, 10000);
        }

        // Close notification
        function closeNotification() {
            const notification = document.getElementById('stock-notification');
            if (notification) {
                notification.remove();
            }
        }

        // Show general notification
        function showNotification(message, type = 'info') {
            const colors = {
                info: 'bg-blue-500',
                success: 'bg-green-500',
                error: 'bg-red-500',
                warning: 'bg-yellow-500'
            };

            const notificationHtml = `
                <div id="general-notification" class="fixed top-4 right-4 ${colors[type]} text-white p-4 rounded-lg shadow-lg z-50 max-w-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-sm">${message}</p>
                        <button onclick="closeGeneralNotification()" class="ml-2 text-white hover:text-gray-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', notificationHtml);

            setTimeout(() => {
                closeGeneralNotification();
            }, 5000);
        }

        // Close general notification
        function closeGeneralNotification() {
            const notification = document.getElementById('general-notification');
            if (notification) {
                notification.remove();
            }
        }

        // Check for low stock items on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Check if there are any low stock items and show initial alert
            const lowStockCount = parseInt(document.getElementById('low-stock-count').textContent);
            const outOfStockCount = parseInt(document.getElementById('out-of-stock-count').textContent);

            if (lowStockCount > 0 || outOfStockCount > 0) {
                // Play notification sound (optional)
                // playNotificationSound();

                // You can add browser notification here if needed
                if (Notification.permission === 'granted') {
                    new Notification('Stock Alert', {
                        body: `${lowStockCount} items are low on stock, ${outOfStockCount} items are out of stock`,
                        icon: '/favicon.ico'
                    });
                }
            }
        });

        // Request notification permission on page load
        if (Notification.permission === 'default') {
            Notification.requestPermission();
        }
    </script>

    <style>
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .animate-spin {
        animation: spin 1s linear infinite;
    }

    .rotate-180 {
        transform: rotate(180deg);
    }

    .transition-transform {
        transition: transform 0.2s ease-in-out;
    }

    /* Pulse animation for low stock alerts */
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    .pulse {
        animation: pulse 2s infinite;
    }

    /* Add pulse to low stock badges */
    .bg-red-600.text-white.text-xs.px-1.py-0\.5.rounded {
        animation: pulse 2s infinite;
    }
    </style>
@endsection
