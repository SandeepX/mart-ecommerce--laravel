<?php

namespace App\Modules\Wallet\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Store\Models\Store;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wallet extends Model
{
    use SoftDeletes,ModelCodeGenerator;
    protected $table = 'wallets';
    protected $primaryKey = 'wallet_code';
    public $incrementing = false;
    protected $keyType = 'string';

    const PAGINATE_BY = 20;

    protected $relationTypes = [
         'App\Modules\Store\Models\Store'=>'Store',
         'App\Modules\User\Models\User'=> 'User',
         'App\Modules\SalesManager\Models\Manager'=> 'Manager',
         'App\Modules\Vendor\Models\Vendor'=>'Vendor'
    ];

    const WALLET_TYPE = [
        'store',
        'manager',
        'vendor'
    ];

    protected $fillable = [
        'wallet_code',
        'wallet_uuid',
        'wallet_holder_type',
        'wallet_type',
        'wallet_holder_code',
        'current_balance',
        'last_balance',
        'is_active',
        'created_by',
        'updated_by'
    ];

    const CREDIT_TYPES = [
        'sales_return',
        'load_balance',
        'rewards',
        'interest',
        'sales_reconciliation_increment',
        'pre_orders_sales_reconciliation_increment',
        'refund_release',
        'transaction_correction_increment',
        'preorder_refund',
        'janata_bank_increment',
        'cash_received',
        'store_referred_commission',
        'investment_commission'
    ];

    const DEBIT_TYPES = [
        'sales',
        'withdraw',
        'annual_charge',
        'refundable',
        'royalty',
        'preorder',
        'initial_registrations',
        'sales_reconciliation_deduction',
        'pre_orders_sales_reconciliation_deduction',
        'transaction_correction_deduction',
        'non_refundable_registration_charge'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->wallet_code = $model->generateWalletCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

    }

    public function generateWalletCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'WC', '1000', true);
    }

    public function walletable()
    {
        return $this->morphTo(__FUNCTION__,'wallet_holder_type','wallet_holder_code');
    }

    public function walletTransactions(){
        return $this->hasMany(WalletTransaction::class,'wallet_code','wallet_code');
    }

    public function getMorphObjectAttribute()
    {
        return (new \ReflectionClass($this))->getShortName();
    }

    public function getRelTypeAttribute($type)
    {
        if ($type === null) {
            return null;
        }
        return  $this->relationTypes[$type];
    }

    public function getLatestTransaction(){
        return $this->hasOne(WalletTransaction::class,'wallet_code','wallet_code')->latest();
    }


}
