<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;

it('displays mapped weather data on the admin dashboard', function () {
    config()->set('services.weather.city', 'Berlin');

    Http::preventStrayRequests();
    Http::fake([
        'https://api.open-meteo.com/v1/forecast*' => Http::response([
            'current' => [
                'temperature_2m' => 18.4,
                'relative_humidity_2m' => 72,
                'wind_speed_10m' => 3.2,
                'weather_code' => 61,
            ],
        ]),
    ]);

    $response = $this->actingAs(User::factory()->create(['role' => 'admin']))
        ->get(route('admin.dashboard'));

    $response->assertOk()
        ->assertSee('Berlin')
        ->assertSee('18.4')
        ->assertSee('Rain')
        ->assertSee('72%')
        ->assertSee('3.2 m/s');
});

it('keeps the admin dashboard available when weather data is unavailable', function () {
    Http::preventStrayRequests();
    Http::fake([
        'https://api.open-meteo.com/v1/forecast*' => Http::failedConnection(),
    ]);

    $response = $this->actingAs(User::factory()->create(['role' => 'admin']))
        ->get(route('admin.dashboard'));

    $response->assertOk()
        ->assertSee('Weather information is currently unavailable. Please try again later.');
});
