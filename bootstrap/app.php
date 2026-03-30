<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\MiddlewareGroup;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
        ->withMiddleware(function (Middleware $middleware): void {
            $middleware->alias([
                'auth' => \App\Http\Middleware\Authenticate::class,
                'check.role' => \App\Http\Middleware\CheckAdminRole::class,
                'check.trangchu' => \App\Http\Middleware\CheckTrangChu::class,
                'check.permission' => \App\Http\Middleware\CheckAdminPermission::class,
            ]);
        })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
