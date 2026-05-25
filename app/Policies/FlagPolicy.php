<?php

namespace App\Policies;

use App\Models\Flag;
use App\Models\User;

class FlagPolicy
{
    /**
     * Determine whether the user can view any flags.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the flag.
     */
    public function view(User $user, Flag $flag): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create flags.
     */
    public function create(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isReviewer();
    }

    /**
     * Determine whether the user can update the flag's status.
     */
    public function updateStatus(User $user, Flag $flag): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can add a comment to the flag.
     */
    public function addComment(User $user, Flag $flag): bool
    {
        return true;
    }
}
