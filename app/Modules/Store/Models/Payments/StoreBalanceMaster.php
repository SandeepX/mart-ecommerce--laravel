<?php
/**
 * Created by VScode.
 * User: Sandeep
 * Date: 12/16/2020
 * Time: 12:25 PM
 */

namespace App\Modules\Store\Models\Payments;


use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Store\Models\Store;
use App\Modules\User\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\This;

class StoreBalanceMaster extends Model
{

    use ModelCodeGenerator;

    protected $table = 'store_balance_master';
    protected $primaryKey = 'store_balance_master_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'store_balance_master_code ',
        'store_code',
        'transaction_type',
        'transaction_amount',
        'current_balance',
        'created_by',
        'remarks',
        'proof_of_document'
    ];

    const TRANSACTION_TYPE = [
        'sales',
        'sales_return',
        'load_balance',
        'withdraw',
        'royalty',
        'annual_charge',
        'rewards',
        'interest',
        'refundable',
        'initial_registrations',
        'sales_reconciliation_increment',
        'sales_reconciliation_deduction',
        'pre_orders_sales_reconciliation_increment',
        'pre_orders_sales_reconciliation_deduction',
        'refund_release',
        'transaction_correction_increment',
        'transaction_correction_deduction',
        'preorder_refund',
        'janata_bank_increment',
        'cash_received',
        'non_refundable_registration_charge'
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
            'cash_received'
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

    const RECORDS_PER_PAGE=10;
    const IMAGE_PATH='uploads/store_balance_master/proof_of_document/';

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->store_balance_master_code = $model->generateMiscAccountandStatementCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

    }

    public function generateMiscAccountandStatementCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'SBM', '1000', false);
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_code', 'store_code');
    }

    public function storeloadbalancedetail(){
        return $this->hasOne(StoreLoadBalanceDetail::class,'store_balance_master_code');
    }


    public function createdBy(){
        return $this->belongsTo(User::class,'created_by','user_code');
    }
    public function salesreconciliation(){
        return $this->hasOne(SaleReconciliation::class,'store_balance_master_code');
    }
    public function storetransactioncorrection(){
        return $this->hasOne(StoreTransactionCorrectionDetail::class,'store_balance_master_code');
    }

    public function getProofOfDocumentImagePath(){
        return photoToUrl($this->proof_of_document,asset(self::IMAGE_PATH));
    }
}
