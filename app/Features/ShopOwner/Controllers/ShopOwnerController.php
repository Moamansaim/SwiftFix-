<?php

namespace App\Features\ShopOwner\Controllers;

use App\Features\ShopOwner\DTOs\ProfileShopOwnerDTO;
use App\Features\ShopOwner\DTOs\ShopOwnerVerificationsDTO;
use App\Features\ShopOwner\Models\Shop;
use App\Features\ShopOwner\Requests\ProfileShopOwnerRequest;
use App\Features\ShopOwner\Requests\ShopOwnerVerificationRequest;
use App\Features\ShopOwner\Resources\ShopDetailsResource;
use App\Features\ShopOwner\Resources\ShopProfileResource;
use App\Features\ShopOwner\Resources\ShopResource;
use App\Features\ShopOwner\UseCases\ShopOwnerVerifications;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ShopOwnerController extends Controller
{
    public function __construct(
        private ShopOwnerVerifications $shopOwnerVerifications
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
            $shopOwnerVerificationRequest->district,
            $shopOwnerVerificationRequest->street,
            $shopOwnerVerificationRequest->latitude,
            $shopOwnerVerificationRequest->longitude,
            $shopOwnerVerificationRequest->working_hours,
            $shopOwnerVerificationRequest->services
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
            ->first();

        if (! $shop) {
            return response()->json([
                'message' => 'لا توجد بيانات مسجلة للملف الشخصي. يرجى إكمال البيانات أولاً.',
            ], 404);
        }

        return response()->json([
            'data' => new ShopProfileResource($shop),
        ], 200);
    }

    // Get all shop  data.
    public function getAllShop(): JsonResponse
    {
        $shop = Shop::with([
            'services',
            'country',
            'city',
        ])->get();

        return response()->json([
            'data' =>  ShopResource::collection($shop),
        ], 200);
    }

    // Get all shops data.
    public function shopDetails(): JsonResponse
    {
        $shops = Shop::with([
            'services',
            'country',
            'city',
            'favorites',
            'shopProducts.product.category',
            'shopProducts.product.deviceModel',
        ])->get();

        return response()->json([
            'data' => ShopDetailsResource::collection($shops),
        ], 200);
    }
}