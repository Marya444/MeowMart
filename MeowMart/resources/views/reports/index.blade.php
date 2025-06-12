@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto mt-8 p-6 bg-white shadow rounded">
    <h1 class="text-2xl font-bold mb-6">Sales Reports</h1>

    {{-- Period Selection Form --}}
    <form action="{{ route('admin.reports.index') }}" method="GET" class="mb-6 flex items-center space-x-4">
        <label for="period" class="font-medium">Select Period:</label>
        <select name="period" id="period" class="border rounded p-2" onchange="this.form.submit()">
            <option value="daily" {{ $period == 'daily' ? 'selected' : '' }}>Daily</option>
            <option value="weekly" {{ $period == 'weekly' ? 'selected' : '' }}>Weekly</option>
            <option value="monthly" {{ $period == 'monthly' ? 'selected' : '' }}>Monthly</option>
            <option value="quarterly" {{ $period == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
            <option value="custom" {{ $period == 'custom' ? 'selected' : '' }}>Custom Range</option>
        </select>

        {{-- Custom Date Range Inputs (conditionally displayed with JavaScript) --}}
        <div id="custom-date-inputs" class="{{ $period == 'custom' ? '' : 'hidden' }} flex items-center space-x-2">
            <input type="date" name="start_date" value="{{ $startDate ? $startDate->format('Y-m-d') : '' }}" class="border rounded p-2">
            <span>to</span>
            <input type="date" name="end_date" value="{{ $endDate ? $endDate->format('Y-m-d') : '' }}" class="border rounded p-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Apply</button>
        </div>
    </form>

    {{-- JavaScript to show/hide custom date inputs --}}
    <script>
        document.getElementById('period').addEventListener('change', function() {
            const customInputs = document.getElementById('custom-date-inputs');
            if (this.value === 'custom') {
                customInputs.classList.remove('hidden');
            } else {
                customInputs.classList.add('hidden');
                this.form.submit(); // Automatically submit if not custom
            }
        });
    </script>


    <h2 class="text-xl font-bold mb-4">Summary for {{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }}</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-blue-100 p-4 rounded-lg shadow">
            <p class="text-lg font-semibold">Total Sales</p>
            <p class="text-3xl font-extrabold">₱{{ number_format($totalSales, 2) }}</p>
        </div>
        <div class="bg-green-100 p-4 rounded-lg shadow">
            <p class="text-lg font-semibold">Number of Orders</p>
            <p class="text-3xl font-extrabold">{{ $numberOfOrders }}</p>
        </div>
        <div class="bg-purple-100 p-4 rounded-lg shadow">
            <p class="text-lg font-semibold">Average Order Value</p>
            <p class="text-3xl font-extrabold">₱{{ number_format($averageOrderValue, 2) }}</p>
        </div>
    </div>

    <h3 class="text-xl font-bold mb-4">Sales by Payment Method</h3>
    @if ($salesByPaymentMethod->isEmpty())
        <p class="mb-4">No sales data for payment methods in this period.</p>
    @else
        <div class="mb-8">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b text-left">Payment Method</th>
                        <th class="py-2 px-4 border-b text-right">Total Amount</th>
                        <th class="py-2 px-4 border-b text-right">Number of Orders</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($salesByPaymentMethod as $item)
                        <tr>
                            <td class="py-2 px-4 border-b">{{ ucfirst($item->payment_method) }}</td>
                            <td class="py-2 px-4 border-b text-right">₱{{ number_format($item->total_amount, 2) }}</td>
                            <td class="py-2 px-4 border-b text-right">{{ $item->total_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif


    <h2 class="text-xl font-bold mb-4">Transaction History</h2>
        @if ($transactions->isEmpty())
            <p>No transactions found for the selected period.</p>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">Order ID</th>
                        <th class="py-2 px-4 border-b">Date & Time</th>
                        <th class="py-2 px-4 border-b">Total</th>
                        <th class="py-2 px-4 border-b">Payment Method</th>
                        <th class="py-2 px-4 border-b">Cashier</th>
                        <th class="py-2 px-4 border-b">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $transaction)
                        <tr>
                            <td class="py-2 px-4 border-b">{{ $transaction->id }}</td>
                            <td class="py-2 px-4 border-b">{{ $transaction->created_at->format('M d, Y h:i A') }}</td>
                            <td class="py-2 px-4 border-b">₱{{ number_format($transaction->total, 2) }}</td>
                            <td class="py-2 px-4 border-b">{{ ucfirst($transaction->payment_method) }}</td>
                            <td class="py-2 px-4 border-b">{{ $transaction->user->name ?? 'N/A' }}</td>
                            <td class="py-2 px-4 border-b">
                                <a href="{{ route('admin.reports.transactions.show', $transaction->id) }}" class="text-blue-600 hover:underline">View Details</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection