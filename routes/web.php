<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/profile', [DashboardController::class, 'profile'])
        ->name('profile');

});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->name('admin.')
    ->group(function () {

        // Dashboard (supports search, role filter & pagination)
        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('dashboard');

        // Statistics
        Route::get('/statistics', [AdminController::class, 'statistics'])
            ->name('statistics');

        // Settings
        Route::get('/settings', function () {
            return view('admin.settings');
        })->name('settings');

});

/*
|--------------------------------------------------------------------------
| Moderator Routes
|--------------------------------------------------------------------------
*/

Route::prefix('moderator')
    ->middleware(['auth', 'role:moderator,admin'])
    ->group(function () {

        Route::get('/panel', function () {
            return view('moderator.panel');
        })->name('moderator.panel');

});

/*
|--------------------------------------------------------------------------
| Management Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin,moderator'])
    ->group(function () {

        Route::get('/management', function () {
            return view('management.dashboard');
        })->name('management.dashboard');

    });