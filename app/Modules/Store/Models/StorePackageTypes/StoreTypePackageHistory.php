<?php

namespace App\Modules\Store\Models\StorePackageTypes;

use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Types\Models\StoreType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreTypePackageHistory extends Model
{
    use ModelCodeGenerator,SoftDeletes;

    protected $table = 'store_type_package_history';
    protected $primaryKey = 'store_type_package_history_code';
    public $incrementing = false;
    protected $keyType = 'string';
    const IMAGE_PATH='uploads/stores/storetypepackages/histories/images/';

    protected $fillable = [
        'store_type_package_history_code',
        'from_date',
        'to_date',
        'store_type_package_master_code',
        'store_type_code',
        'package_name',
        'package_slug',
        'description',
        'image',
        'refundable_registration_charge',
        'non_refundable_registration_charge',
        'base_investment',
        'annual_purchasing_limit',
        'referal_registration_incentive_amount',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->store_type_package_history_code = $model->generateCode();
            $model->created_by = getAuthUserCode();
            $model->updated_by = getAuthUserCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
            $model->updated_by = getAuthUserCode();
        });
        static::deleting(function ($model) {
            $model->deleted_at = Carbon::now();
            $model->deleted_by = getAuthUserCode();
        });

    }

    public function generateCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'STPHC', '1000', true);
    }


    public function storeType()
    {
        return $this->belongsTo(StoreType::class,'store_type_code','store_type_code');
    }

    public function storeTypePackageMaster()
    {
        return $this->belongsTo(StoreTypePackageMaster::class,'store_type_package_master_code');
    }

}
