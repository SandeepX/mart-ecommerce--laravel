<?php


namespace App\Modules\Vendor\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Application\Traits\SetTimeZone;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\User\Models\User;
use App\Modules\Product\Models\ProductMaster;

class VendorTargetIncentive extends Model
{
    use SoftDeletes, ModelCodeGenerator, SetTimeZone;

    protected $table = 'vendor_target_incentive';

    protected $primaryKey = 'vendor_target_incentive_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'vendor_target_incentive_code',
        'vendor_target_master_code',
        'product_code',
        'product_variant_code',
        'starting_range',
        'end_range',
        'incentive_type',
        'incentive_value',
        'has_meet_target',
        'created_by',
        'updated_by',

    ];

    const INCENTIVE_TYPE = ['p','f' ];

    const ROWS_PER_PAGE = 10;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->vendor_target_incentive_code = $model->generateCode();
            $model->created_by = getAuthUserCode();
        });

        static::deleting(function ($model) {
            $model->deleted_by = getAuthUserCode();
            $model->save();
        });
    }

    public function generateCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'VTI', 1000, true);
    }

    public function vendorTargetmaster()
    {
        return $this->belongsTo(VendorTargetMaster::class, 'vendor_target_master_code','vendor_target_master_code');
    }

    public function productDetail()
    {
        return $this->belongsToMany(ProductMaster::class,'product_code','product_code');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by','user_code');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by','user_code');
    }

}

