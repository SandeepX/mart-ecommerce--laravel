<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/22/2020
 * Time: 12:34 PM
 */

namespace App\Modules\Store\Models\Payments;


use App\Modules\Application\Traits\ModelCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class StoreMiscellaneousPaymentDocument extends Model
{

    use ModelCodeGenerator;

    protected $table = 'store_miscellaneous_payments_docs';
    protected $primaryKey = 'store_payment_doc_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'store_payment_doc_code','store_misc_payment_code','document_type','file_name'
    ];

    const UPLOAD_PATH = 'uploads/stores/payments/';

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->store_payment_doc_code = $model->generateMiscPaymentDocumentCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

    }

    public function generateMiscPaymentDocumentCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'SMPD', '1000', false);
    }

    public function storePayment()
    {
        return $this->belongsTo(StoreMiscellaneousPayment::class, 'store_misc_payment_code', 'store_misc_payment_code');
    }
}