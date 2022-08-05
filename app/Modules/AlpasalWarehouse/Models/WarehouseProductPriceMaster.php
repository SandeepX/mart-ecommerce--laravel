<?php


namespace App\Modules\AlpasalWarehouse\Models;


use Illuminate\Database\Eloquent\Model;

class WarehouseProductPriceMaster extends Model
{

    protected $table = 'warehouse_product_price_master';
    protected $primaryKey = 'warehouse_product_price_code';
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
        'created_by',
        'updated_by'
    ];

    public function generateCode()
    {
        $prefix = 'WPPM';
        $initialIndex = '1000';
        $warehouseProductPriceMaster = self::latest('id')->first();
        if($warehouseProductPriceMaster){
            $codeTobePad = (int) (str_replace($prefix,"",$warehouseProductPriceMaster->warehouse_product_price_code) +1 );
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
            $authUserCode = getAuthUserCode();
            $model->warehouse_product_price_code = $model->generateCode();
            $model->created_by = $authUserCode;
            $model->updated_by = $authUserCode;
        });

        static::updating(function ($model) {
            $authUserCode = getAuthUserCode();
            $model->updated_by = $authUserCode;
        });
    }

    public function warehouseProductMaster(){
        return $this->belongsTo(WarehouseProductMaster::class,'warehouse_product_master_code','warehouse_product_master_code');
    }
}
