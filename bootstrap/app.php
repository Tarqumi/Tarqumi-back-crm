<?php

use App\Http\Middleware\CanEditLandingPage;
use App\Http\Middleware\CanViewContactSubmissions;
use App\Http\Middleware\FounderRoleMiddleware;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\UpdateLastActive;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Global middleware
        $middleware->append(SecurityHeaders::class);
        
        // Middleware aliases
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'founder.role' => FounderRoleMiddleware::class,
            'update.last.active' => UpdateLastActive::class,
            'can.edit.landing' => CanEditLandingPage::class,
            'can.view.contact' => CanViewContactSubmissions::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
