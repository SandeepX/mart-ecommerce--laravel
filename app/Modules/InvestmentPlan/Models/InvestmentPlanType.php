<?php


namespace App\Modules\InvestmentPlan\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class InvestmentPlanType extends Model
{
    use ModelCodeGenerator;

    protected $table = 'investment_plan_types';
    protected $primaryKey = 'ip_type_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ip_type_code',
        'name',
        'slug',
        'description',
        'is_active',
        'created_by',
        'updated_by'
    ];

    const RECORDS_PER_PAGE = 10;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = getAuthUserCode();
            $model->updated_by = getAuthUserCode();
            $model->ip_type_code = $model->generateInvestmentPlanTypeCode();
        });

        static::updating(function ($model) {
            $model->updated_by = getAuthUserCode();
            $model->updated_at = Carbon::now();
        });

    }

    public function generateInvestmentPlanTypeCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'IPT', '1000', false);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_code');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'user_code');
    }

}


