<?php

namespace App\Modules\SMSProcessor\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;

use App\Modules\Store\Models\Payments\StoreBalanceMaster;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SmsMaster extends Model
{
    use ModelCodeGenerator;

    const PURPOSE = [
        'load_balance',
        'withdraw',
        'testing',

        'initial_registrations',
        'preorder_refund',
        'sales',
        'sales_return',
        'preorder',

        'royalty',
        'annual_charge',
        'rewards',
        'interest',
        'refundable',
        'sales_reconciliation_increment',
        'sales_reconciliation_deduction',
        'pre_orders_sales_reconciliation_increment',
        'pre_orders_sales_reconciliation_deduction',
        'refund_release',
        'transaction_correction_increment',
        'transaction_correction_deduction',
        'janata_bank_increment',
        'cash_received'

    ];
    protected $table = 'sms_master';
    protected $primaryKey = 'sms_master_code';
    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'sms_master_code',
        'request_body',
        'response_body',
        'purpose',
        'purpose_code'
    ];

    const RECORDS_PER_PAGE = 10;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->sms_master_code = $model->generateSmsMasterCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

    }

    public function generateSmsMasterCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'SMS', '1000', false);
    }

    public function balanceMasterDetail()
    {
        return $this->belongsTo(StoreBalanceMaster::class,'purpose_code','store_balance_master_code');
    }
}

