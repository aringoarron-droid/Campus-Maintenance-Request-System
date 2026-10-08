<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Admin Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-2">Welcome Admin!</h1>
                <p class="text-gray-600 mb-6">You are logged in as an Administrator.</p>

                <a href="{{ route('users.index') }}"
                   class="inline-block px-4 py-2 bg-gray-800 text-white rounded-md">
                    Manage Users
                </a>
            </div>

            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900">Current Weather</h2>

                @if ($weather)
                    <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
                        <div>
                            <p class="text-sm text-gray-500">Location</p>
                            <p class="font-semibold text-gray-900">{{ $weather['city'] }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Temperature</p>
                            <p class="font-semibold text-gray-900">{{ $weather['temperature'] }}&deg;C</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Condition</p>
                            <p class="font-semibold text-gray-900">{{ ucfirst($weather['description']) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Humidity</p>
                            <p class="font-semibold text-gray-900">{{ $weather['humidity'] }}%</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Wind Speed</p>
                            <p class="font-semibold text-gray-900">{{ $weather['wind_speed'] }} m/s</p>
                        </div>
                    </div>
                @else
                    <p class="mt-4 text-gray-600">Weather information is currently unavailable. Please try again later.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>