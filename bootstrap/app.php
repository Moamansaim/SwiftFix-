<?php

use App\Features\Auth\middlewares\GuestSanctum;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Routing\Exceptions\InvalidSignatureException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->alias([
            'guest.sanctum' => GuestSanctum::class,
        ]);

        $middleware->redirectGuestsTo(function (Request $request) {
            return $request->is('api/*')
                ? null
                : route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->shouldRenderJsonWhen(
            fn(Request $request) => $request->is('api/*'),
        );

        $exceptions->render(function (
            AuthenticationException $e,
            Request $request
        ) {
            return response()->json([
                'message' => 'غير مصرح لك بالوصول. يرجى تسجيل الدخول أولاً.',
            ], 401);
        });

        $exceptions->render(function (
            InvalidSignatureException $e,
            Request $request
        ) {
            return response()->json([
                'message' => 'الرابط غير صالح.',
            ], 403);
        });
    })
    ->create();