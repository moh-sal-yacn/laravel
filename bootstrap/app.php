<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // ─── تسجيل الـ middleware aliases ───
        $middleware->alias([
            'auth'  => \Illuminate\Auth\Middleware\Authenticate::class,
            'guest' => \Illuminate\Auth\Middleware\RedirectIfAuthenticated::class,
            'role'  => \App\Http\Middleware\EnsureUserHasRole::class,
        ]);

        // ─── توجيه غير المسجَّلين إلى /login ───
        $middleware->redirectGuestsTo('/login');

        // ─── توجيه المسجَّلين عند زيارة /login إلى / ───
        $middleware->redirectUsersTo('/');

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();