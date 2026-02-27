<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Public endpoint
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'subject' => ['nullable', 'string', 'max:150'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
            'privacy_accepted' => ['required', 'accepted'],
            'language' => ['required', 'in:ar,en'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'name.min' => 'Name must be at least 2 characters',
            'name.max' => 'Name cannot exceed 100 characters',
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'phone.max' => 'Phone number cannot exceed 20 characters',
            'subject.max' => 'Subject cannot exceed 150 characters',
            'message.required' => 'Message is required',
            'message.min' => 'Message must be at least 10 characters',
            'message.max' => 'Message cannot exceed 2000 characters',
            'privacy_accepted.required' => 'You must accept the privacy policy',
            'privacy_accepted.accepted' => 'You must accept the privacy policy',
            'language.required' => 'Language is required',
            'language.in' => 'Language must be either ar or en',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Sanitize inputs
        $this->merge([
            'name' => strip_tags($this->name ?? ''),
            'email' => strtolower(trim($this->email ?? '')),
            'phone' => $this->phone ? preg_replace('/[^0-9+\-() ]/', '', $this->phone) : null,
            'subject' => strip_tags($this->subject ?? ''),
            'message' => strip_tags($this->message ?? ''),
        ]);
    }
}
