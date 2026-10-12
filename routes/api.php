<?php

use App\Http\Controllers\Api\MaintenanceRequestController;
use Illuminate\Support\Facades\Route;

Route::apiResource('maintenance-requests', MaintenanceRequestController::class);
