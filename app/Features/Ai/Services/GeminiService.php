<?php

namespace App\Features\Ai\Services;

use Illuminate\Support\Facades\Http;
use JsonException;

class GeminiService
{
    private string $apiKey;

    private string $model;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');

        $this->model = config(
            'services.gemini.model',
            'gemini-flash-lite-latest'
        );
    }

    /**
     * Analyze the user's request and recommend suitable shops
     * based only on the real shop data provided by Laravel.
     *
     * @param string $userPrompt
     * @param array<int, array<string, mixed>> $shops
     * @param array<int, array<string, string>> $conversation
     * @return array<string, mixed>
     */
    public function recommendShops(
        string $userPrompt,
        array $shops,
        array $conversation = []
    ): array {
        try {
            $response = Http::timeout(60)
                ->post(
                    "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}",
                    [
                        'systemInstruction' => [
                            'parts' => [
                                [
                                    'text' => $this->recommendationSystemPrompt(),
                                ],
                            ],
                        ],

                        'contents' => $this->buildRecommendationContents(
                            userPrompt: $userPrompt,
                            shops: $shops,
                            conversation: $conversation
                        ),

                        'generationConfig' => [
                            'temperature' => 0.1,
                            'responseMimeType' => 'application/json',

                            'responseSchema' => [
                                'type' => 'OBJECT',

                                'properties' => [
                                    'intent' => [
                                        'type' => 'OBJECT',

                                        'properties' => [
                                            'type' => [
                                                'type' => 'STRING',
                                                'enum' => [
                                                    'shop_search',
                                                    'general_question',
                                                    'clarification',
                                                ],
                                            ],

                                            'device' => [
                                                'type' => 'STRING',
                                                'nullable' => true,
                                            ],

                                            'brand' => [
                                                'type' => 'STRING',
                                                'nullable' => true,
                                            ],

                                            'model' => [
                                                'type' => 'STRING',
                                                'nullable' => true,
                                            ],

                                            'service' => [
                                                'type' => 'STRING',
                                                'nullable' => true,
                                            ],

                                            'spare_part' => [
                                                'type' => 'STRING',
                                                'nullable' => true,
                                            ],

                                            'city' => [
                                                'type' => 'STRING',
                                                'nullable' => true,
                                            ],

                                            'price_preference' => [
                                                'type' => 'STRING',
                                                'enum' => [
                                                    'cheapest',
                                                    'most_expensive',
                                                ],
                                                'nullable' => true,
                                            ],

                                            'distance_preference' => [
                                                'type' => 'STRING',
                                                'enum' => [
                                                    'nearest',
                                                    'farthest',
                                                ],
                                                'nullable' => true,
                                            ],

                                            'rating_preference' => [
                                                'type' => 'STRING',
                                                'enum' => [
                                                    'highest',
                                                    'lowest',
                                                ],
                                                'nullable' => true,
                                            ],
                                        ],

                                        'required' => [
                                            'type',
                                            'device',
                                            'brand',
                                            'model',
                                            'service',
                                            'spare_part',
                                            'city',
                                            'price_preference',
                                            'distance_preference',
                                            'rating_preference',
                                        ],
                                    ],

                                    'message' => [
                                        'type' => 'STRING',
                                    ],

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
                                    'intent',
                                    'message',
                                    'recommendations',
                                ],
                            ],
                        ],
                    ]
                );

            if ($response->failed()) {
                return $this->errorResponse();
            }

            $text = data_get(
                $response->json(),
                'candidates.0.content.parts.0.text'
            );

            if (! is_string($text) || trim($text) === '') {
                return $this->errorResponse();
            }

            $result = json_decode(
                $text,
                true,
                512,
                JSON_THROW_ON_ERROR
            );

            return $this->validateResult(
                result: $result,
                shops: $shops
            );
        } catch (JsonException) {
            return $this->errorResponse();
        } catch (\Throwable) {
            return $this->errorResponse();
        }
    }

    /**
     * Normal SwiftFix chat.
     *
     * @param string $userPrompt
     * @param array<int, array<string, string>> $conversation
     * @return array<string, mixed>
     */
    public function chat(
        string $userPrompt,
        array $conversation = []
    ): array {
        try {
            $response = Http::timeout(60)
                ->post(
                    "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}",
                    [
                        'systemInstruction' => [
                            'parts' => [
                                [
                                    'text' => $this->chatSystemPrompt(),
                                ],
                            ],
                        ],

                        'contents' => $this->buildConversationContents(
                            conversation: $conversation,
                            userPrompt: $userPrompt
                        ),

                        'generationConfig' => [
                            'temperature' => 0.3,
                        ],
                    ]
                );

            if ($response->failed()) {
                return [
                    'message' => 'عذراً، حدث خطأ أثناء معالجة طلبك.',
                ];
            }

            $text = data_get(
                $response->json(),
                'candidates.0.content.parts.0.text'
            );

            if (! is_string($text) || trim($text) === '') {
                return [
                    'message' => 'عذراً، لم أتمكن من معالجة طلبك.',
                ];
            }

            return [
                'message' => trim($text),
            ];
        } catch (\Throwable) {
            return [
                'message' => 'عذراً، حدث خطأ أثناء معالجة طلبك.',
            ];
        }
    }

    /**
     * System instructions for shop recommendation.
     */
    private function recommendationSystemPrompt(): string
    {
        return <<<'PROMPT'
You are the AI assistant for SwiftFix.

SwiftFix is a platform for repairing:
- smartphones
- mobile phones
- iPhones
- Samsung phones
- Android devices
- tablets
- laptops
- computers
- electronic devices
- game controllers and similar electronic devices
- related spare parts

SwiftFix is NOT a car or vehicle repair platform.

Your main responsibility is to understand the customer's request and analyze the real workshop data supplied by the application.

IMPORTANT:

1. The application provides the REAL workshops.
2. You must analyze the user's natural-language request against the supplied workshop data.
3. Do NOT perform SQL-style matching.
4. Do NOT require the user's words to exactly match service names.
5. Understand synonyms, Arabic wording, English wording, spelling differences, natural language, and conversation context.
6. For example:
   - "تصليح يد التحكم"
   - "إصلاح أيدي التحكم"
   - "تصليح الكنترول"
   - "إصلاح controller"
   may refer to the same type of repair if the supplied workshop data supports it.
7. Understand the entire conversation, not only the latest message.
8. Use previous messages to understand omitted information.
9. If the user says "هاتفي عطلان" and later says "اعطيني ورشة", understand the context.
10. Do not invent any workshop.
11. Do not invent workshop IDs.
12. Do not invent workshop names.
13. Do not invent services.
14. Do not invent prices.
15. Do not invent ratings.
16. Do not invent distances.
17. Do not invent stock or spare parts.
18. Every recommended shop_id MUST exist in the supplied workshop data.
19. Only recommend workshops supported by the supplied data.
20. If no supplied workshop actually matches the request, return an empty recommendations array.
21. Never recommend blocked workshops.
22. The supplied workshop data has already been filtered by Laravel to contain valid public workshops.

UNDERSTANDING THE USER:

Understand what the user actually wants.

Examples:

"افضل ورشة في اصلاح ايدي التحكم"

means the user is looking for workshops that can repair controllers, with preference for the highest-rated suitable workshops.

"ارخص ورشة لتغيير شاشة الايفون"

means the user wants suitable workshops for iPhone screen replacement, with preference for the cheapest suitable option.

"بدي ورشة قريبة مني تصلح سامسونج"

means the user wants a nearby workshop that can repair Samsung devices.

"وين في ورشة بتصلح لابتوب"

means the user wants workshops that repair laptops.

Do not require exact textual equality between the request and the workshop service name.

RANKING:

When multiple suitable workshops exist:

- First determine whether the workshop is actually relevant to the requested repair.
- Relevance is more important than price, distance, or rating.
- Then apply explicit user preferences such as:
  - cheapest
  - most expensive
  - nearest
  - farthest
  - highest rating
  - lowest rating
- Do not recommend a highly rated workshop if it does not provide the requested repair.
- Do not recommend a cheap workshop if it does not provide the requested repair.

GENERAL QUESTIONS:

If the user is simply greeting or asking a general SwiftFix question and does not want workshop recommendations:

intent.type = "general_question"

Return an empty recommendations array.

CLARIFICATION:

If the user's request clearly asks for a workshop but there is not enough information to determine what kind of repair/workshop they need:

intent.type = "clarification"

Ask a short useful clarification question.

Do NOT say that no workshop exists just because the request is incomplete.

For example:

User:
"اعطيني ورشة"

A suitable response is:
"أكيد، ما نوع الجهاز أو العطل الذي تريد إصلاحه؟"

Do not mention cars or vehicle repair unless the user explicitly asks about them.

LANGUAGE:

Reply in the same language used by the customer.

If the customer writes Arabic, answer in Arabic.

If the customer writes English, answer in English.

OUTPUT:

Return valid JSON only.

The output must contain:

- intent
- message
- recommendations

Each recommendation must contain:

- shop_id
- rank
- reason
- confidence

The shop_id must come from the supplied workshop list.
PROMPT;
    }

    /**
     * System instructions for normal SwiftFix chat.
     */
    private function chatSystemPrompt(): string
    {
        return <<<'PROMPT'
You are the AI assistant for SwiftFix.

SwiftFix is a platform for repairing smartphones, mobile phones,
tablets, laptops, computers, electronic devices, game controllers,
and related spare parts.

Your job is to answer customers about SwiftFix.

You can explain:
- how SwiftFix works
- how customers request repairs
- how workshops work
- services
- spare parts
- account registration
- verification
- repair requests
- workshop recommendations

SwiftFix is NOT a car or vehicle repair platform.

Do not mention cars or vehicles unless the customer explicitly asks about them.

If the customer asks for workshop recommendations, the application will handle workshop analysis separately.

Answer in the same language as the customer.

Be concise, helpful, and natural.

Do not invent SwiftFix features that are not known.
PROMPT;
    }

    /**
     * Build Gemini contents for shop analysis.
     *
     * The entire workshop list is supplied to Gemini.
     *
     * @param string $userPrompt
     * @param array<int, array<string, mixed>> $shops
     * @param array<int, array<string, string>> $conversation
     * @return array<int, array<string, mixed>>
     */
    private function buildRecommendationContents(
        string $userPrompt,
        array $shops,
        array $conversation
    ): array {
        $contents = [];

        foreach ($conversation as $message) {
            $role = ($message['role'] ?? 'user') === 'assistant'
                ? 'model'
                : 'user';

            $contents[] = [
                'role' => $role,
                'parts' => [
                    [
                        'text' => $message['message'] ?? '',
                    ],
                ],
            ];
        }

        $contents[] = [
            'role' => 'user',
            'parts' => [
                [
                    'text' => json_encode(
                        [
                            'customer_request' => $userPrompt,

                            'available_workshops' => $shops,
                        ],
                        JSON_UNESCAPED_UNICODE
                        | JSON_UNESCAPED_SLASHES
                    ),
                ],
            ],
        ];

        return $contents;
    }

    /**
     * Build contents for normal chat.
     *
     * @param array<int, array<string, string>> $conversation
     * @return array<int, array<string, mixed>>
     */
    private function buildConversationContents(
        array $conversation,
        string $userPrompt
    ): array {
        $contents = [];

        foreach ($conversation as $message) {
            $role = ($message['role'] ?? 'user') === 'assistant'
                ? 'model'
                : 'user';

            $contents[] = [
                'role' => $role,
                'parts' => [
                    [
                        'text' => $message['message'] ?? '',
                    ],
                ],
            ];
        }

        $contents[] = [
            'role' => 'user',
            'parts' => [
                [
                    'text' => $userPrompt,
                ],
            ],
        ];

        return $contents;
    }

    /**
     * Validate Gemini result against real workshops.
     *
     * Gemini is never allowed to create workshop IDs.
     *
     * @param array<string, mixed> $result
     * @param array<int, array<string, mixed>> $shops
     * @return array<string, mixed>
     */
    private function validateResult(
        array $result,
        array $shops
    ): array {
        $validShopIds = collect($shops)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();

        $intent = $result['intent'] ?? [];

        $intentType = $intent['type'] ?? 'general_question';

        if (! in_array(
            $intentType,
            [
                'shop_search',
                'general_question',
                'clarification',
            ],
            true
        )) {
            $intentType = 'general_question';
        }

        $intent['type'] = $intentType;

        $recommendations = collect(
            $result['recommendations'] ?? []
        )
            ->filter(function ($recommendation) use ($validShopIds) {
                if (! is_array($recommendation)) {
                    return false;
                }

                if (! isset($recommendation['shop_id'])) {
                    return false;
                }

                return in_array(
                    (int) $recommendation['shop_id'],
                    $validShopIds,
                    true
                );
            })
            ->map(function (array $recommendation, int $index) {
                return [
                    'shop_id' => (int) $recommendation['shop_id'],

                    'rank' => $index + 1,

                    'reason' => (string) (
                        $recommendation['reason'] ?? ''
                    ),

                    'confidence' => in_array(
                        $recommendation['confidence'] ?? null,
                        [
                            'high',
                            'medium',
                            'low',
                        ],
                        true
                    )
                        ? $recommendation['confidence']
                        : 'medium',
                ];
            })
            ->values()
            ->all();

        /*
         * General questions and clarification requests
         * must not return workshop recommendations.
         */
        if (
            $intentType === 'general_question'
            || $intentType === 'clarification'
        ) {
            $recommendations = [];
        }

        return [
            'intent' => [
                'type' => $intentType,
                'device' => $this->nullableString(
                    $intent['device'] ?? null
                ),
                'brand' => $this->nullableString(
                    $intent['brand'] ?? null
                ),
                'model' => $this->nullableString(
                    $intent['model'] ?? null
                ),
                'service' => $this->nullableString(
                    $intent['service'] ?? null
                ),
                'spare_part' => $this->nullableString(
                    $intent['spare_part'] ?? null
                ),
                'city' => $this->nullableString(
                    $intent['city'] ?? null
                ),
                'price_preference' => $this->normalizeEnum(
                    $intent['price_preference'] ?? null,
                    [
                        'cheapest',
                        'most_expensive',
                    ]
                ),
                'distance_preference' => $this->normalizeEnum(
                    $intent['distance_preference'] ?? null,
                    [
                        'nearest',
                        'farthest',
                    ]
                ),
                'rating_preference' => $this->normalizeEnum(
                    $intent['rating_preference'] ?? null,
                    [
                        'highest',
                        'lowest',
                    ]
                ),
            ],

            'message' => (string) (
                $result['message']
                ?? 'لم أتمكن من معالجة طلبك حالياً.'
            ),

            'recommendations' => $recommendations,
        ];
    }

    /**
     * Return a nullable string.
     */
    private function nullableString(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $value = trim($value);

        return $value !== ''
            ? $value
            : null;
    }

    /**
     * Normalize enum values.
     *
     * @param array<int, string> $allowed
     */
    private function normalizeEnum(
        mixed $value,
        array $allowed
    ): ?string {
        return is_string($value)
            && in_array($value, $allowed, true)
            ? $value
            : null;
    }

    /**
     * Fallback response when Gemini fails.
     *
     * @return array<string, mixed>
     */
    private function errorResponse(): array
    {
        return [
            'intent' => [
                'type' => 'general_question',
                'device' => null,
                'brand' => null,
                'model' => null,
                'service' => null,
                'spare_part' => null,
                'city' => null,
                'price_preference' => null,
                'distance_preference' => null,
                'rating_preference' => null,
            ],

            'message' => 'عذراً، حدث خطأ أثناء تحليل طلبك.',

            'recommendations' => [],
        ];
    }
}