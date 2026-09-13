<?php

namespace App\Features\Favorite\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FavoriteRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'shop_id' => [
                'required',
                'integer',
                'exists:shops,id',
            ],
        ];
    }
}
