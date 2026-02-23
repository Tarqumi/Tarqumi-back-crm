<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermissionsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'role' => $user->role->value,
                'founder_role' => $user->founder_role?->value,
                'permissions' => $user->getAllPermissions(),
            ],
        ]);
    }
}
