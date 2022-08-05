<?php
/**
 * Created by VScode.
 * User: Sandeep
 * Date: 12/17/2020
 * Time: 11:25 PM
 */

namespace App\Modules\Store\Models\Balance;


use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Bank\Models\Bank;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreBalanceWithdrawRequestVerificationDetail extends Model
{

    use ModelCodeGenerator,SoftDeletes;

    protected $table = 'withdraw_request_verification_details';
    protected $primaryKey = 'withdraw_request_verification_details_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'withdraw_request_verification_details_code',
        'store_balance_withdraw_request_code',
        'payment_method',
        'payment_body_code',
        'status',
        'payment_verification_source',
        'amount',
        'payment_meta',
        'remarks',
        'created_by',
        'updated_by',
        'proof',
        'deleted_by',

    ];


    const DOCUMENT_PATH = 'uploads/stores/verification/documents/';

    const RECORDS_PER_PAGE=10;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->withdraw_request_verification_details_code = $model->generateStoreBalanceWithdrawRequestVerificationDetailCode();
            $model->created_by=getAuthUserCode();
        });

        static::updating(function ($model) {
            $model->updated_by = getAuthUserCode();
        });

        static::deleting(function ($model) {
            $model->deleted_by = getAuthUserCode();
        });

    }

    public function generateStoreBalanceWithdrawRequestVerificationDetailCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'BWRVD', '1000', true);
    }
    public function bank()
    {
        return $this->belongsTo(Bank::class,'payment_body_code','bank_code');
    }
    public function getPaymentBodyName()
    {
        if($this->payment_method=="bank")
        {
            return $this->bank ? $this->bank->bank_name : '--';
        }
    }
    public function withdrawRequest()
    {
        return $this->belongsTo(StoreBalanceWithdrawRequest::class,'store_balance_withdraw_request_code','store_balance_withdraw_request_code');
    }
}
