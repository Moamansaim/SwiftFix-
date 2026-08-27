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
     */
    public function addToFavorites(FavoriteRequest $favoriteRequest): JsonResponse
    {
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

        $favorite = Favorite::create([
            'user_id' => $user->id,
            'shop_id' => $shopId,
        ]);

        return response()->json([
            'message' => 'تمت إضافة الورشة إلى المفضلة بنجاح.',
            'favorite' => $favorite,
        ], 201);
    }

    /**
     * Get current user's favorites.
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
     */
    public function removeFromFavorites(FavoriteRequest $favoriteRequest): JsonResponse
    {
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
            'message' => 'تم حذف الورشة من المفضلة بنجاح.',
        ], 200);
    }
}