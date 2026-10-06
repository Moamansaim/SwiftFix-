<?php

namespace App\Features\UserSettings\Controllers;

use App\Features\UserSettings\Models\AccountDeletionReason;
use App\Features\UserSettings\Requests\DeleteAccountRequest;
use App\Features\UserSettings\Requests\UpdateProfileRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class UserSettingsController extends Controller
{
    /**
     * Get the authenticated user's profile information.
     */
    public function getProfile(): JsonResponse
    {
        $user = auth()->user();

        return response()->json([
            'data' => [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
            ],
        ]);
    }

    /**
     * Update the authenticated user's profile information.
     */
    public function updateProfile(
        UpdateProfileRequest $request
    ): JsonResponse {
        $user = auth()->user();

        $emailChanged = $user->email !== $request->email;

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;

        if ($emailChanged) {
            $user->email_verified_at = null;
        }

        $user->save();

        return response()->json([
            'message' => $emailChanged
                ? 'تم تحديث بيانات الحساب، يرجى إعادة تفعيل البريد الإلكتروني الجديد.'
                : 'تم تحديث بيانات الحساب بنجاح.',

            'data' => [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
            ],
        ]);
    }

    /**
     * Get the available account deletion reasons.
     */
    public function getDeletionReasons(): JsonResponse
    {
        $reasons = AccountDeletionReason::query()
            ->where('is_active', true)
            ->get();

        return response()->json([
            'data' => $reasons,
        ]);
    }


    /**
     * Get the available account deletion reasons => Admin.
     */
    public function getAllDeletionReasons(): JsonResponse
    {
        $reasons = AccountDeletionReason::query()
           ->get();

        return response()->json([
            'data' => $reasons,
        ]);
    }

    /**
     * Delete the authenticated user's account.
     */
    public function deleteAccount(
        DeleteAccountRequest $request
    ): JsonResponse {
        $user = auth()->user();

        DB::transaction(function () use ($user, $request) {

            $reasonIds = $request->input('reason_id');

            $reasons = AccountDeletionReason::query()
                ->whereIn('id', $reasonIds)
                ->where('is_active', true)
                ->get();

            if ($reasons->count() !== count($reasonIds)) {
                abort(422, 'أحد أسباب حذف الحساب غير صالح أو غير مفعّل.');
            }

            foreach ($reasons as $reason) {
                $reason->increment('deletion_count');
            }

            $user->tokens()->delete();

            $user->forceDelete();
        });

        return response()->json([
            'message' => 'تم حذف الحساب بنجاح.',
        ]);
    }

    /**
     * Toggle the activation status of an account deletion reason.
     */
    public function toggleDeletionReason(
        int $id
    ): JsonResponse {
        $reason = AccountDeletionReason::query()
            ->findOrFail($id);

        $reason->update([
            'is_active' => ! $reason->is_active,
        ]);

        return response()->json([
            'message' => $reason->is_active
                ? 'تم تفعيل سبب حذف الحساب بنجاح.'
                : 'تم إلغاء تفعيل سبب حذف الحساب بنجاح.',
            'data' => [
                'id' => $reason->id,
                'reason' => $reason->reason,
                'is_active' => $reason->is_active,
            ],
        ]);
    }
}