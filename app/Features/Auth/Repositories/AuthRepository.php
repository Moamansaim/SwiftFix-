<?php

namespace App\Features\Auth\Repositories;

use App\Features\Auth\DTOs\ChangePasswordDTO;
use App\Features\Auth\DTOs\LoginUserDTO;
use App\Features\Auth\DTOs\RegisterUserDTO;
use App\Features\Auth\DTOs\ResetPasswordDTO;
use App\Features\Auth\DTOs\SendPasswordResetCodeDTO;
use App\Features\Auth\Interfaces\AuthRepositoryInterface;
use App\Features\Auth\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Authentication repository.
 *
 * Handles database operations related to user authentication,
 * registration, password reset, password changes, and logout.
 */
class AuthRepository implements AuthRepositoryInterface
{
    /**
     * Create a new user account.
     *
     * Creates a user using the data provided by the registration DTO,
     * saves the user to the database, and sends an email verification
     * notification to the newly registered user.
     *
     * @param RegisterUserDTO $registerUserDTO Registration data.
     * @return User The newly created user.
     */
    public function create(RegisterUserDTO $registerUserDTO): User
    {
        // Create a new user instance.
        $user = new User;

        // Assign the registration data to the user.
        $user->first_name = $registerUserDTO->first_name;
        $user->last_name = $registerUserDTO->last_name;
        $user->email = $registerUserDTO->email;
        $user->phone_number = $registerUserDTO->phone_number;

        // Hash the user's password before storing it in the database.
        $user->password = Hash::make($registerUserDTO->password);

        // Save the new user to the database.
        $user->save();

        //  Assign customer role
        $user->assignRole('customer');

        // Send the email verification notification.
        $user->sendEmailVerificationNotification();

        // Return the newly created user.
        return $user;
    }

    /**
     * Find a user by their email address during login.
     *
     * Retrieves the user associated with the provided email.
     * Returns null if no user is found.
     *
     * @param LoginUserDTO $loginUserDTO Login credentials.
     * @return User|null The user if found, otherwise null.
     */
    public function login(LoginUserDTO $loginUserDTO): ?User
    {
        // Find the user using their email address.
        return User::where('email', $loginUserDTO->email)->first();
    }

    /**
     * Log out the currently authenticated user.
     *
     * Retrieves the authenticated user using the Sanctum guard
     * and deletes their current access token.
     *
     * @return array|null Error information if the user is not authenticated,
     *                    otherwise null after successful logout.
     */
    public function logout()
    {
        // Get the currently authenticated user using Sanctum.
        $user = Auth::guard('sanctum')->user();

        // Return an error if no authenticated user was found.
        if (! $user) {
            return [
                'error' => true,
                'message' => 'المستخدم غير موجود',
            ];
        }

        // Delete the current Sanctum access token.
        $user->currentAccessToken()->delete();
    }

    /**
     * Find a user by email for the password reset process.
     *
     * Retrieves the user who requested a password reset using
     * the email address provided in the DTO.
     *
     * @param SendPasswordResetCodeDTO $sendPasswordResetCodeDTO Password reset request data.
     * @return User|null The user if found, otherwise null.
     */
    public function findUserByEmailForResetCode(
        SendPasswordResetCodeDTO $sendPasswordResetCodeDTO
    ): ?User {
        // Find the user by their email address.
        return User::where('email', $sendPasswordResetCodeDTO->email)->first();
    }

    /**
     * Find a user by their email address.
     *
     * Returns the user associated with the given email.
     *
     * @param string $email User's email address.
     * @return User|null The user if found, otherwise null.
     */
    public function findByEmail(string $email): ?User
    {
        // Find and return the user by email.
        return User::where('email', $email)->first();
    }

    /**
     * Reset the user's password.
     *
     * Updates the user's password and clears the password reset
     * verification code and its expiration time.
     *
     * @param ResetPasswordDTO $resetPasswordDTO Password reset data.
     * @param User $user The user whose password will be reset.
     * @return void
     */
    public function resetPassword(
        ResetPasswordDTO $resetPasswordDTO,
        User $user
    ): void {
        // Hash and update the user's new password.
        $user->password = Hash::make($resetPasswordDTO->password);

        // Clear the password reset code after successful reset.
        $user->code = null;

        // Clear the password reset code expiration time.
        $user->code_expires_at = null;

        // Save the changes to the database.
        $user->save();
    }

    /**
     * Change the authenticated user's password.
     *
     * Updates the user's password with the new password
     * provided in the DTO.
     *
     * @param ChangePasswordDTO $changePasswordDTO Password change data.
     * @param User $user The authenticated user.
     * @return void
     */
    public function changePassword(
        ChangePasswordDTO $changePasswordDTO,
        User $user
    ): void {
        // Hash and update the user's new password.
        $user->password = Hash::make($changePasswordDTO->password);

        // Save the updated password to the database.
        $user->save();
    }
}