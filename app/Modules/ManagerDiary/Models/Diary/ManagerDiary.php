<?php

namespace App\Modules\ManagerDiary\Models\Diary;

use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Location\Models\LocationHierarchy;
use App\Modules\SalesManager\Models\Manager;
use App\Modules\Store\Models\Store;
use App\Modules\User\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ManagerDiary extends Model
{

    use SoftDeletes, ModelCodeGenerator;
    protected $table = 'manager_diaries';
    protected $primaryKey = 'manager_diary_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'manager_diary_code',
        'manager_code',
        'store_name',
        'referred_store_code',
        'owner_name',
        'phone_no',
        'alt_phone_no',
        'pan_no',
        'ward_code',
        'full_location',
        'latitude',
        'longitude',
        'business_investment_amount',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    CONST PAGINATE_BY = 20;

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->manager_diary_code = $model->generateCode();
        });
        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });
    }

    public function generateCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'MD', 1000, true);
    }

    public function ward()
    {
        return $this->belongsTo(LocationHierarchy::class, 'ward_code', 'location_code');
    }

    public function referredStore(){
        return $this->belongsTo(Store::class,'referred_store_code','store_code');
    }

    public function createdBy(){
        return $this->belongsTo(User::class,'created_by','user_code');
    }

    public function manager(){
        return $this->belongsTo(Manager::class,'manager_code','manager_code');
    }

}
