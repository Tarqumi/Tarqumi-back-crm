<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Project;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectService
{
    public function createProject(array $data): Project
    {
        return DB::transaction(function () use ($data) {
            // Extract clients
            $clients = $data['clients'] ?? [];
            unset($data['clients']);

            // If no clients specified, use default "Tarqumi" client
            if (empty($clients)) {
                $defaultClient = Client::where('is_default', true)->first();
                if ($defaultClient) {
                    $clients = [$defaultClient->id];
                }
            }

            // Set created_by and updated_by
            $data['created_by'] = Auth::id();
            $data['updated_by'] = Auth::id();
            $data['is_active'] = $data['is_active'] ?? true;
            
            // Status defaults to planning
            $data['status'] = $data['status'] ?? 'planning';

            // Create project (code auto-generated in model boot)
            $project = Project::create($data);

            // Attach clients
            if (!empty($clients)) {
                $pivotData = [];
                foreach ($clients as $index => $clientId) {
                    $pivotData[$clientId] = ['is_primary' => $index === 0];
                }
                $project->clients()->attach($pivotData);
            }

            return $project->load(['clients', 'manager']);
        });
    }

    public function updateProject(Project $project, array $data): Project
    {
        return DB::transaction(function () use ($project, $data) {
            // Extract clients if provided
            $clients = null;
            if (isset($data['clients'])) {
                $clients = $data['clients'];
                unset($data['clients']);
            }

            // Set updated_by
            $data['updated_by'] = Auth::id();

            // Update project
            $project->update($data);

            // Sync clients if provided
            if ($clients !== null) {
                $pivotData = [];
                foreach ($clients as $index => $clientId) {
                    $pivotData[$clientId] = ['is_primary' => $index === 0];
                }
                $project->clients()->sync($pivotData);
            }

            return $project->fresh(['clients', 'manager']);
        });
    }

    public function deleteProject(Project $project): bool
    {
        // Soft delete
        return $project->delete();
    }

    public function getProjects(array $filters): LengthAwarePaginator
    {
        $query = Project::query();

        // Search
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Filter by priority
        if (isset($filters['priority_min'])) {
            $query->where('priority', '>=', $filters['priority_min']);
        }
        if (isset($filters['priority_max'])) {
            $query->where('priority', '<=', $filters['priority_max']);
        }

        // Filter by manager
        if (!empty($filters['manager_id'])) {
            $query->where('manager_id', $filters['manager_id']);
        }

        // Filter by client
        if (!empty($filters['client_id'])) {
            $query->whereHas('clients', function ($q) use ($filters) {
                $q->where('clients.id', $filters['client_id']);
            });
        }

        // Filter by active status
        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'start_date';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $filters['per_page'] ?? 25;
        
        return $query->with(['clients', 'manager'])->paginate($perPage);
    }

    public function getKanbanData(): array
    {
        $statuses = ['planning', 'analysis', 'design', 'implementation', 'testing', 'deployment'];
        $kanban = [];

        foreach ($statuses as $status) {
            $kanban[$status] = Project::where('status', $status)
                ->where('is_active', true)
                ->with(['clients', 'manager'])
                ->get();
        }

        return $kanban;
    }
}
