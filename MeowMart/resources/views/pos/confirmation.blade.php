@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-6 p-6 text-center bg-white shadow rounded">
    <h2 class="text-xl font-bold text-green-700 mb-4">Thank you for your purchase!</h2>
    <p class="mb-6 text-gray-700">Your order has been processed successfully.</p>

    @if ($order->customer_email)
        <p class="text-sm mb-2">A receipt was sent to: <strong>{{ $order->customer_email }}</strong></p>
    @endif

    {{-- Optional Survey --}}
    <div class="mt-6">
        <p class="mb-2 font-medium">How was your experience?</p>
        <a href="https://forms.gle/Q5sU7n43M9uWTPqn6" target="_blank" class="inline-block bg-blue-600 text-white px-4 py-2 rounded">Give Feedback</a>
    </div>

    <a href="{{ route('pos.index') }}" class="mt-6 inline-block text-blue-500 underline">Order Again</a>
</div>
@endsection
