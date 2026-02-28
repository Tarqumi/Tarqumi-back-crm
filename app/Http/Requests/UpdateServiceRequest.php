<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->canEditLandingPage();
    }

    public function rules(): array
    {
        return [
            'icon' => ['nullable', 'string', 'max:255'],
            'title_ar' => ['sometimes', 'string', 'min:3', 'max:100', 'regex:/[\x{0600}-\x{06FF}]/u'],
            'title_en' => ['sometimes', 'string', 'min:3', 'max:100', 'regex:/[a-zA-Z]/'],
            'description_ar' => ['sometimes', 'string', 'min:10', 'max:1000', 'regex:/[\x{0600}-\x{06FF}]/u'],
            'description_en' => ['sometimes', 'string', 'min:10', 'max:1000', 'regex:/[a-zA-Z]/'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,gif,webp,svg', 'max:20480'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'show_on_home' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title_ar.min' => 'Arabic title must be at least 3 characters',
            'title_ar.regex' => 'Arabic title must contain Arabic characters',
            'title_en.min' => 'English title must be at least 3 characters',
            'title_en.regex' => 'English title must contain English characters',
            'description_ar.min' => 'Arabic description must be at least 10 characters',
            'description_ar.regex' => 'Arabic description must contain Arabic characters',
            'description_en.min' => 'English description must be at least 10 characters',
            'description_en.regex' => 'English description must contain English characters',
            'image.max' => 'Image must not exceed 20MB',
            'is_active.boolean' => 'The is active field must be true or false',
            'show_on_home.boolean' => 'The show on home field must be true or false',
        ];
    }
}
