<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FounderRoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$founderRoles): Response
    {
        $user = $request->user();

        if (!$user || $user->role !== UserRole::FOUNDER) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Founder role required.',
            ], 403);
        }

        $userFounderRole = $user->founder_role?->value;

        if (!in_array($userFounderRole, $founderRoles)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Specific founder role required.',
            ], 403);
        }

        return $next($request);
    }
}
