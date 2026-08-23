<?php

namespace App\Features\ShopOwner\Interfaces;

use App\Features\ShopOwner\DTOs\ShopOwnerVerificationsDTO;


interface ShopOwnerVerificationsInterface
{
    public function create(ShopOwnerVerificationsDTO $shopOwnerVerificationsDTO);
    public function getAllCountries();
    public function getAllServices();
    public function getAllCity($id);
    public function getAllDistrict($id);
   // public function saveProfile();
}