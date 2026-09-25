<?php

namespace App\Features\Complaint\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreComplaintRequest extends FormRequest
{

    /**
     * Get the validation rules.
     */
    public function rules(): array
    {
        return [
            'shop_id' => [
                'required',
                'integer',
                'exists:shops,id',
            ],

            'subject' => [
                'required',
                'string',
                'max:255',
            ],

            'message' => [
                'required',
                'string',
                'max:5000',
            ],
        ];
    }
}