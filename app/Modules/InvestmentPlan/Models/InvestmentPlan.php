<?php


namespace App\Modules\InvestmentPlan\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class InvestmentPlan extends Model
{
    use ModelCodeGenerator;

    protected $table = 'investment_plans';
    protected $primaryKey = 'investment_plan_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'investment_plan_code',
        'name',
        'ip_type_code',
        'paid_up_capital',
        'per_unit_share_price',
        'maturity_period',
        'target_capital',
        'interest_rate',
        'terms',
        'description',
        'image',
        'price_start_range',
        'price_end_range',
        'is_active',
        'created_by',
        'updated_by'

    ];

    const RECORDS_PER_PAGE = 10;

    const IMAGE_PATH = 'uploads/investment/images/';

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = getAuthUserCode();
            $model->updated_by = getAuthUserCode();
            $model->investment_plan_code = $model->generateInvestmentPlanCode();
        });

        static::updating(function ($model) {
            $model->updated_by = getAuthUserCode();
            $model->updated_at = Carbon::now();
        });

    }

    public function generateInvestmentPlanCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'IP', '1000', false);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by','user_code');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class,'updated_by','user_code');
    }

    public function investmentInterestDetail()
    {
        return $this->hasMany(InvestmentInterestRelease::class,'investment_plan_code','investment_plan_code');
    }

    public function investmentCommissionDetail()
    {
        return $this->hasMany(InvestmentPlanCommission::class,'investment_plan_code','investment_plan_code');
    }

    public function activeInvestmentInterestDetail()
    {
        return $this->investmentInterestDetail()->where('is_active',1);
    }

    public function activeInvestmentCommissionDetail()
    {
        return $this->investmentCommissionDetail()->where('is_active',1);
    }

    public function activeInstantInvestmentCommissionDetail()
    {
        return $this->investmentCommissionDetail()->where('commission_type','instant')->where('is_active',1)->first();
    }

    public function investmentType()
    {
        return $this->belongsTo(InvestmentPlanType::class,'ip_type_code','ip_type_code');
    }


}

