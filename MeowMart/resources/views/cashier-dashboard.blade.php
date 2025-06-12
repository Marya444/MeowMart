@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto mt-4 sm:mt-6 lg:mt-8 p-3 sm:p-4 lg:p-6 bg-white shadow rounded">
    <h1 class="text-2xl sm:text-3xl font-bold mb-6 sm:mb-8 text-gray-800">Cashier Dashboard</h1>

    {{-- Summary Cards - Only Today's Sales, Orders, and Recent Transactions --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4 sm:p-6 rounded-lg shadow-lg text-white">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-blue-100 text-xs sm:text-sm font-medium">Today's Sales</p>
                    <p class="text-xl sm:text-2xl lg:text-3xl font-bold truncate">₱{{ number_format($todaySales ?? 0, 2) }}</p>
                </div>
                <div class="text-blue-200 ml-3 flex-shrink-0">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-600 p-4 sm:p-6 rounded-lg shadow-lg text-white">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-green-100 text-xs sm:text-sm font-medium">Today's Orders</p>
                    <p class="text-xl sm:text-2xl lg:text-3xl font-bold">{{ $todayOrders ?? 0 }}</p>
                </div>
                <div class="text-green-200 ml-3 flex-shrink-0">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2L3 7v11a2 2 0 002 2h10a2 2 0 002-2V7l-7-5zM8 15a1 1 0 01-1-1v-2a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1H8z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions Section --}}
    <div class="bg-gray-50 p-4 sm:p-6 rounded-lg shadow-lg border mb-6 sm:mb-8">
        <h2 class="text-lg sm:text-xl font-semibold mb-3 sm:mb-4 text-gray-800">Quick Actions</h2>
        <div class="flex flex-col sm:flex-row sm:flex-wrap gap-3 sm:gap-4">
            <a href="{{ route('pos.index') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 sm:px-6 py-3 rounded-lg transition duration-200 flex items-center justify-center sm:justify-start text-sm sm:text-base">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                </svg>
                <span class="whitespace-nowrap">Go to POS System</span>
            </a>
            {{-- Add more quick action buttons here if needed --}}
        </div>
    </div>

    {{-- Recent Transactions Summary --}}
    <div class="bg-white rounded-lg shadow-lg border">
        <div class="p-4 sm:p-6 border-b border-gray-200">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-800">Recent Transactions</h2>
        </div>

        {{-- Mobile View (Hidden on desktop) --}}
        <div class="block lg:hidden">
            @forelse($recentTransactions ?? [] as $transaction)
                <div class="p-4 border-b border-gray-200 hover:bg-gray-50">
                    <div class="flex justify-between items-start mb-2">
                        <div class="font-medium text-gray-900">#{{ $transaction->id }}</div>
                        <div class="text-sm text-gray-500">{{ $transaction->created_at->format('h:i A') }}</div>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <div class="text-lg font-semibold text-gray-900">₱{{ number_format($transaction->total, 2) }}</div>
                        <div class="text-sm text-gray-500">{{ ucfirst($transaction->payment_method) }}</div>
                    </div>
                    <div class="flex justify-end">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            Completed
                        </span>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-500">No recent transactions</div>
            @endforelse
        </div>

        {{-- Desktop/Tablet View (Hidden on mobile) --}}
        <div class="hidden lg:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentTransactions ?? [] as $transaction)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $transaction->id }}</td>
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction->created_at->format('h:i A') }}</td>
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">₱{{ number_format($transaction->total, 2) }}</td>
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucfirst($transaction->payment_method) }}</td>
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Completed
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 lg:px-6 py-4 text-center text-gray-500">No recent transactions</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
