<?php

namespace App\Modules\ManagerDiary\Models\VisitClaim;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class StoreVisitClaimScanRedirection extends Model
{
    use ModelCodeGenerator;
    protected $table = 'store_visit_claim_scan_redirections';
    protected $primaryKey = 'store_visit_claim_scan_redirection_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'store_visit_claim_scan_redirection_code',
        'title',
        'image',
        'app_page',
        'external_link',
        'is_active',
        'created_by',
        'updated_by'
    ];

    CONST APP_PAGE = ['Quiz','Link'];

    CONST IMAGE_PATH = 'uploads/visit-claim-redirection/image/';

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->store_visit_claim_scan_redirection_code = $model->generateCode();
        });
        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });
    }

    public function generateCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'SVCSRC', 1000, false);
    }

    public function getImageUploadPath(){

        return self::IMAGE_PATH;
    }

}
