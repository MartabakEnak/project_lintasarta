<?php

use App\Http\Controllers\FiberCoreController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('fiber-cores.index');
});

// Fiber Core Management Routes
Route::resource('fiber-cores', FiberCoreController::class);

// Additional route for generating sample data
Route::get('/generate-sample', [FiberCoreController::class, 'generateSample'])
    ->name('fiber-cores.generate-sample');

Route::get('/fiber-cores/{cable_id}', [FiberCoreController::class, 'show'])->name('fiber-cores.show');
Route::put('/fiber-cores/{cable_id}/{id}', [FiberCoreController::class, 'update'])->name('fiber-cores.update');

