<?php


namespace App\Modules\AlpasalWarehouse\Models\PreOrder;


use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;

class PreOrderPackagingUnitDisableList extends Model
{

    protected $table = 'preorder_packaging_unit_disable_list';
    protected $primaryKey = 'preorder_packaging_unit_disable_list_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'warehouse_preorder_product_code',
        'unit_name',
        'disabled_by',
    ];

    const UNIT_NAMES=['micro','unit','macro','super'];

    public function generateCode()
    {
        $prefix = 'PPUDL';
        $initialIndex = '1000';
        $list = self::latest('id')->first();
        if($list){
            $codeTobePad = (int) (str_replace($prefix,"",$list->preorder_packaging_unit_disable_list_code) +1 );
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
            $model->preorder_packaging_unit_disable_list_code = $model->generateCode();
        });

        static::updating(function ($model) {
            $authUserCode = getAuthUserCode();
            $model->disabled_by = $authUserCode;
        });

    }

    public function warehousePreOrderProduct(){
        return $this->belongsTo(
            WarehousePreOrderProduct::class,
            'warehouse_preorder_product_code',
            'warehouse_preorder_product_code');
    }

    public function disabledBy(){
        return $this->belongsTo(User::class, 'disabled_by', 'user_code');
    }
}
