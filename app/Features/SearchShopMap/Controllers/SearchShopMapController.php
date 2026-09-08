<?php

namespace App\Features\SearchShopMap\Controllers;

use App\Features\SearchShopMap\Requests\SearchShopMapRequest;
use App\Features\Shop\UseCases\SearchShopMap;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class SearchShopMapController extends Controller
{
    /**
     * Search shops by service or spare part.
     *
     * @param SearchShopMapRequest $request
     * @param SearchShopMap $searchShopMap
     * @return JsonResponse
     *
     * @hint Route:
     *        GET /api/shops/search
     */
    public function search(
        SearchShopMapRequest $request,
        SearchShopMap $searchShopMap
    ): JsonResponse {
        $shops = $searchShopMap->execute(
            $request->validated()
        );

        return response()->json([
            'shops' => $shops,
        ], 200);
    }
}