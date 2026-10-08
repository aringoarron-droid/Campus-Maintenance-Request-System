<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherService
{
    /**
     * @return array{city: string, temperature: float|int, description: string, humidity: int, wind_speed: float|int}|null
     */
    public function current(): ?array
    {
        try {
            $response = Http::connectTimeout(3)
                ->timeout(5)
                ->get(config('services.weather.base_url'), [
                    'latitude' => config('services.weather.latitude'),
                    'longitude' => config('services.weather.longitude'),
                    'current' => 'temperature_2m,relative_humidity_2m,wind_speed_10m,weather_code',
                    'temperature_unit' => 'celsius',
                    'wind_speed_unit' => 'ms',
                    'timezone' => 'auto',
                ]);

            if (! $response->successful()) {
                Log::warning('Weather API returned an unsuccessful response.', [
                    'status' => $response->status(),
                ]);

                return null;
            }

            $weather = $response->json();
            $weatherCode = data_get($weather, 'current.weather_code');

            if (! is_array($weather)
                || ! is_numeric(data_get($weather, 'current.temperature_2m'))
                || ! is_numeric(data_get($weather, 'current.relative_humidity_2m'))
                || ! is_numeric(data_get($weather, 'current.wind_speed_10m'))
                || ! is_int($weatherCode) && ! is_float($weatherCode)) {
                Log::warning('Weather API returned an invalid payload.');

                return null;
            }

            return [
                'city' => (string) config('services.weather.city'),
                'temperature' => data_get($weather, 'current.temperature_2m'),
                'description' => $this->descriptionFor((int) $weatherCode),
                'humidity' => data_get($weather, 'current.relative_humidity_2m'),
                'wind_speed' => data_get($weather, 'current.wind_speed_10m'),
            ];
        } catch (ConnectionException $exception) {
            Log::warning('Weather API request failed.', [
                'message' => $exception->getMessage(),
            ]);

            return null;
        }
    }

    private function descriptionFor(int $weatherCode): string
    {
        return match (true) {
            $weatherCode === 0 => 'clear sky',
            in_array($weatherCode, [1, 2, 3], true) => 'partly cloudy',
            in_array($weatherCode, [45, 48], true) => 'foggy',
            in_array($weatherCode, [51, 53, 55, 56, 57], true) => 'drizzle',
            in_array($weatherCode, [61, 63, 65, 66, 67, 80, 81, 82], true) => 'rain',
            in_array($weatherCode, [71, 73, 75, 77, 85, 86], true) => 'snow',
            in_array($weatherCode, [95, 96, 99], true) => 'thunderstorm',
            default => 'unknown conditions',
        };
    }
}
