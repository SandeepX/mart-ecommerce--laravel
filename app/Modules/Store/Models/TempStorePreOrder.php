<?php

namespace App\Modules\Store\Models;

use App\Modules\AlpasalWarehouse\Models\PreOrder\WarehousePreOrderListing;
use Illuminate\Database\Eloquent\Model;

class TempStorePreOrder extends Model
{
    protected $table = 'temp_store_preorder';

    public function store(){
        return $this->belongsTo(Store::class,'store_code','store_code');
    }

    public function warehousePreOrderListing(){
        return $this->belongsTo(WarehousePreOrderListing::class,'warehouse_preorder_listing_code','warehouse_preorder_listing_code');
    }
}
