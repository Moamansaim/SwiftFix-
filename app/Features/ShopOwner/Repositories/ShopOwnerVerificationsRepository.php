<?php

namespace App\Features\ShopOwner\Repositories;

use App\Features\Auth\Models\User;
use App\Features\ShopOwner\DTOs\ProfileShopOwnerDTO;
use App\Features\ShopOwner\DTOs\ShopOwnerVerificationsDTO;
use App\Features\ShopOwner\Interfaces\ShopOwnerVerificationsInterface;
use App\Features\ShopOwner\Models\Shop;
use App\Features\ShopOwner\Models\ShopOwnerVerification;
use App\Features\ShopOwner\Services\UploadImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ShopOwnerVerificationsRepository implements ShopOwnerVerificationsInterface
{
    use UploadImage;

    public function create(ShopOwnerVerificationsDTO $registerUserDTO)
    {
        $imagePath = null;

        try {
            return DB::transaction(function () use ($registerUserDTO, &$imagePath) {
                $imagePath = $registerUserDTO->national_id_image
                    ->store('shop-owner/national-ids', 'public');

                $verification = ShopOwnerVerification::create([
                    'first_name' => $registerUserDTO->first_name,
                    'last_name' => $registerUserDTO->last_name,
                    'email' => $registerUserDTO->email,
                    'phone_number' => $registerUserDTO->phone_number,
                    'national_id_image' => $imagePath,
                    'country_id' => $registerUserDTO->country_id,
                    'notes' => $registerUserDTO->notes,
                ]);

                $verification->services()->attach(
                    $registerUserDTO->service_ids
                );

                return $verification;
            });
        } catch (\Throwable $e) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }

            throw $e;
        }
    }

    public function createShopProfile(ProfileShopOwnerDTO $profileShopOwnerDTO)
    {
        $imagePath = null;

        try {
            return DB::transaction(function () use (
                $profileShopOwnerDTO,
                &$imagePath
            ) {
                $user = Auth::guard('sanctum')->user();

                if (! $user instanceof User) {
                    throw new \RuntimeException(
                        'لم يتم العثور على المستخدم المسجّل دخوله.'
                    );
                }

                $userId = $user->id;

                // الحصول على الورشة الحالية
                $shop = Shop::where('user_id', $userId)->first();

                // حفظ مسار الصورة القديمة
                $oldImagePath = $shop?->cover_image;

                // رفع الصورة الجديدة
                $imagePath = $this->uploadImage(
                    $profileShopOwnerDTO->cover_image,
                    'shop-owner/cover-image-profile'
                );

                // إنشاء أو تحديث الورشة
                $shop = Shop::updateOrCreate(
                    [
                        'user_id' => $userId,
                    ],
                    [
                        'shop_name' => $profileShopOwnerDTO->shop_name,
                        'description' => $profileShopOwnerDTO->description,
                        'cover_image' => $imagePath,
                        'country_id' => $profileShopOwnerDTO->country_id,
                        'city_id' => $profileShopOwnerDTO->city_id,
                        'district' => $profileShopOwnerDTO->district,
                        'street' => $profileShopOwnerDTO->street,
                        'latitude' => $profileShopOwnerDTO->latitude,
                        'longitude' => $profileShopOwnerDTO->longitude,
                        'working_hours' => $profileShopOwnerDTO->working_hours,
                    ]
                );

                // تجهيز الخدمات مع الأسعار
                $services = collect($profileShopOwnerDTO->services)
                    ->mapWithKeys(function ($service) {
                        return [
                            $service['service_id'] => [
                                'price' => $service['price'],
                            ],
                        ];
                    })
                    ->toArray();

                // حفظ الخدمات
                $shop->services()->sync($services);

                // حذف الصورة القديمة بعد نجاح الـ Transaction
                if ($oldImagePath) {
                    DB::afterCommit(function () use ($oldImagePath) {
                        $this->deleteImage($oldImagePath);
                    });
                }

                return $shop;
            });
        } catch (\Throwable $e) {

            // حذف الصورة الجديدة إذا فشلت العملية
            if ($imagePath) {
                $this->deleteImage($imagePath);
            }

            throw $e;
        }
    }
}
