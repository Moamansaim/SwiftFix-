<?php

namespace App\Features\Auth\UseCases;

use App\Features\Auth\DTOs\ChangePasswordDTO;
use App\Features\Auth\Interfaces\AuthRepositoryInterface;
use App\Features\Auth\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * Use Case responsible for changing the authenticated user's password.
 *
 * This class contains the business logic for the password change operation
 * and delegates the actual password update to the AuthRepository.
 */
class ChangePasswordUser
{
    /**
     * Create a new ChangePasswordUser instance.
     *
     * The AuthRepositoryInterface is injected through dependency injection
     * to separate the business logic from the data access layer.
     *
     * @param AuthRepositoryInterface $authRepository
     *        Repository responsible for user authentication-related operations.
     */
    public function __construct(
        private AuthRepositoryInterface $authRepository,
    ) {}

    /**
     * Change the password of the currently authenticated user.
     *
     * The method:
     * - Retrieves the authenticated user through the Sanctum guard.
     * - Ensures that the authenticated user is an instance of User.
     * - Returns an authorization error if no valid authenticated user exists.
     * - Delegates the password update to the AuthRepository.
     *
     * @param ChangePasswordDTO $changePasswordDTO
     *        Contains the current password and the new password.
     *
     * @return array<string, mixed>|null
     *         Returns an error array when the user is not authenticated.
     *         Otherwise, the repository performs the password update.
     */
    public function changePassword(ChangePasswordDTO $changePasswordDTO)
    {
        /*
         * Retrieve the currently authenticated user using
         * Laravel Sanctum authentication.
         */
        $user = Auth::guard('sanctum')->user();

        /*
         * Ensure that the authenticated user is a valid User model.
         *
         * If no user is authenticated, return an authorization error.
         */
        if (! $user instanceof User) {
            return [
                'error' => true,
                'message' => 'غير مصرح لك بالوصول. يرجى تسجيل الدخول أولاً.',
            ];
        }

        /*
         * Delegate the actual password update to the repository.
         *
         * The repository is responsible for updating the user's
         * password in the database.
         */
        $this->authRepository->changePassword(
            $changePasswordDTO,
            $user
        );
    }
}