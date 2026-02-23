<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->canViewClients();
    }

    public function rules(): array
    {
        return [
            'page' => ['integer', 'min:1'],
            'per_page' => ['integer', Rule::in([10, 20, 50, 100])],
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', Rule::in(['active', 'inactive'])],
            'industry' => ['nullable', 'string', 'max:100'],
            'has_projects' => ['nullable', 'in:true,false,1,0'],
            'sort_by' => ['nullable', Rule::in(['name', 'company_name', 'email', 'created_at'])],
            'sort_order' => ['nullable', Rule::in(['asc', 'desc'])],
        ];
    }
}
