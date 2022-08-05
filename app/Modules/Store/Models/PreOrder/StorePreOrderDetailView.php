<?php


namespace App\Modules\Store\Models\PreOrder;


use Illuminate\Database\Eloquent\Model;

class StorePreOrderDetailView extends Model
{
    protected $table='store_pre_order_detail_view';


    public function relatedStorePreOrderDetail(){
        return $this->belongsTo(StorePreOrderDetail::class,
            'store_preorder_detail_code',
        "store_preorder_detail_code");
    }

}
