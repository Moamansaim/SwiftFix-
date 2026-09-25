<?php

namespace App\Features\Ai\Request;

use Illuminate\Foundation\Http\FormRequest;

class ShopRecommendationRequest extends FormRequest
{
    /**
     * Get the validation rules.
     */
    public function rules(): array
    {
        return [
            'prompt' => [
                'required',
                'string',
                'min:1',
                'max:2000',
            ],

            'conversation_id' => [
                'nullable',
                'integer',
                'exists:ai_conversations,id',
            ],

            'latitude' => [
                'nullable',
                'numeric',
                'between:-90,90',
            ],

            'longitude' => [
                'nullable',
                'numeric',
                'between:-180,180',
            ],
        ];
    }
}