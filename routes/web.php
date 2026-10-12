<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\MaintenanceRequestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return match (request()->user()->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'staff' => redirect()->route('staff.dashboard'),
        'requester' => redirect()->route('requester.dashboard'),
        default => abort(403, 'Access Denied.'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:requester'])->group(function () {
    Route::get('/maintenance-requests', [MaintenanceRequestController::class, 'index'])
        ->name('requester.maintenance-requests.index');
    Route::post('/maintenance-requests', [MaintenanceRequestController::class, 'store'])
        ->name('requester.maintenance-requests.store');
    Route::delete('/maintenance-requests/{maintenanceRequest}', [MaintenanceRequestController::class, 'destroy'])
        ->name('requester.maintenance-requests.destroy');
});

Route::middleware(['auth', 'role:admin'])->get('/admin-dashboard', AdminDashboardController::class)
    ->name('admin.dashboard');

Route::middleware(['auth', 'role:staff'])->get('/staff-dashboard', function () {
    return view('dashboards.staff');
})->name('staff.dashboard');

Route::middleware(['auth', 'role:requester'])->get('/requester-dashboard', function () {
    return view('dashboards.requester');
})->name('requester.dashboard');

Route::middleware(['auth', 'role:admin'])->resource('users', UserManagementController::class);

require __DIR__.'/auth.php';
