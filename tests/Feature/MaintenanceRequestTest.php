<?php

use App\Models\MaintenanceRequest;
use App\Models\User;

it('shows the maintenance request form and only the requester own requests', function () {
    $requester = User::factory()->create(['role' => 'requester']);
    $ownRequest = MaintenanceRequest::factory()->create(['user_id' => $requester->id]);
    $otherRequest = MaintenanceRequest::factory()->create();

    $this->actingAs($requester)
        ->get(route('requester.maintenance-requests.index'))
        ->assertOk()
        ->assertSee('Submit a request')
        ->assertSee($ownRequest->title)
        ->assertDontSee($otherRequest->title);
});

it('creates a maintenance request for the signed-in requester', function () {
    $requester = User::factory()->create(['role' => 'requester']);
    $otherUser = User::factory()->create(['role' => 'requester']);

    $this->actingAs($requester)
        ->post(route('requester.maintenance-requests.store'), [
            'title' => 'Broken hallway light',
            'description' => 'The light does not turn on.',
            'location' => 'Second floor hallway',
            'priority' => 'high',
            'user_id' => $otherUser->id,
            'status' => 'completed',
        ])
        ->assertRedirect(route('requester.maintenance-requests.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('maintenance_requests', [
        'title' => 'Broken hallway light',
        'user_id' => $requester->id,
        'status' => 'pending',
        'priority' => 'high',
    ]);

    $this->actingAs($requester)
        ->get(route('requester.maintenance-requests.index'))
        ->assertOk()
        ->assertSee('Broken hallway light');
});

it('returns validation errors when the request form is incomplete', function () {
    $this->actingAs(User::factory()->create(['role' => 'requester']))
        ->from(route('requester.maintenance-requests.index'))
        ->post(route('requester.maintenance-requests.store'), [])
        ->assertRedirect(route('requester.maintenance-requests.index'))
        ->assertSessionHasErrors(['title', 'description', 'location', 'priority']);
});

it('does not allow a requester to delete another users request', function () {
    $requester = User::factory()->create(['role' => 'requester']);
    $maintenanceRequest = MaintenanceRequest::factory()->create();

    $this->actingAs($requester)
        ->delete(route('requester.maintenance-requests.destroy', $maintenanceRequest))
        ->assertNotFound();

    $this->assertModelExists($maintenanceRequest);
});

it('does not allow non-requesters to access the requester page', function () {
    $this->actingAs(User::factory()->create(['role' => 'staff']))
        ->get(route('requester.maintenance-requests.index'))
        ->assertForbidden();
});

it('requires authentication to access maintenance requests', function () {
    $this->get(route('requester.maintenance-requests.index'))
        ->assertRedirect(route('login'));
});
