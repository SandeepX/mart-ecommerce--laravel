<?php

namespace App\Modules\SalesManager\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Location\Models\LocationHierarchy;
use App\Modules\User\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manager extends Model
{
    use SoftDeletes, ModelCodeGenerator;
    protected $table = 'managers_detail';
    protected $primaryKey = 'manager_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'manager_code',
        'manager_name',
        'manager_email',
        'manager_phone_no',
        'manager_photo',
        'has_two_wheeler_license',
        'has_four_wheeler_license',
        'is_active',
        'temporary_ward_code',
        'permanent_ward_code',
        'temporary_full_location',
        'permanent_full_location',
        'referral_code',
        'status',
        'status_responded_at',
        'assigned_area_code',
        'remarks',
        'email_verified_at',
        'phone_verified_at',
        'user_code',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    const STATUS =['pending','processing','approved','rejected'];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->manager_code = $model->generateCode();
        });
        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });
    }

    public function generateCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'M', 1000, true);
    }

    public function user(){
        return $this->belongsTo(User::class,'user_code');
    }

    public function ward()
    {
        return $this->belongsTo(LocationHierarchy::class, 'permanent_ward_code', 'location_code');
    }

    public function temporaryLocation()
    {
        return $this->belongsTo(LocationHierarchy::class, 'temporary_ward_code', 'location_code');
    }

    public function managerDocs()
    {
        return $this->hasMany(ManagerDoc::class, 'manager_code', 'manager_code');
    }

    public function getLocationName()
    {
        return $this->belongsTo(LocationHierarchy::class,'assigned_area_code','location_code');
    }
    public function wallet()
    {
        return $this->morphOne('App\Modules\Wallet\Models\Wallet', 'walletable', 'wallet_holder_type', 'wallet_holder_code');
    }

    public function offlinePayments()
    {
        return $this->morphMany('App\Modules\OfflinePayment\Models\OfflinePaymentMaster', 'offlinePaymentable', 'offline_payment_holder_namespace','offline_payment_holder_code');
    }

    public function investmentSubscriptions(){
        return $this->morphMany('App\Modules\InvestmentPlan\Models\InvestmentPlanSubscription', 'investmentSubscriptionable', 'investment_plan_holder','investment_holder_id');
    }

    public function managerUserTypeCode(){
        return $this->user->userType->user_type_code;
    }

    public function isAccountVerified(){
        if($this->phone_verified_at  || $this->email_verified_at){
            return true;
        }
        return false;
    }

}
