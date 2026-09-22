<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'screen' => \App\Http\Middleware\EnsureScreenAccess::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // A 419 happens when the CSRF token has expired (e.g. the login page
        // was left idle for a long time). Instead of a dead-end "Page Expired"
        // screen, send the user back to refresh the form (and a fresh token)
        // with a friendly message.
        $exceptions->render(function (TokenMismatchException $e, Request $request) {
            return redirect()->back()
                ->withInput($request->except('_token', 'password', 'password_confirmation'))
                ->with('error', 'Your session timed out. Please try again.');
        });
    })->create();
