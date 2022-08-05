<?php

namespace App\Modules\AlpasalWarehouse\Models\Setting;


use App\Modules\Application\Traits\ModelCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class WarehouseMinOrderAmountSetting extends Model
{

    use ModelCodeGenerator;
    protected $table = 'warehouse_min_order_amount_settings';
    protected $primaryKey = 'warehouse_min_order_amount_setting_code';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'warehouse_code',
        'min_order_amount',
        'status',
        'created_by',
        'updated_by'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->warehouse_min_order_amount_setting_code = $model->generateSettingWarehouseMinOrderAmountCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
            $model->updated_by = getAuthUserCode();
        });


    }

    public function generateSettingWarehouseMinOrderAmountCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'WMOASC', '1000', false);
    }

}
