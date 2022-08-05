<?php

namespace App\Modules\Vendor\Repositories;

use App\Modules\Product\Models\ProductMaster;

class VendorProductRepository
{

    public function getProductOfVendor($productCode,$authVendorCode,$with=[]){
      return ProductMaster::with($with)->where('product_code',$productCode)->where('vendor_code',$authVendorCode)->first();
    }

    public function findOrFailProductOfVendor($productCode,$authVendorCode,$with=[]){
        return ProductMaster::with($with)->where('product_code',$productCode)
            ->where('vendor_code',$authVendorCode)->firstOrFail();
    }
    public function findOrFailProductOfVendorBySlug($productSlug,$authVendorCode,$with=[]){
        return ProductMaster::with($with)->where('slug',$productSlug)
            ->where('vendor_code',$authVendorCode)->firstOrFail();
    }

    public function updateVendorProductTaxability($product){
      $product->update([
        'is_taxable' => ! ($product->is_taxable)
      ]);

      return true;
    }


    public function updateVendorProductActivation($product){
        $product->update([
            'is_active' => ! ($product->is_active)
        ]);

        return true;
    }


}
