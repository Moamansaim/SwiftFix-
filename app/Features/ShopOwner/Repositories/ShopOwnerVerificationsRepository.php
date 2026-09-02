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

    /**
     * Create a shop owner verification request.
     */
    public function create(
        ShopOwnerVerificationsDTO $registerUserDTO
    ): ShopOwnerVerification {

        $uploadedImages = [];

        try {

            return DB::transaction(function () use (
                $registerUserDTO,
                &$uploadedImages
            ) {

                // Upload national ID image.
                $uploadedImages['national_id_image'] =
                    $registerUserDTO->national_id_image
                    ->store(
                        'shop-owner/national-ids',
                        'public'
                    );

                // Upload commercial record image if provided.
                if ($registerUserDTO->commercial_record_image) {

                    $uploadedImages['commercial_record_image'] =
                        $registerUserDTO->commercial_record_image
                        ->store(
                            'shop-owner/commercial-records',
                            'public'
                        );
                }

                // Create verification request.
                $verification = ShopOwnerVerification::create([
                    'first_name' => $registerUserDTO->first_name,
                    'last_name' => $registerUserDTO->last_name,
                    'email' => $registerUserDTO->email,
                    'phone_number' => $registerUserDTO->phone_number,

                    'national_id_image' =>
                    $uploadedImages['national_id_image'],

                    'commercial_record_image' =>
                    $uploadedImages['commercial_record_image'] ?? null,

                    'country_id' => $registerUserDTO->country_id,
                    'notes' => $registerUserDTO->notes,
                ]);

                // Attach services.
                $verification->services()->attach(
                    $registerUserDTO->service_ids
                );

                return $verification;
            });
        } catch (\Throwable $e) {

            // Delete uploaded images if the transaction fails.
            foreach ($uploadedImages as $imagePath) {

                Storage::disk('public')->delete($imagePath);
            }

            throw $e;
        }
    }

    /**
     * Create a shop owner profile.
     */
    public function createShopProfile(
        ProfileShopOwnerDTO $profileShopOwnerDTO
    ): Shop {
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

                // Get the current shop.
                $shop = Shop::where('user_id', $userId)->first();

                // Save the old image path.
                $oldImagePath = $shop?->cover_image;

                // Upload the new image.
                $imagePath = $this->uploadImage(
                    $profileShopOwnerDTO->cover_image,
                    'shop-owner/cover-image-profile'
                );

                // Create or update the shop.
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

                // Prepare services with prices.
                $services = collect($profileShopOwnerDTO->services)
                    ->mapWithKeys(function ($service) {
                        return [
                            $service['service_id'] => [
                                'price' => $service['price'],
                            ],
                        ];
                    })
                    ->toArray();

                // Sync shop services.
                $shop->services()->sync($services);

                // Delete the old image after the transaction is committed.
                if ($oldImagePath) {
                    DB::afterCommit(function () use ($oldImagePath) {
                        $this->deleteImage($oldImagePath);
                    });
                }

                return $shop;
            });
        } catch (\Throwable $e) {

            // Delete the new image if the operation fails.
            if ($imagePath) {
                $this->deleteImage($imagePath);
            }

            throw $e;
        }
    }

    /**
     * Find a shop owner verification by ID.
     */
    public function findById(int $id): ShopOwnerVerification
    {
        return ShopOwnerVerification::findOrFail($id);
    }

    /**
     * Update verification status.
     */
    public function updateStatus(
        ShopOwnerVerification $verification,
        string $status
    ): void {
        $verification->update([
            'status' => $status,
            'reviewed_by' => Auth::guard('sanctum')->id(),
            'reviewed_at' => now(),
        ]);
    }

    /**
     * Approve a shop owner verification request.
     */
    public function accountCreationApproval(int $id): void
    {
        $verification = $this->findById($id);

        $this->updateStatus(
            $verification,
            'approved'
        );
    }

    /**
     * Reject a shop owner verification request.
     */
    public function accountCreationRefused(int $id): void
    {
        $verification = $this->findById($id);

        $this->updateStatus(
            $verification,
            'rejected'
        );
    }

    /**
     * Delete a shop owner verification request.
     */
    public function delete(int $id): void
    {
        $verification = $this->findById($id);

        $verification->delete();
    }
}