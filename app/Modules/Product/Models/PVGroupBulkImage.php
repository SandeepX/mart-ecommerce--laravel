<?php

namespace App\Modules\Product\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PVGroupBulkImage extends Model
{
    use SoftDeletes,ModelCodeGenerator;
    protected $table = 'pv_group_bulk_images';
    protected $primaryKey = 'pv_group_bulk_image_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'pv_group_bulk_image_code',
        'product_variant_group_code',
        'image'
    ];
    const IMAGE_PATH='uploads/products/variants/groups/';

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->pv_group_bulk_image_code = $model->generatePVGroupBulkImageCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

        static::deleting(function ($model){
            $model->deleted_at = Carbon::now();
        });

    }

    public function generatePVGroupBulkImageCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'PVGBIC', '1000', true);
    }

    public function variantGroup(){
        return $this->belongsTo(ProductVariantGroup::class, 'product_variant_group_code','product_variant_group_code');
    }
}
