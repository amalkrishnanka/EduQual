<?php

namespace App\Policies;

use App\Models\Assessment;
use App\Models\User;

class AssessmentPolicy
{
    /**
     * Determine whether the user can view any assessments.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the assessment.
     */
    public function view(User $user, Assessment $assessment): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create assessments.
     */
    public function create(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isReviewer();
    }

    /**
     * Determine whether the user can update the assessment.
     * Only the owning reviewer can update, and only while it is still a draft.
     */
    public function update(User $user, Assessment $assessment): bool
    {
        return $user->id === $assessment->reviewer_id
            && $assessment->isDraft();
    }

    /**
     * Determine whether the user can submit the assessment.
     * Only the owning reviewer can submit, and only while it is still a draft.
     */
    public function submit(User $user, Assessment $assessment): bool
    {
        return $user->id === $assessment->reviewer_id
            && $assessment->isDraft();
    }

    /**
     * Determine whether the user can unlock the assessment.
     * Only super_admin can unlock a submitted/locked assessment.
     */
    public function unlock(User $user, Assessment $assessment): bool
    {
        return $user->isSuperAdmin();
    }
}
