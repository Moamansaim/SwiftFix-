<?php

namespace App\Features\Favorite\Controller;

use App\Features\Favorite\Models\Favorite;
use App\Features\Favorite\Requests\FavoriteRequest;
use App\Features\Favorite\Resources\FavoriteResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Add a shop to favorites.
     *
     * @param FavoriteRequest $favoriteRequest
     *        The validated favorite request data.
     *
     * @return JsonResponse
     *
     * @hint Checks whether the shop is already in the user's favorites
     *        before creating a new favorite record. This prevents duplicate
     *        favorite entries for the same user and shop.
     */
    public function addToFavorites(
        FavoriteRequest $favoriteRequest
    ): JsonResponse {
        $user = Auth::guard('sanctum')->user();

        $shopId = $favoriteRequest->validated('shop_id');

        $favoriteExists = Favorite::where('user_id', $user->id)
            ->where('shop_id', $shopId)
            ->exists();

        if ($favoriteExists) {
            return response()->json([
                'message' => 'هذه الورشة مضافة بالفعل إلى المفضلة.',
            ], 422);
        }

        Favorite::create([
            'user_id' => $user->id,
            'shop_id' => $shopId,
        ]);

        return response()->json([
            'success' => true,
        ], 201);
    }

    /**
     * Get current user's favorites.
     *
     * @return JsonResponse
     *
     * @hint Retrieves the authenticated user's favorite shops
     *        with their country, city, and services using eager loading
     *        to avoid unnecessary database queries.
     */
    public function getMyFavorites(): JsonResponse
    {
        $user = Auth::guard('sanctum')->user();

        $favorites = Favorite::with([
            'shop.country',
            'shop.city',
            'shop.services',
        ])
            ->where('user_id', $user->id)
            ->get();

        return response()->json([
            'favorites' => FavoriteResource::collection($favorites),
        ], 200);
    }

    /**
     * Remove a shop from favorites.
     *
     * @param FavoriteRequest $favoriteRequest
     *        The validated favorite request data.
     *
     * @return JsonResponse
     *
     * @hint Finds the favorite record belonging to the authenticated user
     *        and the specified shop. If the favorite exists, it is deleted;
     *        otherwise, a not-found response is returned.
     */
    public function removeFromFavorites(
        FavoriteRequest $favoriteRequest
    ): JsonResponse {
        $user = Auth::guard('sanctum')->user();

        $shopId = $favoriteRequest->validated('shop_id');

        $favorite = Favorite::where('user_id', $user->id)
            ->where('shop_id', $shopId)
            ->first();

        if (! $favorite) {
            return response()->json([
                'message' => 'الورشة غير موجودة في المفضلة.',
            ], 404);
        }

        $favorite->delete();

        return response()->json([
            'success' => true,
        ], 200);
    }
}