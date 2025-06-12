@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto mt-8 p-6 bg-white shadow rounded">
        {{-- Header: Stacks on small screens, row on medium --}}
        <div class="flex flex-col sm:flex-row justify-between items-center mb-4 space-y-4 sm:space-y-0">
            <h1 class="text-2xl font-bold mb-4 sm:mb-0">User Management</h1>
            {{-- Button: Full width on small, auto on medium --}}
            <a href="{{ route('admin.users.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 w-full sm:w-auto text-center">
                + Add New User
            </a>
        </div>

        @if(session('success'))
            <div class="mt-4 text-green-600 bg-green-100 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- Table Container: Enables horizontal scrolling if content overflows --}}
        <div class="overflow-x-auto">
            {{-- Table: Minimum width ensures scrollability on small screens --}}
            <table class="w-full text-left border border-gray-200 min-w-[500px] md:min-w-full">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 border text-sm md:text-base">Name</th>
                        {{-- Hide Email on extra small screens --}}
                        <th class="px-4 py-2 border hidden xs:table-cell text-sm md:text-base">Email</th>
                        <th class="px-4 py-2 border text-sm md:text-base">Role</th>
                        <th class="px-4 py-2 border text-sm md:text-base">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="border px-4 py-2 text-sm md:text-base">{{ $user->name }}</td>
                            <td class="border px-4 py-2 hidden xs:table-cell text-sm md:text-base">{{ $user->email }}</td>
                            <td class="border px-4 py-2 capitalize text-sm md:text-base">{{ $user->role }}</td>
                            <td class="border px-4 py-2 text-sm md:text-base">
                                {{-- Action buttons: Stacks on small, row on medium --}}
                                <div class="flex flex-col space-y-1 sm:flex-row sm:space-y-0 sm:space-x-2 items-start sm:items-center">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:underline">Edit</a>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-4 text-center text-gray-600">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
