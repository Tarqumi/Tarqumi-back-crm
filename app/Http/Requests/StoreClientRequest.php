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
            'name' => ['required', 'string', 'min:2', 'max:80'],
            'email' => ['required', 'email', 'max:100', 'unique:clients,email'],
            'company_name' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:200'],
            'website' => ['nullable', 'url', 'max:150'],
            'industry' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Client name is required',
            'name.min' => 'Client name must be at least 2 characters',
            'name.max' => 'Client name cannot exceed 80 characters',
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'email.max' => 'Email cannot exceed 100 characters',
            'email.unique' => 'A client with this email already exists',
            'company_name.max' => 'Company name cannot exceed 100 characters',
            'address.max' => 'Address cannot exceed 200 characters',
            'website.url' => 'Please enter a valid URL',
            'website.max' => 'Website URL cannot exceed 150 characters',
            'notes.max' => 'Notes cannot exceed 1000 characters',
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

        // Normalize email to lowercase
        if ($this->has('email')) {
            $this->merge([
                'email' => strtolower($this->email),
            ]);
        }

        // Sanitize company_name to prevent XSS
        if ($this->has('company_name')) {
            $this->merge([
                'company_name' => strip_tags($this->company_name),
            ]);
        }

        // Sanitize notes to prevent XSS
        if ($this->has('notes')) {
            $this->merge([
                'notes' => strip_tags($this->notes),
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
