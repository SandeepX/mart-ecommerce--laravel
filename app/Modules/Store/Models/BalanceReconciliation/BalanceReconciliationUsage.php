<?php

namespace App\Modules\Store\Models\BalanceReconciliation;

use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class BalanceReconciliationUsage extends Model
{
    use SoftDeletes, ModelCodeGenerator;

    protected $table = 'balance_reconciliation_usages';
    protected $primaryKey = 'balance_reconciliation_usages_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'balance_reconciliation_usages_code',
        'balance_reconciliation_code',
        'used_for',
        'used_for_code',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->balance_reconciliation_usages_code = $model->generateBalanceReconciliationUsageCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

    }

    public function generateBalanceReconciliationUsageCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'BRU', '1000', false);
    }

    public function balanceReconciliationDetail()
    {
        return $this->belongsTo(StoreBalanceReconciliation::class, 'balance_reconciliation_code','balance_reconciliation_code');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by','user_code');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by','user_code');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by','user_code');
    }

    public function balanceReconciliationUsageRemarks(){
        return $this->hasMany(BalanceReconciliationUsageRemark::class,'balance_reconciliation_usages_code','balance_reconciliation_usages_code');
    }

}

