<?php

namespace App\Modules\Store\Models;

use App\Modules\AlpasalWarehouse\Models\Warehouse;
use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Application\Traits\SetTimeZone;
use App\Modules\Store\Models\Payments\StoreOrderOfflinePayment;
use App\Modules\User\Models\User;
use App\Modules\Wallet\Models\WalletTransactionPurpose;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreOrder extends Model
{
    use SoftDeletes, ModelCodeGenerator,SetTimeZone;
    protected $table = 'store_orders';

    protected $primaryKey = 'store_order_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'total_price',
        'delivery_status',
        'acceptable_amount',
        'wh_code',
        'has_merged',
        'payment_status',
        'wh_code'
    ];

    const DELIVERY_STATUSES=[
        'Pending'=>'pending',
        'Dispatched'=>'dispatched',
        'Processing'=>'processing',
        'Accepted'=>'accepted',
        'Received'=>'received',
        'Cancelled'=>'cancelled',
        'Partially Accepted' => 'partially-accepted',
        'Under Verification' => 'under-verification',
        'Ready To Dispatch' => 'ready_to_dispatch',
    ];

    const ROWS_PER_PAGE=10;

    const VAT_PERCENTAGE_VALUE = 13;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->store_order_code = $model->generateCode();
            $model->user_code = getAuthUserCode();
            $model->store_code = getAuthStoreCode();
        });
    }

    public function generateCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this,$this->primaryKey,'S0',"1000",true);
        // return $this->generateModelCode($this, $this->primaryKey, 'SO', '00001', 5);
        // $prefix = 'SO';
        // $initialIndex = '1000';
        // $cart = self::latest('id')->first();
        // if ($cart) {
        //     $codeTobePad = (int) (str_replace($prefix, "", $cart->cart_code) + 1);
        //     // $paddedCode = str_pad($codeTobePad, 5, '0', STR_PAD_LEFT);
        //     $latestCode = $prefix . $codeTobePad;
        // } else {
        //     $latestCode = $prefix . $initialIndex;
        // }
        // return $latestCode;
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_code');
    }

    public function details()
    {
        return $this->hasMany(StoreOrderDetails::class, 'store_order_code');
    }

    public function statusLogs()
    {
        return $this->hasMany(StoreOrderStatusLog::class, 'store_order_code');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_code');
    }

    public function offlinePayments(){
        return $this->hasMany(StoreOrderOfflinePayment::class,'store_order_code','store_order_code');
    }

    public function ownStoreOfflinePayments(){
        return $this->offlinePayments()->where('store_code',getAuthStoreCode());
    }

    public function hasBeenPaid(){

        if ($this->payment_status == 1){
            return true;
        }

        return false;
    }

    public function getLatestOfflinePayment(){
        return $this->offlinePayments()->latest('id')->first();
    }

    public function getLatestOfflinePaymentStatus(){
        if ($this->getLatestOfflinePayment()){
            return $this->getLatestOfflinePayment()->payment_status;
        }
    }

    public function getLatestTranslatedOfflinePaymentStatus(){
        if ($this->getLatestOfflinePayment()){
            $paymentStatus= $this->getLatestOfflinePayment()->payment_status;
           /* if ($paymentStatus == 'verified'){
                return 'Paid';
            }*/
            return $paymentStatus;
        }

        return 'Unpaid';
    }

    public function isPaymentPending(){

       $paymentStatus = $this->getLatestOfflinePaymentStatus();
        if ($paymentStatus && $paymentStatus =='pending'){
            return true;
        }
        return false;
    }

    public function isPaymentVerified(){

        $paymentStatus = $this->getLatestOfflinePaymentStatus();
        if ($paymentStatus && $paymentStatus =='verified'){
            return true;
        }
        return false;

    }

    public function isPaymentRejected(){

        $paymentStatus = $this->getLatestOfflinePaymentStatus();
        if ($paymentStatus && $paymentStatus =='rejected'){
            return true;
        }

    }

    public function canAddOfflinePayment(){

        if($this->isPaymentRejected()){
            return true;
        }

        return false;
    }

    public function storeOrderDispatchDetail()
    {
        return $this->hasOne(StoreOrderDispatchDetail::class,'store_order_code', 'store_order_code' );
    }
    public function warehouse(){
        return $this->belongsTo(Warehouse::class,'wh_code');
    }

    public function orderRemarks(){
        return $this->hasMany(StoreOrderRemark::class,'store_order_code','store_order_code');
    }

    public function latestRemarks(){
        return $this->orderRemarks()->latest();
    }



}
