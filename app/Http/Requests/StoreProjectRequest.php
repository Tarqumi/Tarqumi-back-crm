<?php

namespace App\Http\Requests;

use App\Enums\ProjectStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->canManageProjects();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:150'],
            'description' => ['nullable', 'string', 'max:10000'],
            'clients' => ['nullable', 'array', 'min:1', 'max:10'],
            'clients.*' => ['required', 'exists:clients,id'],
            'manager_id' => ['required', 'exists:users,id'],
            'budget' => ['nullable', 'numeric', 'min:0', 'max:999999999'],
            'currency' => ['required_with:budget', 'string', 'max:3'],
            'priority' => ['required', 'integer', 'min:1', 'max:10'],
            'status' => ['nullable', Rule::enum(ProjectStatus::class)],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
            'estimated_hours' => ['nullable', 'numeric', 'min:0', 'max:100000'],
            'is_active' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Project name is required',
            'name.min' => 'Project name must be at least 3 characters',
            'name.max' => 'Project name cannot exceed 150 characters',
            'clients.required' => 'At least one client is required',
            'clients.min' => 'At least one client is required',
            'clients.max' => 'Maximum 10 clients allowed per project',
            'clients.*.exists' => 'Selected client does not exist',
            'manager_id.required' => 'Project manager is required',
            'manager_id.exists' => 'Selected project manager does not exist',
            'priority.required' => 'Priority is required',
            'priority.min' => 'Priority must be between 1 and 10',
            'priority.max' => 'Priority must be between 1 and 10',
            'status.enum' => 'Status must be one of: planning, analysis, design, implementation, testing, deployment',
            'start_date.required' => 'Start date is required',
            'end_date.after' => 'End date must be after start date',
            'currency.required_with' => 'Currency is required when budget is set',
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
