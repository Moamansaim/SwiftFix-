<?php

use App\Features\Auth\middlewares\GuestSanctum;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))

    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withBroadcasting(
        __DIR__ . '/../routes/channels.php',
        [
            'middleware' => ['api', 'auth:sanctum'],
        ],
    )

    ->withMiddleware(function (Middleware $middleware) {

        $middleware->alias([
            'guest.sanctum' => GuestSanctum::class,
            'permission' => PermissionMiddleware::class,
        ]);

        $middleware->redirectGuestsTo(function (Request $request) {

            return $request->is('api/*')
                ? null
                : route('login');
        });
    })

    ->withExceptions(function (Exceptions $exceptions): void {

        /*
        |--------------------------------------------------------------------------
        | إجبار API على إرجاع JSON
        |--------------------------------------------------------------------------
        */

        $exceptions->shouldRenderJsonWhen(
            fn(Request $request) => $request->is('api/*'),
        );

        /*
        |--------------------------------------------------------------------------
        | المستخدم غير مسجل الدخول
        |--------------------------------------------------------------------------
        */

        $exceptions->render(function (
            AuthenticationException $e,
            Request $request
        ) {

            if ($request->is('api/*')) {

                return response()->json([
                    'message' => 'غير مصرح لك بالوصول. يرجى تسجيل الدخول أولاً.',
                ], 401);
            }
        });

        /*
        |--------------------------------------------------------------------------
        | المستخدم لا يملك الصلاحية المطلوبة
        |--------------------------------------------------------------------------
        */

        $exceptions->render(function (
            UnauthorizedException $e,
            Request $request
        ) {

            if ($request->is('api/*')) {

                return response()->json([
                    'message' => 'ليس لديك صلاحية لتنفيذ هذا الإجراء.',
                ], 403);
            }
        });

        /*
        |--------------------------------------------------------------------------
        | Model غير موجود
        |--------------------------------------------------------------------------
        */

        $exceptions->render(function (
            ModelNotFoundException $e,
            Request $request
        ) {

            if ($request->is('api/*')) {

                return response()->json([
                    'message' => 'العنصر المطلوب غير موجود.',
                ], 404);
            }
        });

        /*
        |--------------------------------------------------------------------------
        | Route / Resource غير موجود
        |--------------------------------------------------------------------------
        */

        $exceptions->render(function (
            NotFoundHttpException $e,
            Request $request
        ) {

            if ($request->is('api/*')) {

                return response()->json([
                    'message' => 'المورد المطلوب غير موجود.',
                ], 404);
            }
        });

        /*
        |--------------------------------------------------------------------------
        | الرابط غير صالح
        |--------------------------------------------------------------------------
        */

        $exceptions->render(function (
            InvalidSignatureException $e,
            Request $request
        ) {

            if ($request->is('api/*')) {

                return response()->json([
                    'message' => 'الرابط غير صالح.',
                ], 403);
            }
        });
    })

    ->create();