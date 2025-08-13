<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register custom middleware aliases here if needed
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'regional' => \App\Http\Middleware\RegionalAccessMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

// Jika menggunakan Laravel 10 atau sebelumnya, tambahkan di app/Http/Kernel.php:

// protected $middlewareAliases = [
//     // ... existing middleware
//     'role' => \App\Http\Middleware\RoleMiddleware::class,
//     'regional' => \App\Http\Middleware\RegionalAccessMiddleware::class,
// ];
