<?php

namespace App\Features\Auth\UseCases;

use App\Features\Auth\Interfaces\AuthRepositoryInterface;

/**
 * Use Case responsible for logging out the authenticated user.
 *
 * This class delegates the logout operation to the authentication
 * repository, keeping the business logic separated from the
 * data/access layer.
 */
class LogOutUser
{
    /**
     * Create a new LogOutUser instance.
     *
     * The repository is injected through dependency injection
     * and is used to perform the actual logout operation.
     *
     * @param AuthRepositoryInterface $authRepository
     *        Repository responsible for authentication-related operations.
     */
    public function __construct(
        private AuthRepositoryInterface $authRepository,
    ) {}

    /**
     * Log out the currently authenticated user.
     *
     * The actual logout operation is delegated to the
     * AuthRepository implementation.
     *
     * @return mixed
     *         Returns the result provided by the repository.
     */
    public function logout()
    {
        /*
         * Delegate the logout operation to the repository.
         *
         * The repository is responsible for retrieving the
         * authenticated user and deleting the current Sanctum token.
         */
        return $this->authRepository->logout();
    }
}