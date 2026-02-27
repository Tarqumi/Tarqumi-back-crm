<?php

namespace App\Policies;

use App\Models\ContactSubmission;
use App\Models\User;

class ContactSubmissionPolicy
{
    /**
     * Determine if user can view any submissions
     */
    public function viewAny(User $user): bool
    {
        // Only Super Admin, Admin, and CTO can view contact submissions
        return in_array($user->role, ['super_admin', 'admin']) 
            || ($user->role === 'founder' && $user->founder_sub_role === 'cto');
    }

    /**
     * Determine if user can view a submission
     */
    public function view(User $user, ContactSubmission $submission): bool
    {
        return $this->viewAny($user);
    }

    /**
     * Determine if user can update a submission
     */
    public function update(User $user, ContactSubmission $submission): bool
    {
        return in_array($user->role, ['super_admin', 'admin']);
    }

    /**
     * Determine if user can delete a submission
     */
    public function delete(User $user, ContactSubmission $submission): bool
    {
        return in_array($user->role, ['super_admin', 'admin']);
    }
}
