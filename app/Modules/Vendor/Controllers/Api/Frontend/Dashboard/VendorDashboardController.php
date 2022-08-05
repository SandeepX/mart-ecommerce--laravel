<?php

namespace App\Modules\Vendor\Controllers\Api\Frontend\Dashboard;

use App\Http\Controllers\Controller;
use App\Modules\Product\Models\ProductMaster;
use App\Modules\Product\Resources\ProductListResource;

class VendorDashboardController extends Controller
{
    public function getDashboardStats()
    {
        $authVendorCode = getAuthVendorCode();
        $productsCount = ProductMaster::where('vendor_code', $authVendorCode)->count();
        $latestProductsAdded = ProductMaster::where('vendor_code', $authVendorCode)->latest()->limit(10)->get();





        return [
            'products_count' => $productsCount,
            'latestProductsAdded' => count($latestProductsAdded) ? ProductListResource::collection($latestProductsAdded) : [],

        ];
    }
}
