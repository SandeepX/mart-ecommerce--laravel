<?php

namespace App\Modules\Store\Models\StorePackageTypes;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreTypePackageMaster extends Model
{
    use ModelCodeGenerator,SoftDeletes;

    protected $table = 'store_type_package_master';
    protected $primaryKey = 'store_type_package_master_code';
    public $incrementing = false;
    protected $keyType = 'string';
    const IMAGE_PATH='uploads/stores/storetypepackages/images/';

    protected $fillable = [
        'store_type_package_master_code',
        'store_type_code',
        'package_name',
        'sort_order',
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
            $model->store_type_package_master_code = $model->generateCode();
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
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'STPMC', '1000', true);
    }

    public function storeTypePackageHistory(){
        return $this->hasMany(StoreTypePackageHistory::class,'store_type_package_master_code');
    }



}
