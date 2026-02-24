<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexContactSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->canViewContactSubmissions();
    }

    public function rules(): array
    {
        return [
            'page' => ['integer', 'min:1'],
            'per_page' => ['integer', Rule::in([10, 20, 50, 100])],
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', Rule::in(['new', 'read', 'replied', 'archived', 'spam'])],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'include_spam' => ['nullable', 'boolean'],
            'sort_by' => ['nullable', Rule::in(['name', 'email', 'submitted_at', 'status'])],
            'sort_order' => ['nullable', Rule::in(['asc', 'desc'])],
        ];
    }
}
