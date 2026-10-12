<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Requester Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-2">Welcome User!</h1>
                <p class="text-gray-600">You are logged in as a Requester.</p>
                <a href="{{ route('requester.maintenance-requests.index') }}"
                   class="mt-4 inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                    Create or view maintenance requests
                </a>
            </div>
        </div>
    </div>
</x-app-layout>