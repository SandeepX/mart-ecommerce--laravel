<?php

namespace App\Modules\Product\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariantGroup extends Model
{
    use SoftDeletes, ModelCodeGenerator;
    protected $table = 'product_variant_groups';
    protected $primaryKey = 'product_variant_group_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'product_variant_group_code',
        'product_code',
        'group_name',
        'group_variant_value_code'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->product_variant_group_code = $model->generateProductVariantGroupCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

        static::deleting(function ($model){
            $model->deleted_at = Carbon::now();
        });

    }

    public function generateProductVariantGroupCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'PVGC', '1000', true);
    }

    public function product(){
        return $this->belongsTo(ProductMaster::class, 'product_code','product_code');
    }

    public function variantGroupBulkImages()
    {
        return $this->hasMany(PVGroupBulkImage::class, 'product_variant_group_code','product_variant_group_code');
    }

    public function groupProductVariants(){
        return $this->hasMany(ProductVariant::class, 'product_variant_group_code','product_variant_group_code');
    }

}
