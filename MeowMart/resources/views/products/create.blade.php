@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-6">
    <h2 class="text-2xl font-bold mb-4">Add New Product</h2>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label class="block font-semibold">Product Image</label>
            <input type="file" name="image" accept="image/*" id="imageInput" class="w-full border rounded px-3 py-2">
            <img id="preview" class="mt-2 max-h-48 hidden" />
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Name</label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">SKU</label>
            <input type="text" name="sku" class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Description</label>
            <textarea name="description" class="w-full border rounded px-3 py-2"></textarea>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Price(₱)</label>
            <input type="number" step="0.01" name="price" class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Stock</label>
            <input type="number" name="stock" class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Low Stock Threshold</label>
            <input type="number" name="low_stock_threshold" class="w-full border rounded px-3 py-2" value="100" required>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Category</label>
            <select name="category_id" class="w-full border rounded px-3 py-2" required>
                <option value="">Select Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <a href="{{ route('admin.products.index') }}" class="ml-4 text-gray-600 hover:underline">Cancel</a>
        <button type="submit" class="bg-green-600 text-black px-4 py-2 rounded hover:bg-green-700">Save</button>
    </form>
</div>

<script>
document.getElementById('imageInput')?.addEventListener('change', function (event) {
    const [file] = event.target.files;
    const preview = document.getElementById('preview');
    if (file) {
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('hidden');
    } else {
        preview.classList.add('hidden');
    }
});
</script>

@endsection