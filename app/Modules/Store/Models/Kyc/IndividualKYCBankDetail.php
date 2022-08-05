<?php

namespace App\Modules\Store\Models\Kyc;

use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Bank\Models\Bank;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class IndividualKYCBankDetail extends Model
{
    use ModelCodeGenerator;

    protected $table = 'individual_kyc_bank_details';
    protected $primaryKey = 'kyc_bank_detail_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kyc_bank_detail_code',
        'kyc_code',
        'bank_code',
        'bank_branch_name',
        'bank_account_no',
        'bank_account_holder_name'

    ];


    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->kyc_bank_detail_code = $model->generateKycBankCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

    }

    public function individualKycMaster(){
        return $this->belongsTo(IndividualKYCMaster::class,'kyc_code','kyc_code');
    }

    public function generateKycBankCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'KYC-BNK-', '1000', false);
    }

    public function bank(){
        return $this->belongsTo(Bank::class,'bank_code','bank_code');
    }


}
