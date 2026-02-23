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
                'message' => 'Team member created successfully',
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
                'message' => 'Team member updated successfully',
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
                        'message' => "Cannot delete team member. They are managing {$managedProjects} projects. Please provide new_manager_id for reassignment.",
                        'managed_projects_count' => $managedProjects,
                    ], 422);
                }
                
                $newManager = User::findOrFail($request->new_manager_id);
                $reassignedCount = $this->teamService->reassignProjects($user, $newManager);
            }
            
            $this->teamService->deleteTeamMember($user);

            return response()->json([
                'success' => true,
                'message' => 'Team member deleted successfully',
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
