<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'manager' => new UserResource($this->whenLoaded('manager')),
            'manager_id' => $this->manager_id,
            'budget' => $this->budget,
            'currency' => $this->currency,
            'priority' => $this->priority,
            'start_date' => $this->start_date?->toDateString(),
            'end_date' => $this->end_date?->toDateString(),
            'estimated_hours' => $this->estimated_hours,
            'status' => $this->status,
            'status_label' => $this->status_label,
            'status_percentage' => $this->status_percentage,
            'is_active' => $this->is_active,
            'is_overdue' => $this->is_overdue,
            'clients' => ClientResource::collection($this->whenLoaded('clients')),
            'primary_client' => new ClientResource($this->whenLoaded('clients', function () {
                return $this->primary_client;
            })),
            'creator' => new UserResource($this->whenLoaded('creator')),
            'updater' => new UserResource($this->whenLoaded('updater')),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
