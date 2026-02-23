<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class DeleteTeamMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->route('user');
        
        // Only Super Admin can delete Super Admin accounts
        if ($user->role === UserRole::SUPER_ADMIN && $this->user()->role !== UserRole::SUPER_ADMIN) {
            return false;
        }
        
        // Cannot delete last Super Admin
        if ($user->role === UserRole::SUPER_ADMIN) {
            $superAdminCount = User::where('role', UserRole::SUPER_ADMIN)->count();
            if ($superAdminCount <= 1) {
                return false;
            }
        }
        
        return $this->user()->canDeleteTeamMember();
    }

    public function rules(): array
    {
        return [
            'new_manager_id' => ['nullable', 'exists:users,id'],
        ];
    }
}
