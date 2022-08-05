<?php


namespace App\Modules\PaymentGateway\Models;


use App\Modules\PaymentMedium\Models\DigitalWallet;
use App\Modules\Store\Models\Store;
use App\Modules\User\Models\User;
use App\Modules\Wallet\Models\WalletTransactionPurpose;
use Illuminate\Database\Eloquent\Model;

class OnlinePaymentMaster extends Model
{
    protected $table = 'online_payment_master';
    protected $primaryKey = 'online_payment_master_code';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'initiator_code',
        'wallet_code',
        'amount',
        'transaction_type',
        'transaction_id',
        'request',
        'request_at',
        'response',
        'payment_initiator',
        'reference_code',
        'response_at',
        'status',
        'created_at'
    ];

    public function generateCode()
    {
        $prefix = 'OPM';
        $initialIndex = '1000';
        $paymentMaster = self::latest('id')->first();
        if($paymentMaster){
            $codeTobePad = (int) (str_replace($prefix,"",$paymentMaster->online_payment_master_code) +1 );
            //$paddedCode = str_pad($codeTobePad, 5, '0', STR_PAD_LEFT);
            $latestCode = $prefix.$codeTobePad;
        }else{
            $latestCode = $prefix.$initialIndex;
        }
        return $latestCode;
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->online_payment_master_code  = $model->generateCode();
            $model->created_by  = getAuthUserCode();
        });
    }

    public function isPending()
    {
        if ($this->verification_status == 'pending') {
            return true;
        }
        return false;
    }

    public function isVerified()
    {

        if ($this->verification_status == 'verified') {
            return true;
        }
        return false;
    }

    public function isRejected()
    {
        if ($this->verification_status == 'rejected') {
            return true;
        }
        return false;
    }

    public function digitalWallet(){
        return $this->belongsTo(DigitalWallet::class,'wallet_code','wallet_code');
    }

    public function generateTransactionId(){
        return strtoupper(uniqueHash(8,request()->ip()));
    }

    public function onlinePaymentable()
    {
        return $this->morphTo(__FUNCTION__,'payment_initiator','initiator_code');
    }

    public function getWalletTransactionPurposeForLoadBalance(){
       return WalletTransactionPurpose::where('slug','load-balance')->latest()->first();
    }

    public function store(){
        return $this->belongsTo(Store::class,'initiator_code','store_code');
    }

    public function submittedBy(){
        return $this->belongsTo(User::class,'created_by','user_code');
    }

    public function paymentMetaData()
    {
        return $this->hasMany(OnlinePaymentMeta::class,  'online_payment_code','online_payment_master_code');
    }


}
