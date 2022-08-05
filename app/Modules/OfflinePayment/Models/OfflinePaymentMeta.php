<?php

namespace App\Modules\OfflinePayment\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class OfflinePaymentMeta extends Model
{
    use ModelCodeGenerator;
    protected $table = 'offline_payments_meta';
    protected $primaryKey = 'offline_payment_meta_code';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'offline_payment_meta_code',
        'offline_payment_code',
        'key',
        'value'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->offline_payment_meta_code = $model->generateOfflinePaymentMetaCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });
    }

    public function generateOfflinePaymentMetaCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'OFPMC', '1000', false);
    }
}
