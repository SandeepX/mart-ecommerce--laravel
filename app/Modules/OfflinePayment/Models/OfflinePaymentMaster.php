<?php

namespace App\Modules\OfflinePayment\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Store\Models\Payments\StoreMiscellaneousPaymentDocument;
use App\Modules\User\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class OfflinePaymentMaster extends Model
{
    use ModelCodeGenerator;
    protected $table = 'offline_payment_master';
    protected $primaryKey = 'offline_payment_code';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'offline_payment_code',
        'payment_for',
        'offline_payment_holder_namespace',
        'payment_holder_type',
        'offline_payment_holder_code',
        'payment_type',
        'deposited_by',
        'transaction_date',
        'contact_phone_no',
        'amount',
        'verification_status',
        'responded_by',
        'responded_at',
        'remarks',
        'has_matched',
        'questions_checked_meta',
        'created_by',
        'reference_code'
    ];

    const PAYMENT_FOR = ['initial_registration','load_balance','investment'];
    const PAYMENT_TYPE = ['cash', 'cheque','remit','wallet','mobile_banking'];
    const VERIFICATION_STATUSES = ['pending', 'verified', 'rejected'];

    const RECORDS_PER_PAGE = 10;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->offline_payment_code = $model->generateOfflinePaymentCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });
    }

    public function generateOfflinePaymentCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'OFPC', '1000', false);
    }

    public function respondedBy()
    {
        return $this->belongsTo(User::class, 'responded_by', 'user_code');
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

    public function offlinePaymentRemarks(){
        return $this->hasMany(OfflinePaymentRemark::class,'offline_payment_code','offline_payment_code');
    }

    public function paymentMetaData()
    {
        return $this->hasMany(OfflinePaymentMeta::class, 'offline_payment_code', 'offline_payment_code');
    }

    public function paymentDocuments()
    {
        return $this->hasMany(OfflinePaymentDoc::class, 'offline_payment_code', 'offline_payment_code');
    }

    public function submittedBy(){
       return $this->belongsTo(User::class ,'created_by','user_code');
    }

    public function offlinePaymentable()
    {
        return $this->morphTo(__FUNCTION__,'offline_payment_holder_namespace','offline_payment_holder_code');
    }


}
