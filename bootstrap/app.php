<?php

use App\Features\Auth\middlewares\GuestSanctum;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'guest.sanctum' => GuestSanctum::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Validation failed.', 'errors' => $e->errors()], 422);
        });
        $exceptions->render(function (AuthenticationException $e) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        });
        $exceptions->render(function (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Resource not found.'], 404);
    });
    })->create();