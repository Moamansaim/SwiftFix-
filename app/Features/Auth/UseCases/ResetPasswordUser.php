<?php

namespace App\Features\Auth\UseCases;

use App\Features\Auth\DTOs\ResetPasswordDTO;
use App\Features\Auth\Interfaces\AuthRepositoryInterface;
use Illuminate\Support\Facades\Hash;

/**
 * Use Case responsible for resetting a user's password.
 *
 * This class handles the business logic required to:
 * - Find the user by email.
 * - Validate the password reset code.
 * - Check whether the reset code has expired.
 * - Delegate the password update to the repository.
 */
class ResetPasswordUser
{
    /**
     * Create a new ResetPasswordUser instance.
     *
     * The AuthRepositoryInterface is injected through dependency
     * injection to retrieve the user and update their password.
     *
     * @param AuthRepositoryInterface $authRepository
     *        Repository responsible for authentication-related
     *        database operations.
     */
    public function __construct(
        private AuthRepositoryInterface $authRepository,
    ) {}

    /**
     * Reset the user's password.
     *
     * The process performs the following steps:
     * - Finds the user using the provided email.
     * - Verifies the reset code against the hashed code stored in the database.
     * - Checks whether the reset code has expired.
     * - Resets the password through the repository.
     *
     * @param ResetPasswordDTO $resetPasswordDTO
     *        Contains the user's email, reset code, and new password.
     *
     * @return array<string, mixed>
     *         Returns an error response when the user or code is invalid,
     *         or a success response after the password has been reset.
     */
    public function handle(ResetPasswordDTO $resetPasswordDTO): array
    {
        /*
         * Find the user associated with the provided email address.
         */
        $user = $this->authRepository->findByEmail(
            $resetPasswordDTO->email
        );

        /*
         * If no user is found with the provided email,
         * return an error response.
         */
        if (! $user) {
            return [
                'error' => true,
                'message' => 'عذرا , البريد الإلكتروني غير موجود',
            ];
        }

        /*
         * Verify the reset code and its expiration time.
         *
         * Hash::check():
         * Compares the code entered by the user with the
         * hashed reset code stored in the database.
         *
         * now()->isAfter():
         * Checks whether the current time is after the
         * expiration time stored for the reset code.
         *
         * If either condition fails, the code is considered invalid.
         */
        if (
            ! Hash::check($resetPasswordDTO->code, $user->code)
            || now()->isAfter($user->code_expires_at)
        ) {
            return [
                'error' => true,
                'message' => 'الكود غير صالح',
            ];
        }

        /*
         * Delegate the password reset operation to the repository.
         *
         * The repository is responsible for:
         * - Hashing the new password.
         * - Updating the user's password.
         * - Clearing the reset code.
         * - Clearing the reset code expiration time.
         */
        $this->authRepository->resetPassword(
            $resetPasswordDTO,
            $user
        );

        /*
         * Return a successful response after the password
         * has been reset successfully.
         */
        return [
            'success' => true,
            'message' => 'تم إعادة تعيين كلمة المرور بنجاح',
        ];
    }
}