<?php

namespace App\Features\Auth\UseCases;

use App\Features\Auth\DTOs\LoginUserDTO;
use App\Features\Auth\Interfaces\AuthRepositoryInterface;
use Illuminate\Support\Facades\Hash;

/**
 * Use Case responsible for handling the user login process.
 *
 * This class contains the business logic required to authenticate
 * a user and generate a Sanctum access token after successful login.
 */
class LoginUser
{
    /**
     * Create a new LoginUser instance.
     *
     * The AuthRepositoryInterface is injected through dependency
     * injection to retrieve the user from the data source.
     *
     * @param AuthRepositoryInterface $authRepository
     *        Repository responsible for authentication-related
     *        database operations.
     */
    public function __construct(
        private AuthRepositoryInterface $authRepository,
    ) {}

    /**
     * Authenticate a user and generate an access token.
     *
     * The login process performs the following steps:
     * - Retrieves the user using the provided email.
     * - Verifies the provided password.
     * - Checks whether the user's email has been verified.
     * - Determines the token expiration based on remember_me.
     * - Creates a Sanctum access token.
     *
     * @param LoginUserDTO $loginUserDTO
     *        Contains the user's email, password, and remember_me option.
     *
     * @return array<string, mixed>
     *         Returns an error array when authentication fails,
     *         or the authenticated user and access token on success.
     */
    public function login(LoginUserDTO $loginUserDTO)
    {
        /*
         * Retrieve the user from the repository using
         * the email provided in the login request.
         */
        $user = $this->authRepository->login($loginUserDTO);

        /*
         * Verify that the user exists and that the provided
         * password matches the hashed password stored in the database.
         *
         * Hash::check() compares the plain-text password received
         * from the user with the hashed password stored on the User model.
         */
        if (! $user || ! Hash::check($loginUserDTO->password, $user->password)) {
            return [
                'error' => true,
                'message' => 'بيانات تسجيل الدخول غير صحيحة',
            ];
        }

        /*
         * Ensure that the user has verified their email address
         * before allowing them to log in.
         */
        if (! $user->hasVerifiedEmail()) {
            return [
                'error' => true,
                'message' => 'يرجى تفعيل البريد الإلكتروني قبل تسجيل الدخول',
            ];
        }

        /*
         * Determine the access token expiration time.
         *
         * If remember_me is enabled:
         * The token remains valid for 30 days.
         *
         * Otherwise:
         * The token remains valid for 2 hours.
         */
        $expiresAt = $loginUserDTO->remember_me
            ? now()->addDays(30)
            : now()->addHours(2);

        /*
         * Create a new Laravel Sanctum personal access token.
         *
         * 'auth-token':
         * The name assigned to the token.
         *
         * []:
         * No specific abilities/scopes are assigned to the token.
         *
         * $expiresAt:
         * Defines when the token should expire.
         */
        $token = $user->createToken(
            'auth-token',
            [],
            $expiresAt
        )->plainTextToken;

        /*
         * Return the authenticated user and the generated
         * plain-text access token to the controller.
         */
        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}