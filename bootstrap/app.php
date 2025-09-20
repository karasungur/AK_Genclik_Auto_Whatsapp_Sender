<?php

use App\Http\Middleware\AdminGuard;
use App\Http\Middleware\CsrfTokenRotate;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\SetSecurityHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append([
            SetSecurityHeaders::class,
            SetLocale::class,
        ]);

        $middleware->appendToGroup('web', [
            CsrfTokenRotate::class,
        ]);

        $middleware->alias([
            'admin.guard' => AdminGuard::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
