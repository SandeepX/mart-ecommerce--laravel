<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/1/2020
 * Time: 12:37 PM
 */

namespace App\Modules\Store\Models\Payments;


use App\Modules\Application\Traits\ModelCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class StoreOrderOfflinePaymentDocument extends Model
{

    use ModelCodeGenerator;

    protected $table = 'store_order_offline_payment_docs';
    protected $primaryKey = 'payment_doc_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'payment_doc_code','store_order_offline_payment_code','document_type','file_name'
    ];

    const UPLOAD_PATH = 'uploads/stores/payments/';

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->payment_doc_code = $model->generateOrderPaymentDocumentCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

    }

    public function generateOrderPaymentDocumentCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'SOPD', '1000', false);
    }

    public function storeOrderPayment()
    {
        return $this->belongsTo(StoreOrderOfflinePayment::class, 'store_order_offline_payment_code', 'store_offline_payment_code');
    }
}