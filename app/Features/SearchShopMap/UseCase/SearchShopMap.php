<?php

namespace App\Features\SearchShopMap\UseCase;

use App\Features\ShopOwner\Models\Shop;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class SearchShopMap
{
    /**
     * Search for nearby shops by service or spare part.
     *
     * The result contains only:
     * - Shop ID.
     * - Shop name.
     * - Shop latitude.
     * - Shop longitude.
     * - Distance from customer.
     * - Requested service information.
     * - Requested spare part information.
     */
    public function execute(array $data): Collection
    {
        $latitude = (float) $data['latitude'];

        $longitude = (float) $data['longitude'];

        /*
        |--------------------------------------------------------------------------
        | Fixed Search Radius
        |--------------------------------------------------------------------------
        |
        | The customer does not send the radius.
        | The search radius is fixed to 20 kilometers.
        |
        */

        $radius = 20;

        /*
        |--------------------------------------------------------------------------
        | Base Query
        |--------------------------------------------------------------------------
        |
        | Only return:
        | - Shops with a name.
        | - Open or closed shops.
        | - Verified shops.
        | - Shops with valid coordinates.
        |
        */

        $query = Shop::query()
            ->select([
                'shops.id',
                'shops.shop_name',
                'shops.latitude',
                'shops.longitude',
            ])
            ->whereNotNull('shops.shop_name')
            ->whereIn(
                'shops.status',
                ['open', 'closed']
            )
            ->whereNotNull('shops.latitude')
            ->whereNotNull('shops.longitude');

        /*
        |--------------------------------------------------------------------------
        | Calculate Distance
        |--------------------------------------------------------------------------
        */

        $distanceQuery = $this->distanceQuery(
            $latitude,
            $longitude
        );

        $query->selectRaw(
            "{$distanceQuery} AS distance",
            [
                $latitude,
                $longitude,
                $latitude,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Service Search
        |--------------------------------------------------------------------------
        */

        if (!empty($data['service_id'])) {

            $this->applyServiceSearch(
                $query,
                (int) $data['service_id']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Spare Part Search
        |--------------------------------------------------------------------------
        */

        if (!empty($data['product_id'])) {

            $this->applyProductSearch(
                $query,
                (int) $data['product_id']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Distance Filter
        |--------------------------------------------------------------------------
        |
        | Only shops within 20 kilometers are returned.
        |
        */

        $query
            ->having(
                'distance',
                '<=',
                $radius
            )
            ->orderBy('distance');

        /*
        |--------------------------------------------------------------------------
        | Get Results
        |--------------------------------------------------------------------------
        */

        $shops = $query->get();

        /*
        |--------------------------------------------------------------------------
        | Transform Response
        |--------------------------------------------------------------------------
        |
        | Do not return all Shop model fields.
        | Return only the data required by the map.
        |
        */

        return $shops->map(
            function (Shop $shop) use ($data) {

                /*
                |--------------------------------------------------------------------------
                | Basic Shop Information
                |--------------------------------------------------------------------------
                */

                $result = [
                    'id' => $shop->id,

                    'shop_name' => $shop->shop_name,

                    'latitude' => (float) $shop->latitude,

                    'longitude' => (float) $shop->longitude,

                    'distance' => round(
                        (float) $shop->distance,
                        2
                    ),
                ];

                /*
                |--------------------------------------------------------------------------
                | Service Result
                |--------------------------------------------------------------------------
                */

                if (!empty($data['service_id'])) {

                    $service = $shop->services->first();

                    if ($service) {

                        $result['service'] = [
                            'id' => $service->id,

                            'name' => $service->service_name,

                            'price' => $service->pivot->price,
                        ];
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Spare Part Result
                |--------------------------------------------------------------------------
                */

                if (!empty($data['product_id'])) {

                    $product = $shop->shopProducts->first();

                    if ($product) {

                        $result['product'] = [
                            'id' => $product->product_id,

                            'name' => $product->product->product_name,

                            'price' => $product->price,

                            'quantity' => $product->quantity,

                            'status' => $product->status,
                        ];
                    }
                }

                return $result;
            }
        );
    }

    /**
     * Search shops that provide the requested service.
     */
    private function applyServiceSearch(
        Builder $query,
        int $serviceId
    ): void {

        /*
        |--------------------------------------------------------------------------
        | Filter Shops By Service
        |--------------------------------------------------------------------------
        */

        $query->whereHas(
            'services',
            function (Builder $serviceQuery) use ($serviceId) {

                $serviceQuery->where(
                    'services.id',
                    $serviceId
                );
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Load Only Requested Service
        |--------------------------------------------------------------------------
        |
        | We do not need all services of the shop.
        |
        */

        $query->with([
            'services' => function ($serviceQuery) use ($serviceId) {

                $serviceQuery
                    ->select([
                        'services.id',
                        'services.service_name',
                    ])
                    ->where(
                        'services.id',
                        $serviceId
                    );
            },
        ]);
    }

    /**
     * Search shops that have the requested spare part.
     */
    private function applyProductSearch(
        Builder $query,
        int $productId
    ): void {

        /*
        |--------------------------------------------------------------------------
        | Filter Shops By Spare Part
        |--------------------------------------------------------------------------
        |
        | The product must:
        | - Match the requested product.
        | - Have quantity greater than zero.
        | - Have available status.
        |
        */

        $query->whereHas(
            'shopProducts',
            function (Builder $productQuery) use ($productId) {

                $productQuery
                    ->where(
                        'product_id',
                        $productId
                    )
                    ->where(
                        'quantity',
                        '>',
                        0
                    )
                    ->where(
                        'status',
                        'available'
                    );
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Load Only Requested Spare Part
        |--------------------------------------------------------------------------
        */

        $query->with([
            'shopProducts' => function ($productQuery) use ($productId) {

                $productQuery
                    ->select([
                        'id',
                        'shop_id',
                        'product_id',
                        'quantity',
                        'price',
                        'status',
                    ])
                    ->where(
                        'product_id',
                        $productId
                    )
                    ->where(
                        'quantity',
                        '>',
                        0
                    )
                    ->where(
                        'status',
                        'available'
                    )
                    ->with([
                        'product:id,product_name',
                    ]);
            },
        ]);
    }

    /**
     * Build Haversine distance formula.
     *
     * Distance is returned in kilometers.
     */
    private function distanceQuery(
        float $latitude,
        float $longitude
    ): string {

        return '
            6371 * ACOS(
                LEAST(
                    1,
                    GREATEST(
                        -1,
                        COS(RADIANS(?))
                        * COS(RADIANS(latitude))
                        * COS(
                            RADIANS(longitude)
                            - RADIANS(?)
                        )
                        + SIN(RADIANS(?))
                        * SIN(RADIANS(latitude))
                    )
                )
            )
        ';
    }
}