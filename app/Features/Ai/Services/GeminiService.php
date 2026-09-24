<?php

namespace App\Features\Ai\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class GeminiService
{
    /**
     * Generate a shop recommendation response using Gemini.
     *
     * @param  string  $userPrompt
     * @param  array<int, array<string, mixed>>  $shops
     * @return array<string, mixed>
     */
    public function recommendShops(
        string $userPrompt,
        array $shops
    ): array {
        $apiKey = config('services.gemini.api_key');

        $model = config(
            'services.gemini.model',
            'gemini-flash-lite-latest'
        );

        $systemPrompt = $this->systemPrompt();

        $userContent = $this->buildUserContent(
            $userPrompt,
            $shops
        );

        $response = Http::timeout(60)
            ->withHeaders([
                'x-goog-api-key' => $apiKey,
                'Content-Type' => 'application/json',
            ])
            ->post(
                "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent",
                [
                    'systemInstruction' => [
                        'parts' => [
                            [
                                'text' => $systemPrompt,
                            ],
                        ],
                    ],

                    'contents' => [
                        [
                            'role' => 'user',

                            'parts' => [
                                [
                                    'text' => $userContent,
                                ],
                            ],
                        ],
                    ],

                    'generationConfig' => [
                        'temperature' => 0.2,
                        'responseMimeType' => 'application/json',

                        'responseSchema' => [
                            'type' => 'OBJECT',

                            'properties' => [
                                'recommendations' => [
                                    'type' => 'ARRAY',

                                    'items' => [
                                        'type' => 'OBJECT',

                                        'properties' => [
                                            'shop_id' => [
                                                'type' => 'INTEGER',
                                            ],

                                            'rank' => [
                                                'type' => 'INTEGER',
                                            ],

                                            'reason' => [
                                                'type' => 'STRING',
                                            ],

                                            'confidence' => [
                                                'type' => 'STRING',

                                                'enum' => [
                                                    'high',
                                                    'medium',
                                                    'low',
                                                ],
                                            ],
                                        ],

                                        'required' => [
                                            'shop_id',
                                            'rank',
                                            'reason',
                                            'confidence',
                                        ],
                                    ],
                                ],
                            ],

                            'required' => [
                                'recommendations',
                            ],
                        ],
                    ],
                ]
            );

        $response->throw();

        $text = $response->json(
            'candidates.0.content.parts.0.text'
        );

        return json_decode(
            $text,
            true,
            512,
            JSON_THROW_ON_ERROR
        );
    }

    /**
     * System instructions that control Gemini's behavior.
     */
    private function systemPrompt(): string
    {
        return <<<'PROMPT'
You are the AI shop recommendation engine for SwiftFix.

Your task is to analyze the customer's repair request and compare it
against the provided workshop data.

Your job is ONLY to recommend and rank workshops.

Rules:

1. Use ONLY the workshop data provided by the application.
2. Never invent a workshop, service, product, price, rating, distance,
   availability, or any other information.
3. Never modify or assume database values.
4. Match the customer's request against the available workshop services
   and spare parts.
5. Consider the following factors when they are relevant to the user's request:
   - service relevance
   - spare part availability
   - price
   - distance
   - rating
   - workshop verification
   - workshop status
6. If the customer explicitly asks for a cheap workshop, give greater
   importance to lower prices.
7. If the customer explicitly asks for a nearby workshop, give greater
   importance to shorter distance.
8. If the customer requires a specific spare part, prioritize workshops
   where that part is actually available.
9. If the customer requires a specific service, prioritize workshops
   that actually provide that service.
10. Do not recommend a blocked workshop.
11. Do not create facts that are not present in the provided data.
12. A workshop may only appear in the recommendations if its shop_id
    exists in the provided workshop data.
13. Rank the recommendations from the most suitable to the least suitable.
14. Return only the requested JSON structure.
15. The reason must be concise and based only on actual provided data.
16. If none of the workshops sufficiently match the customer's request,
    return an empty recommendations array.

The application, not the AI, is the source of truth for workshop data.
PROMPT;
    }

    /**
     * Build the user content sent to Gemini.
     *
     * @param  string  $userPrompt
     * @param  array<int, array<string, mixed>>  $shops
     */
    private function buildUserContent(
        string $userPrompt,
        array $shops
    ): string {
        return json_encode(
            [
                'customer_request' => $userPrompt,
                'workshops' => $shops,
            ],
            JSON_UNESCAPED_UNICODE |
                JSON_UNESCAPED_SLASHES |
                JSON_PRETTY_PRINT
        );
    }
}