<?php

namespace App\Features\Shop\UseCases;

use App\Features\ShopOwner\Models\Shop;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class SearchShopMap
{
    /**
     * Search for shops by service or spare part.
     *
     * Calculates the distance between the customer location
     * and each shop when latitude and longitude are provided.
     *
     * @param array $data
     * @return Collection
     */
    public function execute(array $data): Collection
    {
        $latitude = $data['latitude'] ?? null;
        $longitude = $data['longitude'] ?? null;

        $radius = $data['radius'] ?? 20;

        $query = Shop::query()
            ->whereNotNull('shop_name')
            ->where('status', '!=', 'blocked')
            ->where('is_verified', true);

        /*
        |--------------------------------------------------------------------------
        | Distance
        |--------------------------------------------------------------------------
        */

        if (
            $latitude !== null &&
            $longitude !== null
        ) {
            $distanceQuery = $this->distanceQuery(
                $latitude,
                $longitude
            );

            $query
                ->select('shops.*')
                ->selectRaw(
                    "{$distanceQuery} AS distance",
                    [
                        $latitude,
                        $longitude,
                        $latitude,
                    ]
                )
                ->whereNotNull('latitude')
                ->whereNotNull('longitude');

            /*
             * Search only within the requested radius.
             */
            $query->having('distance', '<=', $radius);

            /*
             * Nearest shops first.
             */
            $query->orderBy('distance');
        }

        /*
        |--------------------------------------------------------------------------
        | Service Search
        |--------------------------------------------------------------------------
        */

        if (!empty($data['service_id'])) {
            $this->applyServiceSearch(
                $query,
                $data
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Spare Part Search
        |--------------------------------------------------------------------------
        */

        if (!empty($data['spare_part_id'])) {
            $this->applySparePartSearch(
                $query,
                $data
            );
        }

        return $query->get();
    }

    /**
     * Apply service search.
     *
     * @param Builder $query
     * @param array $data
     * @return void
     */
    private function applyServiceSearch(
        Builder $query,
        array $data
    ): void {
        $query->whereHas(
            'services',
            function (Builder $serviceQuery) use ($data) {

                $serviceQuery->where(
                    'services.id',
                    $data['service_id']
                );

                /*
                 * Filter service price.
                 */
                if (
                    isset($data['min_price']) ||
                    isset($data['max_price'])
                ) {
                    $serviceQuery->wherePivot(
                        'price',
                        '>=',
                        $data['min_price'] ?? 0
                    );

                    if (isset($data['max_price'])) {
                        $serviceQuery->wherePivot(
                            'price',
                            '<=',
                            $data['max_price']
                        );
                    }
                }
            }
        );

        /*
         * Return the requested service with its price.
         */
        $query->with([
            'services' => function ($serviceQuery) use ($data) {
                $serviceQuery
                    ->where(
                        'services.id',
                        $data['service_id']
                    )
                    ->withPivot('price');
            },
        ]);
    }

    /**
     * Apply spare part search.
     *
     * @param Builder $query
     * @param array $data
     * @return void
     */
    private function applySparePartSearch(
        Builder $query,
        array $data
    ): void {
        $query->whereHas(
            'shopProducts',
            function (Builder $productQuery) use ($data) {

                $productQuery->where(
                    'product_id',
                    $data['spare_part_id']
                );

                /*
                 * Search by device model when provided.
                 */
                if (!empty($data['device_model_id'])) {
                    $productQuery->where(
                        'device_model_id',
                        $data['device_model_id']
                    );
                }

                /*
                 * Filter product price.
                 */
                if (
                    isset($data['min_price']) ||
                    isset($data['max_price'])
                ) {
                    $productQuery->where(
                        'price',
                        '>=',
                        $data['min_price'] ?? 0
                    );

                    if (isset($data['max_price'])) {
                        $productQuery->where(
                            'price',
                            '<=',
                            $data['max_price']
                        );
                    }
                }

                /*
                 * Only products that are actually available.
                 */
                $productQuery->where(
                    'quantity',
                    '>',
                    0
                );
            }
        );

        /*
         * Return the requested spare part
         * with quantity and price.
         */
        $query->with([
            'shopProducts' => function ($productQuery) use ($data) {

                $productQuery
                    ->where(
                        'product_id',
                        $data['spare_part_id']
                    )
                    ->where(
                        'quantity',
                        '>',
                        0
                    );

                if (!empty($data['device_model_id'])) {
                    $productQuery->where(
                        'device_model_id',
                        $data['device_model_id']
                    );
                }

                $productQuery->with([
                    'product',
                    'deviceModel',
                ]);
            },
        ]);
    }

    /**
     * Build Haversine distance formula.
     *
     * @param float $latitude
     * @param float $longitude
     * @return string
     */
    private function distanceQuery(
        float $latitude,
        float $longitude
    ): string {
        return '
            6371 * ACOS(
                COS(RADIANS(?))
                * COS(RADIANS(latitude))
                * COS(
                    RADIANS(longitude)
                    - RADIANS(?)
                )
                + SIN(RADIANS(?))
                * SIN(RADIANS(latitude))
            )
        ';
    }
}