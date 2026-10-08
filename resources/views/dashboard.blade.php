<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Admin Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="text-2xl mb-4">Welcome Admin!</h1>

                <a href="{{ route('users.index') }}"
                   class="inline-block px-4 py-2 bg-gray-800 text-white rounded-md">
                    Manage Users
                </a>
            </div>
        </div>
    </div>
</x-app-layout>