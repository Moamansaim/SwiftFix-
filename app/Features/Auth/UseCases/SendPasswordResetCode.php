<?php

namespace App\Features\Auth\UseCases;

use App\Features\Auth\DTOs\SendPasswordResetCodeDTO;
use App\Features\Auth\Interfaces\AuthRepositoryInterface;
use App\Features\Auth\Interfaces\SendEmailInterface;

/**
 * Use Case responsible for sending a password reset code
 * to a user's email address.
 *
 * This class handles the business logic of finding the user
 * by email and delegating the process of sending the reset code
 * to the email service.
 */
class SendPasswordResetCode
{
    /**
     * Create a new SendPasswordResetCode instance.
     *
     * Two interfaces are injected through dependency injection:
     *
     * - AuthRepositoryInterface:
     *   Responsible for finding the user by email.
     *
     * - SendEmailInterface:
     *   Responsible for generating and sending the password
     *   reset code to the user's email.
     *
     * @param AuthRepositoryInterface $authRepository
     *        Repository responsible for authentication-related
     *        user data operations.
     *
     * @param SendEmailInterface $sendEmailRepository
     *        Service responsible for sending the password reset code.
     */
    public function __construct(
        private AuthRepositoryInterface $authRepository,
        private SendEmailInterface $sendEmailRepository,
    ) {}

    /**
     * Handle the password reset code request.
     *
     * The process performs the following steps:
     * - Finds the user using the provided email.
     * - Returns an error if the user does not exist.
     * - Sends a password reset code to the user's email.
     * - Returns a success response after sending the code.
     *
     * @param SendPasswordResetCodeDTO $sendPasswordResetCodeDTO
     *        Contains the email address for which the reset code
     *        should be generated and sent.
     *
     * @return array<string, mixed>
     *         Returns an error response when the user is not found,
     *         or a success response after sending the reset code.
     */
    public function handle(
        SendPasswordResetCodeDTO $sendPasswordResetCodeDTO
    ): array {
        /*
         * Find the user associated with the provided email address.
         */
        $user = $this->authRepository->findUserByEmailForResetCode(
            $sendPasswordResetCodeDTO
        );

        /*
         * If no user is found with the provided email,
         * return an error response.
         */
        if (! $user) {
            return [
                'error' => true,
                'message' => 'عذراً , البريد الإلكتروني غير موجود',
            ];
        }

        /*
         * Delegate the process of generating and sending
         * the reset code to the email service.
         *
         * The SendEmailRepository is responsible for:
         * - Generating the reset code.
         * - Sending the code by email.
         * - Hashing the code.
         * - Storing the hashed code.
         * - Setting the expiration time.
         */
        $this->sendEmailRepository->sendResetCodeToEmail($user);

        /*
         * Return a successful response after the reset code
         * has been sent.
         */
        return [
            'success' => true,
            'message' => 'إذا كان البريد الإلكتروني مسجلاً، ستصلك رسالة تحتوي على كود إعادة التعيين',
        ];
    }
}