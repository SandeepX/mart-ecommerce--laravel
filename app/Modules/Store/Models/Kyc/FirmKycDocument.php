<?php

namespace App\Modules\Store\Models\Kyc;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class FirmKycDocument extends Model
{
    use ModelCodeGenerator;

    protected $table = 'firm_kyc_documents';
    protected $primaryKey = 'kyc_document_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kyc_document_code',
        'kyc_code',
        'document_type',
        'document_file'
    ];


    const DOCUMENT_TYPES = [
        'firm_darta_pramaan_patra',
        'prabhanda_patra',
        'niyamaawali',
        'pan_vat_darta',
        'minute'
        ];

    const DOCUMENT_PATH = 'uploads/stores/kyc/firms/documents/';



    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->kyc_document_code = $model->generateFirmKycDocCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

    }


    public function firmKycMaster(){
        return $this->belongsTo(FirmKycMaster::class,'kyc_code','kyc_code');
    }

    public function generateFirmKycDocCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'KYC-F-DOC-', '1000', false);
    }


}
