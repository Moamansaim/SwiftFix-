<?php

namespace App\Features\Contact\Policies;

use App\Features\Contact\Models\ContactMessage;
use App\Features\Auth\Models\User;

class ContactMessagePolicy
{
    /**
     * Determine whether the user can view all contact messages.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('عرض رسائل التواصل');
    }

    /**
     * Determine whether the user can reply to a contact message.
     */
    public function reply(
        User $user,
        ContactMessage $contactMessage
    ): bool {
        return $user->can('الرد على رسائل التواصل');
    }

    /**
     * Determine whether the user can delete a contact message.
     */
    public function delete(
        User $user,
        ContactMessage $contactMessage
    ): bool {
        return $user->can('حذف رسائل التواصل');
    }
}