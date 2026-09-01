<?php

namespace App\Features\ShopOwner\Controllers;

use App\Features\ShopOwner\DTOs\ProfileShopOwnerDTO;
use App\Features\ShopOwner\DTOs\ShopOwnerVerificationsDTO;
use App\Features\ShopOwner\DTOs\ApproveShopOwnerVerificationDTO;
use App\Features\ShopOwner\Models\Shop;
use App\Features\ShopOwner\Requests\ProfileShopOwnerRequest;
use App\Features\ShopOwner\Requests\ShopOwnerVerificationRequest;
use App\Features\ShopOwner\Requests\ApproveShopOwnerVerificationRequest;
use App\Features\ShopOwner\Resources\ShopProfileResource;
use App\Features\ShopOwner\UseCases\ShopOwnerVerifications;
use App\Features\ShopOwner\UseCases\ApproveShopOwnerVerification;
use App\Features\ShopOwner\UseCases\ListShopOwnerVerifications;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;




class ShopOwnerController extends Controller
{
    public function __construct(
        public ShopOwnerVerifications $shopOwnerVerifications,
        public ApproveShopOwnerVerification $approveShopOwnerVerification,
        public ListShopOwnerVerifications $listShopOwnerVerifications,
    ) {}

    /**
     * Store shop owner verification data  .
     */
    public function storeShopOwnerVerifications(ShopOwnerVerificationRequest $shopOwnerVerificationRequest): JsonResponse
    {
        $dto = new ShopOwnerVerificationsDTO(
            $shopOwnerVerificationRequest->first_name,
            $shopOwnerVerificationRequest->last_name,
            $shopOwnerVerificationRequest->email,
            $shopOwnerVerificationRequest->phone_number,
            $shopOwnerVerificationRequest->national_id_image,
            $shopOwnerVerificationRequest->country_id,
            $shopOwnerVerificationRequest->service_ids,
            $shopOwnerVerificationRequest->notes,
        );

        try {
            $this->shopOwnerVerifications->create($dto);

            return response()->json([
                'message' => 'تم إرسال طلبك ينجاح ! طلبك الآن قيد المراجعة وسيتم إشعارك عبر البريد الإلكتروني بنتيجة المراجعة . ',
            ], 201);
        } catch (\Throwable $e) {

            return response()->json([
                'message' => 'فشل إرسال الطلب ؟ يرجى المحاولة لاحقاً',
            ], 500);
        }
    }

    // Store and update shop profile data .
    public function saveOrUpdateProfile(ProfileShopOwnerRequest $shopOwnerVerificationRequest): JsonResponse
    {
        $dto = new ProfileShopOwnerDTO(
            $shopOwnerVerificationRequest->shop_name,
            $shopOwnerVerificationRequest->description,
            $shopOwnerVerificationRequest->cover_image,
            $shopOwnerVerificationRequest->country_id,
            $shopOwnerVerificationRequest->city_id,
            $shopOwnerVerificationRequest->district_id,
            $shopOwnerVerificationRequest->street,
            $shopOwnerVerificationRequest->latitude,
            $shopOwnerVerificationRequest->longitude,
            $shopOwnerVerificationRequest->working_hours,
            $shopOwnerVerificationRequest->service_ids
        );

        try {
            $this->shopOwnerVerifications->saveOrUpdateProfile($dto);

            return response()->json([
                'message' => 'تم حفظ التغييرات بنجاح.',
            ], 201);
        } catch (\Throwable $e) {

            return response()->json([
                'message' => 'فشل إرسال الطلب ؟ يرجى المحاولة لاحقاً',
            ], 500);
        }
    }

    // Get shop profile data.
    public function getShopProfile(): JsonResponse
    {

        $userId = Auth::guard('sanctum')->id();

        $shop = Shop::with('services')
            ->where('user_id', $userId)
            ->firstOrFail();

        return response()->json([
            'data' => new ShopProfileResource($shop),
        ], 200);
    }


    // دالة الموافقة/الرفض:
    public function approveVerification(ApproveShopOwnerVerificationRequest $request): JsonResponse
    {
        $dto = new ApproveShopOwnerVerificationDTO(
            (int) $request->verification_id,
            $request->status,
            $request->notes,
        );

        $result = $this->approveShopOwnerVerification->handle($dto);

        if (is_array($result) && ($result['error'] ?? false) === true) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], $result['status'] ?? 422);
        }

        return response()->json([
            'success' => true,
            'message' => $result->status === 'approved'
                ? 'تمت الموافقة على الطلب بنجاح.'
                : 'تم رفض الطلب بنجاح.',
            'data'    => $result,
        ], 200);
    }

    // دالة القائمة:
    public function getVerifications(Request $request): JsonResponse
    {
        $request->validate([
            'status' => ['nullable', 'in:pending,approved,rejected'],
        ]);

        $verifications = $this->listShopOwnerVerifications->handle(
            $request->query('status')
        );

        return response()->json([
            'success' => true,
            'message' => 'تم جلب الطلبات بنجاح.',
            'data'    => $verifications,
        ], 200);
    }
}
