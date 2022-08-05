<?php

namespace App\Modules\Store\Models\Kyc;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class IndividualKYCCitizenshipDetail extends Model
{
    use ModelCodeGenerator;

    protected $table = 'individual_kyc_citizenship_details';
    protected $primaryKey = 'kyc_c_d_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kyc_c_d_code',
        'kyc_code',
        'citizenship_no',
        //'citizenship_full_name',
        'citizenship_nationality',
        'citizenship_issued_date',
        'citizenship_gender',
        'citizenship_birth_place',
        'citizenship_district',
        //'citizenship_municipality',
        //'citizenship_ward_no',
        'citizenship_dob',
        'citizenship_father_name',
        //'citizenship_father_address',
        //'citizenship_father_nationality',
        'citizenship_mother_name',
        //'citizenship_mother_address',
        //'citizenship_mother_nationality',
        'citizenship_spouse_name',
        //'citizenship_spouse_address',
        //'citizenship_spouse_nationality',
        'citizenship_grandfather_name',
        //'citizenship_grandfather_nationality'
    ];


    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->kyc_c_d_code = $model->generateKycCDCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

    }


    public function individualKycMaster(){
        return $this->belongsTo(IndividualKYCMaster::class,'kyc_code','kyc_code');
    }

    public function generateKycCDCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'KYC-CD-', '1000', false);
    }


}
