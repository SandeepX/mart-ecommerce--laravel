<?php


namespace App\Modules\AlpasalWarehouse\Models;


use App\Modules\AlpasalWarehouse\Models\PreOrder\WarehousePreOrderProduct;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;

class WarehouseProductPackagingUnitDisableList extends Model
{

    protected $table = 'warehouse_product_packaging_unit_disable_list';
    protected $primaryKey = 'warehouse_product_packaging_unit_disable_list_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'warehouse_product_master_code',
        'unit_name',
        'disabled_by',
    ];

    const UNIT_NAMES=['micro','unit','macro','super'];

    public function generateCode()
    {
        $prefix = 'WPPUDL';
        $initialIndex = '1000';
        $list = self::latest('id')->first();
        if($list){
            $codeTobePad = (int) (str_replace($prefix,"",$list->warehouse_product_packaging_unit_disable_list_code) +1 );
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
            $model->warehouse_product_packaging_unit_disable_list_code = $model->generateCode();
        });

        static::updating(function ($model) {
            $authUserCode = getAuthUserCode();
            $model->disabled_by = $authUserCode;
        });

    }

    public function warehouseProduct(){
        return $this->belongsTo(
            WarehouseProductMaster::class,
            'warehouse_product_master_code',
            'warehouse_product_master_code');
    }

    public function disabledBy(){
        return $this->belongsTo(User::class, 'disabled_by', 'user_code');
    }
}
