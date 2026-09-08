<?php

namespace App\Features\Auth\Controllers;

use App\Features\Auth\DTOs\ChangePasswordDTO;
use App\Features\Auth\DTOs\LoginUserDTO;
use App\Features\Auth\DTOs\RegisterUserDTO;
use App\Features\Auth\DTOs\ResetPasswordDTO;
use App\Features\Auth\DTOs\SendPasswordResetCodeDTO;
use App\Features\Auth\DTOs\SendVerificationEmailDTO;

use App\Features\Auth\Requests\ChangePasswordRequest;
use App\Features\Auth\Requests\LoginRequest;
use App\Features\Auth\Requests\RegisterRequest;
use App\Features\Auth\Requests\ResetPasswordRequest;
use App\Features\Auth\Requests\SendPasswordResetCodeRequest;
use App\Features\Auth\Requests\SendVerificationEmailRequest;
use App\Features\Auth\Requests\VerifyEmailRequest;

use App\Features\Auth\Resources\UserResource;

use App\Features\Auth\UseCases\ChangePasswordUser;
use App\Features\Auth\UseCases\LoginUser;
use App\Features\Auth\UseCases\LogOutUser;
use App\Features\Auth\UseCases\RegisterUser;
use App\Features\Auth\UseCases\ResetPasswordUser;
use App\Features\Auth\UseCases\SendPasswordResetCode;
use App\Features\Auth\UseCases\SendVerificationEmail;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * Injects the authentication-related use cases required
     * to handle registration, login, logout, password reset,
     * email verification, and password changes.
     */
    public function __construct(
        public RegisterUser $registerUser,
        public LoginUser $loginUser,
        public LogOutUser $logOutUser,
        public SendPasswordResetCode $sendPasswordResetCode,
        public ResetPasswordUser $resetPasswordUser,
        public SendVerificationEmail $sendVerificationEmail,
        public ChangePasswordUser $changePasswordUser
    ) {}

    /**
     * Register a new customer account.
     *
     * Validates the registration data through RegisterRequest,
     * converts the validated data into a DTO, and passes it
     * to the RegisterUser use case.
     *
     * @param RegisterRequest $registerRequest
     * @return JsonResponse
     */
    public function register(RegisterRequest $registerRequest): JsonResponse
    {
        // Create a DTO containing the validated registration data.
        $request = new RegisterUserDTO(
            $registerRequest->first_name,
            $registerRequest->last_name,
            $registerRequest->email,
            $registerRequest->phone_number,
            $registerRequest->password,
        );

        // Execute the user registration process.
        $this->registerUser->register($request);

        // Return a success response asking the user to verify their email.
        return response()->json([
            'message' => 'تم إنشاء الحساب بنجاح، يرجى التحقق من بريدك الإلكتروني لتفعيل الحساب',
        ], 201);
    }

    /**
     * Authenticate a user and create an access token.
     *
     * Validates the login credentials, passes them to the login
     * use case, and returns the authenticated user with their token.
     *
     * @param LoginRequest $loginRequest
     * @return JsonResponse
     */
    public function login(LoginRequest $loginRequest): JsonResponse
    {
        // Create a DTO containing the login credentials.
        $request = new LoginUserDTO(
            $loginRequest->email,
            $loginRequest->password,
            $loginRequest->remember_me
        );

        // Execute the login process.
        $result = $this->loginUser->login($request);

        // Return an error response if authentication fails.
        if (isset($result['error']) && $result['error'] === true) {
            return response()->json([
                'message' => $result['message'],
            ], 422);
        }

        // Return the authenticated user and access token.
        return response()->json([
            'message' => 'تم تسجيل الدخول بنجاح',
            'data' => new UserResource($result['user']),
            'token' => $result['token'],
        ], 200);
    }

    /**
     * Log out the currently authenticated user.
     *
     * Delegates the logout operation to the LogOutUser use case
     * and returns the appropriate response based on the result.
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        // Execute the logout process.
        $result = $this->logOutUser->logout();

        // Return an error response if the logout operation fails.
        if (isset($result['error']) && $result['error'] === true) {
            return response()->json([
                'message' => $result['message'],
            ], 401);
        }

        // Return a successful logout response.
        return response()->json([
            'message' => 'تم تسجيل الخروج بنجاح',
        ], 200);
    }

    /**
     * Send a password reset code to the user's email.
     *
     * Creates a DTO containing the user's email and passes it
     * to the password reset code use case.
     *
     * @param SendPasswordResetCodeRequest $sendPasswordResetCodeRequest
     * @return JsonResponse
     */
    public function sendPasswordResetCode(
        SendPasswordResetCodeRequest $sendPasswordResetCodeRequest
    ): JsonResponse {
        // Create a DTO containing the email address.
        $dto = new SendPasswordResetCodeDTO(
            $sendPasswordResetCodeRequest->email
        );

        // Generate and send the password reset code.
        $result = $this->sendPasswordResetCode->handle($dto);

        // Return an error response if sending the code fails.
        if (isset($result['error']) && $result['error'] === true) {
            return response()->json([
                'message' => $result['message'],
            ], 422);
        }

        // Return a successful response.
        return response()->json([
            'message' => $result['message'],
        ], 200);
    }

    /**
     * Reset the user's password using the verification code.
     *
     * Validates the email, reset code, and new password,
     * then delegates the password reset operation to the use case.
     *
     * @param ResetPasswordRequest $resetPasswordRequest
     * @return JsonResponse
     */
    public function resetPassword(
        ResetPasswordRequest $resetPasswordRequest
    ): JsonResponse {
        // Create a DTO containing the password reset information.
        $dto = new ResetPasswordDTO(
            $resetPasswordRequest->email,
            $resetPasswordRequest->code,
            $resetPasswordRequest->password,
        );

        // Execute the password reset process.
        $result = $this->resetPasswordUser->handle($dto);

        // Return an error response if the reset operation fails.
        if (isset($result['error']) && $result['error'] === true) {
            return response()->json([
                'message' => $result['message'],
            ], 422);
        }

        // Return a successful password reset response.
        return response()->json([
            'message' => $result['message'],
        ], 200);
    }

    /**
     * Send a verification email to the user.
     *
     * Creates a DTO containing the user's email and delegates
     * the email verification process to the use case.
     *
     * @param SendVerificationEmailRequest $request
     * @return JsonResponse
     */
    public function sendVerificationEmail(
        SendVerificationEmailRequest $request
    ): JsonResponse {
        // Create a DTO containing the user's email.
        $dto = new SendVerificationEmailDTO($request->email);

        // Send the email verification message.
        $result = $this->sendVerificationEmail->handle($dto);

        // Return an error response if sending the email fails.
        if (isset($result['error']) && $result['error'] === true) {
            return response()->json([
                'message' => $result['message'],
            ], 422);
        }

        // Return a successful response.
        return response()->json([
            'message' => $result['message'],
        ], 200);
    }

    /**
     * Verify the user's email address.
     *
     * Uses the VerifyEmailRequest to validate and complete
     * the email verification process.
     *
     * @param VerifyEmailRequest $request
     * @return JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function verifyEmail(VerifyEmailRequest $request)
    {
        // Attempt to verify the user's email address.
        $verified = $request->fulfill();

        // Redirect to the React application after successful verification.
        if ($verified) {
            return redirect()->away(
                'https://swiftfix-app-project.vercel.app/'
            );
        }

        // Return an error if the email was already verified.
        return response()->json([
            'message' => 'بريدك الإلكتروني مفعل من قبل.',
        ], 422);
    }


    /**
     * Resend the email verification message.
     *
     * Reuses the sendVerificationEmail method to avoid
     * duplicating the verification email logic.
     *
     * @param SendVerificationEmailRequest $request
     * @return JsonResponse
     */
    public function resendVerificationEmail(
        SendVerificationEmailRequest $request
    ): JsonResponse {
        // Reuse the existing verification email functionality.
        return $this->sendVerificationEmail($request);
    }

    /**
     * Change the password of the currently authenticated user.
     *
     * Creates a DTO containing the current and new passwords,
     * then delegates the operation to the ChangePasswordUser use case.
     *
     * @param ChangePasswordRequest $changePasswordRequest
     * @return JsonResponse
     */
    public function changePassword(
        ChangePasswordRequest $changePasswordRequest
    ): JsonResponse {
        // Create a DTO containing the current and new passwords.
        $dto = new ChangePasswordDTO(
            $changePasswordRequest->current_password,
            $changePasswordRequest->password,
        );

        // Execute the password change process.
        $result = $this->changePasswordUser->changePassword($dto);

        // Return an error if the current password is incorrect
        // or the password change operation fails.
        if (isset($result['error']) && $result['error'] === true) {
            return response()->json([
                'message' => $result['message'],
            ], 401);
        }

        // Return a successful password change response.
        return response()->json([
            'message' => 'تم تحديث كلمة المرور بنجاح',
        ], 200);
    }
}
