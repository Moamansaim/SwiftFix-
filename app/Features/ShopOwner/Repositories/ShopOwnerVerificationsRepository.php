<?php

namespace App\Features\ShopOwner\Repositories;

use App\Features\Auth\Models\User;
use App\Features\ShopOwner\DTOs\ProfileShopOwnerDTO;
use App\Features\ShopOwner\DTOs\ShopOwnerVerificationsDTO;
use App\Features\ShopOwner\Interfaces\ShopOwnerVerificationsInterface;
use App\Features\ShopOwner\Mail\ShopOwnerApprovedMail;
use App\Features\ShopOwner\Mail\ShopOwnerRejectedMail;
use App\Features\ShopOwner\Models\Shop;
use App\Features\ShopOwner\Models\ShopOwnerVerification;
use App\Features\ShopOwner\Services\UploadImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

                // Get the existing shop
                $shop = Shop::where('user_id', $userId)->first();

                // Save the old image path
                $oldImagePath = $shop?->cover_image;

                // Upload the new image
                $imagePath = $this->uploadImage(
                    $profileShopOwnerDTO->cover_image,
                    'shop-owner/cover-image-profile'
                );

                // Fill the existing shop with its profile data
                $shop = Shop::updateOrCreate(['user_id' => $userId], [                   
                    'shop_name' => $profileShopOwnerDTO->shop_name,
                    'description' => $profileShopOwnerDTO->description,
                    'cover_image' => $imagePath,
                    'commercial_record_image' => $profileShopOwnerDTO->commercial_record_image,
                    'country_id' => $profileShopOwnerDTO->country_id,
                    'city_id' => $profileShopOwnerDTO->city_id,
                    'district' => $profileShopOwnerDTO->district,
                    'street' => $profileShopOwnerDTO->street,
                    'latitude' => $profileShopOwnerDTO->latitude,
                    'longitude' => $profileShopOwnerDTO->longitude,
                    'working_hours' => $profileShopOwnerDTO->working_hours,
                ]);

                // Prepare services with prices
                $services = collect($profileShopOwnerDTO->services)
                    ->mapWithKeys(function ($service) {
                        return [
                            $service['service_id'] => [
                                'price' => $service['price'],
                            ],
                        ];
                    })
                    ->toArray();

                // Sync shop services
                $shop->services()->sync($services);

                // Delete the old image after the transaction is committed
                if ($oldImagePath) {
                    DB::afterCommit(function () use ($oldImagePath) {
                        $this->deleteImage($oldImagePath);
                    });
                }

                return $shop->fresh();
            });
        } catch (\Throwable $e) {

            // Delete the new image if the operation fails
            if ($imagePath) {
                $this->deleteImage($imagePath);
            }
            throw $e;
        }
    }

    // ==========================================
    // دوال مؤمن (مع المنطق الكامل الذي أضفناه)
    // ==========================================

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
        // نستخدم التعيين المباشر لأن status/reviewed_by قد لا تكون في fillable
        $verification->status = $status;
        $verification->reviewed_by = Auth::guard('sanctum')->id();
        $verification->reviewed_at = now();
        $verification->save();
    }

    /**
     * Approve a shop owner verification request.
     * (Logic: Create Account + Random Password + Role + Email)
     */
    public function accountCreationApproval(int $id): void
    {
        $verification = $this->findById($id);

        // حارس: يجب أن يكون الطلب معلّقاً
        if ($verification->status !== 'pending') {
            throw new \RuntimeException('تمت مراجعة هذا الطلب مسبقاً.');
        }

        // حارس: يجب ألا يوجد حساب بنفس الإيميل
        if ($this->userExistsByEmail($verification->email)) {
            throw new \RuntimeException('يوجد حساب مسجل بهذا البريد مسبقاً.');
        }

        $password = Str::random(12);

        DB::transaction(function () use ($verification, $password) {
            // 1. إنشاء الحساب
            $user = $this->createOwnerAccount($verification, $password);
            
            // 2. منح الدور
            $user->assignRole('workshop_owner');
            
            // 3. تحديث حالة الطلب
            $this->updateStatus($verification, 'approved');
        });

        // 4. إرسال الإيميل (خارج الترانزاكشن لضمان عدم الإرسال عند الفشل)
        Mail::to($verification->email)->send(
            new ShopOwnerApprovedMail($verification->first_name, $verification->email, $password)
        );
    }

    /**
     * Reject a shop owner verification request.
     * (Logic: Mark Rejected + Soft Delete + Email)
     */
    public function accountCreationRefused(int $id, ?string $notes = null): void
    {
        $verification = $this->findById($id);

        // حارس: يجب أن يكون الطلب معلّقاً
        if ($verification->status !== 'pending') {
            throw new \RuntimeException('تمت مراجعة هذا الطلب مسبقاً.');
        }

        DB::transaction(function () use ($verification, $notes) {
            // حفظ ملاحظات الرفض
            if ($notes) {
                $verification->notes = $notes;
                $verification->save();
            }

            // تحديث الحالة
            $this->updateStatus($verification, 'rejected');

            // حذف الطلب (Soft Delete)
            $verification->delete();
        });

        // إرسال إيميل الرفض
        Mail::to($verification->email)->send(
            new ShopOwnerRejectedMail($verification->first_name, $notes)
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
    public function verifyShop(int $shopId): void
    {
        $shop = Shop::findOrFail($shopId);
        $shop->is_verified = true;
        $shop->save();
    }


    // ==========================================
    // دوال مساعدة (أضفناها لدعم المنطق)
    // ==========================================

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
        $user->email_verified_at = now(); // تفعيل فوري لأننا أرسلنا البيانات
        $user->save();

        return $user;
    }

    public function getVerifications(?string $status = null)
    {
        return ShopOwnerVerification::query()
            ->when($status, fn ($query) => $query->where('status', $status))
            ->with('country')
            ->latest()
            ->get();
    }

    public function hasApprovedVerification(string $email): bool
    {
        return ShopOwnerVerification::where('email', $email)
            ->where('status', 'approved')
            ->exists();
    }

}