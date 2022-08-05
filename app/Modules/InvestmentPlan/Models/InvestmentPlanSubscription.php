<?php


namespace App\Modules\InvestmentPlan\Models;




use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\OfflinePayment\Models\OfflinePaymentMaster;
use App\Modules\PaymentGateway\Models\OnlinePaymentMaster;
use App\Modules\SalesManager\Models\Manager;
use App\Modules\Store\Models\Store;
use App\Modules\User\Models\User;
use App\Modules\Vendor\Models\Vendor;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class InvestmentPlanSubscription extends Model
{
    use ModelCodeGenerator;

    protected $table = 'investment_plan_subscriptions';
    protected $primaryKey = 'ip_subscription_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ip_subscription_code',
        'investment_plan_code',
        'investment_plan_holder',
        'investment_holder_type',
        'investment_holder_id',
        'payment_mode',
        'payment_code',
        'investment_plan_name',
        'maturity_period',
        'ipir_option_code',
        'interest_rate',
        'invested_amount',
        'price_start_range',
        'price_end_range',
        'referred_by',
        'is_mature',
        'maturity_date',
        'is_active',
        'admin_status',
        'admin_remark',
        'status',
        'has_paid',
        'remark',
        'created_by',
        'updated_by'
    ];

    const RECORDS_PER_PAGE = 10;

    const PAYMENT_MODE = ['online','offline'];

    const PAYMENT_FOR = ['investment'];

    const PAYMENT_TYPE = ['cash', 'cheque','remit','wallet','mobile_banking'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->ip_subscription_code = $model->generateInvestmentPlanSubscriptionCode();
            $model->created_by = getAuthUserCode();
            $model->updated_by = getAuthUserCode();
        });

        static::updating(function ($model) {
            if(auth()->user()){
                $model->updated_by = getAuthUserCode();
            }
           // $model->updated_by = getAuthUserCode();
            $model->updated_at = Carbon::now();
        });
    }

    public function generateInvestmentPlanSubscriptionCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'IPS', '1000', false);
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

    public function investmentPlanInterestRelease()
    {
        return $this->belongsTo(InvestmentInterestRelease::class, 'ipir_option_code', 'ipir_option_code');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'referred_by', 'user_code');
    }

    public function userHolderId()
    {
        return $this->belongsTo(User::class, 'investment_holder_id', 'user_code');
    }

    public function managerHolderId()
    {
        return $this->belongsTo(Manager::class, 'investment_holder_id', 'manager_code');
    }

    public function vendorHolderId()
    {
        return $this->belongsTo(Vendor::class, 'investment_holder_id', 'vendor_code');
    }

    public function storeHolderId()
    {
        return $this->belongsTo(Store::class, 'investment_holder_id', 'store_code');
    }

    public function referredBy()
    {
        return $this->belongsTo(Manager::class, 'referred_by', 'manager_code');
    }

    public function investmentSubscriptionable(){
        return $this->morphTo(__FUNCTION__,'investment_plan_holder','investment_holder_id');
    }

    public function onlinePayment(){
        return $this->belongsTo(OnlinePaymentMaster::class,'payment_code','online_payment_master_code');
    }

    public function offlinePayment(){
        return $this->belongsTo(OfflinePaymentMaster::class,'payment_code','offline_payment_code');
    }

}


