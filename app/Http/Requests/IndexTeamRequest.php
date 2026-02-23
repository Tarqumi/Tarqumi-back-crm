<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexTeamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->canManageTeam();
    }

    public function rules(): array
    {
        return [
            'page' => ['integer', 'min:1'],
            'per_page' => ['integer', Rule::in([10, 20, 50, 100])],
            'search' => ['nullable', 'string', 'max:255'],
            'role' => ['nullable', Rule::enum(UserRole::class)],
            'status' => ['nullable', Rule::in(['active', 'inactive'])],
            'department' => ['nullable', 'string', 'max:100'],
            'sort_by' => ['nullable', Rule::in(['name', 'email', 'role', 'created_at', 'last_active_at'])],
            'sort_order' => ['nullable', Rule::in(['asc', 'desc'])],
        ];
    }
}
