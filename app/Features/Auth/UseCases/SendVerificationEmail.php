<?php

namespace App\Features\Auth\UseCases;

use App\Features\Auth\DTOs\SendVerificationEmailDTO;
use App\Features\Auth\Interfaces\AuthRepositoryInterface;

/**
 * Use Case responsible for sending an email verification link
 * to a registered user.
 *
 * This class handles the business logic of finding the user,
 * checking whether their email has already been verified,
 * and sending the verification notification when necessary.
 */
class SendVerificationEmail
{
    /**
     * Create a new SendVerificationEmail instance.
     *
     * The AuthRepositoryInterface is injected through dependency
     * injection and is used to retrieve the user by email.
     *
     * @param AuthRepositoryInterface $authRepository
     *        Repository responsible for authentication-related
     *        user data operations.
     */
    public function __construct(
        private AuthRepositoryInterface $authRepository,
    ) {}

    /**
     * Send an email verification notification to the user.
     *
     * The process performs the following steps:
     * - Finds the user by their email address.
     * - Returns an error if the user does not exist.
     * - Checks whether the user's email is already verified.
     * - Sends the verification notification if the email is not verified.
     * - Returns an appropriate result based on the verification status.
     *
     * @param SendVerificationEmailDTO $sendVerificationEmailDTO
     *        Contains the user's email address.
     *
     * @return array<string, mixed>
     *         Returns an error or success response depending on
     *         the user's existence and email verification status.
     */
    public function handle(
        SendVerificationEmailDTO $sendVerificationEmailDTO
    ): array {
        /*
         * Find the user associated with the provided email address.
         */
        $user = $this->authRepository->findByEmail(
            $sendVerificationEmailDTO->email
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
         * Check whether the user's email has already been verified.
         *
         * If the email has not been verified yet, send
         * Laravel's email verification notification.
         */
        if (! $user->hasVerifiedEmail()) {

            /*
             * Send the custom email verification notification
             * defined in the User model.
             */
            $user->sendEmailVerificationNotification();

            /*
             * Return a success response after sending
             * the verification notification.
             */
            return [
                'success' => true,
                'message' => 'إذا كان البريد الإلكتروني مسجلاً وغير مفعّل، ستصلك رسالة تحتوي على رابط التفعيل',
            ];
        }

        /*
         * The user's email is already verified,
         * so there is no need to send another verification email.
         */
        return [
            'error' => true,
            'message' => 'بريدك الإلكتروني مفعل من قبل؟',
        ];
    }
}