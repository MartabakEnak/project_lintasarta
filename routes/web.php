<?php

use App\Http\Controllers\FiberCoreController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Redirect root to login if not authenticated, otherwise to fiber-cores
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('fiber-cores.index');
    }
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Registration Routes (only accessible when no users exist or by superadmin)
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Protected Routes (require authentication)
Route::middleware(['auth'])->group(function () {

    // Fiber Core Management Routes
    Route::resource('fiber-cores', FiberCoreController::class)->except(['show']);

    // Custom show route for cable_id
    Route::get('/fiber-cores/{cable_id}', [FiberCoreController::class, 'show'])->name('fiber-cores.show');

    // Custom update route for individual cores
    Route::put('/fiber-cores/{cable_id}/{id}', [FiberCoreController::class, 'update'])->name('fiber-cores.update');

    // Generate sample data route (superadmin only)
    Route::get('/generate-sample', [FiberCoreController::class, 'generateSample'])
        ->name('fiber-cores.generate-sample');

    // AJAX search route
    Route::get('/fiber-cores/ajax/search', [FiberCoreController::class, 'search'])->name('fiber-cores.search');

    // User Management Routes (superadmin only)
    Route::resource('users', UserController::class);
});