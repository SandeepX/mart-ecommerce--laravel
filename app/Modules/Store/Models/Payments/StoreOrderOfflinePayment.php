<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/1/2020
 * Time: 12:29 PM
 */

namespace App\Modules\Store\Models\Payments;


use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Store\Models\Store;
use App\Modules\Store\Models\StoreOrder;
use App\Modules\User\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class StoreOrderOfflinePayment extends Model
{

    use ModelCodeGenerator;

    protected $table = 'store_order_offline_payments';
    protected $primaryKey = 'store_offline_payment_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'store_offline_payment_code',
        'user_code',
        'store_code',
        'store_order_code',
        'payment_type',
        'deposited_by',
        'purpose',
        'amount',
        'voucher_number',
        'payment_status',
        'responded_by',
        'responded_at',
        'remarks'
    ];


    const PAYMENT_TYPE = ['cash', 'cheque','remit','wallet'];

    const PAYMENT_STATUSES = ['pending', 'verified', 'rejected'];

    const RECORDS_PER_PAGE=10;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->store_offline_payment_code = $model->generateStoreOfflinePaymentCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

    }

    public function generateStoreOfflinePaymentCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'SOP', '1000', false);
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_code', 'store_code');
    }

    public function storeOrder()
    {
        return $this->belongsTo(StoreOrder::class, 'store_order_code', 'store_order_code');
    }

    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'user_code', 'user_code');
    }

    public function respondedBy()
    {
        return $this->belongsTo(User::class, 'responded_by', 'user_code');
    }

    public function paymentDocuments()
    {
        return $this->hasMany(StoreOrderOfflinePaymentDocument::class, 'store_order_offline_payment_code', 'store_offline_payment_code');
    }

    public function paymentMetaData()
    {
        return $this->hasMany(StoreOrderOfflinePaymentMeta::class, 'store_order_offline_payment_code', 'store_offline_payment_code');
    }


    public function isPending()
    {
        if ($this->payment_status == 'pending') {
            return true;
        }
        return false;
    }

    public function isVerified()
    {

        if ($this->payment_status == 'verified') {
            return true;
        }
        return false;
    }

    public function isRejected()
    {

        if ($this->payment_status == 'rejected') {
            return true;
        }
        return false;
    }
}