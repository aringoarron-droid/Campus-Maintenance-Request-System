<?php

use App\Models\MaintenanceRequest;

it('lists maintenance requests as paginated JSON', function () {
    $maintenanceRequest = MaintenanceRequest::factory()->create();

    $this->getJson('/api/maintenance-requests')
        ->assertOk()
        ->assertJsonPath('data.0.id', $maintenanceRequest->id)
        ->assertJsonPath('data.0.title', $maintenanceRequest->title)
        ->assertJsonStructure(['data', 'links', 'meta']);
});

it('creates a maintenance request and returns it with a 201 response', function () {
    $payload = [
        'title' => 'Leaking kitchen faucet',
        'description' => 'Water continues to drip after the tap is closed.',
        'location' => 'Unit 12, kitchen',
        'priority' => 'high',
    ];

    $this->postJson('/api/maintenance-requests', $payload)
        ->assertCreated()
        ->assertJsonPath('data.title', $payload['title'])
        ->assertJsonPath('data.status', 'pending');

    $this->assertDatabaseHas('maintenance_requests', $payload);
});

it('returns a maintenance request from the API after creating it', function () {
    $payload = [
        'title' => 'Bathroom drain is blocked',
        'description' => 'Water drains very slowly.',
        'location' => 'Unit 4 bathroom',
        'priority' => 'medium',
    ];

    $created = $this->postJson('/api/maintenance-requests', $payload)
        ->assertCreated()
        ->json('data.id');

    $this->getJson('/api/maintenance-requests')
        ->assertOk()
        ->assertJsonFragment([
            'id' => $created,
            'title' => $payload['title'],
        ]);
});

it('rejects an invalid maintenance request with a 422 response', function () {
    $this->postJson('/api/maintenance-requests', [
        'title' => '',
        'description' => '',
        'location' => '',
        'priority' => 'critical',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['title', 'description', 'location', 'priority']);
});

it('shows a maintenance request', function () {
    $maintenanceRequest = MaintenanceRequest::factory()->create();

    $this->getJson("/api/maintenance-requests/{$maintenanceRequest->id}")
        ->assertOk()
        ->assertJsonPath('data.id', $maintenanceRequest->id)
        ->assertJsonPath('data.description', $maintenanceRequest->description);
});

it('returns a 404 response for an unknown maintenance request', function () {
    $this->getJson('/api/maintenance-requests/999')
        ->assertNotFound();
});

it('updates a maintenance request and persists the changes', function () {
    $maintenanceRequest = MaintenanceRequest::factory()->create([
        'status' => 'pending',
    ]);

    $this->patchJson("/api/maintenance-requests/{$maintenanceRequest->id}", [
        'status' => 'in_progress',
        'priority' => 'urgent',
    ])
        ->assertOk()
        ->assertJsonPath('data.status', 'in_progress')
        ->assertJsonPath('data.priority', 'urgent');

    $this->assertDatabaseHas('maintenance_requests', [
        'id' => $maintenanceRequest->id,
        'status' => 'in_progress',
        'priority' => 'urgent',
    ]);
});

it('rejects an invalid maintenance request update with a 422 response', function () {
    $maintenanceRequest = MaintenanceRequest::factory()->create();

    $this->patchJson("/api/maintenance-requests/{$maintenanceRequest->id}", [
        'status' => 'started',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('status');
});

it('deletes a maintenance request and returns a 204 response', function () {
    $maintenanceRequest = MaintenanceRequest::factory()->create();

    $this->deleteJson("/api/maintenance-requests/{$maintenanceRequest->id}")
        ->assertNoContent();

    $this->assertDatabaseMissing('maintenance_requests', [
        'id' => $maintenanceRequest->id,
    ]);
});
