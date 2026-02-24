<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBlogPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->canManageBlog();
    }

    public function rules(): array
    {
        return [
            'title_ar' => ['sometimes', 'string', 'min:10', 'max:200'],
            'title_en' => ['sometimes', 'string', 'min:10', 'max:200'],
            'excerpt_ar' => ['sometimes', 'string', 'min:50', 'max:500'],
            'excerpt_en' => ['sometimes', 'string', 'min:50', 'max:500'],
            'content_ar' => ['sometimes', 'string', 'min:100'],
            'content_en' => ['sometimes', 'string', 'min:100'],
            'featured_image' => ['nullable', 'image', 'mimes:jpeg,png,gif,webp', 'max:20480'],
            'meta_title_ar' => ['nullable', 'string', 'max:60'],
            'meta_title_en' => ['nullable', 'string', 'max:60'],
            'meta_description_ar' => ['nullable', 'string', 'max:160'],
            'meta_description_en' => ['nullable', 'string', 'max:160'],
            'meta_keywords_ar' => ['nullable', 'string', 'max:255'],
            'meta_keywords_en' => ['nullable', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:blog_categories,id'],
            'tags' => ['sometimes', 'array'],
            'tags.*' => ['required', 'exists:blog_tags,id'],
            'status' => ['sometimes', Rule::in(['draft', 'published', 'scheduled'])],
            'scheduled_at' => ['required_if:status,scheduled', 'nullable', 'date', 'after:now'],
            'is_featured' => ['boolean'],
        ];
    }
}
