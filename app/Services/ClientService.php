<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class ClientService
{
    public function createClient(array $data): Client
    {
        // Set created_by
        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();
        $data['is_active'] = $data['is_active'] ?? true;

        return Client::create($data);
    }

    public function updateClient(Client $client, array $data): Client
    {
        // Protect default client
        if ($client->is_default) {
            // Name and email cannot be changed
            unset($data['name'], $data['email']);
            
            // Status cannot be set to inactive
            if (isset($data['is_active']) && !$data['is_active']) {
                throw new \Exception('Default client must remain active');
            }
        }

        // Set updated_by
        $data['updated_by'] = Auth::id();

        $client->update($data);

        return $client->fresh();
    }

    public function deleteClient(Client $client): bool
    {
        // Cannot delete default client
        if (!$client->canBeDeleted()) {
            throw new \Exception('Default client cannot be deleted');
        }

        // Soft delete
        return $client->delete();
    }

    public function getClients(array $filters): LengthAwarePaginator
    {
        $query = Client::query();

        // Search
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if (!empty($filters['status'])) {
            $query->where('is_active', $filters['status'] === 'active');
        }

        // Filter by industry
        if (!empty($filters['industry'])) {
            $query->where('industry', $filters['industry']);
        }

        // Filter by has_projects
        if (isset($filters['has_projects'])) {
            if ($filters['has_projects']) {
                $query->has('projects');
            } else {
                $query->doesntHave('projects');
            }
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'name';
        $sortOrder = $filters['sort_order'] ?? 'asc';
        
        // Default client always first
        $query->orderBy('is_default', 'desc')
              ->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $filters['per_page'] ?? 20;
        
        return $query->withCount('projects')->paginate($perPage);
    }
}
