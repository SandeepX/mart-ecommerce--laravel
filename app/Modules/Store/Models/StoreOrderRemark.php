<?php

namespace App\Modules\Store\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class StoreOrderRemark extends Model
{
    use ModelCodeGenerator;

    protected $table = 'store_order_remarks';

    protected $primaryKey = 'store_order_remark_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'store_order_remark_code',
        'store_order_code',
        'remark',
        'created_by',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->store_order_remark_code = $model->generateCode();
        });
        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });
    }

    public function generateCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this,$this->primaryKey,'SORC',"1000",false);
    }

    public function storeOrder(){
         return $this->belongsTo(StoreOrder::class,'store_order_code','store_order_code');
    }

}
