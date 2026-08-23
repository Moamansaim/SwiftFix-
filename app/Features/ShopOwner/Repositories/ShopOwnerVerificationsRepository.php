<?php

namespace App\Features\ShopOwner\Repositories;

use App\Features\ShopOwner\DTOs\ShopOwnerVerificationsDTO;
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
}