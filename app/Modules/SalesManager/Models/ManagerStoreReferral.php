<?php

namespace App\Modules\SalesManager\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Store\Models\Store;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ManagerStoreReferral extends Model
{
    use ModelCodeGenerator;
    protected $table = 'manager_store_referrals';
    protected $primaryKey = 'manager_store_referrals_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'manager_store_referrals_code',
        'manager_code',
        'referred_store_code',
        'referred_incentive_amount',
        'referred_incentive_amount_meta',
        'created_by',
        'updated_by'
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->manager_store_referrals_code = $model->generateCode();
        });
        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });
    }

    public function generateCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'MSR', 1000, false);
    }

    public function referredStore(){
        return $this->belongsTo(Store::class,'referred_store_code','store_code');
    }

    public function manager(){
        return $this->belongsTo(Manager::class,'manager_code','manager_code');
    }


}
