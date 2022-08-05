<?php

namespace App\Modules\Store\Models\Kyc;

use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Store\Models\Store;
use App\Modules\User\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class KycAgreementVideo extends Model
{
    use  ModelCodeGenerator;

    protected $table = 'kyc_agreement_videos';
    protected $primaryKey = 'kyc_agreement_vcode';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kyc_agreement_vcode',
        'user_code',
        'store_code',
        'agreement_video_for',
        'agreement_video_name'
    ];


    const VIDEO_UPLOAD_PATH = 'uploads/stores/kyc/agreement-videos/';

    const AGREEMENT_VIDEO_FOR_TYPES =['samjhauta_patra','akhtiyari_patra'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->kyc_agreement_vcode = $model->generateAgreementVideoCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

    }


    public function generateAgreementVideoCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'KYC-AGV-', '1000', false);
    }

    public function store(){
        return $this->belongsTo(Store::class, 'store_code', 'store_code');
    }

    public function submittedBy(){
        return $this->belongsTo(User::class, 'user_code', 'user_code');
    }

}
