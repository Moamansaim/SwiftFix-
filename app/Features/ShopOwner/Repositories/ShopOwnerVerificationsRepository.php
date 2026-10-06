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
        $commercialRecordImagePath = null;

        try {
            return DB::transaction(function () use (
                $profileShopOwnerDTO,
                &$imagePath,
                &$commercialRecordImagePath
            ) {
                $user = Auth::guard('sanctum')->user();

                if (! $user instanceof User) {
                    throw new \RuntimeException(
                        'لم يتم العثور على المستخدم المسجّل دخوله.'
                    );
                }

                $userId = $user->id;

                // Get the existing shop.
                $shop = Shop::where('user_id', $userId)
                    ->firstOrFail();

                // Save the old image paths.
                $oldImagePath = $shop->cover_image;

                $oldCommercialRecordImagePath =
                    $shop->commercial_record_image;

                /**
                 * Upload the new cover image only if
                 * the user actually sent a new image.
                 */
                if ($profileShopOwnerDTO->cover_image !== null) {
                    $imagePath = $this->uploadImage(
                        $profileShopOwnerDTO->cover_image,
                        'shop-owner/cover-image-profile'
                    );
                }

                /**
                 * Upload the new commercial record image only if
                 * the user actually sent a new image.
                 */
                if (
                    $profileShopOwnerDTO->commercial_record_image !== null
                ) {
                    $commercialRecordImagePath = $this->uploadImage(
                        $profileShopOwnerDTO->commercial_record_image,
                        'shop-owner/commercial-record'
                    );
                }

                /**
                 * Prepare the shop profile data.
                 *
                 * Do not include the image fields here by default.
                 * This prevents the old images from being replaced
                 * with null when no new image is sent.
                 */
                $data = [
                    'shop_name' => $profileShopOwnerDTO->shop_name,
                    'description' => $profileShopOwnerDTO->description,
                    'country_id' => $profileShopOwnerDTO->country_id,
                    'city_id' => $profileShopOwnerDTO->city_id,
                    'district' => $profileShopOwnerDTO->district,
                    'street' => $profileShopOwnerDTO->street,
                    'latitude' => $profileShopOwnerDTO->latitude,
                    'longitude' => $profileShopOwnerDTO->longitude,
                    'working_hours' => $profileShopOwnerDTO->working_hours,
                ];

                /**
                 * Update the cover image only when
                 * a new image was uploaded.
                 */
                if ($imagePath !== null) {
                    $data['cover_image'] = $imagePath;
                }

                /**
                 * Update the commercial record image only when
                 * a new image was uploaded.
                 */
                if ($commercialRecordImagePath !== null) {
                    $data['commercial_record_image'] =
                        $commercialRecordImagePath;
                }

                // Update the shop profile.
                $shop->update($data);

                /**
                 * Prepare services with prices.
                 */
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

                /**
                 * Delete the old cover image only if
                 * a new cover image was uploaded.
                 */
                if (
                    $oldImagePath !== null
                    && $imagePath !== null
                ) {
                    DB::afterCommit(function () use ($oldImagePath) {
                        $this->deleteImage($oldImagePath);
                    });
                }

                /**
                 * Delete the old commercial record image only if
                 * a new commercial record image was uploaded.
                 */
                if (
                    $oldCommercialRecordImagePath !== null
                    && $commercialRecordImagePath !== null
                ) {
                    DB::afterCommit(function () use (
                        $oldCommercialRecordImagePath
                    ) {
                        $this->deleteImage(
                            $oldCommercialRecordImagePath
                        );
                    });
                }

                return $shop->fresh();
            });
        } catch (\Throwable $e) {
            /**
             * Delete the new cover image if the operation fails.
             */
            if ($imagePath !== null) {
                $this->deleteImage($imagePath);
            }

            /**
             * Delete the new commercial record image
             * if the operation fails.
             */
            if ($commercialRecordImagePath !== null) {
                $this->deleteImage($commercialRecordImagePath);
            }

            throw $e;
        }
    }

    /**
     * Update verification status.
     */
    private function updateStatus(
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
    public function accountCreationApproval(
        ShopOwnerVerification $verification
    ): void {
        $this->updateStatus(
            $verification,
            'approved'
        );
    }

    /**
     * Reject a shop owner verification request.
     */
    public function accountCreationRefused(
        ShopOwnerVerification $verification
    ): void {
        $this->updateStatus(
            $verification,
            'rejected'
        );
    }

    /**
     * Delete a shop owner verification request.
     */
    public function delete(
        ShopOwnerVerification $verification
    ): void {
        $verification->delete();
    }
}