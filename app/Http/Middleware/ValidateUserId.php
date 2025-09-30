<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateUserId
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = $request->header('X-User-Id');
        
        if (!$userId) {
            return response()->json([
                'code' => 'UNAUTHORIZED',
                'message' => 'X-User-Id header is required for testing.',
                'data' => []
            ], 401);
        }

        if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $userId)) {
            return response()->json([
                'code' => 'INVALID_USER_ID',
                'message' => 'X-User-Id must be a valid UUID.',
                'data' => []
            ], 400);
        }

        $request->merge(['user_id' => $userId]);
        
        return $next($request);
    }
}
