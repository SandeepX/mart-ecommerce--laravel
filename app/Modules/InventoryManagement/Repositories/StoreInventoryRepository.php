<?php


namespace App\Modules\InventoryManagement\Repositories;


use App\Modules\Application\Abstracts\RepositoryAbstract;
use App\Modules\InventoryManagement\Models\StoreInventory;

class StoreInventoryRepository extends RepositoryAbstract
{

    public function findStoreInventoryProductByProductAndProductVariantCode($product_code,$pv_code)
    {
        return StoreInventory::with($this->with)
            ->select($this->select)
            ->where('product_code',$product_code)
            ->where('product_variant_code',$pv_code)
            ->where('store_code',getAuthStoreCode())
            ->first();
    }

    public function store($validatedData)
    {
        return StoreInventory::create($validatedData)->fresh();
    }

    public function getAllProductFromInventoryByStoreCode($storeCode)
    {
        return StoreInventory::with($this->with)
            ->select($this->select)
            ->where('store_code',$storeCode)
            ->groupBy('product_code')
            ->get();
    }

    public function getAllStoreProductvariantFromInventoryByProductCode($productCode,$storeCode)
    {
        return StoreInventory::with($this->with)
            ->select($this->select)
            ->where('store_code',$storeCode)
            ->where('product_code',$productCode)
            ->get();
    }

}
