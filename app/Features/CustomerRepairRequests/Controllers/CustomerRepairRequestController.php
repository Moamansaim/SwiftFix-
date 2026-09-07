<?php

namespace App\Features\CustomerRepairRequest\Controllers;

use App\Features\CustomerRepairRequest\DTOs\CustomerRepairRequestDTO;
use App\Features\CustomerRepairRequest\Requests\CustomerRepairRequestRequest;
use App\Features\CustomerRepairRequest\Resources\CustomerRepairRequestResource;
use App\Features\CustomerRepairRequest\UseCases\CustomerRepairRequestUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CustomerRepairRequestController
{
    /**
     * Create a new controller instance.
     *
     * The CustomerRepairRequestUseCase is injected into the controller
     * through Laravel's service container and is responsible for
     * handling the business logic related to customer repair requests.
     *
     * Keeping the business logic inside the use case prevents the
     * controller from becoming responsible for database operations
     * and other application logic.
     *
     * @param CustomerRepairRequestUseCase $useCase
     *        The use case responsible for managing customer repair requests.
     */
    public function __construct(
        private CustomerRepairRequestUseCase $useCase
    ) {}

    /**
     * Store a new customer repair request.
     *
     * This method receives and validates the repair request data
     * through CustomerRepairRequestRequest, then creates a DTO
     * containing the required data for the business logic layer.
     *
     * The authenticated customer's ID is retrieved from the
     * Sanctum guard and included in the DTO.
     *
     * The DTO is then passed to the use case, which is responsible
     * for processing and storing the repair request.
     *
     * @param CustomerRepairRequestRequest $request
     *        The validated repair request data.
     *
     * @return JsonResponse
     *         Returns a success response with HTTP status 201
     *         after the repair request has been created.
     */
    public function store(
        CustomerRepairRequestRequest $request
    ): JsonResponse {
        $dto = new CustomerRepairRequestDTO(
            Auth::guard('sanctum')->id(),
            $request->shop_id,
            $request->device_model_id,
            $request->service_id,
            $request->description,
            $request->image,
            $request->phone_number,
            $request->address,
        );

        $repairRequest = $this->useCase->create($dto);

        return response()->json([
            'message' => 'تم إرسال طلب الصيانة بنجاح.',
        ], 201);
    }

    /**
     * Delete a customer repair request.
     *
     * This method delegates the deletion operation to the
     * CustomerRepairRequestUseCase instead of handling the
     * database operation directly inside the controller.
     *
     * The use case is responsible for locating the repair request,
     * validating whether it can be deleted, and performing the
     * deletion according to the application's business rules.
     *
     * @param int $id
     *        The unique identifier of the repair request to delete.
     *
     * @return JsonResponse
     *         Returns a success response after the repair request
     *         has been successfully deleted.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->useCase->delete($id);

        return response()->json([
            'message' => 'تم حذف طلب الصيانة بنجاح.',
        ]);
    }

    /**
     * Approve a customer repair request.
     *
     * This method delegates the approval process to the use case.
     * The use case is responsible for applying the business rules
     * required to approve the repair request and updating its status.
     *
     * After the request is approved, the updated repair request
     * is transformed using CustomerRepairRequestResource before
     * being returned to the client.
     *
     * @param int $id
     *        The unique identifier of the repair request to approve.
     *
     * @return JsonResponse
     *         Returns a success message and the updated repair request.
     */
    public function approve(int $id): JsonResponse
    {
        $repairRequest = $this->useCase->approve($id);

        return response()->json([
            'message' => 'تمت الموافقة على طلب الصيانة بنجاح.',
            'data' => new CustomerRepairRequestResource(
                $repairRequest
            ),
        ]);
    }

    /**
     * Reject a customer repair request.
     *
     * This method delegates the rejection process to the use case.
     * The use case is responsible for applying the business rules
     * required to reject the repair request and updating its status.
     *
     * After the request is rejected, the updated repair request
     * is transformed using CustomerRepairRequestResource before
     * being returned to the client.
     *
     * @param int $id
     *        The unique identifier of the repair request to reject.
     *
     * @return JsonResponse
     *         Returns a success message and the updated repair request.
     */
    public function reject(int $id): JsonResponse
    {
        $repairRequest = $this->useCase->reject($id);

        return response()->json([
            'message' => 'تم رفض طلب الصيانة بنجاح.',
            'data' => new CustomerRepairRequestResource(
                $repairRequest
            ),
        ]);
    }
}