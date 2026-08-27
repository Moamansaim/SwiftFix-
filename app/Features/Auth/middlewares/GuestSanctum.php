<?php

namespace App\Features\Auth\middlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class GuestSanctum
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('sanctum')->check()) {
            return response()->json([
                'message' => 'أنت مسجل دخول بالفعل',
            ], 403);
        }

        return $next($request);
    }
}
