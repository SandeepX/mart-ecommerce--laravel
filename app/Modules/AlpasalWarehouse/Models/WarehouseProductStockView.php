<?php


namespace App\Modules\AlpasalWarehouse\Models;


use Illuminate\Database\Eloquent\Model;

class WarehouseProductStockView extends Model
{
    protected $table = 'warehouse_product_stock_view';

    public function warehouseProductMaster(){
        return $this->belongsTo(WarehouseProductMaster::class,'code','warehouse_product_master_code');
    }
}
