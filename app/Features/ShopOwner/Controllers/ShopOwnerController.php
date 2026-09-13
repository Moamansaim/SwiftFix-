<?php

namespace App\Features\ShopOwner\Controllers;

use App\Features\ShopOwner\DTOs\ProfileShopOwnerDTO;
use App\Features\ShopOwner\DTOs\ShopOwnerVerificationsDTO;
// use App\Features\ShopOwner\DTOs\ApproveShopOwnerVerificationDTO;
use App\Features\ShopOwner\Models\Shop; 
use App\Features\ShopOwner\Models\ShopOwnerVerification;
use App\Features\ShopOwner\Requests\ProfileShopOwnerRequest;
use App\Features\ShopOwner\Requests\ShopOwnerVerificationRequest;
use App\Features\ShopOwner\Requests\ApproveShopOwnerVerificationRequest;
use App\Features\ShopOwner\Requests\ShopStatusRequest;
use App\Features\ShopOwner\Resources\ShopDetailsResource;
use App\Features\ShopOwner\Resources\ShopOwnerVerificationResource;
use App\Features\ShopOwner\Resources\ShopProfileResource;
use App\Features\ShopOwner\Resources\ShopResource;
use App\Features\ShopOwner\UseCases\ShopOwnerVerifications;
use App\Features\ShopOwner\UseCases\ApproveShopOwnerVerification;
use App\Features\ShopOwner\UseCases\ListShopOwnerVerifications;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
     * Store shop owner verification data.
     */
    public function storeShopOwnerVerifications(ShopOwnerVerificationRequest $shopOwnerVerificationRequest): JsonResponse
    {
        $dto = new ShopOwnerVerificationsDTO(
            $shopOwnerVerificationRequest->first_name,
            $shopOwnerVerificationRequest->last_name,
            $shopOwnerVerificationRequest->email,
            $shopOwnerVerificationRequest->phone_number,
            $shopOwnerVerificationRequest->national_id_image,
            $shopOwnerVerificationRequest->commercial_record_image,
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

    /**
     * Retrieve all shop owner verification requests.
     */
    public function getShopOwnerVerificationData(): JsonResponse
    {
        try {
            $shopOwnerVerifications = ShopOwnerVerification::with([
                'services',
                'country',
            ])->get();

            return response()->json([
                'data' => ShopOwnerVerificationResource::collection(
                    $shopOwnerVerifications
                ),
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'بيانات التحقق غير موجودة.',
            ], 404);
        }
    }

    public function accountCreationApproval(Request $request): JsonResponse
    {
        $id = (int) $request->route('id');

        try {
            $result = $this->approveShopOwnerVerification->handle($id, 'approved', null);

            if (is_array($result) && ($result['error'] ?? false)) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'],
                ], $result['status'] ?? 422);
            }

            return response()->json($result, 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'فشل إنشاء الحساب: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function accountCreationRefused(Request $request): JsonResponse
    {
        $id = (int) $request->route('id');
        $notes = $request->input('notes');

        try {
            $result = $this->approveShopOwnerVerification->handle($id, 'rejected', $notes);

            if (is_array($result) && ($result['error'] ?? false)) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'],
                ], $result['status'] ?? 422);
            }

            return response()->json($result, 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'فشل رفض الطلب: ' . $e->getMessage(),
            ], 500);
        }
    }
    /**
     * Delete a shop owner verification request.
     */
    public function delete(int $id): JsonResponse
    {
        try {
            $this->shopOwnerVerifications->delete($id);

            return response()->json([
                'message' => 'تم حذف طلب التحقق بنجاح.',
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'فشل حذف طلب التحقق، يرجى المحاولة لاحقاً.',
            ], 500);
        }
    }

    /**
     * Store and update shop profile data.
     */
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

    /**
     * Get shop profile data.
     */
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

    /**
     * Get all shop data (Moamen's method).
     */
    public function getAllShop(): JsonResponse
    {
        $shop = Shop::with([
            'services',
            'country',
            'city',
        ])->get();

        return response()->json([
            'data' => new ShopResource($shop),
        ], 200);
    }

    /**
     * Get all shops details (Moamen's method).
     */
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

    /**
     * Get all shop owner verification requests (Moamen's method).
     */
    public function getAllShopOwnerVerificationData(): JsonResponse
    {
        $shopOwnerVerificationData = ShopOwnerVerification::with([
            'services',
            'country',
        ])->get();

        return response()->json([
            'data' => ShopOwnerVerificationResource::collection(
                $shopOwnerVerificationData
            ),
        ], 200);
    }

    /**
     * Update shop status (Moamen's method).
     */
    public function updateShopStatus(ShopStatusRequest $request, int $id): JsonResponse
    {
        try {
            $userId = Auth::guard('sanctum')->id();

            $shop = Shop::where('id', $id)
                ->where('user_id', $userId)
                ->firstOrFail();

            $shop->update([
                'status' => $request->status,
            ]);

            return response()->json([
                'message' => 'تم تحديث حالة الورشة بنجاح.',
                'status' => $shop->status,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'الورشة غير موجودة أو لا تملك صلاحية تعديلها.',
            ], 404);
        }
    }

    // ==========================================
    // دوالنا المضافة (القائمة + الفلترة)
    // ==========================================

    /**
     * Get list of verifications with filter (Our method).
     */
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