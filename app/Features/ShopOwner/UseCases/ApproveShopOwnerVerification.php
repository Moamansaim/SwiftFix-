<?php

// namespace App\Features\ShopOwner\UseCases;

// use App\Features\ShopOwner\DTOs\ApproveShopOwnerVerificationDTO;
// use App\Features\ShopOwner\Interfaces\ShopOwnerVerificationsInterface;
// use App\Features\ShopOwner\Mail\ShopOwnerApprovedMail;
// use App\Features\ShopOwner\Mail\ShopOwnerRejectedMail;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Mail;
// use Illuminate\Support\Str;

// class ApproveShopOwnerVerification
// {
//     public function __construct(
//         public ShopOwnerVerificationsInterface $shopOwnerVerificationsInterface
//     ) {}

//     public function handle(ApproveShopOwnerVerificationDTO $dto)
//     {
//         $verification = $this->shopOwnerVerificationsInterface
//             ->findVerificationById($dto->verification_id);

//         if (! $verification) {
//             return ['error' => true, 'status' => 404, 'message' => 'الطلب غير موجود.'];
//         }

//         if ($verification->status !== 'pending') {
//             return ['error' => true, 'status' => 422, 'message' => 'تمت مراجعة هذا الطلب مسبقاً.'];
//         }

//         return $dto->status === 'approved'
//             ? $this->approve($verification, $dto)
//             : $this->reject($verification, $dto);
//     }

//     private function approve($verification, $dto)
//     {
//         if ($this->shopOwnerVerificationsInterface->userExistsByEmail($verification->email)) {
//             return ['error' => true, 'status' => 422, 'message' => 'يوجد حساب مسجل بهذا البريد مسبقاً.'];
//         }

//         $password = Str::random(12);

//         DB::transaction(function () use ($verification, $password, $dto) {
//             $user = $this->shopOwnerVerificationsInterface
//                 ->createOwnerAccount($verification, $password);

//             $user->assignRole('workshop_owner');

//             $this->shopOwnerVerificationsInterface
//                 ->markReviewed($verification, 'approved', $dto->notes);
//         });

//         Mail::to($verification->email)->send(
//             new ShopOwnerApprovedMail($verification->first_name, $verification->email, $password)
//         );

//         return ['success' => true, 'message' => 'تمت الموافقة وإنشاء الحساب وإرسال بيانات الدخول.'];
//     }

//     private function reject($verification, $dto)
//     {
//         DB::transaction(function () use ($verification, $dto) {
//             $this->shopOwnerVerificationsInterface
//                 ->markReviewed($verification, 'rejected', $dto->notes);

//             $this->shopOwnerVerificationsInterface->deleteVerification($verification);
//         });

//         Mail::to($verification->email)->send(
//             new ShopOwnerRejectedMail($verification->first_name, $dto->notes)
//         );

//         return ['success' => true, 'message' => 'تم رفض الطلب وإرسال الإشعار وحذف الطلب.'];
//     }
// }




namespace App\Features\ShopOwner\UseCases;

use App\Features\ShopOwner\Interfaces\ShopOwnerVerificationsInterface;

class ApproveShopOwnerVerification
{
    public function __construct(
        private ShopOwnerVerificationsInterface $shopOwnerVerificationsInterface
    ) {}

    /**
     * معالجة قرار المراجعة (موافقة / رفض).
     * المنطق التنفيذي داخل الـ Repository (تصميم مؤمن) — وهنا التنسيق فقط.
     */
    public function handle(int $verificationId, string $status, ?string $notes = null)
    {
        try {
            if ($status === 'approved') {
                $this->shopOwnerVerificationsInterface->accountCreationApproval($verificationId);

                return [
                    'success' => true,
                    'message' => 'تمت الموافقة وإنشاء الحساب وإرسال بيانات الدخول.',
                ];
            }

            $this->shopOwnerVerificationsInterface->accountCreationRefused($verificationId, $notes);

            return [
                'success' => true,
                'message' => 'تم رفض الطلب وإرسال الإشعار وحذف الطلب.',
            ];
        } catch (\RuntimeException $e) {
            // أخطاء العمل (مش pending / الإيميل موجود) → 422
            return [
                'error' => true,
                'status' => 422,
                'message' => $e->getMessage(),
            ];
        }
    }
}