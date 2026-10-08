<?php

namespace App\Http\Controllers;

use App\Services\WeatherService;
use Illuminate\Contracts\View\View;

class AdminDashboardController extends Controller
{
    public function __construct(private readonly WeatherService $weatherService) {}

    public function __invoke(): View
    {
        return view('dashboards.admin', [
            'weather' => $this->weatherService->current(),
        ]);
    }
}
