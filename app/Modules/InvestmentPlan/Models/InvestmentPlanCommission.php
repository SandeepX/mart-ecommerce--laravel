<?php


namespace App\Modules\InvestmentPlan\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class InvestmentPlanCommission extends Model
{
    use ModelCodeGenerator;

    protected $table = 'investment_plan_commission';
    protected $primaryKey = 'ipc_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ipc_code',
        'investment_plan_code',
        'commission_type',
        'commission_mount_type',
        'commission_amount_value',
        'is_active',
        'created_by',
        'updated_by'
    ];

    const RECORDS_PER_PAGE = 10;

    const COMMISSION_TYPE = ['annual','instant'];
    const COMMISSION_MOUNT_TYPE = ['p', 'f'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = getAuthUserCode();
            $model->updated_by = getAuthUserCode();
            $model->ipc_code = $model->generateInvestmentCommissionCode();
        });

        static::updating(function ($model) {
            $model->updated_by = getAuthUserCode();
            $model->updated_at = Carbon::now();
        });

    }

    public function generateInvestmentCommissionCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'IPC', '1000', false);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_code');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'user_code');
    }

    public function investmentPlan()
    {
        return $this->belongsTo(InvestmentPlan::class, 'investment_plan_code', 'investment_plan_code');
    }

}


