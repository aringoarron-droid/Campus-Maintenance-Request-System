<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">User Management</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if (session('success'))
                    <div class="mb-4 text-green-600">{{ session('success') }}</div>
                @endif

                <a href="{{ route('users.create') }}"
                   class="inline-block mb-4 px-4 py-2 bg-gray-800 text-white rounded-md">
                    + Add User
                </a>

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2">Name</th>
                            <th class="py-2">Email</th>
                            <th class="py-2">Role</th>
                            <th class="py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="border-b">
                                <td class="py-2">{{ $user->name }}</td>
                                <td class="py-2">{{ $user->email }}</td>
                                <td class="py-2">{{ ucfirst($user->role) }}</td>
                                <td class="py-2 space-x-2">
                                    <a href="{{ route('users.edit', $user->id) }}"
                                       class="text-blue-600">Edit</a>
                                    <form action="{{ route('users.destroy', $user->id) }}"
                                          method="POST" class="inline"
                                          onsubmit="return confirm('Delete this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>