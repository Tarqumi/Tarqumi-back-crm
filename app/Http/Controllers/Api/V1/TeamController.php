<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteTeamMemberRequest;
use App\Http\Requests\IndexTeamRequest;
use App\Http\Requests\StoreTeamMemberRequest;
use App\Http\Requests\UpdateTeamMemberRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\TeamService;
use Illuminate\Http\JsonResponse;

class TeamController extends Controller
{
    public function __construct(
        private TeamService $teamService
    ) {}

    public function index(IndexTeamRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $users = $this->teamService->getTeamMembers($filters);

        return response()->json([
            'success' => true,
            'data' => UserResource::collection($users),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
                'from' => $users->firstItem(),
                'to' => $users->lastItem(),
            ],
        ]);
    }

    public function store(StoreTeamMemberRequest $request): JsonResponse
    {
        try {
            $user = $this->teamService->createTeamMember($request->validated());

            return response()->json([
                'success' => true,
                'data' => new UserResource($user),
                'message' => __('team.created'),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create team member: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show(User $user): JsonResponse
    {
        $user->load('managedProjects');

        return response()->json([
            'success' => true,
            'data' => new UserResource($user),
        ]);
    }

    public function update(UpdateTeamMemberRequest $request, User $user): JsonResponse
    {
        try {
            $updatedUser = $this->teamService->updateTeamMember($user, $request->validated());

            return response()->json([
                'success' => true,
                'data' => new UserResource($updatedUser),
                'message' => __('team.updated'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update team member: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(DeleteTeamMemberRequest $request, User $user): JsonResponse
    {
        try {
            // Check if user is managing projects
            $managedProjects = $user->managedProjects()->count();
            
            if ($managedProjects > 0) {
                if (!$request->has('new_manager_id')) {
                    return response()->json([
                        'success' => false,
                        'message' => __('team.must_reassign_projects'),
                        'managed_projects_count' => $managedProjects,
                    ], 422);
                }
                
                $newManager = User::findOrFail($request->new_manager_id);
                $reassignedCount = $this->teamService->reassignProjects($user, $newManager);
            }
            
            $this->teamService->deleteTeamMember($user);

            return response()->json([
                'success' => true,
                'message' => __('team.deleted'),
                'reassigned_projects' => $reassignedCount ?? 0,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete team member: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function departments(): JsonResponse
    {
        $departments = $this->teamService->getDepartments();

        return response()->json([
            'success' => true,
            'data' => $departments,
        ]);
    }

    public function reassign(User $oldManager, User $newManager): JsonResponse
    {
        try {
            $count = $this->teamService->reassignProjects($oldManager, $newManager);

            return response()->json([
                'success' => true,
                'message' => "{$count} projects reassigned successfully",
                'reassigned_count' => $count,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reassign projects: ' . $e->getMessage(),
            ], 500);
        }
    }
}
