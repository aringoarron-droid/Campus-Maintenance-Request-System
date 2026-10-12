<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Maintenance Requests
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto grid max-w-7xl gap-6 px-4 sm:px-6 lg:grid-cols-3 lg:px-8">
            <section class="h-fit rounded-lg bg-white p-6 shadow-sm">
                <h1 class="text-lg font-semibold text-gray-900">Submit a request</h1>
                <p class="mt-1 text-sm text-gray-600">Tell us what needs attention and where.</p>

                @if (session('success'))
                    <div class="mt-4 rounded-md bg-green-50 p-3 text-sm text-green-800" role="status">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('requester.maintenance-requests.store') }}" method="POST" class="mt-6 space-y-4">
                    @csrf

                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                        <input id="title" name="title" type="text" value="{{ old('title') }}" required maxlength="255"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea id="description" name="description" rows="4" required
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                        <input id="location" name="location" type="text" value="{{ old('location') }}" required maxlength="255"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700">Priority</label>
                        <select id="priority" name="priority" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach (['low' => 'Low', 'medium' => 'Medium', 'high' => 'High', 'urgent' => 'Urgent'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('priority', 'medium') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('priority')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                            class="inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Submit request
                    </button>
                </form>
            </section>

            <section class="rounded-lg bg-white p-6 shadow-sm lg:col-span-2">
                <h2 class="text-lg font-semibold text-gray-900">Your requests</h2>

                <div class="mt-4 space-y-4">
                    @forelse ($maintenanceRequests as $maintenanceRequest)
                        <article class="rounded-md border border-gray-200 p-4">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $maintenanceRequest->title }}</h3>
                                    <p class="mt-1 text-sm text-gray-600">{{ $maintenanceRequest->location }}</p>
                                </div>
                                <div class="flex gap-2 text-xs font-medium uppercase">
                                    <span class="rounded-full bg-indigo-50 px-2.5 py-1 text-indigo-700">
                                        {{ str_replace('_', ' ', $maintenanceRequest->status) }}
                                    </span>
                                    <span class="rounded-full bg-gray-100 px-2.5 py-1 text-gray-700">
                                        {{ $maintenanceRequest->priority }}
                                    </span>
                                </div>
                            </div>
                            <p class="mt-3 whitespace-pre-line text-sm text-gray-700">{{ $maintenanceRequest->description }}</p>
                            <div class="mt-4 flex items-center justify-between gap-3">
                                <time class="text-xs text-gray-500" datetime="{{ $maintenanceRequest->created_at->toIso8601String() }}">
                                    Submitted {{ $maintenanceRequest->created_at->format('M j, Y g:i A') }}
                                </time>
                                <form action="{{ route('requester.maintenance-requests.destroy', $maintenanceRequest) }}" method="POST"
                                      onsubmit="return confirm('Delete this maintenance request?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </article>
                    @empty
                        <p class="rounded-md bg-gray-50 p-4 text-sm text-gray-600">
                            You haven’t submitted any maintenance requests yet.
                        </p>
                    @endforelse
                </div>

                <div class="mt-6">
                    {{ $maintenanceRequests->links() }}
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
