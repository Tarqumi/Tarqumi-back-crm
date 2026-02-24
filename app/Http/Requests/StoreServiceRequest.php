<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->canEditLandingPage();
    }

    public function rules(): array
    {
        return [
            'icon' => ['nullable', 'string', 'max:255'],
            'title_ar' => ['required', 'string', 'min:3', 'max:100'],
            'title_en' => ['required', 'string', 'min:3', 'max:100'],
            'description_ar' => ['required', 'string', 'min:10', 'max:1000'],
            'description_en' => ['required', 'string', 'min:10', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,gif,webp,svg', 'max:20480'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'show_on_home' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title_ar.required' => 'Arabic title is required',
            'title_ar.min' => 'Arabic title must be at least 3 characters',
            'title_en.required' => 'English title is required',
            'title_en.min' => 'English title must be at least 3 characters',
            'description_ar.required' => 'Arabic description is required',
            'description_ar.min' => 'Arabic description must be at least 10 characters',
            'description_en.required' => 'English description is required',
            'description_en.min' => 'English description must be at least 10 characters',
            'image.max' => 'Image must not exceed 20MB',
        ];
    }
}
