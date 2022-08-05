<?php

namespace App\Modules\Store\Models;
use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Application\Traits\SetTimeZone;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreOrderStatusLog extends Model
{
    use SoftDeletes, ModelCodeGenerator,SetTimeZone;
    protected $table = 'store_order_status_log';

    protected $primaryKey = 'store_order_status_log_code';
    public $incrementing = false;
    protected $keyType = 'string';

    const MODEL_PREFIX="S0SL";

    protected $fillable = [
        'store_order_code',
        'status',
        'status_update_date',
        'remarks',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->store_order_status_log_code = $model->generateCode();
            $model->updated_by = getAuthUserCode();
        });
    }

    public function generateCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this,$this->primaryKey,'S0SL',"1000",true);
       // return $this->generateModelCode($this, $this->primaryKey, 'SOSL', '00001', 5);
    }

}
