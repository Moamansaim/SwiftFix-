<?php

namespace App\Features\CustomerRepairRequests\Interfaces;

use App\Features\CustomerRepairRequests\DTOs\CustomerRepairRequestDTO;
use App\Features\CustomerRepairRequests\Models\CustomerRepairRequest;

/**
 * Defines the contract for customer repair request operations.
 *
 * Provides methods for creating, retrieving, deleting,
 * and updating the status of repair requests.
 */
interface CustomerRepairRequestInterface
{
    /**
     * Create a new customer repair request.
     *
     * @param CustomerRepairRequestDTO $dto
     *        The validated repair request data.
     *
     * @return CustomerRepairRequest
     *         The newly created repair request.
     *
     * @hint Implemented by the repository or data-access layer.
     */
    public function create(
        CustomerRepairRequestDTO $dto
    ): CustomerRepairRequest;

    /**
     * Find a repair request by its ID.
     *
     * @param int $id
     *        The ID of the repair request.
     *
     * @return CustomerRepairRequest
     *         The requested repair request.
     *
     * @hint Throws an exception if the repair request does not exist.
     */
    public function findById(
        int $id
    ): CustomerRepairRequest;

    /**
     * Delete a customer repair request.
     *
     * @param CustomerRepairRequest $repairRequest
     *        The repair request to delete.
     *
     * @return void
     *
     * @hint Deletes the specified repair request from the data source.
     */
    public function delete(
        CustomerRepairRequest $repairRequest
    ): void;

    /**
     * Update the status of a repair request.
     *
     * @param CustomerRepairRequest $repairRequest
     *        The repair request whose status will be updated.
     *
     * @param string $status
     *        The new status of the repair request.
     *
     * @return CustomerRepairRequest
     *         The repair request with its updated status.
     *
     * @hint Used when approving, rejecting, or changing
     *        the status of a repair request.
     */
    public function updateStatus(
        CustomerRepairRequest $repairRequest,
        string $status
    ): CustomerRepairRequest;
}