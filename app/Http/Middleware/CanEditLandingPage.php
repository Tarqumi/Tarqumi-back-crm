<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CanEditLandingPage
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()->canEditLandingPage()) {
            return response()->json([
                'success' => false,
                'message' => __('auth.unauthorized')
            ], 403);
        }

        return $next($request);
    }
}
