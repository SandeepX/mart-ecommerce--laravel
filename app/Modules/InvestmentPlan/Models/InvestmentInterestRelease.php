<?php


namespace App\Modules\InvestmentPlan\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class InvestmentInterestRelease extends Model
{
    use ModelCodeGenerator;

    protected $table = 'investment_plan_interest_release_options';
    protected $primaryKey = 'ipir_option_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ipir_option_code',
        'investment_plan_code',
        'interest_release_time',
        'is_active',
        'created_by',
        'updated_by'
    ];

    const RECORDS_PER_PAGE = 10;

    const INTEREST_RELEASE_TIME = ['monthly','yearly','quaterly','semi-annually'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = getAuthUserCode();
            $model->updated_by = getAuthUserCode();
            $model->ipir_option_code = $model->generateInvestmentInterestReleaseCode();
        });

        static::updating(function ($model) {
            $model->updated_by = getAuthUserCode();
            $model->updated_at = Carbon::now();
        });

    }

    public function generateInvestmentInterestReleaseCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'IPIR', '1000', false);
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

