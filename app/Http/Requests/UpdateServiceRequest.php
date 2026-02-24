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
            'title_ar' => ['sometimes', 'string', 'min:3', 'max:100'],
            'title_en' => ['sometimes', 'string', 'min:3', 'max:100'],
            'description_ar' => ['sometimes', 'string', 'min:10', 'max:1000'],
            'description_en' => ['sometimes', 'string', 'min:10', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,gif,webp,svg', 'max:20480'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'show_on_home' => ['boolean'],
        ];
    }
}
