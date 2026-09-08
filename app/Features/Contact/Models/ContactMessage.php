<?php

namespace App\Features\Contact\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * ContactMessage Model
 *
 * Represents a contact message submitted by a user through
 * the application's contact form.
 *
 * This model is responsible for interacting with the
 * contact_messages database table through Laravel's
 * Eloquent ORM.
 */
class ContactMessage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * These fields are allowed to be assigned using
     * Eloquent's mass assignment methods such as:
     *
     * ContactMessage::create([...])
     *
     * Only fields listed here can be assigned through
     * mass assignment.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'email',
        'subject',
        'message',
        'status'
    ];
}