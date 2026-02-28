<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TeamService
{
    public function createTeamMember(array $data): User
    {
        // Handle profile picture upload
        if (isset($data['profile_picture']) && $data['profile_picture'] instanceof UploadedFile) {
            $data['profile_picture'] = $this->uploadProfilePicture($data['profile_picture']);
        }

        // Hash password
        $data['password'] = Hash::make($data['password']);

        // Set default values
        $data['is_active'] = $data['is_active'] ?? true;
        $data['email_verified_at'] = now();
        $data['last_active_at'] = now();

        // Create user
        $user = User::create($data);

        // TODO: Send welcome email (implement later)
        // $this->sendWelcomeEmail($user, $originalPassword);

        return $user;
    }

    public function getTeamMembers(array $filters): LengthAwarePaginator
    {
        $query = User::query();

        // Search
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if (!empty($filters['role'])) {
            $query->where('role', $filters['role']);
        }

        // Filter by status
        if (!empty($filters['status'])) {
            $query->where('is_active', $filters['status'] === 'active');
        }

        // Filter by department
        if (!empty($filters['department'])) {
            $query->where('department', $filters['department']);
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'name';
        $sortOrder = $filters['sort_order'] ?? 'asc';
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $filters['per_page'] ?? 20;
        
        return $query->with('managedProjects')->paginate($perPage);
    }

    public function getDepartments(): array
    {
        return User::whereNotNull('department')
            ->distinct()
            ->pluck('department')
            ->sort()
            ->values()
            ->toArray();
    }

    public function updateTeamMember(User $user, array $data): User
    {
        // Handle profile picture upload
        if (isset($data['profile_picture']) && $data['profile_picture'] instanceof UploadedFile) {
            // Delete old picture
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $data['profile_picture'] = $this->uploadProfilePicture($data['profile_picture']);
        }

        // Hash password if provided
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        // Update user
        $user->update($data);

        return $user->fresh();
    }

    public function deleteTeamMember(User $user, ?int $newManagerId = null): bool
    {
        // Check if user is project manager
        $managedProjects = $user->managedProjects()->count();
        
        if ($managedProjects > 0) {
            // If no new manager provided, throw exception with project count
            if ($newManagerId === null) {
                throw new \Exception("Cannot delete team member. They are managing {$managedProjects} project(s). Please reassign projects first.");
            }

            // Validate new manager exists and is active
            $newManager = User::findOrFail($newManagerId);
            if (!$newManager->is_active) {
                throw new \Exception("Cannot reassign projects to an inactive team member.");
            }

            // Reassign all projects to new manager
            $this->reassignProjects($user, $newManager);
        }

        // Soft delete
        return $user->delete();
    }

    public function deleteTeamMemberWithReassignment(User $user, int $newManagerId): bool
    {
        return $this->deleteTeamMember($user, $newManagerId);
    }

    public function reassignProjects(User $oldManager, User $newManager): int
    {
        $projects = $oldManager->managedProjects;
        
        foreach ($projects as $project) {
            $project->update(['manager_id' => $newManager->id]);
        }

        return $projects->count();
    }

    private function uploadProfilePicture(UploadedFile $file): string
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('profile-pictures', $filename, 'public');
        
        return $path;
    }
}
