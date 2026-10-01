<?php

namespace App\Features\UserSettings\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteAccountRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'reason_id' => [
                'required',
                'array',
                'min:1',
            ],

            'reason_id.*' => [
                'required',
                'integer',
                'distinct',
                'exists:account_deletion_reasons,id',
            ],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'reason_id.required' =>
                'يرجى اختيار سبب حذف الحساب.',

            'reason_id.array' =>
                'أسباب حذف الحساب يجب أن تكون في صورة مصفوفة.',

            'reason_id.min' =>
                'يرجى اختيار سبب حذف حساب واحد على الأقل.',

            'reason_id.*.required' =>
                'سبب حذف الحساب مطلوب.',

            'reason_id.*.integer' =>
                'معرف سبب حذف الحساب يجب أن يكون رقمًا.',

            'reason_id.*.distinct' =>
                'لا يمكن اختيار سبب حذف الحساب أكثر من مرة.',

            'reason_id.*.exists' =>
                'أحد أسباب حذف الحساب غير صالح.',
        ];
    }
}