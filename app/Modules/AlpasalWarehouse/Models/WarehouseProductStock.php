<?php


namespace App\Modules\AlpasalWarehouse\Models;


use App\Modules\AlpasalWarehouse\Models\StockTransfer\WarehouseTransferStock;
use App\Modules\Package\Models\PackageType;
use Illuminate\Database\Eloquent\Model;

class WarehouseProductStock extends Model
{

    protected $table = 'warehouse_product_stock';
    protected $primaryKey = 'warehouse_product_stock_code';
    public $incrementing = false;
    protected $keyType = 'string';

    const INCREMENTS_TYPES = [
              'purchase',
              'sales-return',
              'received-stock-transfer'
    ];
    const DECREMENT_TYPES = [
              'sales',
              'purchase-return',
              'preorder_sales',
              'stock-transfer'
    ];

    const  STOCK_ACTIONS_TYPES = [
        'purchase',
        'sales-return',
        'received-stock-transfer',
        'sales',
        'purchase-return',
        'preorder_sales',
        'stock-transfer'
    ];

    protected $fillable = [
        'warehouse_product_master_code',
        'quantity',
        'package_qty',
        'package_code',
        'product_packaging_history_code',
        'reference_code',
        'action',
    ];

    public function generateCode()
    {
        $prefix = 'WPS';
        $initialIndex = '1000';
        $warehouseProductStock = self::latest('id')->first();
        if($warehouseProductStock){
            $codeTobePad = (int) (str_replace($prefix,"",$warehouseProductStock->warehouse_product_stock_code) +1 );
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
            $model->warehouse_product_stock_code  = $model->generateCode();
        });
    }

    public function warehouseProductMaster(){
        return $this->belongsTo(WarehouseProductMaster::class,'warehouse_product_master_code','warehouse_product_master_code');
    }

    public function packageType(){
        return $this->belongsTo(PackageType::class,'package_code','package_code');
    }

    public function warehousePurchaseStock(){
        return $this->hasOne(WarehousePurchaseStock::class, 'warehouse_product_stock_code  ', 'warehouse_product_stock_code ');
    }

    public function warehouseTransferStock()
    {
        return $this->hasOne(WarehouseTransferStock::class, 'warehouse_product_stock_code', 'warehouse_product_stock_code');
    }
}
