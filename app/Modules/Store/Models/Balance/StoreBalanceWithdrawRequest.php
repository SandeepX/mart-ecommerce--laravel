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
use App\Modules\Store\Models\Store;
use App\Modules\User\Models\User;
use App\Modules\Wallet\Models\WalletTransactionPurpose;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class StoreBalanceWithdrawRequest extends Model
{

    use ModelCodeGenerator;

    protected $table = 'store_balance_withdraw_request';
    protected $primaryKey = 'store_balance_withdraw_request_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'store_balance_withdraw_request_code',
        'store_code',
        'reason',
        'status',
        'withdraw_date',
        'requested_amount',
        'document',
        'remarks',
        'verified_by',
        'verified_at',
        'account_no',
        'payment_body_code',
        'account_meta'

    ];


    const status = ['pending','completed','rejected','processing','cancelled'];

    const DOCUMENT_PATH = 'uploads/stores/withdraw/documents/';

    const RECORDS_PER_PAGE=10;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->store_balance_withdraw_request_code = $model->generateStoreBalanceWithdrawRequestCode();
            $model->created_by=getAuthUserCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

    }

    public function generateStoreBalanceWithdrawRequestCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'BWR', '1000', false);
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_code', 'store_code');
    }

    public function verifiedBy(){
        return $this->belongsTo(User::class,'verified_by','user_code');
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
    public function verificationDetails()
    {
        return $this->hasMany(StoreBalanceWithdrawRequestVerificationDetail::class,'store_balance_withdraw_request_code','store_balance_withdraw_request_code');
    }

    public function getWalletTransactionPurpose(){
         return WalletTransactionPurpose::where('slug','withdraw')
                                     ->where('user_type_code',$this->store->storeUserTypeCode())
                                     ->latest()
                                     ->first();
    }


}
