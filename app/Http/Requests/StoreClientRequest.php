<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->canManageClients();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:clients,email'],
            'company_name' => ['nullable', 'string', 'max:150'],
            'phone' => ['nullable', 'string', 'max:20'],
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'website' => ['nullable', 'url', 'max:255'],
            'industry' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'is_active' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Client name is required',
            'name.min' => 'Client name must be at least 2 characters',
            'name.max' => 'Client name cannot exceed 100 characters',
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'A client with this email already exists',
            'company_name.max' => 'Company name cannot exceed 150 characters',
            'website.url' => 'Please enter a valid URL',
            'notes.max' => 'Notes cannot exceed 5000 characters',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Normalize email to lowercase
        if ($this->has('email')) {
            $this->merge([
                'email' => strtolower($this->email),
            ]);
        }

        // Add https:// to website if missing
        if ($this->has('website') && $this->website && !preg_match('/^https?:\/\//', $this->website)) {
            $this->merge([
                'website' => 'https://' . $this->website,
            ]);
        }
    }
}
