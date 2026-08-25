<?php

namespace App\Features\ShopOwner\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApproveShopOwnerVerificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // الحماية عبر role:admin على المسار
    }

    public function rules(): array
    {
        return [
            'verification_id' => ['required', 'integer', 'exists:shop_owner_verifications,id'],
            'status'          => ['required', 'in:approved,rejected'],
            'notes'           => ['nullable', 'string'],
        ];
    }
}