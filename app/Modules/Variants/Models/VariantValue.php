<?php

namespace App\Modules\Variants\Models;

use App\Modules\Application\Traits\CheckDelete\CheckDelete;
use App\Modules\Product\Models\ProductVariantDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VariantValue extends Model
{
    use SoftDeletes,CheckDelete;
    protected $table = 'variant_values';
    protected $primaryKey = 'variant_value_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'variant_value_name',
        'variant_value_code', // Pk
        'variant_code',
        'slug',
        'created_by',
        'updated_by'
    ];

    public static function generateVariantValueCode()
    {
        $variantValuePrefix = 'VL';
        $initialIndex = '0001';
        $variantValue = self::withTrashed()->latest('created_at')->first();
        if($variantValue){
            $codeTobePad = str_replace($variantValuePrefix,"",$variantValue->variant_value_code) +1 ;
            $paddedCode = str_pad($codeTobePad, 4, '0', STR_PAD_LEFT);
            $latestVariantValueCode = $variantValuePrefix.$paddedCode;
        }else{
            $latestVariantValueCode = $variantValuePrefix.$initialIndex;
        }
        return $latestVariantValueCode;
    }

    public function variant(){
        return $this->belongsTo(Variant::class, 'variant_code');
    }


    public function productVariantDetails(){
        return $this->hasMany(ProductVariantDetail::class,'variant_value_code');
    }

    
}
