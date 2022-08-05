<?php

namespace App\Modules\Store\Models\PreOrder;
use App\Modules\AlpasalWarehouse\Models\PreOrder\WarehousePreOrderListing;
use App\Modules\Store\Models\Store;
use Illuminate\Database\Eloquent\Model;

class StorePreOrderView extends Model
{
    protected $table = 'store_pre_orders_view';

    public function store(){
        return $this->belongsTo(Store::class,'store_code','store_code');
    }

    public function warehousePreOrderListing(){
        return $this->belongsTo(WarehousePreOrderListing::class,'warehouse_preorder_listing_code','warehouse_preorder_listing_code');
    }

    public function storePreOrderEarlyFinalization(){
        return $this->hasOne(StorePreOrderEarlyFinalization::class,'store_preorder_code','store_preorder_code');
    }

    public function storePreOrderEarlyCancellation(){
        return $this->hasOne(StorePreorderEarlyCancellation::class,'store_preorder_code','store_preorder_code');
    }

}
