<?php


namespace App\Modules\AlpasalWarehouse\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Store\Models\StoreOrder;

class WarehouseSalesStock extends Model
{


    protected $table = 'warehouse_sales_stocks';

    protected $primaryKey = 'warehouse_sales_stock_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [

        'warehouse_sales_stock_code',
        'store_order_code',
        'warehouse_product_stock_code',
        'remarks'

    ];

    public function generateCode()
    {
        $prefix = 'WSS';
        $initialIndex = '1000';
        $warehouseSalesStock = self::latest('id')->first();
        if ($warehouseSalesStock) {
            $codeTobePad = (int)(str_replace($prefix, "", $warehouseSalesStock->warehouse_sales_stock_code) + 1);
            //$paddedCode = str_pad($codeTobePad, 5, '0', STR_PAD_LEFT);
            $latestCode = $prefix . $codeTobePad;
        } else {
            $latestCode = $prefix . $initialIndex;
        }
        return $latestCode;
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->warehouse_sales_stock_code = $model->generateCode();
        });
    }

    public function storeOrder()
    {
        return $this->belongsTo(StoreOrder::class, 'store_order_code','store_order_code');
    }
}






















