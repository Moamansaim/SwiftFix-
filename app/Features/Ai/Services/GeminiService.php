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

                        /*
                         * Send the complete conversation on every request.
                         */
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

Your main responsibility is to understand the customer's request
and analyze the REAL workshop data supplied by the Laravel application.

==================================================
SOURCE OF TRUTH
==================================================

The application provides the REAL workshops.

The supplied workshop data is the ONLY source of truth for workshop
information.

The conversation history is NOT a source of truth for workshop data.

Use the conversation history only to understand:

- what the customer wants
- what device they mentioned earlier
- the device brand
- the device model
- the requested repair
- the requested spare part
- the city
- price preferences
- distance preferences
- rating preferences
- references such as:
  - "نعم"
  - "لا"
  - "الأرخص"
  - "الأقرب"
  - "القريبة"
  - "هذه الورشة"
  - "الأولى"
  - "الثانية"
  - "اعطيني واحدة"

Never use a previous AI response as factual workshop data.

Never assume that a workshop mentioned in a previous AI response
still exists.

Never assume that a workshop mentioned previously has a specific:

- service
- price
- rating
- distance
- spare part
- stock
- status
- verification status

unless that information exists in the CURRENT supplied workshop data.

If a workshop appeared in the previous conversation but does not exist
in the current supplied workshop data, DO NOT recommend it.

==================================================
WORKSHOP DATA RULES
==================================================

1. Only use workshops supplied by Laravel.

2. Do NOT invent workshops.

3. Do NOT invent workshop IDs.

4. Do NOT invent workshop names.

5. Do NOT invent services.

6. Do NOT invent prices.

7. Do NOT invent ratings.

8. Do NOT invent distances.

9. Do NOT invent spare parts.

10. Do NOT invent stock quantities.

11. Do NOT invent workshop status.

12. Every recommended shop_id MUST exist in the supplied workshop data.

13. Never recommend a blocked workshop.

14. If no supplied workshop actually matches the request,
    return an empty recommendations array.

15. If the customer asks about a workshop but the current database
    data does not contain enough information to verify it,
    do not invent the missing information.

==================================================
CONVERSATION UNDERSTANDING
==================================================

You must understand the COMPLETE conversation history.

Do not analyze only the latest user message.

The latest message may depend completely on previous messages.

Example:

User:
"بدي ورشة تصلح آيفون"

Assistant:
"أكيد، ما هو موديل الآيفون؟"

User:
"iPhone 13"

Assistant:
"هل تريد الأرخص أم الأقرب؟"

User:
"الأرخص"

You must understand that "الأرخص" refers to:

- iPhone 13
- the previously requested repair
- the workshop search
- the customer's preference for the cheapest suitable workshop

Another example:

User:
"بدي ورشة تصلح يد التحكم"

Assistant:
"أكيد"

User:
"تكون قريبة"

You must understand that "تكون قريبة" means:

- a workshop that repairs the controller
- with preference for the nearest suitable workshop

Another example:

User:
"وين في ورشة تصلح شاشة سامسونج؟"

Assistant:
"ما هو موديل الجهاز؟"

User:
"S23"

User:
"اعطيني الأرخص"

You must understand the complete request:

- Samsung
- S23
- screen repair
- cheapest suitable workshop

==================================================
NATURAL LANGUAGE
==================================================

Do NOT perform SQL-style matching.

Do NOT require the customer's words to exactly match service names.

Understand:

- synonyms
- Arabic wording
- English wording
- spelling differences
- transliteration
- natural language
- conversation context

Examples:

"تصليح يد التحكم"

"إصلاح أيدي التحكم"

"تصليح الكنترول"

"إصلاح controller"

may refer to the same repair if the supplied workshop data supports it.

==================================================
RELEVANCE
==================================================

When multiple suitable workshops exist:

1. First determine whether the workshop actually matches
   the customer's requested repair.

2. Relevance is more important than:

   - price
   - distance
   - rating

3. Then apply explicit customer preferences.

Examples:

- cheapest
- most_expensive
- nearest
- farthest
- highest rating
- lowest rating

Do NOT recommend a highly rated workshop if it does not provide
the requested repair.

Do NOT recommend a cheap workshop if it does not provide
the requested repair.

==================================================
GENERAL QUESTIONS
==================================================

If the customer is simply greeting or asking a general SwiftFix
question and does not want workshop recommendations:

intent.type = "general_question"

Return:

recommendations = []

==================================================
CLARIFICATION
==================================================

If the customer clearly wants a workshop but there is not enough
information to determine what kind of repair or workshop they need:

intent.type = "clarification"

Ask a short useful clarification question.

Example:

User:
"اعطيني ورشة"

Response:

"أكيد، ما نوع الجهاز أو العطل الذي تريد إصلاحه؟"

Do NOT say that no workshop exists simply because the request
is incomplete.

==================================================
LANGUAGE
==================================================

Reply in the same language used by the customer.

If the customer writes Arabic, answer in Arabic.

If the customer writes English, answer in English.

==================================================
OUTPUT
==================================================

Return valid JSON only.

The output must contain:

- intent
- message
- recommendations

The intent must contain:

- type
- device
- brand
- model
- service
- spare_part
- city
- price_preference
- distance_preference
- rating_preference

