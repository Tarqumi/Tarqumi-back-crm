<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'whatsapp' => $this->whatsapp,
            'department' => $this->department,
            'job_title' => $this->job_title,
            'profile_picture' => $this->profile_picture ? asset('storage/' . $this->profile_picture) : null,
            'role' => $this->role->value,
            'role_label' => $this->role->label(),
            'founder_role' => $this->founder_role?->value,
            'founder_role_label' => $this->founder_role?->label(),
            'is_active' => $this->is_active,
            'last_login_at' => $this->last_login_at?->toIso8601String(),
            'last_active_at' => $this->last_active_at?->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'managed_projects_count' => $this->whenLoaded('managedProjects', fn() => $this->managedProjects->count()),
        ];
    }
}
