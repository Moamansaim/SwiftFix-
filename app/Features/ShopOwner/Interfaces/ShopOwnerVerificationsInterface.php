<?php

namespace App\Features\ShopOwner\Interfaces;

use App\Features\ShopOwner\DTOs\ApproveShopOwnerVerificationDTO;

use App\Features\ShopOwner\DTOs\ShopOwnerVerificationsDTO;

use App\Features\ShopOwner\DTOs\CreateShopDTO;
interface ShopOwnerVerificationsInterface
{
    public function create(ShopOwnerVerificationsDTO $shopOwnerVerificationsDTO);
    public function getAllCountries();
    public function getAllServices();
    public function getAllCity($id);
    public function getAllDistrict($id);
    public function approve(ApproveShopOwnerVerificationDTO $dto);
    public function getVerifications(?string $status = null);
    public function findApprovedVerificationByEmail(string $email);
    public function userAlreadyHasShop(int $userId): bool;
    public function createShop(CreateShopDTO $dto);
}