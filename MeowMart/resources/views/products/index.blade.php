@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto mt-8 p-6 bg-white shadow rounded">
        {{-- Header: Stacks on small screens, row on medium --}}
        <div class="flex flex-col sm:flex-row justify-between items-center mb-4 space-y-4 sm:space-y-0">
            <h1 class="text-2xl font-bold mb-4 sm:mb-0">Inventory</h1>
            {{-- Button: Full width on small, auto on medium --}}
            <a href="{{ route('admin.products.create') }}"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 w-full sm:w-auto text-center">
                + Add Product
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- Table Container: Enables horizontal scrolling if content overflows --}}
        <div class="overflow-x-auto">
            {{-- Table: Minimum width ensures scrollability on small screens --}}
            <table class="w-full text-left border border-gray-200 min-w-[700px] md:min-w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 border w-20">Image</th>
                        {{-- Hide ID on screens smaller than 'sm' --}}
                        <th class="px-4 py-2 border hidden sm:table-cell">ID</th>
                        <th class="px-4 py-2 border">Name</th>
                        {{-- Hide SKU on screens smaller than 'md' --}}
                        <th class="px-4 py-2 border hidden md:table-cell">SKU</th>
                        <th class="px-4 py-2 border">Price(₱)</th>
                        <th class="px-4 py-2 border">Stock</th>
                        {{-- Hide Category on screens smaller than 'lg' --}}
                        <th class="px-4 py-2 border hidden lg:table-cell">Category</th>
                        <th class="px-4 py-2 border">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td class="px-4 py-2 border">
                                @if ($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                        class="h-12 w-12 object-cover rounded md:h-16 md:w-16">
                                @else
                                    <span class="text-gray-400 text-xs md:text-base">No Image</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 border hidden sm:table-cell text-sm">{{ $product->id }}</td>
                            <td class="px-4 py-2 border text-sm md:text-base font-medium">{{ $product->name }}</td>
                            <td class="px-4 py-2 border hidden md:table-cell text-sm">{{ $product->sku }}</td>
                            <td class="px-4 py-2 border text-sm md:text-base">₱{{ number_format($product->price, 2) }}</td>
                            <td class="px-4 py-2 border text-sm md:text-base">
                                {{ $product->stock }}
                                @if ($product->stock <= $product->low_stock_threshold)
                                    <span
                                        class="ml-1 inline-block bg-red-600 text-white text-xs px-1 py-0.5 rounded">Low</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 border hidden lg:table-cell text-sm">{{ $product->category->name ?? '—' }}
                            </td>
                            <td class="px-4 py-2 border text-sm">
                                {{-- Action buttons: Stacks on small, row on medium --}}
                                <div
                                    class="flex flex-col space-y-1 sm:flex-row sm:space-y-0 sm:space-x-2 items-start sm:items-center">
                                    <a href="{{ route('admin.products.edit', $product) }}"
                                        class="text-blue-600 hover:underline">Edit</a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            onclick="return confirm('Are you sure you want to delete this product?')"
                                            class="text-red-600 hover:underline">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-4 text-center text-gray-600">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
