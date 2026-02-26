<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexProjectRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    public function __construct(
        private ProjectService $projectService
    ) {}

    public function index(IndexProjectRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $projects = $this->projectService->getProjects($filters);

        return response()->json([
            'success' => true,
            'data' => ProjectResource::collection($projects),
            'meta' => [
                'current_page' => $projects->currentPage(),
                'last_page' => $projects->lastPage(),
                'per_page' => $projects->perPage(),
                'total' => $projects->total(),
                'from' => $projects->firstItem(),
                'to' => $projects->lastItem(),
            ],
        ]);
    }

    public function store(StoreProjectRequest $request): JsonResponse
    {
        try {
            $project = $this->projectService->createProject($request->validated());

            return response()->json([
                'success' => true,
                'data' => new ProjectResource($project),
                'message' => __('project.created'),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create project: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show(Project $project): JsonResponse
    {
        $project->load(['clients', 'manager', 'creator', 'updater']);

        return response()->json([
            'success' => true,
            'data' => new ProjectResource($project),
        ]);
    }

    public function update(UpdateProjectRequest $request, Project $project): JsonResponse
    {
        try {
            $updatedProject = $this->projectService->updateProject($project, $request->validated());

            return response()->json([
                'success' => true,
                'data' => new ProjectResource($updatedProject),
                'message' => __('project.updated'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update project: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Project $project): JsonResponse
    {
        try {
            $this->projectService->deleteProject($project);

            return response()->json([
                'success' => true,
                'message' => __('project.deleted'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete project: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function restore(Project $project): JsonResponse
    {
        try {
            $project->restore();

            return response()->json([
                'success' => true,
                'data' => new ProjectResource($project->fresh(['clients', 'manager'])),
                'message' => __('project.restored'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to restore project: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function kanban(): JsonResponse
    {
        try {
            $kanbanData = $this->projectService->getKanbanData();

            return response()->json([
                'success' => true,
                'data' => $kanbanData,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load kanban data: ' . $e->getMessage(),
            ], 500);
        }
    }
}
