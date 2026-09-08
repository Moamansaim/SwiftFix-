<?php

namespace App\Features\CustomerRepairRequests\DTOs;

use Illuminate\Http\UploadedFile;

/**
 * Data Transfer Object for customer repair requests.
 *
 * Contains the validated data required to create a repair request.
 */
class CustomerRepairRequestDTO
{
    /**
     * Create a new customer repair request DTO.
     *
     * @param int $user_id
     *        The ID of the customer submitting the repair request.
     *
     * @param int $shop_id
     *        The ID of the shop receiving the repair request.
     *
     * @param int $device_model_id
     *        The ID of the device model that needs repair.
     *
     * @param int $service_id
     *        The ID of the requested repair service.
     *
     * @param string $description
     *        A description of the customer's repair problem.
     *
     * @param UploadedFile|null $image
     *        An optional image related to the repair request.
     *
     * @param string $address
     *        The customer's address for the repair request.
     *
     * @hint Used to transfer validated repair request data
     *        from the Request layer to the UseCase layer.
     */
    public function __construct(
        public int $user_id,
        public int $shop_id,
        public int $device_model_id,
        public int $service_id,
        public string $description,
        public ?UploadedFile $image,
        public string $address,
    ) {}
}