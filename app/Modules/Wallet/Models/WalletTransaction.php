<?php

namespace App\Modules\Wallet\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\User\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WalletTransaction extends Model
{
    use SoftDeletes, ModelCodeGenerator;
    protected $table = 'wallet_transaction';
    protected $primaryKey = 'wallet_transaction_code';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'wallet_transaction_code',
        'reference_code',
        'wallet_code',
        'wallet_transaction_purpose_code',
        'transaction_purpose_reference_code',
        'amount',
        'transaction_uuid',
        'remarks',
        'meta',
        'proof_of_document',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    const IMAGE_PATH = 'uploads/wallet_transaction/proof_of_document/';

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->wallet_transaction_code = $model->generateWalletTransactionCode();
        });

        static::updating(function ($model) {
            if(auth()->user()){
                $model->updated_by = getAuthUserCode();
            }else{
                $model->updated_by = 'U00000001';
            }
            $model->updated_at = Carbon::now();
        });

    }

    public function generateWalletTransactionCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'WTXC', '1000', true);
    }

    public function wallet(){
        return $this->belongsTo(Wallet::class,'wallet_code','wallet_code');
    }

    public function walletTransactionPurpose(){
        return $this->belongsTo(WalletTransactionPurpose::class,'wallet_transaction_purpose_code','wallet_transaction_purpose_code');
    }
    public function getProofOfDocumentImagePath(){
        return photoToUrl($this->proof_of_document,asset(self::IMAGE_PATH));
    }

    public function generateReferenceCode(){
        return rand(0000000001, 9999999999);
    }

    public function createdBy(){
        return $this->belongsTo(User::class,'created_by','user_code');
    }

    public function extraRemarks(){
        return $this->hasMany(WalletTransactionRemark::class,'wallet_transaction_code','wallet_transaction_code');
    }

    public function getAllTransactionCorrectionReferenceCode($walletTransactionCode)
    {
        $referenceTransactionCode = self::where('transaction_purpose_reference_code',$walletTransactionCode)
            ->select('wallet_transaction_code')
            ->get();
        return ($referenceTransactionCode) ?
           $referenceTransactionCode:
           null;
    }

}
