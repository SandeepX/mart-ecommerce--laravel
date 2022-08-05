<?php

namespace App\Modules\PaymentGateway\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class OnlinePaymentMeta extends Model
{
    use ModelCodeGenerator;
    protected $table = 'online_payment_meta';
    protected $primaryKey = 'online_payment_meta_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'online_payment_meta_code',
        'online_payment_code',
        'key',
        'value'
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->online_payment_meta_code = $model->generateOnlinePaymentMetaCode();
        });
        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });
    }

    public function generateOnlinePaymentMetaCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'ONPMC', '1000', false);
    }
}
