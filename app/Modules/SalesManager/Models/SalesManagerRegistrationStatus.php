<?php

namespace App\Modules\SalesManager\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Location\Models\LocationHierarchy;

class SalesManagerRegistrationStatus extends Model
{
    use ModelCodeGenerator;

    protected $table = 'sales_manager_registration_status';
    protected $primaryKey = 'sales_manager_registration_status_code';
    public $incrementing = false;

    protected $fillable = [
        'sales_manager_registration_status_code',
        'user_code',
        'status',
        'remarks',
        'assigned_area_code'
    ];


    const STATUS =['pending','processing','approved','rejected'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->sales_manager_registration_status_code = $model->generateUserRegistrationStatusCodeCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

    }

    public function generateUserRegistrationStatusCodeCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'SMRSC', '1000', false);
    }

    public function getLocationName()
    {
        return $this->belongsTo(LocationHierarchy::class,'assigned_area_code','location_code');
    }
}
