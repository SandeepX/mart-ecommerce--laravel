<?php

namespace App\Modules\PaymentGateway\Models;


use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'demo_payments';

    protected $primaryKey = 'txn_id';
    //protected $keyType = 'string';

    protected $fillable = [
        'txn_date',
        'txn_currency',
        'txn_amount',
        'reference_id',
        'remarks',
        'particulars',
        'status'
    ];


    /*protected $fillable = [
        'wallet_code',
        'amount',
        'transaction_type',
        'request',
        'request_at',
        'response',
        'response_at',
        'status'
    ];*/

    /*public function generateCode()
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
        });
    }*/


}
