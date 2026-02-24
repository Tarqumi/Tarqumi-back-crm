<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
            'slug_ar' => $this->slug_ar,
            'slug_en' => $this->slug_en,
            'description_ar' => $this->description_ar,
            'description_en' => $this->description_en,
            'parent' => new BlogCategoryResource($this->whenLoaded('parent')),
            'children' => BlogCategoryResource::collection($this->whenLoaded('children')),
            'posts_count' => $this->whenCounted('posts'),
            'order' => $this->order,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
