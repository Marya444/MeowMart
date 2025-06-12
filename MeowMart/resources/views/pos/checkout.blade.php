@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-6 p-6 bg-white shadow rounded">
    <h2 class="text-xl font-bold mb-4">Checkout Summary</h2>

    @if (empty($cart))
        <p>No items in the cart.</p>
        <a href="{{ route('pos.index') }}" class="text-blue-600 underline text-sm">Back to Order Page</a>
    @else
        <div class="mb-6">
            <h3 class="font-semibold mb-2">Order Details</h3>
            <ul class="divide-y divide-gray-200">
                @foreach ($cart as $item)
                    <li class="py-2 flex justify-between items-center">
                        <span>{{ $item['name'] }} (x{{ $item['quantity'] }})</span>
                        <span>₱{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                    </li>
                @endforeach
            </ul>
            <div class="mt-4 text-sm space-y-1">
                <p>Subtotal: <span class="font-medium">₱{{ number_format($subtotal, 2) }}</span></p>
                @if (session('discount'))
                    <p class="text-green-600">Discount ({{ ucfirst(session('discount')) }}): <span class="font-medium">- ₱{{ number_format($discountAmount, 2) }}</span></p>
                @endif
                <p class="font-bold text-lg mt-2">Total: <span class="text-blue-700">₱{{ number_format($total, 2) }}</span></p>

                {{-- Display payment method and customer email for user review --}}
                {{-- These variables ($paymentMethod, $customerEmail) are assumed to be passed from PosController@checkout --}}
                <p class="text-base mt-3">Payment Method: <span class="font-semibold">{{ ucfirst($paymentMethod ?? 'Not Selected') }}</span></p>
            </div>
        </div>

        <hr class="my-6 border-gray-300">

        {{-- This is the form for the FINAL SUBMISSION to confirm the order --}}
        <form method="POST" action="{{ route('pos.checkout.confirm') }}">
            @csrf
<!-- 
            {{-- HIDDEN INPUTS TO CARRY OVER DATA FROM THE PREVIOUS PAGE --}}
            {{-- These ensure payment_method and customer_email are sent to confirmOrder --}} -->
            <input type="hidden" name="payment_method" value="{{ $paymentMethod ?? '' }}">
            <input type="hidden" name="customer_email" value="{{ $customerEmail ?? '' }}">

            <!-- {{--
                Optionally, you can also pass other summary totals as hidden inputs if
                your confirmOrder method strictly relies on them from the form
                instead of recalculating from the session (recalculating is generally safer).
            --}} -->
            {{-- <input type="hidden" name="subtotal" value="{{ $subtotal }}"> --}}
            {{-- <input type="hidden" name="total" value="{{ $total }}"> --}}
            {{-- <input type="hidden" name="discount_amount" value="{{ $discountAmount }}"> --}}

<!-- 
            {{-- The customer email input is here again, but now it pre-fills with the
                 value from the previous page, allowing for last-minute edits. --}} -->
            <div class="mb-4">
                <label for="customer_email_final" class="block text-sm font-medium text-gray-700">Customer Email (optional)</label>
                <input type="email" name="customer_email" id="customer_email_final"
                       class="mt-1 block w-full border rounded p-2"
                       placeholder="example@email.com"
                       value="{{ $customerEmail ?? '' }}"> {{-- Pre-fills with the value from the first page --}}
            </div>

            <!-- {{-- Action Buttons --}} -->
            <div class="flex justify-between items-center mt-6">
                <!-- {{-- Go back to the main POS page (cart editing) --}} -->
                <a href="{{ route('pos.index') }}" class="text-red-500 hover:text-red-700 underline text-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Cancel / Go Back to Cart
                </a>

                <!-- {{-- The button to finally confirm the order --}} -->
                <button type="submit" class="bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700 transition duration-200 ease-in-out">
                    Confirm Order <i class="fas fa-check ml-2"></i>
                </button>
            </div>
        </form>
    @endif
</div>
@endsection