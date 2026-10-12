<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MaintenanceRequestResource;
use App\Models\MaintenanceRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\Rule;

class MaintenanceRequestController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return MaintenanceRequestResource::collection(
            MaintenanceRequest::query()->latest()->paginate(15),
        );
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location' => ['required', 'string', 'max:255'],
            'priority' => ['required', Rule::in(['low', 'medium', 'high', 'urgent'])],
            'status' => ['sometimes', Rule::in(['pending', 'in_progress', 'completed', 'cancelled'])],
        ]);

        $maintenanceRequest = MaintenanceRequest::create($validated);

        return MaintenanceRequestResource::make($maintenanceRequest)
            ->response()
            ->setStatusCode(201);
    }

    public function show(MaintenanceRequest $maintenanceRequest): MaintenanceRequestResource
    {
        return MaintenanceRequestResource::make($maintenanceRequest);
    }

    public function update(Request $request, MaintenanceRequest $maintenanceRequest): MaintenanceRequestResource
    {
        $validated = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'required', 'string'],
            'location' => ['sometimes', 'required', 'string', 'max:255'],
            'priority' => ['sometimes', 'required', Rule::in(['low', 'medium', 'high', 'urgent'])],
            'status' => ['sometimes', 'required', Rule::in(['pending', 'in_progress', 'completed', 'cancelled'])],
        ]);

        $maintenanceRequest->update($validated);

        return MaintenanceRequestResource::make($maintenanceRequest->refresh());
    }

    public function destroy(MaintenanceRequest $maintenanceRequest): JsonResponse
    {
        $maintenanceRequest->delete();

        return response()->json(null, 204);
    }
}
