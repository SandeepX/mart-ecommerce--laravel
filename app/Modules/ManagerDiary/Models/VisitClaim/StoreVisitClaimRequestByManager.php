<?php

namespace App\Modules\ManagerDiary\Models\VisitClaim;

use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\ManagerDiary\Models\Diary\ManagerDiary;
use App\Modules\User\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class StoreVisitClaimRequestByManager extends Model
{
    use ModelCodeGenerator;
    protected $table = 'store_visit_claim_requests_by_manager';
    protected $primaryKey = 'store_visit_claim_request_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'store_visit_claim_request_code',
        'manager_diary_code',
        'status',
        'manager_latitude',
        'manager_longitude',
        'manager_device_info',
        'store_latitude',
        'store_longitude',
        'store_device_info',
        'qr_scanned_at',
        'responded_by',
        'responded_at',
        'remarks',
        'visit_image',
        'submitted_at',
        'pay_per_visit',
        'created_by',
        'updated_by'
    ];

    CONST PAGINATE_BY = 20;

    CONST MAXIMUM_DISTANCE = 10;

    CONST VISIT_IMAGE_PATH = 'uploads/visit-claim-request/visit_image/';

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->store_visit_claim_request_code = $model->generateCode();
        });
        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });
    }

    public function generateCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'SVCRC', 1000, false);
    }

    public function managerDiary(){
        return $this->belongsTo(ManagerDiary::class,'manager_diary_code','manager_diary_code');
    }

    public function createdBy(){
        return $this->belongsTo(User::class,'created_by','user_code');
    }

    public function updatedBy(){
        return $this->belongsTo(User::class,'updated_by','user_code');
    }

    public function respondedBy(){
        return $this->belongsTo(User::class,'responded_by','user_code');
    }

    public function getVisitImagePath(){
        return self::VISIT_IMAGE_PATH;
    }

}
