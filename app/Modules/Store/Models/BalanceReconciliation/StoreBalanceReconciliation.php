<?php
/**
 * Created by phpstrome.
 * User: Sandeep
 * Date: 1/4/2021
 * Time: 11:25 PM
 */

namespace App\Modules\Store\Models\BalanceReconciliation;


use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Bank\Models\Bank;
use App\Modules\PaymentMedium\Models\DigitalWallet;
use App\Modules\PaymentMedium\Models\Remit;
use App\Modules\User\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class StoreBalanceReconciliation extends Model
{

    use ModelCodeGenerator;

    protected $table = 'balance_reconciliation';
    protected $primaryKey = 'balance_reconciliation_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'balance_reconciliation_code',
        'transaction_type',
        'payment_method',
        'payment_body_code',
        'transaction_no',
        'transaction_amount',
        'transacted_by',
        'description',
        'transaction_date',
        'created_by',
        'updated_by',
        'status'
    ];


    const transaction_type = ['withdraw','deposit'];

    const payment_method = ['bank','remit','digital_wallet'];

    const status = ['used','unused'];

    const RECORDS_PER_PAGE = 10;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->balance_reconciliation_code = $model->generateStoreBalanceReconciliationCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

    }

    public function generateStoreBalanceReconciliationCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'BR', '1000', false);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_code');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'user_code');
    }

    public function getBankName()
    {
        return $this->belongsTo(Bank::class,'payment_body_code','bank_code');
    }

    public function getRemitName()
    {
        return $this->belongsTo(Remit::class,'payment_body_code','remit_code');
    }

    public function getDigitalWalletName()
    {
        return $this->belongsTo(DigitalWallet::class, 'payment_body_code','wallet_code');
    }

    public function balanceReconciliationUsage(){
        return $this->hasOne(BalanceReconciliationUsage::class,'balance_reconciliation_code','balance_reconciliation_code');
    }

}
