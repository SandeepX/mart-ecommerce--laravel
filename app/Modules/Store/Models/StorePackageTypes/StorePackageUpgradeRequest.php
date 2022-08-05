<?php

namespace App\Modules\Store\Models\StorePackageTypes;

use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Types\Models\StoreType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class StorePackageUpgradeRequest extends Model
{
    use ModelCodeGenerator;

    protected $table = 'store_package_upgrade_request';
    protected $primaryKey = 'store_package_upgrade_request_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'store_package_upgrade_request_code',
        'requested_store_type',
        'store_code',
        'requested_package_type',
        'status',
        'remark',
        'requested_by',
        'responded_by',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->store_package_upgrade_request_code = $model->generateCode();
            $model->requested_by = getAuthUserCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

    }

    public function generateCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'SPUR', '1000', false);
    }

    public function storeType(){
        return $this->belongsTo(StoreType::class,'requested_store_type','store_type_code');
    }
    public function storeTypePackage(){
        return $this->belongsTo(StoreTypePackageHistory::class,'requested_package_type','store_type_package_history_code');
    }



}
