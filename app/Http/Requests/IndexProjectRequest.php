<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->canViewProjects();
    }

    public function rules(): array
    {
        return [
            'page' => ['integer', 'min:1'],
            'per_page' => ['integer', Rule::in([10, 25, 50, 100])],
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', Rule::in(['planning', 'analysis', 'design', 'implementation', 'testing', 'deployment'])],
            'priority_min' => ['nullable', 'integer', 'min:1', 'max:10'],
            'priority_max' => ['nullable', 'integer', 'min:1', 'max:10'],
            'manager_id' => ['nullable', 'exists:users,id'],
            'client_id' => ['nullable', 'exists:clients,id'],
            'is_active' => ['nullable', 'boolean'],
            'sort_by' => ['nullable', Rule::in(['name', 'code', 'priority', 'start_date', 'end_date', 'created_at'])],
            'sort_order' => ['nullable', Rule::in(['asc', 'desc'])],
        ];
    }
}
