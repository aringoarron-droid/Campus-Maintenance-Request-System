<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MaintenanceRequestController extends Controller
{
    public function index(Request $request): View
    {
        $maintenanceRequests = $request->user()
            ->maintenanceRequests()
            ->latest()
            ->paginate(10);

        return view('maintenance-requests.index', compact('maintenanceRequests'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location' => ['required', 'string', 'max:255'],
            'priority' => ['required', 'in:low,medium,high,urgent'],
        ]);

        $request->user()->maintenanceRequests()->create($validated);

        return redirect()
            ->route('requester.maintenance-requests.index')
            ->with('success', 'Maintenance request submitted successfully.');
    }

    public function destroy(Request $request, MaintenanceRequest $maintenanceRequest): RedirectResponse
    {
        abort_unless($maintenanceRequest->user_id === $request->user()->id, 404);

        $maintenanceRequest->delete();

        return redirect()
            ->route('requester.maintenance-requests.index')
            ->with('success', 'Maintenance request deleted successfully.');
    }
}
