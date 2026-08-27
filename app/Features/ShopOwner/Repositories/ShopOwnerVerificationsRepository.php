<?php

namespace App\Features\ShopOwner\Repositories;

use App\Features\ShopOwner\DTOs\ApproveShopOwnerVerificationDTO;   // 👈 الجديد
use App\Features\ShopOwner\DTOs\ShopOwnerVerificationsDTO;
use App\Features\ShopOwner\DTOs\CreateShopDTO;   // 👈 الجديد
use App\Features\ShopOwner\Models\Shop;
use App\Features\ShopOwner\Interfaces\ShopOwnerVerificationsInterface;
use App\Features\ShopOwner\Models\City;
use App\Features\ShopOwner\Models\Country;
use App\Features\ShopOwner\Models\Districts;
use App\Features\ShopOwner\Models\Service;
use App\Features\ShopOwner\Models\ShopOwnerVerification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ShopOwnerVerificationsRepository implements ShopOwnerVerificationsInterface
{
    public function create(ShopOwnerVerificationsDTO $registerUserDTO)
    {
        $imagePath = null;

        try {
            return DB::transaction(function () use ($registerUserDTO, &$imagePath) {

                $imagePath = $registerUserDTO->national_id_image
                    ->store('shop-owner/national-ids', 'public');

                $verification = ShopOwnerVerification::create([
                    'first_name'       => $registerUserDTO->first_name,
                    'last_name'        => $registerUserDTO->last_name,
                    'email'            => $registerUserDTO->email,
                    'phone_number'     => $registerUserDTO->phone_number,
                    'national_id_image' => $imagePath,
                    'country_id'       => $registerUserDTO->country_id,
                    'notes'            => $registerUserDTO->notes,
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

    public function getAllCountries()
    {
        return Country::all();
    }

    public function getAllServices()
    {
        return Service::all();
    }

    public function getAllCity($id)
    {
        return  City::where('country_id', $id)
            ->select('id', 'name')
            ->get();
    }

    public function getAllDistrict($id)
    {
        return  Districts::where('city_id', $id)
            ->select('id', 'name')
            ->get();
    }

    public function approve(ApproveShopOwnerVerificationDTO $dto)
    {
        return DB::transaction(function () use ($dto) {
            $verification = ShopOwnerVerification::findOrFail($dto->verification_id);

            if (! $verification->isPending()) {
                return [
                    'error'   => true,
                    'message' => 'هذا الطلب تمت مراجعته مسبقاً.',
                ];
            }

            $verification->update([
                'status'      => $dto->status,
                'notes'       => $dto->notes,
                'reviewed_by' => $dto->reviewed_by,
                'reviewed_at' => now(),
            ]);

            return $verification;
        });
    }

    public function getVerifications(?string $status = null)
    {
        return ShopOwnerVerification::query()
            ->when($status, fn ($query) => $query->where('status', $status))
            ->with('country')
            ->latest()
            ->get();
    }
    public function createShop(CreateShopDTO $dto)
    {
        $imagePath = null;

        try {
            return DB::transaction(function () use ($dto, &$imagePath) {
                if ($dto->cover_image) {
                    $imagePath = $dto->cover_image->store('shops/covers', 'public');
                }

                $shop = Shop::create([
                    'user_id'       => $dto->user_id,
                    'shop_name'     => $dto->shop_name,
                    'description'   => $dto->description,
                    'cover_image'   => $imagePath,
                    'country_id'    => $dto->country_id,
                    'city_id'       => $dto->city_id,
                    'district_id'   => $dto->district_id,
                    'street'        => $dto->street,
                    'latitude'      => $dto->latitude,
                    'longitude'     => $dto->longitude,
                    'working_hours' => $dto->working_hours,
                ]);

                $shop->services()->attach($dto->service_ids);

                return $shop;
            });
        } catch (\Throwable $e) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }

            throw $e;
        }
    }
    
    public function findApprovedVerificationByEmail(string $email)
    {
        return ShopOwnerVerification::query()
            ->where('email', $email)
            ->where('status', 'approved')
            ->first();
    }

    public function userAlreadyHasShop(int $userId): bool
    {
        return Shop::query()->where('user_id', $userId)->exists();
    }
}