<?php

namespace App\Modules\Store\Models\Kyc;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class IndividualKYCDocument extends Model
{
    use ModelCodeGenerator;

    protected $table = 'individual_kyc_documents';
    protected $primaryKey = 'kyc_document_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kyc_document_code',
        'kyc_code',
        'document_type',
        'document_file'
    ];


    const DOCUMENT_TYPES = ['citizenship_front','citizenship_back'];

    const DOCUMENT_PATH = 'uploads/stores/kyc/individuals/documents/';

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->kyc_document_code = $model->generateKycDocCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

    }


    public function individualKycMaster(){
        return $this->belongsTo(IndividualKYCMaster::class,'kyc_code','kyc_code');
    }

    public function generateKycDocCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'KYC-DOC-', '1000', false);
    }


}
