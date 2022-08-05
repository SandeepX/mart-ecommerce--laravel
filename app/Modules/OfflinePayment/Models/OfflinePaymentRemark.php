<?php

namespace App\Modules\OfflinePayment\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class OfflinePaymentRemark extends Model
{
    use ModelCodeGenerator;
    protected $table = 'offline_payment_remarks';
    protected $primaryKey = 'offline_payment_remark_code';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'offline_payment_remark_code',
        'offline_payment_code',
        'remark',
        'created_by'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->offline_payment_remark_code = $model->generateOfflinePaymentRemarkCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });
    }
    public function generateOfflinePaymentRemarkCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'OFPRC', '1000', false);
    }
}
