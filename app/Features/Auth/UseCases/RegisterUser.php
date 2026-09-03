<?php

namespace App\Features\Auth\UseCases;

use App\Features\Auth\DTOs\RegisterUserDTO;
use App\Features\Auth\Interfaces\AuthRepositoryInterface;

/**
 * Use Case responsible for handling the user registration process.
 *
 * This class delegates the creation of the new user to the
 * authentication repository.
 */
class RegisterUser
{
    /**
     * Create a new RegisterUser instance.
     *
     * The AuthRepositoryInterface is injected through dependency
     * injection and is used to perform the user creation operation.
     *
     * @param AuthRepositoryInterface $authRepository
     *        Repository responsible for authentication-related
     *        database operations.
     */
    public function __construct(
        private AuthRepositoryInterface $authRepository,
    ) {}

    /**
     * Register a new user.
     *
     * The registration data is passed to the repository, which is
     * responsible for creating and saving the user in the database.
     *
     * @param RegisterUserDTO $registerUserDTO
     *        Contains the data required to create the new user.
     *
     * @return void
     */
    public function register(RegisterUserDTO $registerUserDTO)
    {
        /*
         * Delegate the user creation operation to the repository.
         *
         * The repository handles:
         * - Creating the User model.
         * - Assigning the registration data.
         * - Hashing the password.
         * - Saving the user.
         * - Sending the email verification notification.
         */
        $this->authRepository->create($registerUserDTO);
    }
}