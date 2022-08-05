<?php

namespace App\Modules\Store\Models\Kyc;

use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Application\Traits\SetTimeZone;
use App\Modules\Store\Models\Store;
use App\Modules\User\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class IndividualKYCMaster extends Model
{
    use  ModelCodeGenerator,SetTimeZone;

    protected $table = 'individual_kyc_master';
    protected $primaryKey = 'kyc_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kyc_code',
        'user_code',
        'store_code',
        'kyc_for',
        'name_in_devanagari',
        'name_in_english',
        'marital_status',
       // 'gender',
        'pan_no',
        'educational_qualification',
        'permanent_house_no',
        'permanent_street',
        'permanent_ward_no',
        'present_house_no',
        'present_street',
        'present_ward_no',
       // 'landmark',
       // 'latitude',
       // 'longitude',
        'landlord_name',
        'landlord_contact_no',
        'verification_status',
        'responded_by',
        'responded_at',
        'remarks',
        'can_update_kyc',
        'update_request_allowed_by'
    ];


    const EDUCATIONAL_QUALIFICATIONS=['illiterate','literate','see','plus_two','bachelors','masters','phd'];

    const KYC_FOR_TYPES =['sanchalak','akhtiyari'];

    const VERIFICATION_STATUSES=['pending','verified','rejected'];
    const RECORDS_PER_PAGE=10;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->kyc_code = $model->generateKYCCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

    }


    public function generateKYCCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'KYC-I-', '1000', false);
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

    public function kycFamilyDetail(){
        return $this->hasOne(IndividualKYCFamilyDetail::class,'kyc_code','kyc_code');
    }

    public function kycCitizenshipDetail(){
        return $this->hasOne(IndividualKYCCitizenshipDetail::class,'kyc_code','kyc_code');
    }

    public function kycDocuments(){
        return $this->hasMany(IndividualKYCDocument::class,'kyc_code','kyc_code');
    }

    public function kycBanksDetail(){
        return $this->hasMany(IndividualKYCBankDetail::class,'kyc_code','kyc_code');
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
