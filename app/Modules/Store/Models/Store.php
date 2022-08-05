<?php

namespace App\Modules\Store\Models;

use App\Modules\AlpasalWarehouse\Models\Warehouse;
use App\Modules\Application\Traits\IsActiveScope;
use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Location\Models\LocationHierarchy;
use App\Modules\Location\Traits\LocationHelper;
use App\Modules\LuckyDraw\Models\StoreLuckydrawWinner;
use App\Modules\SalesManager\Models\Manager;
use App\Modules\SalesManager\Models\ManagerStore;
use App\Modules\SalesManager\Models\ManagerStoreReferral;
use App\Modules\Store\Helpers\StoreTransactionHelper;
use App\Modules\Store\Models\Balance\StoreCurrentBalance;
use App\Modules\Store\Models\Kyc\FirmKycMaster;
use App\Modules\Store\Models\Kyc\IndividualKYCMaster;
use App\Modules\Store\Models\Payments\StoreMiscellaneousPayment;
use App\Modules\Store\Models\PreOrder\StorePreOrder;
use App\Modules\Store\Models\StorePackageTypes\StorePackageHistory;
use App\Modules\Store\Models\StorePackageTypes\StoreTypePackageHistory;
use App\Modules\Types\Models\CompanyType;
use App\Modules\Types\Models\RegistrationType;
use App\Modules\Types\Models\StoreSize;
use App\Modules\Types\Models\StoreType;
use App\Modules\User\Models\User;
use App\Modules\Wallet\Models\WalletTransactionPurpose;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class
Store extends Model
{
    use SoftDeletes,ModelCodeGenerator,LocationHelper,IsActiveScope;
    protected $table = 'stores_detail';

    protected $primaryKey = 'store_code';
    public $incrementing = false;
    protected $keyType = 'string';

    const IMAGE_PATH='uploads/stores/logos/';

    protected $fillable = [
        'store_name',
        'slug',
        'store_location_code',
        'store_owner',
        'store_size_code',
        'store_contact_phone',
        'store_contact_mobile',
        'store_email',
        'store_registration_type_code',
        'store_company_type_code',
        'store_established_date',
        'store_logo',
        'pan_vat_type',
        'pan_vat_no',
        'store_landmark_name',
        'latitude',
        'longitude',
        'phone_verified_at',
        'email_verified_at',
        'user_code',
        'referred_by',
        'store_type_code',
        'status',
        'remarks',
        'registration_charge',
        'refundable_registration_charge',
        'base_investment',
        'created_by',
        'updated_by',
        'has_purchase_power',
        'store_type_package_history_code',
        'referral_code',
        'is_active',
        'has_store',
        'referred_incentive_amount'
    ];

    protected $attributes = [
        'registration_charge'=>0,
        'refundable_registration_charge'=>0,
        'base_investment'=>0,
    ];

    public function generateStoreCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'S', '1000', true);
    }

    public function registrationType(){
        return $this->belongsTo(RegistrationType::class, 'store_registration_type_code');
    }

    public function companyType(){
        return $this->belongsTo(CompanyType::class, 'store_company_type_code', 'company_type_code');
    }

    public function storeType(){
        return $this->belongsTo(StoreType::class, 'store_type_code', 'store_type_code');
    }
    public function storeTypePackage(){
        return $this->belongsTo(StoreTypePackageHistory::class, 'store_type_package_history_code', 'store_type_package_history_code');
    }

    public function storeSize(){
        return $this->belongsTo(StoreSize::class, 'store_size_code', 'store_size_code');
    }

    public function location(){
        return $this->belongsTo(LocationHierarchy::class, 'store_location_code');
    }

    public function documents(){
        return $this->hasMany(StoreDocument::class, 'store_code');
    }

    public function warehouses(){
        return $this->belongsToMany(Warehouse::class, 'store_warehouse', 'store_code', 'warehouse_code')
            ->withPivot('connection_status')->withTimestamps();
    }

    public function orders(){
        return $this->hasMany(StoreOrder::class, 'store_code');
    }


    public function user(){
        return $this->belongsTo(User::class,'user_code');
    }


    public function getLogoUploadPath(){

        return self::IMAGE_PATH;
    }

    public function individualKyc(){
        return $this->hasMany(IndividualKYCMaster::class,'store_code','store_code');
    }

    public function firmKyc(){
        return $this->hasOne(FirmKycMaster::class,'store_code','store_code');
    }

    public function storeCurrentBalance(){
        return $this->hasOne(StoreCurrentBalance::class,'store_code','store_code');
    }

    public function preOrders(){
        return $this->hasMany(StorePreOrder::class, 'store_code','store_code');
    }

    public function getCurrentBalance(){
        return StoreTransactionHelper::getStoreCurrentBalance($this->store_code);
//        if ($this->storeCurrentBalance){
//            return roundPrice($this->storeCurrentBalance->balance);
//        }
    }

    public function hasStorePaidInitialRegistrationCharge($storeCode){
        $store= StoreMiscellaneousPayment::where('store_code',$storeCode)
            ->where('payment_for','initial_registration')
            ->first();
        if($store){
            return true;
        }
        return false;
    }

    public function isApproved(){
        if($this->status == 'approved'){
            return true;
        }
        return false;
    }

    public function frozenBalance(){
        return $this->hasOne(StoreFrozenBalanceView::class,'store_code','store_code');
    }

    public function wallet()
    {
        return $this->morphOne('App\Modules\Wallet\Models\Wallet', 'walletable', 'wallet_holder_type', 'wallet_holder_code');
    }

    public function onlinePayment()
    {
        return $this->morphMany('App\Modules\PaymentGateway\Models\OnlinePaymentMaster', 'onlinePaymentable', 'payment_initiator','initiator_code');
    }

    public function offlinePayments()
    {
        return $this->morphMany('App\Modules\OfflinePayment\Models\OfflinePaymentMaster', 'offlinePaymentable', 'offline_payment_holder_namespace','offline_payment_holder_code');
    }

    public function investmentSubscriptions(){
        return $this->morphMany('App\Modules\InvestmentPlan\Models\InvestmentPlanSubscription', 'investmentSubscriptionable', 'investment_plan_holder','investment_holder_id');
    }

    public function getWalletTransactionPurposeForLoadBalance(){
        return    WalletTransactionPurpose::where('slug','load-balance')
                                        ->where('user_type_code',$this->storeUserTypeCode())
                                        ->latest()
                                        ->first();
    }

    public function getWalletTransactionPurposeForRefundable(){
        return   WalletTransactionPurpose::where('slug','refundable')
                                        ->where('user_type_code',$this->storeUserTypeCode())
                                        ->latest()
                                        ->first();
    }

    public function getWalletTransactionPurposeForNonRefundableRegistrationCharge(){
        return    WalletTransactionPurpose::where('slug','non-refundable-registration-charge')
                                            ->where('user_type_code',$this->storeUserTypeCode())
                                            ->latest()
                                            ->first();
    }

    public function getWalletTransactionPurposeForRefundRelease(){
        return    WalletTransactionPurpose::where('slug','refund-release')
                                            ->where('user_type_code',$this->storeUserTypeCode())
                                            ->latest()
                                            ->first();
    }

    public function getPreOrderWalletTransactionPurpose(){
        return WalletTransactionPurpose::where('slug','preorder')
                                    ->where('user_type_code',$this->storeUserTypeCode())
                                    ->latest()
                                    ->first();
    }

    public function getPreOrderRefundWalletTransactionPurpose(){
        return WalletTransactionPurpose::where('slug','preorder-refund')
                                    ->where('user_type_code',$this->storeUserTypeCode())
                                    ->latest()
                                    ->first();
    }

    public function getStoreOrderWalletTransactionPurpose(){
        return WalletTransactionPurpose::where('slug','sales')
            ->where('user_type_code',$this->storeUserTypeCode())
            ->latest()->first();
    }

    public function getStoreOrderRefundWalletTransactionPurpose(){
        return WalletTransactionPurpose::where('slug','sales-return')
            ->where('user_type_code',$this->storeUserTypeCode())
            ->latest()->first();
    }

    public function storeUserTypeCode(){
        return $this->user->userType->user_type_code;
    }

    public function referredBy(){
       return $this->hasOne(ManagerStoreReferral::class,'referred_store_code','store_code');
    }


    public function storePackageHistories()
    {
        return $this->hasMany(StorePackageHistory::class, 'store_code', 'store_code');
    }

    public function manager()
    {
        return $this->hasOne(ManagerStore::class, 'store_code', 'store_code');
    }

    public function storeLuckydrawWinners()
    {
        return $this->hasMany(StoreLuckydrawWinner::class,'store_code','store_code');
    }

    public function isAccountVerified(){
        if(!is_null($this->phone_verified_at)  || !is_null($this->email_verified_at)){
            return true;
        }
        return false;
    }
}