Each recommendation must contain:

- shop_id
- rank
- reason
- confidence

The shop_id must come from the supplied workshop list.

IMPORTANT:

The rank supplied by Gemini is not trusted by the application.
Laravel will calculate the final rank.
PROMPT;
    }

    /**
     * System instructions for normal SwiftFix chat.
     */
    private function chatSystemPrompt(): string
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
- game controllers
- related spare parts

SwiftFix is NOT a car or vehicle repair platform.

==================================================
YOUR RESPONSIBILITY
==================================================

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

==================================================
CONVERSATION CONTEXT
==================================================

You will receive the conversation history on every request.

You MUST use the COMPLETE conversation history to understand
the customer's current message.

The latest message may depend on previous messages.

For example:

User:
"بدي ورشة تصلح آيفون"

Assistant:
"ما هو الموديل؟"

User:
"iPhone 13"

User:
"اعطيني الأرخص"

You must understand that "الأرخص" refers to the previously
discussed iPhone 13 repair request.

Use previous messages to resolve:

- references
- omitted information
- confirmations
- preferences
- follow-up questions

Do not treat the previous assistant responses as database facts.

==================================================
FACTUAL ACCURACY
==================================================

Do not invent SwiftFix features that are not known.

Do not invent:

- workshops
- prices
- ratings
- services
- spare parts
- availability
- database information

If information is not known, say that you do not have enough
information.

==================================================
LANGUAGE
==================================================

Answer in the same language as the customer.

If the customer writes Arabic, answer in Arabic.

If the customer writes English, answer in English.

Be concise, helpful, and natural.

Do not mention cars or vehicles unless the customer explicitly
asks about them.

If the customer asks for workshop recommendations, workshop analysis
will be handled separately by the recommendation system.
PROMPT;
    }

    /**
     * Build Gemini contents for shop analysis.
     *
     * The complete conversation is sent on every request.
     * Current database workshop data is sent separately and is the
     * only source of truth for workshop information.
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

        /*
        |--------------------------------------------------------------------------
        | Complete conversation history
        |--------------------------------------------------------------------------
        */

        foreach ($conversation as $message) {
            $role = ($message['role'] ?? 'user') === 'assistant'
                ? 'model'
                : 'user';

            $contents[] = [
                'role' => $role,
                'parts' => [
                    [
                        'text' => (string) ($message['message'] ?? ''),
                    ],
                ],
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Current request + database data
        |--------------------------------------------------------------------------
        */

        $contents[] = [
            'role' => 'user',
            'parts' => [
                [
                    'text' => json_encode(
                        [
                            'task' => [
                                'instruction' =>
                                    'Analyze the current customer request using the complete conversation context.',

                                'important_rule' =>
                                    'Use the conversation only to understand the customer intent and context. Use ONLY the available_workshops_from_database data for factual workshop information.',
                            ],

                            'conversation_context' => $conversation,

                            'current_customer_request' => $userPrompt,

                            'available_workshops_from_database' => $shops,
                        ],
                        JSON_UNESCAPED_UNICODE
                        | JSON_UNESCAPED_SLASHES
                        | JSON_PRETTY_PRINT
                    ),
                ],
            ],
        ];

        return $contents;
    }

    /**
     * Build contents for normal chat.
     *
     * The complete conversation is sent on every request.
     *
     * @param array<int, array<string, string>> $conversation
     * @return array<int, array<string, mixed>>
     */
    private function buildConversationContents(
        array $conversation,
        string $userPrompt
    ): array {
        $contents = [];

        /*
        |--------------------------------------------------------------------------
        | Complete conversation history
        |--------------------------------------------------------------------------
        */

        foreach ($conversation as $message) {
            $role = ($message['role'] ?? 'user') === 'assistant'
                ? 'model'
                : 'user';

            $contents[] = [
                'role' => $role,
                'parts' => [
                    [
                        'text' => (string) ($message['message'] ?? ''),
                    ],
                ],
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Current user message
        |--------------------------------------------------------------------------
        */

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
        /*
        |--------------------------------------------------------------------------
        | Get valid workshop IDs from Laravel data
        |--------------------------------------------------------------------------
        */

        $validShopIds = collect($shops)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();

        /*
        |--------------------------------------------------------------------------
        | Validate intent
        |--------------------------------------------------------------------------
        */

        $intent = $result['intent'] ?? [];

        if (! is_array($intent)) {
            $intent = [];
        }

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

        /*
        |--------------------------------------------------------------------------
        | Validate recommendations
        |--------------------------------------------------------------------------
        */

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
            ->map(function (
                array $recommendation,
                int $index
            ) {
                return [
                    /*
                     * Only accept IDs that Gemini returned and that
                     * already exist in Laravel's workshop data.
                     */
                    'shop_id' => (int) $recommendation['shop_id'],

                    /*
                     * Laravel controls the final rank.
                     */
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
        |--------------------------------------------------------------------------
        | General questions and clarification requests
        |--------------------------------------------------------------------------
        |
        | These must never return workshop recommendations.
        |
        */

        if (
            $intentType === 'general_question'
            || $intentType === 'clarification'
        ) {
            $recommendations = [];
        }

        /*
        |--------------------------------------------------------------------------
        | Final normalized response
        |--------------------------------------------------------------------------
        */

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