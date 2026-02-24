<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogPostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title_ar' => $this->title_ar,
            'title_en' => $this->title_en,
            'slug_ar' => $this->slug_ar,
            'slug_en' => $this->slug_en,
            'excerpt_ar' => $this->excerpt_ar,
            'excerpt_en' => $this->excerpt_en,
            'content_ar' => $this->content_ar,
            'content_en' => $this->content_en,
            'featured_image' => $this->featured_image ? asset('storage/' . $this->featured_image) : null,
            'meta_title_ar' => $this->meta_title_ar,
            'meta_title_en' => $this->meta_title_en,
            'meta_description_ar' => $this->meta_description_ar,
            'meta_description_en' => $this->meta_description_en,
            'meta_keywords_ar' => $this->meta_keywords_ar,
            'meta_keywords_en' => $this->meta_keywords_en,
            'category' => new BlogCategoryResource($this->whenLoaded('category')),
            'author' => new UserResource($this->whenLoaded('author')),
            'tags' => BlogTagResource::collection($this->whenLoaded('tags')),
            'status' => $this->status,
            'published_at' => $this->published_at?->toIso8601String(),
            'scheduled_at' => $this->scheduled_at?->toIso8601String(),
            'views_count' => $this->views_count,
            'reading_time' => $this->reading_time,
            'is_featured' => $this->is_featured,
            'is_published' => $this->is_published,
            'is_scheduled' => $this->is_scheduled,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
