@extends('layouts.app')

@section('content')
    {{-- Use flex-col by default (mobile first) and flex-row for medium screens and up --}}
    <div class="flex flex-col md:flex-row max-w-7xl mx-auto p-4 md:p-6 space-y-4 md:space-y-0 md:space-x-4">

        {{-- Adjust width using responsive prefixes --}}
        <div class="w-full md:w-2/3 overflow-y-auto md:h-screen"> {{-- Consider removing h-screen for smaller devices if content is short --}}
            <h2 class="text-xl font-bold mb-4">Products</h2>

            {{-- Product Search Form --}}
            <form action="{{ route('pos.index') }}" method="GET" class="mb-4" id="searchForm">
                <div class="flex items-center space-x-2">
                    <input type="text" name="search" id="searchInput" placeholder="Search products..."
                        value="{{ request('search') }}"
                        class="flex-1 border rounded p-2 text-sm focus:ring-blue-500 focus:border-blue-500"
                        autocomplete="off">
                    {{-- <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">
            Search
        </button> --}}
                    @if (request('search'))
                        <a href="{{ route('pos.index') }}" class="text-red-600 hover:underline text-sm py-2">Clear</a>
                    @endif
                </div>
            </form>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const searchInput = document.getElementById('searchInput');
                    const searchForm = document.getElementById('searchForm');
                    let searchTimeout;

                    // Function to perform the search
                    function performSearch() {
                        const searchValue = searchInput.value.trim();

                        // Create URL with search parameter
                        const url = new URL(window.location.href);
                        if (searchValue) {
                            url.searchParams.set('search', searchValue);
                        } else {
                            url.searchParams.delete('search');
                        }

                        // Navigate to the new URL
                        window.location.href = url.toString();
                    }

                    // Add event listener for real-time search
                    searchInput.addEventListener('input', function() {
                        // Clear existing timeout
                        clearTimeout(searchTimeout);

                        // Set new timeout to avoid too many requests
                        searchTimeout = setTimeout(function() {
                            performSearch();
                        }, 500); // Wait 500ms after user stops typing
                    });

                    // Handle Enter key press
                    searchInput.addEventListener('keypress', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            clearTimeout(searchTimeout);
                            performSearch();
                        }
                    });

                    // Optional: Clear search on Escape key
                    searchInput.addEventListener('keydown', function(e) {
                        if (e.key === 'Escape') {
                            searchInput.value = '';
                            clearTimeout(searchTimeout);
                            searchTimeout = setTimeout(function() {
                                performSearch();
                            }, 100);
                        }
                    });
                });
            </script>

            {{-- Updated grid: 1 column on mobile, 2 on small screens, 3 on large screens --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($products as $product)
                    <form method="POST" action="{{ route('pos.cart.add') }}"
                        class="border rounded-lg p-6 bg-white shadow-lg text-center hover:shadow-xl transition-shadow duration-200">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        {{-- Bigger image --}}
                        <img src="{{ asset('storage/' . $product->image) }}"
                            class="mx-auto h-40 w-40 object-cover mb-4 rounded-lg">
                        {{-- Bigger text and better spacing --}}
                        <div class="font-semibold text-lg mb-2 line-clamp-2">{{ $product->name }}</div>
                        <div class="text-base text-gray-700 mb-4 font-medium">₱{{ number_format($product->price, 2) }}</div>
                        {{-- Bigger button --}}
                        <button type="submit" class="bg-blue-600 text-white text-sm px-6 py-3 rounded-lg w-full hover:bg-blue-700 transition-colors duration-200 font-medium">
                            Add to Cart
                        </button>
                    </form>
                @empty
                    <p class="col-span-full text-gray-500 text-center text-lg">No products found matching your search.</p>
                @endforelse
            </div>
        </div>

        {{-- Cart (Right Panel) - Full Width on Mobile, 1/3 on Medium+ --}}
        {{-- Adjust width and height for smaller screens --}}
        <div
            class="w-full md:w-1/3 md:sticky md:top-0 md:h-[85vh] p-4 flex flex-col border-l md:border-l bg-white shadow rounded">

            {{-- 1. Order Header & Controls --}}
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold">New Order</h2>
                <form action="{{ route('pos.cart.clear') }}" method="POST">
                    @csrf
                    <button class="text-sm text-red-600 hover:underline">Clear Cart</button>
                </form>
            </div>

            {{-- 2. Ordered Items List --}}
            <div class="flex-grow overflow-y-auto space-y-4 pr-1">
                @forelse ($cart as $item)
                    <div class="border-b pb-2 flex gap-2 items-center">
                        <img src="{{ asset('storage/' . $item['image']) }}" class="w-10 h-10 object-cover rounded"
                            alt="Product">

                        <div class="flex-1">
                            <div class="font-medium text-sm">{{ $item['name'] }}</div>
                            <div class="text-xs text-gray-600">₱{{ number_format($item['price'], 2) }} each</div>

                            <form action="{{ route('pos.cart.update') }}" method="POST"
                                class="flex items-center mt-1 gap-2">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                                <div class="flex items-center gap-1">
                                    <button type="button" class="decrease px-2 py-1 bg-gray-200 rounded text-xs"
                                        data-id="{{ $item['id'] }}">-</button>
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1"
                                        class="w-12 text-center text-sm border rounded">
                                    <button type="button" class="increase px-2 py-1 bg-gray-200 rounded text-xs"
                                        data-id="{{ $item['id'] }}">+</button>
                                </div>
                                <button type="submit" class="text-xs bg-yellow-400 px-2 py-1 rounded ml-2">Update</button>
                            </form>
                        </div>

                        <form action="{{ route('pos.cart.remove') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                            <button class="text-red-500 text-sm ml-2">✕</button>
                        </form>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">No items in cart. Add products to get started!</p>
                @endforelse
            </div>

            {{-- 3. Discount Section --}}
            <form action="{{ route('pos.cart.applyDiscount') }}" method="POST" class="mt-4">
                @csrf
                <label class="block text-sm font-medium mb-1">Discount Type:</label>
                <select name="discount_type" class="w-full border rounded p-2 text-sm mb-2">
                    <option value="">None</option>
                    <option value="student" {{ session('discount') == 'student' ? 'selected' : '' }}>Student Discount – 20%
                    </option>
                    <option value="pwd" {{ session('discount') == 'pwd' ? 'selected' : '' }}>PWD Discount – 20%</option>
                    <option value="senior" {{ session('discount') == 'senior' ? 'selected' : '' }}>Senior Citizen – 20%
                    </option>
                    <option value="others" {{ session('discount') == 'others' ? 'selected' : '' }}>Others – 10%</option>
                </select>
                <button type="submit" class="bg-blue-600 text-white w-full text-sm py-1 rounded hover:bg-blue-700">Apply
                    Discount</button>
            </form>

            {{-- 4. Order Summary --}}
            <div class="mt-4 border-t pt-3 text-sm">
                <p>Subtotal: <span class="float-right font-medium">₱{{ number_format($subtotal ?? 0, 2) }}</span></p>
                @if (session('discount') || ($discountAmount ?? 0) > 0)
                    <p>Discount ({{ $discountPercent ?? 0 }}%): <span
                            class="float-right text-green-600">-₱{{ number_format($discountAmount ?? 0, 2) }}</span></p>
                @endif
                <hr class="my-2">
                <p class="text-lg font-bold">TOTAL: <span
                        class="float-right text-black-800">₱{{ number_format($total ?? 0, 2) }}</span></p>
            </div>

            {{-- 5. Payment & Checkout --}}
            <form action="{{ route('pos.checkout') }}" method="POST" class="mt-4 space-y-2">
                @csrf
                <label class="block text-sm font-medium mb-1">Payment Method:</label>
                <div class="flex justify-between space-x-2 text-sm">
                    <label class="flex-1">
                        <input type="radio" name="payment_method" value="cash" checked class="hidden peer">
                        <div
                            class="w-full border rounded py-2 text-center cursor-pointer peer-checked:bg-blue-600 peer-checked:text-white">
                            Cash
                        </div>
                    </label>
                    <label class="flex-1">
                        <input type="radio" name="payment_method" value="card" class="hidden peer">
                        <div
                            class="w-full border rounded py-2 text-center cursor-pointer peer-checked:bg-blue-600 peer-checked:text-white">
                            Card
                        </div>
                    </label>
                    <label class="flex-1">
                        <input type="radio" name="payment_method" value="ewallet" class="hidden peer">
                        <div
                            class="w-full border rounded py-2 text-center cursor-pointer peer-checked:bg-blue-600 peer-checked:text-white">
                            E-Wallet
                        </div>
                    </label>
                </div>

                <button type="submit"
                    class="w-full bg-green-600 text-white font-semibold py-2 text-sm rounded hover:bg-green-700 @if (empty($cart)) opacity-50 cursor-not-allowed @endif"
                    @if (empty($cart)) disabled @endif>Proceed to Checkout</button>
            </form>
        </div>
    </div>
@endsection
