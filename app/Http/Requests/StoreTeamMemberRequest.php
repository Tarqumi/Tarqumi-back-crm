<?php

namespace App\Http\Requests;

use App\Enums\FounderRole;
use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTeamMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->canCreateTeamMember();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'department' => ['nullable', 'string', 'max:100'],
            'job_title' => ['nullable', 'string', 'max:100'],
            'role' => ['required', Rule::enum(UserRole::class)],
            'founder_role' => ['nullable', 'required_if:role,founder', Rule::enum(FounderRole::class)],
            'is_active' => ['boolean'],
            'profile_picture' => ['nullable', 'image', 'max:5120'], // 5MB
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
            'email.unique' => 'This email is already registered',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'password.confirmed' => 'Password confirmation does not match',
            'role.required' => 'Role is required',
            'founder_role.required_if' => 'Founder role is required when role is founder',
            'profile_picture.image' => 'Profile picture must be an image',
            'profile_picture.max' => 'Profile picture cannot exceed 5MB',
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
    }
}
