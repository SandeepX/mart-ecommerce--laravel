<?php

namespace App\Modules\Store\Models\Kyc;

use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Store\Models\Store;
use App\Modules\User\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class FirmKycMaster extends Model
{
    use  ModelCodeGenerator;

    protected $table = 'firm_kyc_master';
    protected $primaryKey = 'kyc_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kyc_code',
        'user_code',
        'store_code',
        'business_name',
        'business_capital',
        'business_registered_from',
        'business_registered_address',
        'business_address_latitude',
        'business_address_longitude',

        'business_pan_vat_type',
        'business_pan_vat_number',
        'business_registration_no',
        'business_registered_date',
        'purpose_of_business',
        'share_holders_no',
        'store_location_ward_no',
        'verification_status',
        'responded_by',
        'responded_at',
        'remarks',
        'can_update_kyc',
        'update_request_allowed_by'
    ];

    const BUSINESS_REGISTERED_FROM = [
        'Ward Palika Gharelu' => 'ward-palika-gharelu',
        'Private Public Ltd' =>'private-public-ltd',
        'Partnership'=>'partnership'
    ];
    const VERIFICATION_STATUSES=['pending','verified','rejected'];

    const RECORDS_PER_PAGE=10;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->kyc_code = $model->generateFirmKycCode();
        });


        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

    }


    public function generateFirmKycCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'KYC-F-', '1000', false);
    }

    public function store(){
        return $this->belongsTo(Store::class, 'store_code', 'store_code');
    }

    public function submittedBy(){
        return $this->belongsTo(User::class, 'user_code', 'user_code');
    }
    public function respondedBy(){
        return $this->belongsTo(User::class, 'responded_by', 'user_code');
    }

    public function kycDocuments(){
        return $this->hasMany(FirmKycDocument::class,'kyc_code','kyc_code');
    }

    public function kycBanksDetail(){
        return $this->hasMany(FirmKycBankDetail::class,'kyc_code','kyc_code');
    }

    public function isPending(){

        if ($this->verification_status == 'pending'){
            return true;
        }
        return false;
    }

    public function isVerified(){

        if ($this->verification_status == 'verified'){
            return true;
        }
        return false;
    }

    public function isRejected(){

        if ($this->verification_status == 'rejected'){
            return true;
        }
        return false;
    }
    public function kycUpdateRequestProvider(){
        return $this->belongsTo(User::class,'update_request_allowed_by','user_code');
    }
}
