<?php

namespace App\Modules\Store\Models\Kyc;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class IndividualKYCFamilyDetail extends Model
{
    use ModelCodeGenerator;

    protected $table = 'individual_kyc_family_details';
    protected $primaryKey = 'kyc_family_detail_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'spouse_name' ,
        'father_name' ,
        'mother_name' ,
        'grand_father_name' ,
        'grand_mother_name' ,
        'sons',
        'daughters',
        'daughter_in_laws',
        'father_in_law' ,
        'mother_in_law'

    ];


    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->kyc_family_detail_code = $model->generateKycFamilyCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

    }

    public function individualKycMaster(){
        return $this->belongsTo(IndividualKYCMaster::class,'kyc_code','kyc_code');
    }

    public function generateKycFamilyCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'KYC-FAM-', '1000', false);
    }


}
