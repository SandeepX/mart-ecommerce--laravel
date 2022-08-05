<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/22/2020
 * Time: 12:25 PM
 */

namespace App\Modules\Store\Models\Payments;


use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Application\Traits\SetTimeZone;
use App\Modules\Store\Models\Store;
use App\Modules\User\Models\User;
use App\Modules\Wallet\Models\WalletTransaction;
use App\Modules\Wallet\Models\WalletTransactionPurpose;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class StoreMiscellaneousPayment extends Model
{

    use ModelCodeGenerator;

    protected $table = 'store_miscellaneous_payments';
    protected $primaryKey = 'store_misc_payment_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'store_misc_payment_code',
        'user_code',
        'store_code',
        'payment_for',
        'payment_type',
        'online_payment_master_code',
        'deposited_by',
        'purpose',
        'transaction_date',
        'amount',
        'voucher_number',
        'verification_status',
        'responded_by',
        'responded_at',
        'remarks',
        'contact_phone_no',
        'has_matched',
        'questions_checked_meta'
    ];


    const PAYMENT_FOR = ['initial_registration','load_balance','investment'];

    const PAYMENT_TYPE = ['cash', 'cheque','remit','wallet','mobile_banking'];

    const VERIFICATION_STATUSES = ['pending', 'verified', 'rejected'];

    const RECORDS_PER_PAGE=10;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->store_misc_payment_code = $model->generateMiscPaymentCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

    }

    public function generateMiscPaymentCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'SMP', '1000', false);
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_code', 'store_code');
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
        return $this->hasMany(StoreMiscellaneousPaymentDocument::class, 'store_misc_payment_code', 'store_misc_payment_code');
    }

    public function storeloadbalancedetail()
    {
        return $this->hasOne(StoreLoadBalanceDetail::class, 'store_load_balance_detail_code');
    }

    public function paymentMetaData()
    {
        return $this->hasMany(StoreMiscellaneousPaymentMeta::class, 'store_misc_payment_code', 'store_misc_payment_code');
    }

    public function isPending()
    {
        if ($this->verification_status == 'pending') {
            return true;
        }
        return false;
    }

    public function isVerified()
    {

        if ($this->verification_status == 'verified') {
            return true;
        }
        return false;
    }

    public function isRejected()
    {
        if ($this->verification_status == 'rejected') {
            return true;
        }
        return false;
    }

    public function miscellaneousPaymentRemarks(){
        return $this->hasMany(MiscellaneousPaymentRemark::class,'store_misc_payment_code','store_misc_payment_code');
    }
}
