<?php

namespace App\Policies;

use App\Models\MySql\Transfer;
use App\Models\MySql\User;
use App\Models\MySql\Role;
use Illuminate\Auth\Access\Response;

class TransferPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role_id, [Role::ADMIN, Role::MANAGER, Role::DRIVER]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Transfer $transfer): bool
    {
        return in_array($user->role_id, [Role::ADMIN, Role::MANAGER, Role::DRIVER]);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role_id, [Role::ADMIN, Role::MANAGER, Role::DRIVER]);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Transfer $transfer): bool
    {
        return in_array($user->role_id, [Role::ADMIN, Role::MANAGER, Role::DRIVER]);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Transfer $transfer): bool
    {
        return in_array($user->role_id, [Role::ADMIN, Role::MANAGER]);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Transfer $transfer): bool
    {
        return in_array($user->role_id, [Role::ADMIN, Role::MANAGER]);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Transfer $transfer): bool
    {
        return in_array($user->role_id, [Role::ADMIN, Role::MANAGER]);
    }
}
