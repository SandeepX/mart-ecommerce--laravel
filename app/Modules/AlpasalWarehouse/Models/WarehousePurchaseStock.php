<?php


namespace App\Modules\AlpasalWarehouse\Models;


use Illuminate\Database\Eloquent\Model;

class WarehousePurchaseStock extends Model
{

    protected $table = 'warehouse_purchase_stocks';
    protected $primaryKey = 'warehouse_purchase_stock_code ';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [

        'warehouse_product_stock_code',
        'warehouse_order_code',
        'remarks'
    ];

    public function generateCode()
    {
        $prefix = 'WPUS';
        $initialIndex = '1000';
        $warehousePurchaseStock = self::latest('id')->first();
        if($warehousePurchaseStock){
            $codeTobePad = (int) (str_replace($prefix,"",$warehousePurchaseStock->warehouse_purchase_stock_code) +1 );
            //$paddedCode = str_pad($codeTobePad, 5, '0', STR_PAD_LEFT);
            $latestCode = $prefix.$codeTobePad;
        }else{
            $latestCode = $prefix.$initialIndex;
        }
        return $latestCode;
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->warehouse_purchase_stock_code   = $model->generateCode();
        });
    }

    public function warehouseProductStock(){
        return $this->belongsTo(WarehouseProductStock::class,'warehouse_product_stock_code ','warehouse_product_stock_code ');
    }

    public function warehousePurchaseOrder(){
        return $this->belongsTo(WarehousePurchaseOrder::class,'warehouse_order_code  ','warehouse_order_code  ');
    }
}
