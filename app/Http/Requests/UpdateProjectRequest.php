<?php

namespace App\Http\Requests;

use App\Enums\ProjectStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->canManageProjects();
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'min:3', 'max:150'],
            'description' => ['nullable', 'string', 'max:10000'],
            'clients' => ['sometimes', 'array', 'min:1', 'max:10'],
            'clients.*' => ['required', 'exists:clients,id'],
            'manager_id' => ['sometimes', 'exists:users,id'],
            'budget' => ['nullable', 'numeric', 'min:0', 'max:999999999'],
            'currency' => ['required_with:budget', 'string', 'max:3'],
            'priority' => ['sometimes', 'integer', 'min:1', 'max:10'],
            'start_date' => ['sometimes', 'date'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
            'estimated_hours' => ['nullable', 'numeric', 'min:0', 'max:100000'],
            'status' => ['sometimes', Rule::enum(ProjectStatus::class)],
            'is_active' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.min' => 'Project name must be at least 3 characters',
            'name.max' => 'Project name cannot exceed 150 characters',
            'clients.min' => 'At least one client is required',
            'clients.max' => 'Maximum 10 clients allowed per project',
            'clients.*.exists' => 'Selected client does not exist',
            'manager_id.exists' => 'Selected project manager does not exist',
            'priority.min' => 'Priority must be between 1 and 10',
            'priority.max' => 'Priority must be between 1 and 10',
            'end_date.after' => 'End date must be after start date',
            'currency.required_with' => 'Currency is required when budget is set',
            'status.enum' => 'Status must be one of: planning, analysis, design, implementation, testing, deployment',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Sanitize name to prevent XSS
        if ($this->has('name')) {
            $this->merge([
                'name' => strip_tags($this->name),
            ]);
        }

        // Sanitize description to prevent XSS
        if ($this->has('description')) {
            $this->merge([
                'description' => strip_tags($this->description),
            ]);
        }
    }
}
