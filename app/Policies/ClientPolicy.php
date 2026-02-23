<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\User;

class ClientPolicy
{
    /**
     * Determine if the user can view any clients.
     */
    public function viewAny(User $user): bool
    {
        return $user->canViewClients();
    }

    /**
     * Determine if the user can view the client.
     */
    public function view(User $user, Client $client): bool
    {
        return $user->canViewClients();
    }

    /**
     * Determine if the user can create clients.
     */
    public function create(User $user): bool
    {
        return $user->canManageClients();
    }

    /**
     * Determine if the user can update the client.
     */
    public function update(User $user, Client $client): bool
    {
        return $user->canManageClients();
    }

    /**
     * Determine if the user can delete the client.
     * CRITICAL: Default "Tarqumi" client CANNOT be deleted (business rule).
     */
    public function delete(User $user, Client $client): bool
    {
        if ($client->is_default) {
            return false; // Default client cannot be deleted by anyone
        }

        return $user->canManageClients();
    }

    /**
     * Determine if the user can restore the client.
     */
    public function restore(User $user, Client $client): bool
    {
        return $user->canManageClients();
    }

    /**
     * Determine if the user can permanently delete the client.
     */
    public function forceDelete(User $user, Client $client): bool
    {
        if ($client->is_default) {
            return false; // Default client cannot be force deleted
        }

        return $user->canManageClients();
    }
}
