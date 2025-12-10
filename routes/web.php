<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Auth routes (simplified for example)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected user routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
});

// Admin routes with role middleware
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/statistics', [AdminController::class, 'statistics'])->name('statistics');
});

// Moderator routes
Route::middleware(['auth', 'role:moderator,admin'])->prefix('moderator')->group(function () {
    Route::get('/panel', function () {
        return view('moderator.panel');
    })->name('moderator.panel');
});

// Route with specific middleware applied directly
Route::get('/admin/settings', function () {
    return view('admin.settings');
})->middleware(['auth', 'role:admin'])->name('admin.settings');

// Route with multiple roles
Route::get('/management', function () {
    return view('management.dashboard');
})->middleware(['auth', 'role:admin,moderator'])->name('management.dashboard');