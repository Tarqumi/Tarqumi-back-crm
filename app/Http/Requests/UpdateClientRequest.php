<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->canManageClients();
    }

    public function rules(): array
    {
        $clientId = $this->route('client')->id;
        $client = $this->route('client');

        return [
            'name' => [
                'sometimes',
                'string',
                'min:2',
                'max:100',
                // Default client name cannot be changed
                function ($attribute, $value, $fail) use ($client) {
                    if ($client->is_default && $value !== $client->name) {
                        $fail('Default client name cannot be changed');
                    }
                },
            ],
            'email' => [
                'sometimes',
                'email',
                'max:255',
                Rule::unique('clients', 'email')->ignore($clientId),
                // Default client email cannot be changed
                function ($attribute, $value, $fail) use ($client) {
                    if ($client->is_default && $value !== $client->email) {
                        $fail('Default client email cannot be changed');
                    }
                },
            ],
            'company_name' => ['nullable', 'string', 'max:150'],
            'phone' => ['nullable', 'string', 'max:20'],
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'website' => ['nullable', 'url', 'max:255'],
            'industry' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'is_active' => [
                'boolean',
                // Default client must remain active
                function ($attribute, $value, $fail) use ($client) {
                    if ($client->is_default && !$value) {
                        $fail('Default client must remain active');
                    }
                },
            ],
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
