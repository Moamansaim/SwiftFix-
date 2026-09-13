<?php

namespace App\Features\Auth\Interfaces;

use App\Features\Auth\DTOs\ChangePasswordDTO;
use App\Features\Auth\DTOs\LoginUserDTO;
use App\Features\Auth\DTOs\RegisterUserDTO;
use App\Features\Auth\DTOs\ResetPasswordDTO;
use App\Features\Auth\DTOs\SendPasswordResetCodeDTO;
use App\Features\Auth\Models\User;

interface AuthRepositoryInterface
{
    /**
     * Create a new user account.
     *
     * Receives the registration data through a DTO and
     * returns the newly created user.
     *
     * @param RegisterUserDTO $registerUserDTO
     * @return User
     */
    public function create(RegisterUserDTO $registerUserDTO): User;

    /**
     * Authenticate a user using their login credentials.
     *
     * Returns the authenticated user if the credentials
     * are valid, otherwise returns null.
     *
     * @param LoginUserDTO $loginUserDTO
     * @return User|null
     */
    public function login(LoginUserDTO $loginUserDTO): ?User;

    /**
     * Log out the currently authenticated user.
     *
     * Processes the logout operation and returns the authenticated
     * user's information and token as part of the result.
     *
     * @return array
     */
    public function logout();


    /**
     * Find a user by email for the password reset process.
     *
     * Used to locate the user who requested a password reset code.
     *
     * @param SendPasswordResetCodeDTO $sendPasswordResetCodeDTO
     * @return User|null
     */
    public function findUserByEmailForResetCode(
        SendPasswordResetCodeDTO $sendPasswordResetCodeDTO
    ): ?User;

    /**
     * Find a user by their email address.
     *
     * Returns the user if an account with the given email exists,
     * otherwise returns null.
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User;

    /**
     * Reset the user's password.
     *
     * Updates the user's password using the data provided
     * by the password reset DTO.
     *
     * @param ResetPasswordDTO $resetPasswordDTO
     * @param User $user
     * @return void
     */
    public function resetPassword(
        ResetPasswordDTO $resetPasswordDTO,
        User $user
    ): void;

    /**
     * Change the password of an authenticated user.
     *
     * Updates the user's password after verifying the
     * current password.
     *
     * @param ChangePasswordDTO $changePasswordDTO
     * @param User $user
     * @return void
     */
    public function changePassword(
        ChangePasswordDTO $changePasswordDTO,
        User $user
    ): void;
}