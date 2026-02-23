<?php

namespace App\Http\Requests;

use App\Enums\FounderRole;
use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTeamMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->route('user');
        
        // Only Super Admin can edit Super Admin accounts
        if ($user->role === UserRole::SUPER_ADMIN && $this->user()->role !== UserRole::SUPER_ADMIN) {
            return false;
        }
        
        // Admin cannot escalate own privileges
        if ($user->id === $this->user()->id && $this->role === UserRole::SUPER_ADMIN->value) {
            return false;
        }
        
        return $this->user()->canEditTeamMember();
    }

    public function rules(): array
    {
        $userId = $this->route('user')->id;
        
        return [
            'name' => ['sometimes', 'string', 'min:2', 'max:100'],
            'email' => ['sometimes', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'password' => ['sometimes', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'department' => ['nullable', 'string', 'max:100'],
            'job_title' => ['nullable', 'string', 'max:100'],
            'role' => ['sometimes', Rule::enum(UserRole::class)],
            'founder_role' => ['nullable', 'required_if:role,founder', Rule::enum(FounderRole::class)],
            'is_active' => ['boolean'],
            'profile_picture' => ['nullable', 'image', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.min' => 'Name must be at least 2 characters',
            'name.max' => 'Name cannot exceed 100 characters',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email is already registered',
            'password.min' => 'Password must be at least 8 characters',
            'password.confirmed' => 'Password confirmation does not match',
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
