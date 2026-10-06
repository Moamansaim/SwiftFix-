<?php

namespace App\Features\Complaint\Policies;   

use App\Features\Auth\Models\User;
use App\Features\Complaint\Models\Complaint;

class ComplaintPolicy
{
    /**
     * Determine whether the user can view all complaints.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('عرض الشكاوى');
    }

    /**
     * Determine whether the user can reply to a complaint.
     */
    public function reply(User $user, Complaint $complaint): bool
    {
        return $user->can('الرد على الشكاوى');
    }

    /**
     * Determine whether the user can delete a complaint.
     */
    public function delete(User $user, Complaint $complaint): bool
    {
        return $user->can('حذف الشكاوى');
    }
}