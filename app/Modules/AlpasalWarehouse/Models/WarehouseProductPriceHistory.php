<?php


namespace App\Modules\AlpasalWarehouse\Models;


use Illuminate\Database\Eloquent\Model;

class WarehouseProductPriceHistory extends Model
{

    protected $table = 'warehouse_product_price_history';
    protected $primaryKey = 'warehouse_product_price_history_code';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'warehouse_product_master_code',
        'mrp',
        'admin_margin_type',
        'admin_margin_value',
        'wholesale_margin_type',
        'wholesale_margin_value',
        'retail_margin_type',
        'retail_margin_value',
        'from_date',
        'to_date'
    ];

    public function generateCode()
    {
        $prefix = 'WPPH';
        $initialIndex = '1000';
        $warehouseProductPriceHistory = self::latest('id')->first();
        if($warehouseProductPriceHistory){
            $codeTobePad = (int) (str_replace($prefix,"",$warehouseProductPriceHistory->warehouse_product_price_history_code) +1 );
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
            $model->warehouse_product_price_history_code = $model->generateCode();
        });
    }

    public function warehouseProductMaster(){
        return $this->belongsTo(WarehouseProductMaster::class,'warehouse_product_master_code','warehouse_product_master_code');
    }
}
