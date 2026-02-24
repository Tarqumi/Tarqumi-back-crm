<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBlogPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->canManageBlog();
    }

    public function rules(): array
    {
        return [
            'title_ar' => ['required', 'string', 'min:10', 'max:200'],
            'title_en' => ['required', 'string', 'min:10', 'max:200'],
            'excerpt_ar' => ['required', 'string', 'min:50', 'max:500'],
            'excerpt_en' => ['required', 'string', 'min:50', 'max:500'],
            'content_ar' => ['required', 'string', 'min:100'],
            'content_en' => ['required', 'string', 'min:100'],
            'featured_image' => ['nullable', 'image', 'mimes:jpeg,png,gif,webp', 'max:20480'], // 20MB
            'meta_title_ar' => ['nullable', 'string', 'max:60'],
            'meta_title_en' => ['nullable', 'string', 'max:60'],
            'meta_description_ar' => ['nullable', 'string', 'max:160'],
            'meta_description_en' => ['nullable', 'string', 'max:160'],
            'meta_keywords_ar' => ['nullable', 'string', 'max:255'],
            'meta_keywords_en' => ['nullable', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:blog_categories,id'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['required', 'exists:blog_tags,id'],
            'status' => ['required', Rule::in(['draft', 'published', 'scheduled'])],
            'scheduled_at' => ['required_if:status,scheduled', 'nullable', 'date', 'after:now'],
            'is_featured' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title_ar.required' => 'Arabic title is required',
            'title_ar.min' => 'Arabic title must be at least 10 characters',
            'title_en.required' => 'English title is required',
            'title_en.min' => 'English title must be at least 10 characters',
            'excerpt_ar.required' => 'Arabic excerpt is required',
            'excerpt_ar.min' => 'Arabic excerpt must be at least 50 characters',
            'excerpt_en.required' => 'English excerpt is required',
            'excerpt_en.min' => 'English excerpt must be at least 50 characters',
            'content_ar.required' => 'Arabic content is required',
            'content_ar.min' => 'Arabic content must be at least 100 characters',
            'content_en.required' => 'English content is required',
            'content_en.min' => 'English content must be at least 100 characters',
            'featured_image.max' => 'Featured image must not exceed 20MB',
            'meta_title_ar.max' => 'Arabic meta title must not exceed 60 characters',
            'meta_title_en.max' => 'English meta title must not exceed 60 characters',
            'meta_description_ar.max' => 'Arabic meta description must not exceed 160 characters',
            'meta_description_en.max' => 'English meta description must not exceed 160 characters',
            'scheduled_at.required_if' => 'Scheduled date is required for scheduled posts',
            'scheduled_at.after' => 'Scheduled date must be in the future',
        ];
    }
}
