@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-8 p-6 bg-white shadow rounded">
    <h1 class="text-2xl font-bold mb-4">Transaction Details (ID: {{ $order->id }})</h1>

    <p><strong>Date:</strong> {{ $order->created_at->format('M d, Y h:i A') }}</p>
    <p><strong>Total:</strong> ₱{{ number_format($order->total, 2) }}</p>
    <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
    <p><strong>Cashier:</strong> {{ $order->user->name ?? 'N/A' }}</p>



    <!-- Products Table -->
    <div class="mb-6">
        <h2 class="text-xl font-semibold mb-3">Products Sold</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @if($order->items && is_array($order->items) && !empty($order->items))
                        @foreach($order->items as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if(isset($item['image']) && $item['image'])
                                            <img class="h-10 w-10 rounded-full mr-3" src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] ?? 'Product' }}">
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $item['name'] ?? 'Unknown Product' }}</div>
                                            <div class="text-sm text-gray-500">ID: {{ $item['id'] ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    ₱{{ number_format($item['price'] ?? 0, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $item['quantity'] ?? 0 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    ₱{{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 0), 2) }}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">No items found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Order Summary -->
    <div class="border-t pt-4">
        <h2 class="text-xl font-semibold mb-3">Order Summary</h2>
        <div class="space-y-2 max-w-sm ml-auto">
            <div class="flex justify-between">
                <span class="text-gray-600">Subtotal:</span>
                <span class="font-semibold">₱{{ number_format($order->subtotal, 2) }}</span>
            </div>

            @if($order->discount > 0)
                <div class="flex justify-between text-red-600">
                    <span>
                        Discount
                        @if($order->discount_type)
                            ({{ ucfirst($order->discount_type) }})
                        @endif
                        :
                    </span>
                    <span class="font-semibold">-₱{{ number_format($order->discount, 2) }}</span>
                </div>
            @endif

            <div class="flex justify-between text-lg font-bold border-t pt-2">
                <span>Total:</span>
                <span>₱{{ number_format($order->total, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-6 flex space-x-4">
        <a href="{{ route('admin.reports.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Back to Reports
        </a>
        <button onclick="window.print()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Print Receipt
        </button>
    </div>
</div>

@section('styles')
<style>
    @media print {
        .no-print {
            display: none !important;
        }

        body * {
            visibility: hidden;
        }

        .max-w-4xl, .max-w-4xl * {
            visibility: visible;
        }

        .max-w-4xl {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
    }
</style>
@endsection
@endsection
