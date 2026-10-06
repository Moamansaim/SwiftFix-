<?php

namespace App\Features\CustomerRepairRequests\Controllers;

use App\Features\CustomerRepairRequests\DTOs\CustomerRepairRequestDTO;
use App\Features\CustomerRepairRequests\Models\CustomerRepairRequest;
use App\Features\CustomerRepairRequests\Requests\CustomerRepairRequestRequest;
use App\Features\CustomerRepairRequests\Resources\CustomerRepairRequestResource;
use App\Features\CustomerRepairRequests\Resources\RepairRequestTrackingResource;
use App\Features\CustomerRepairRequests\UseCases\CustomerRepairRequestUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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
     * Get all customer repair requests for the authenticated shop owner.
     *
     * This method retrieves all repair requests associated with the shop
     * owned by the currently authenticated user.
     *
     * The authenticated user's ID is used to ensure that only repair
     * requests belonging to their own shop are returned.
     *
     * The required relationships are eager loaded to avoid unnecessary
     * database queries when transforming the data through the resource.
     *
     * @return JsonResponse
     *         Returns a success message and a collection of repair requests.
     */
    public function getAllCustomerRepairRequests(): JsonResponse
    {
        Gate::authorize('viewAny', CustomerRepairRequest::class);

        $user = Auth::guard('sanctum')->user();

        $repairRequests = CustomerRepairRequest::with([
            'user',
            'shop',
            'deviceModel',
            'service',
        ])
            ->whereHas('shop', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest()
            ->get();

        return response()->json([
            'message' => 'تم جلب طلبات الصيانة بنجاح.',
            'data' => CustomerRepairRequestResource::collection(
                $repairRequests
            ),
        ]);
    }


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
        $repairRequest = $this->useCase->findById($id);

        Gate::authorize('delete', $repairRequest);

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
        $repairRequest = $this->useCase->findById($id);

        Gate::authorize('approve', $repairRequest);

        $this->useCase->approve($id);

        return response()->json([
            'message' => 'تمت الموافقة على طلب الصيانة بنجاح.',
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
        $repairRequest = $this->useCase->findById($id);

        Gate::authorize('reject', $repairRequest);

        $repairRequest = $this->useCase->reject($id);

        return response()->json([
            'message' => 'تم رفض طلب الصيانة بنجاح.',
            'data' => new CustomerRepairRequestResource(
                $repairRequest
            ),
        ]);
    }


    /**
     * Get all notifications for the authenticated customer.
     *
     * This method retrieves all notifications associated with the
     * currently authenticated customer, including both read and
     * unread notifications.
     *
     * Notifications are retrieved through the authenticated user's
     * notifications relationship and sorted from newest to oldest.
     *
     * Only users with the customer role are allowed to access
     * this method.
     *
     * @return JsonResponse
     *         Returns a success message and a collection of
     *         the customer's notifications.
     *
     * @hint Route:
     *        GET /notifications
     */
    public function notificationsCustomerRepairRequests(): JsonResponse
    {
        $user = Auth::guard('sanctum')->user();

        if (!$user->hasRole('customer')) {
            return response()->json([
                'message' => 'غير مصرح لك بالوصول إلى إشعارات العملاء.',
            ], 403);
        }

        $notifications = $user->notifications()
            ->latest()
            ->get();

        return response()->json([
            'message' => 'تم جلب الإشعارات بنجاح.',
            'data' => $notifications,
        ]);
    }

    /**
     * Mark a specific notification as read.
     *
     * This method marks a specific notification belonging to the
     * currently authenticated customer as read.
     *
     * The notification is retrieved through the authenticated user's
     * notifications relationship to ensure that the customer can
     * only access and update their own notifications.
     *
     * Only users with the customer role are allowed to access
     * this method.
     *
     * @param string $id
     *        The unique identifier of the notification to mark as read.
     *
     * @return JsonResponse
     *         Returns a success message after the notification
     *         has been marked as read.
     *
     * @hint Route:
     *        POST /notifications/{id}/read
     */
    public function markAsRead(string $id): JsonResponse
    {
        $user = Auth::guard('sanctum')->user();

        if (!$user->hasRole('customer')) {
            return response()->json([
                'message' => 'غير مصرح لك بالوصول إلى إشعارات العملاء.',
            ], 403);
        }

        $notification = $user->notifications()
            ->where('id', $id)
            ->firstOrFail();

        $notification->markAsRead();

        return response()->json([
            'message' => 'تمت قراءة الإشعار بنجاح.',
        ]);
    }

    /**
     * Mark all notifications as read.
     *
     * This method marks all unread notifications belonging to the
     * currently authenticated customer as read.
     *
     * Only the notifications associated with the authenticated
     * customer are affected.
     *
     * Only users with the customer role are allowed to access
     * this method.
     *
     * @return JsonResponse
     *         Returns a success message after all unread notifications
     *         have been marked as read.
     *
     * @hint Route:
     *        POST /notifications/read-all
     */
    public function markAllAsRead(): JsonResponse
    {
        $user = Auth::guard('sanctum')->user();

        if (!$user->hasRole('customer')) {
            return response()->json([
                'message' => 'غير مصرح لك بالوصول إلى إشعارات العملاء.',
            ], 403);
        }

        $user->unreadNotifications->markAsRead();

        return response()->json([
            'message' => 'تمت قراءة جميع الإشعارات بنجاح.',
        ], 200);
    }


    /**
     * Mark a customer repair request as completed.
     *
     * This method delegates the completion process to the use case,
     * which is responsible for validating the current status and
     * updating the repair request.
     *
     * @param int $id
     *        The unique identifier of the repair request.
     *
     * @return JsonResponse
     *         Returns a success message and the updated repair request.
     *
     * @hint Route:
     *        POST /customer-repair-requests/{id}/complete
     */
    public function complete(int $id): JsonResponse
    {
        $repairRequest = $this->useCase->complete($id);

        return response()->json([
            'message' => 'تم إكمال طلب الصيانة بنجاح.',
        ]);
    }


    /**
     * Get the authenticated customer's repair requests.
     */
    public function getMyRepairRequests(): JsonResponse
    {
        $user = auth()->user();

        $repairRequests = CustomerRepairRequest::query()
            ->where('user_id', $user->id)
            ->with([
                'shop',
                'deviceModel',
                'service',
            ])
            ->latest()
            ->get();

        return response()->json([
            'data' => RepairRequestTrackingResource::collection($repairRequests),
        ], 200);
    }
}