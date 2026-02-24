<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexBlogPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Public endpoint for viewing published posts
    }

    public function rules(): array
    {
        return [
            'page' => ['integer', 'min:1'],
            'per_page' => ['integer', Rule::in([10, 15, 25, 50])],
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', Rule::in(['draft', 'published', 'scheduled'])],
            'category_id' => ['nullable', 'exists:blog_categories,id'],
            'author_id' => ['nullable', 'exists:users,id'],
            'tag_id' => ['nullable', 'exists:blog_tags,id'],
            'is_featured' => ['nullable', 'boolean'],
            'sort_by' => ['nullable', Rule::in(['title_ar', 'title_en', 'published_at', 'views_count', 'created_at'])],
            'sort_order' => ['nullable', Rule::in(['asc', 'desc'])],
        ];
    }
}
