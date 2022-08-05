<?php


namespace App\Modules\Store\Models\BalanceReconciliation;


use App\Modules\Application\Traits\ModelCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class BalanceReconciliationUsageRemark extends Model
{
    use ModelCodeGenerator;
    protected $table = 'balance_reconciliation_usage_remarks';
    protected $primaryKey = 'balance_reconciliation_usage_remark_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'balance_reconciliation_usage_remark_code',
        'balance_reconciliation_usages_code',
        'remark',
        'created_by',
        'updated_by',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->balance_reconciliation_usage_remark_code = $model->generateBalanceReconciliationUsageRemakCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

    }

    public function generateBalanceReconciliationUsageRemakCode(){
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'BRURC', '1000', false);
    }

    public function balanceReconciliationUsage(){
        return $this->belongsTo(BalanceReconciliationUsage::class,'balance_reconciliation_usages_code','balance_reconciliation_usages_code');
    }


}
