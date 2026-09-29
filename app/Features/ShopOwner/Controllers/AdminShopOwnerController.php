<?php

namespace App\Features\ShopOwner\Controllers;

use App\Features\Auth\Models\User;
use App\Features\ShopOwner\Resources\AdminShopOwnerResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AdminShopOwnerController
{
    /**
     * Display all shop owners and their shop information.
     */
    public function index(): JsonResponse
    {
        $shopOwners = User::withTrashed()
            ->with([
                'shop.country',
                'shop.city',
                'shop.complaints'
            ])
            ->role('shopOwner')
            ->latest()
            ->get();

        return response()->json([
            'data' => AdminShopOwnerResource::collection($shopOwners),
        ], 200);
    }

    /**
     * Freeze shop owner account.
     */
    public function freeze(int $id): JsonResponse
    {
        $shopOwner = User::withTrashed()
            ->role('shopOwner')
            ->with('shop')
            ->findOrFail($id);

        if ($shopOwner->trashed()) {
            return response()->json([
                'message' => 'هذا الحساب مجمد بالفعل.',
            ], 422);
        }

        DB::transaction(function () use ($shopOwner) {

            // Freeze the account
            $shopOwner->delete();

            // Block the shop
            if ($shopOwner->shop) {
                $shopOwner->shop->update([
                    'status' => 'blocked',
                ]);
            }
        });

        return response()->json([
            'message' => 'تم تجميد الحساب وحظر الورشة بنجاح.',
        ], 200);
    }

    /**
     * Unfreeze shop owner account.
     */
    public function unfreeze(int $id): JsonResponse
    {
        $shopOwner = User::withTrashed()
            ->role('shopOwner')
            ->with('shop')
            ->findOrFail($id);

        if (! $shopOwner->trashed()) {
            return response()->json([
                'message' => 'هذا الحساب غير مجمد.',
            ], 422);
        }

        DB::transaction(function () use ($shopOwner) {

            // Unfreeze the account
            $shopOwner->restore();

            // Unblock the shop
            if ($shopOwner->shop) {
                $shopOwner->shop->update([
                    'status' => 'open',
                ]);
            }
        });

        return response()->json([
            'message' => 'تم فك تجميد الحساب وإلغاء حظر الورشة بنجاح.',
        ], 200);
    }

    /**
     * Permanently delete shop owner account and shop.
     */
    public function destroy(int $id): JsonResponse
    {
        $shopOwner = User::withTrashed()
            ->role('shopOwner')
            ->findOrFail($id);

        $shopOwner->forceDelete();

        return response()->json([
            'message' => 'تم حذف الحساب والورشة نهائيًا.',
        ], 200);
    }
}