@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto py-6">
    <h2 class="text-xl font-bold mb-4">Edit User</h2>

    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block font-medium">Name</label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2" value="{{ old('name', $user->name) }}" required>
        </div>

        <div>
            <label class="block font-medium">Email</label>
            <input type="email" name="email" class="w-full border rounded px-3 py-2" value="{{ old('email', $user->email) }}" required>
        </div>

        <div>
            <label class="block font-medium">Role</label>
                <select name="role" class="w-full border rounded px-3 py-2" required>
                    <option value="">Select Role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role }}" {{ $user->role === $role ? 'selected' : '' }}>
                            {{ $role }}
                        </option>
                    @endforeach
                </select>
        </div>

        <div>
            <button type="submit" class="bg-blue-600 text-black px-4 py-2 rounded hover:bg-blue-700">Update User</button>
            <a href="{{ route('admin.users.index') }}" class="ml-4 text-gray-600 hover:underline">Cancel</a>
        </div>
    </form>
</div>
@endsection
