<?php

namespace App\Features\ShopOwner\Controllers;



use App\Features\ShopOwner\DTOs\ProfileShopOwnerDTO;
use App\Features\ShopOwner\DTOs\ShopOwnerVerificationsDTO;
use App\Features\ShopOwner\Requests\ProfileShopOwnerRequest;
use App\Features\ShopOwner\Requests\ShopOwnerVerificationRequest;
use App\Features\ShopOwner\UseCases\ShopOwnerVerifications;
use App\Http\Controllers\Controller;

class ShopOwnerController extends Controller
{
    public function __construct(
        private ShopOwnerVerifications $shopOwnerVerifications
    ) {}

    /**
     * استقبال طلب التحقق من صاحب المتجر وإرساله إلى Use Case لمعالجته.
     */
    public function store(ShopOwnerVerificationRequest $shopOwnerVerificationRequest)
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
                // 'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * جلب جميع الدول المتاحة من قاعدة البيانات وإرجاعها بصيغة JSON.
     */
    public function getAllCountries()
    {
        $countries = $this->shopOwnerVerifications->getAllCountries();

        return response()->json([
            'countries' => $countries,
        ]);
    }

    /**
     * جلب جميع الخدمات المتاحة من قاعدة البيانات وإرجاعها بصيغة JSON.
     */
    public function getAllServices()
    {
        $services = $this->shopOwnerVerifications->getAllServices();

        return response()->json([
            'services' => $services,
        ]);
    }

    /**
     * جلب جميع المدن المتاحة من قاعدة البيانات وإرجاعها بصيغة JSON.
     */
    public function getAllCity($id)
    {
        $cities = $this->shopOwnerVerifications->getAllCity($id);

        return response()->json([
            'cities' => $cities,
        ]);
    }

    /**
     * جلب جميع الأحياء المتاحة من قاعدة البيانات وإرجاعها بصيغة JSON.
     */
    public function getAllDistrict($id)
    {
        $districts = $this->shopOwnerVerifications->getAllDistrict($id);

        return response()->json([
            'districts' => $districts,
        ]);
    }

    // استقبال طلب حفظ أو تحديث بيانات ملف صاحب المحل بعد التحقق من صحة البيانات.
    public function saveOrUpdateProfile(ProfileShopOwnerRequest $shopOwnerVerificationRequest)
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
                'message' => 'تم إرسال طلبك ينجاح ! طلبك الآن قيد المراجعة وسيتم إشعارك عبر البريد الإلكتروني بنتيجة المراجعة . ',
            ], 201);
        } catch (\Throwable $e) {

            return response()->json([
                'message' => $e->getMessage(),    
                //'message' => 'فشل إرسال الطلب ؟ يرجى المحاولة لاحقاً',
            ], 500);
        }
    }
}