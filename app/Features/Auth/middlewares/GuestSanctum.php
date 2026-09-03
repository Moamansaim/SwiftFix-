<?php

namespace App\Features\Auth\middlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to prevent authenticated users from accessing guest-only routes.
 *
 * Checks whether the current user is already authenticated using
 * the Sanctum guard. If authenticated, the request is rejected.
 * Otherwise, the request continues to the next middleware or controller.
 */
class GuestSanctum
{
    /**
     * Handle an incoming request.
     *
     * Prevents authenticated users from accessing routes intended
     * only for guests, such as login and registration.
     *
     * @param Request $request The incoming HTTP request.
     * @param Closure $next The next middleware or controller in the pipeline.
     * @return Response The HTTP response returned by the middleware pipeline.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check whether the user is already authenticated using Sanctum.
        if (Auth::guard('sanctum')->check()) {
            // Prevent authenticated users from accessing guest-only routes.
            return response()->json([
                'message' => 'أنت مسجل دخول بالفعل',
            ], 403);
        }

        // Allow unauthenticated users to continue to the requested route.
        return $next($request);
    }
}