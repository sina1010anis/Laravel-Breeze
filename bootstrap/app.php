<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
// use \Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'Redis' => Illuminate\Support\Facades\Redis::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
