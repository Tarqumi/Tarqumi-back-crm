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
            'title_ar' => ['sometimes', 'string', 'min:10', 'max:200', 'regex:/[\x{0600}-\x{06FF}]/u'],
            'title_en' => ['sometimes', 'string', 'min:10', 'max:200', 'regex:/[a-zA-Z]/'],
            'excerpt_ar' => ['sometimes', 'string', 'min:50', 'max:500', 'regex:/[\x{0600}-\x{06FF}]/u'],
            'excerpt_en' => ['sometimes', 'string', 'min:50', 'max:500', 'regex:/[a-zA-Z]/'],
            'content_ar' => ['sometimes', 'string', 'min:100', 'regex:/[\x{0600}-\x{06FF}]/u'],
            'content_en' => ['sometimes', 'string', 'min:100', 'regex:/[a-zA-Z]/'],
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

    public function messages(): array
    {
        return [
            'title_ar.regex' => 'Arabic title must contain Arabic characters',
            'title_en.regex' => 'English title must contain English characters',
            'excerpt_ar.regex' => 'Arabic excerpt must contain Arabic characters',
            'excerpt_en.regex' => 'English excerpt must contain English characters',
            'content_ar.regex' => 'Arabic content must contain Arabic characters',
            'content_en.regex' => 'English content must contain English characters',
        ];
    }
}
