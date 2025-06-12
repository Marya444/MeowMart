@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-6">
    <h2 class="text-2xl font-bold mb-4">Edit Product</h2>

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block font-semibold">Product Image</label>
            <input type="file" name="image" class="w-full border rounded px-3 py-2">

            @if ($product->image)
                <div class="mt-2">
                    <p class="text-sm text-gray-600">Current Image:</p>
                    <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" class="w-7 h-7 object-cover border rounded">
                </div>
            @endif
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Name</label>
            <input type="text" name="name" value="{{ $product->name }}" class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">sku</label>
            <input type="text" name="sku" value="{{ $product->sku }}" class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Description</label>
            <textarea name="description" class="w-full border rounded px-3 py-2">{{ $product->description }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Price(₱)</label>
            <input type="number" step="0.01" name="price" value="{{ $product->price }}" class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Stock</label>
            <input type="number" name="stock" value="{{ $product->stock }}" class="w-full border rounded px-3 py-2" required>
        </div>
        
        <div class="mb-4">
            <label class="block font-semibold">Low Stock Threshold</label>
            <input type="number" name="low_stock_threshold" class="w-full border rounded px-3 py-2" value="{{ $product->low_stock_threshold}}" required>
        </div>  

        <div class="mb-4">
            <label class="block font-semibold">Category</label>
            <select name="category_id" class="w-full border rounded px-3 py-2" required>
                <option value="">Select Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div> 

        <a href="{{ route('admin.products.index') }}" class="ml-4 text-gray-600 hover:underline">Cancel</a>
        <button type="submit" class="bg-blue-600 text-black px-4 py-2 rounded hover:bg-blue-700">Update</button>
    </form>
</div>
@endsection
