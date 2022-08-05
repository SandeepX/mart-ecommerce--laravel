<?php

namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariantImage extends Model
{
    use SoftDeletes;
    protected $table = 'product_variant_images';
    protected $primaryKey = 'product_variant_image_code';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'product_code',
        'product_variant_code',
        'image',
    ];

    protected $hidden = [
        'backup_image'
    ];

    const IMAGE_PATH='uploads/products/variants/';

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->product_variant_image_code = $model->generateProductVariantImageCode();
            $model->backup_image = $model->image;
        });

        static::updating(function ($model) {
            $model->backup_image = $model->image;
        });

    }

    public static function generateProductVariantImageCode()
    {
        $productVariantImagePrefix = 'PVIC';
        $initialIndex = '1000';
        $productVariantImage = self::withTrashed()->latest('id')->first();
        if($productVariantImage){
            $codeTobePad = (int)(str_replace($productVariantImagePrefix,"",$productVariantImage->product_variant_image_code) +1 );
           // $paddedCode = str_pad($codeTobePad, 4, '0', STR_PAD_LEFT);
            $latestProductVariantImageCode = $productVariantImagePrefix.$codeTobePad;
        }else{
            $latestProductVariantImageCode = $productVariantImagePrefix.$initialIndex;
        }
        return $latestProductVariantImageCode;
    }
}
