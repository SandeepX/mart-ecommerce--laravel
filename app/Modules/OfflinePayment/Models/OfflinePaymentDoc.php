<?php

namespace App\Modules\OfflinePayment\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class OfflinePaymentDoc extends Model
{
    use ModelCodeGenerator;
    protected $table = 'offline_payments_docs';
    protected $primaryKey = 'offline_payment_docs_code';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'offline_payment_docs_code',
        'offline_payment_code',
        'document_type',
        'file_name'
    ];
    const UPLOAD_PATH = 'uploads/offline/payments/';

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->offline_payment_docs_code = $model->generateOfflinePaymentDocCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });
    }
    public function generateOfflinePaymentDocCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'OFPDC', '1000', false);
    }
}
