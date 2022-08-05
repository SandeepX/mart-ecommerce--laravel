<?php

namespace App\Modules\Product\Models;

use App\Modules\Variants\Models\VariantValue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariantDetail extends Model
{
    use SoftDeletes;
    protected $table = 'product_variant_details';
    protected $primaryKey = 'product_variant_detail_code';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'product_variant_detail_code',
        'product_variant_code',
        'variant_value_code',
        'remarks',
        'created_by',
        'updated_by',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            $model->product_variant_detail_code = $model->generateProductVariantDetailCode();

        });
    }

    public static function generateProductVariantDetailCode()
    {
        $productVariantDetailPrefix = 'PVD';
        $initialIndex = '1000';
        $productVariantDetail = self::withTrashed()->latest('id')->first();
        if($productVariantDetail){
            $codeTobePad = (int) (str_replace($productVariantDetailPrefix,"",$productVariantDetail->product_variant_detail_code) +1 );
           // $paddedCode = str_pad($codeTobePad, 4, '0', STR_PAD_LEFT);
            $latestProductVariantDetailCode = $productVariantDetailPrefix.$codeTobePad;
        }else{
            $latestProductVariantDetailCode = $productVariantDetailPrefix.$initialIndex;
        }
        return $latestProductVariantDetailCode;
    }

    public function variantValue(){
        return $this->belongsTo(VariantValue::class, 'variant_value_code');
    }
}
