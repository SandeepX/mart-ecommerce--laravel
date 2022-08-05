<?php

namespace App\Modules\Wallet\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Types\Models\UserType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WalletTransactionPurpose extends Model
{
    use SoftDeletes,ModelCodeGenerator;
    protected $table = 'wallet_transaction_purpose';
    protected $primaryKey = 'wallet_transaction_purpose_code';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'wallet_transaction_purpose_code',
        'purpose',
        'purpose_type',
        'slug',
        'is_active',
        'admin_control',
        'close_for_modification',
        'user_type_code',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    const PAGINATED_BY = 10;

    const PURPOSE_TYPES = ['increment','decrement'];

    const PURPOSE_WISE_REQUIRED_FIELDS = [
        'sales-reconciliation-increment' => ['order_code','ref_bill_no'],
        'pre-orders-sales-reconciliation-increment' => ['order_code','ref_bill_no'],
        'sales-reconciliation-deduction' => ['order_code','ref_bill_no'],
        'pre-orders-sales-reconciliation-deduction' => ['order_code','ref_bill_no'],
        'transaction-correction-deduction' => ['transaction_code'],
        'transaction-correction-increment' => ['transaction_code'],
        'cash-received' => ['ref_bill_no'],
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->wallet_transaction_purpose_code = $model->generateWalletTransactionPurposeCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

    }

    public function generateWalletTransactionPurposeCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'WTPC', '1000', true);
    }

    public function userType(){
        return $this->belongsTo(UserType::class,'user_type_code','user_type_code');
    }
}
