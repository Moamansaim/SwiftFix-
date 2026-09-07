<?php

namespace App\Features\ShopOwner\Controllers;

use App\Features\ShopOwner\DTOs\ProfileShopOwnerDTO;
use App\Features\ShopOwner\DTOs\ShopOwnerVerificationsDTO;
use App\Features\ShopOwner\Models\Shop;
use App\Features\ShopOwner\Models\ShopOwnerVerification;
use App\Features\ShopOwner\Requests\ProfileShopOwnerRequest;
use App\Features\ShopOwner\Requests\ShopOwnerVerificationRequest;
use App\Features\ShopOwner\Requests\ShopStatusRequest;
use App\Features\ShopOwner\Resources\ShopDetailsResource;
use App\Features\ShopOwner\Resources\ShopOwnerVerificationResource;
use App\Features\ShopOwner\Resources\ShopProfileResource;
use App\Features\ShopOwner\Resources\ShopResource;
use App\Features\ShopOwner\UseCases\ShopOwnerVerifications;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

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
            $shopOwnerVerificationRequest->commercial_record_image,
            $shopOwnerVerificationRequest->country_id,
            $shopOwnerVerificationRequest->service_ids,
            $shopOwnerVerificationRequest->notes,
        );

        try {
            $verification = $this->shopOwnerVerifications->create($dto);

            return response()->json([
                'message' => 'تم إرسال طلبك بنجاح! طلبك الآن قيد المراجعة وسيتم إشعارك عبر البريد الإلكتروني بنتيجة المراجعة.',
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'فشل إرسال الطلب، يرجى المحاولة لاحقاً',
            ], 500);
        }
    }

    /**
     * Retrieve all shop owner verification requests with their
     * associated services and country information.
     *
     * @return JsonResponse
     */
    public function getShopOwnerVerificationData(): JsonResponse
    {
        try {
            // Retrieve all verification requests with their related services and country.
            $shopOwnerVerifications = ShopOwnerVerification::with([
                'services',
                'country',
            ])->get();

            // Transform the collection using the verification resource.
            return response()->json([
                'data' => ShopOwnerVerificationResource::collection(
                    $shopOwnerVerifications
                ),
            ], 200);
        } catch (ModelNotFoundException $e) {
            // Return a 404 response if the verification data cannot be found.
            return response()->json([
                'message' => 'بيانات التحقق غير موجودة.',
            ], 404);
        }
    }

    /**
     * Approve a shop owner verification request and create their account.
     */
    public function accountCreationApproval(int $id): JsonResponse
    {
        try {
            $this->shopOwnerVerifications
                ->accountCreationApproval($id);

            return response()->json([
                'message' => 'تم إنشاء حساب صاحب الورشة بنجاح.',
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage(),
                // 'message' => 'فشل إنشاء حساب صاحب الورشة، يرجى المحاولة لاحقاً.',
            ], 500);
        }
    }

    /**
     * Reject a shop owner verification request.
     */
    public function accountCreationRefused(int $id): JsonResponse
    {
        try {
            $this->shopOwnerVerifications
                ->accountCreationRefused($id);

            return response()->json([
                'message' => 'تم رفض طلب التحقق بنجاح.',
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'فشل رفض طلب التحقق، يرجى المحاولة لاحقاً.',
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

    // Store and update shop profile data .
    public function saveOrUpdateProfile(ProfileShopOwnerRequest $shopOwnerVerificationRequest): JsonResponse
    {
        $dto = new ProfileShopOwnerDTO(
            $shopOwnerVerificationRequest->shop_name,
            $shopOwnerVerificationRequest->description,
            $shopOwnerVerificationRequest->cover_image,
            $shopOwnerVerificationRequest->commercial_record_image,
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
                'message' => $e->getMessage(),
                //'message' => 'فشل إرسال الطلب ؟ يرجى المحاولة لاحقاً',
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

    /**
     * Get all shops with optional filters.
     *
     * This method retrieves all available shops and applies the
     * requested filters using the local query scopes defined
     * in the Shop model.
     *
     * The following filters are supported:
     * - Shop name.
     * - Country name.
     * - City name.
     * - District.
     * - Street.
     * - Shop status.
     * - Service.
     * - Service price range.
     *
     * Empty filters are ignored automatically by the corresponding
     * local scopes.
     *
     * Only shops with a name are returned, and blocked shops are
     * excluded from the public shop listing.
     *
     * @param Request $request
     *        The HTTP request containing the optional filter parameters.
     *
     * @return JsonResponse
     *         Returns the filtered shops as a JSON response.
     */
    public function getAllShop(Request $request): JsonResponse
    {
        $shops = Shop::with([
            'services',
            'country',
            'city',
        ])
            ->withAvg('reviews', 'rating')
            ->whereNotNull('shop_name')
            ->where('status', '!=', 'blocked')
            ->byName($request->shopName)
            ->byCity($request->cityName)
            ->byService($request->service_id)
            ->byPrice(
                $request->min_price,
                $request->max_price
            )
            ->bySparePart($request->sparePart)
            ->byRating($request->rating)
            ->byStatus($request->status)
            ->ByVerification($request->is_verified)
            ->get();

        return response()->json([
            'data' => ShopResource::collection($shops),
        ], 200);
    }

    /**
     * Get shop details.
     */
    public function shopDetails(int $id): JsonResponse
    {
        $shop = Shop::with([
            'services',
            'country',
            'city',
            'favorites',
            'shopProducts.product.category',
            'shopProducts.deviceModel',
            'user',
        ])->findOrFail($id);

        return response()->json([
            'data' => new ShopDetailsResource($shop),
        ], 200);
    }


    /**
     * Get all shop owner verification requests.
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
     * Update shop status.
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


    // Verify a shop and update its status to verified
    public function verifyShop(int $shopId): JsonResponse
    {
        try {
            $this->shopOwnerVerifications->verifyShop($shopId);

            return response()->json([
                'message' => 'تم توثيق الورشة بنجاح.',
            ]);
        } catch (RuntimeException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}