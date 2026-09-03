<?php

namespace App\Features\ShopOwner\Repositories;

use App\Features\Auth\Models\User;
use App\Features\ShopOwner\DTOs\ApproveShopOwnerVerificationDTO;
use App\Features\ShopOwner\DTOs\ProfileShopOwnerDTO;
use App\Features\ShopOwner\DTOs\ShopOwnerVerificationsDTO;
use App\Features\ShopOwner\Interfaces\ShopOwnerVerificationsInterface;
use App\Features\ShopOwner\Models\Shop;
use App\Features\ShopOwner\Models\ShopOwnerVerification;
use App\Features\ShopOwner\Services\UploadImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

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
        $oldImagePath = null;

        try {
            return DB::transaction(function () use (
                $profileShopOwnerDTO,
                &$imagePath,
                &$oldImagePath,
            ) {
                $user = Auth::guard('sanctum')->user();

                if (! $user instanceof User) {
                    throw new \RuntimeException(
                        'لم يتم العثور على المستخدم المسجّل دخوله.'
                    );
                }

                $userId = $user->id;

                // حفظ مسار الصورة القديمة
                $shop = Shop::where('user_id', $userId)->first();

                $oldImagePath = $shop?->cover_image;

                // رفع الصورة الجديدة
                $imagePath = $this->uploadImage(
                    $profileShopOwnerDTO->cover_image,
                    'shop-owner/cover-image-profile'
                );

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
                        'district_id' => $profileShopOwnerDTO->district_id,
                        'street' => $profileShopOwnerDTO->street,
                        'latitude' => $profileShopOwnerDTO->latitude,
                        'longitude' => $profileShopOwnerDTO->longitude,
                        'working_hours' => $profileShopOwnerDTO->working_hours,
                    ]
                );

                $shop->services()->sync(
                    $profileShopOwnerDTO->service_ids
                );

                $this->deleteImage($oldImagePath);

                return $shop;
            });
        } catch (\Throwable $e) {
            $this->deleteImage($imagePath);

            throw $e;
        }
    }

    // public function approve(ApproveShopOwnerVerificationDTO $dto)
    // {
    //     $verification = ShopOwnerVerification::findOrFail($dto->verification_id);

    //     if ($verification->status !== 'pending') {
    //         return [
    //             'error'   => true,
    //             'status'  => 422,
    //             'message' => 'تمت مراجعة هذا الطلب مسبقاً.',
    //         ];
    //     }

    //     $verification->status      = $dto->status;
    //     $verification->notes       = $dto->notes;
    //     $verification->reviewed_by = Auth::guard('sanctum')->id();
    //     $verification->reviewed_at = now();
    //     $verification->save();

    //     return $verification;
    // }

    public function findVerificationById(int $id): ?ShopOwnerVerification
    {
        return ShopOwnerVerification::find($id);
    }

    public function userExistsByEmail(string $email): bool
    {
        return User::where('email', $email)->exists();
    }

    public function createOwnerAccount(ShopOwnerVerification $verification, string $password): User
    {
        $user = new User;
        $user->first_name = $verification->first_name;
        $user->last_name = $verification->last_name;
        $user->email = $verification->email;
        $user->phone_number = $verification->phone_number;
        $user->password = Hash::make($password);
        $user->email_verified_at = now();
        $user->save();

        return $user;
    }

    public function markReviewed(ShopOwnerVerification $verification, string $status, ?string $notes): void
    {
        $verification->status = $status;
        $verification->notes = $notes ?? $verification->notes;
        $verification->reviewed_by = Auth::guard('sanctum')->id();
        $verification->reviewed_at = now();
        $verification->save();
    }

    public function deleteVerification(ShopOwnerVerification $verification): void
    {
        $verification->delete(); // SoftDeletes = حذف آمن قابل للاسترجاع
    }

    public function getVerifications(?string $status = null)
    {
        return ShopOwnerVerification::query()
            ->when($status, fn ($query) => $query->where('status', $status))
            ->with('country')
            ->latest()
            ->get();
    }
}
