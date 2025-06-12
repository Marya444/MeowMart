@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto py-6">
    <h2 class="text-xl font-bold mb-4">Add New User</h2>

    <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block font-medium">Name</label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2" value="{{ old('name') }}" required>
        </div>

        <div>
            <label class="block font-medium">Email</label>
            <input type="email" name="email" class="w-full border rounded px-3 py-2" value="{{ old('email') }}" required>
        </div>

        <div>
            <label class="block font-medium">Password</label>
            <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
            @error('password')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block font-medium">Confirm Password</label>
            <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block font-medium">Role</label>
                <select name="role" class="w-full border rounded px-3 py-2" required>
                    <option value="">Select Role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role }}">{{ $role }}</option>
                    @endforeach
                </select>
        </div>

        <div>
            <button type="submit" class="bg-green-600 text-black px-4 py-2 rounded hover:bg-green-700">Create User</button>
            <a href="{{ route('admin.users.index') }}" class="ml-4 text-gray-600 hover:underline">Cancel</a>
        </div>
    </form>
</div>
@endsection
