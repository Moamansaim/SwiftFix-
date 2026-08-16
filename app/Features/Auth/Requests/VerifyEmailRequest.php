<?php

namespace App\Features\Auth\Requests;

use App\Features\Auth\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Http\FormRequest;

class VerifyEmailRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = User::find($this->route('id'));

        if (! $user) {
            return false;
        }

        if (! hash_equals((string) $user->getKey(), (string) $this->route('id'))) {
            return false;
        }

        if (! hash_equals(sha1($user->getEmailForVerification()), (string) $this->route('hash'))) {
            return false;
        }

        return true;
    }

    public function rules(): array
    {
        return [];
    }

    public function fulfill(): void
    {
        $user = User::findOrFail($this->route('id'));

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();

            event(new Verified($user));
        }
    }
}
