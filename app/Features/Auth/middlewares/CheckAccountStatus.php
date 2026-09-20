<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountStatus
{
    /**
     * Check whether the authenticated user's account is frozen.
     *
     * A user is considered frozen when the deleted_at column
     * contains a value.
     *
     * @param Request $request
     * @param Closure(Request): Response $next
     *
     * @return Response
     */
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $user = $request->user();

        if ($user && $user->trashed()) {
            return response()->json([
                'message' => 'تم تجميد حسابك، لا يمكنك استخدام النظام.',
            ], 403);
        }

        return $next($request);
    }
}