<?php

namespace App\Modules\Variants\Models;

use App\Modules\Application\Traits\CheckDelete\CheckDelete;
use App\Modules\Product\Models\ProductMaster;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Variant extends Model
{
    use SoftDeletes,CheckDelete;

    protected $table = 'variants';

    protected $primaryKey = 'variant_code';
    public $incrementing = false;
    protected $keyType = 'string';


    protected $fillable = [
        'id',
        'variant_name',
        'variant_code',
        'slug',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public static function generateVariantCode()
    {
        $variantPrefix = 'V';
        $initialIndex = '00001';
        $variant = self::withTrashed()->latest('created_at')->first();
        if($variant){
            $codeTobePad = str_replace($variantPrefix,"",$variant->variant_code) +1 ;
            $paddedCode = str_pad($codeTobePad, 5, '0', STR_PAD_LEFT);
            $latestvariantCode = $variantPrefix.$paddedCode;
        }else{
            $latestvariantCode = $variantPrefix.$initialIndex;
        }
        return $latestvariantCode;
    }

    public function variantValues(){
        return $this->hasMany(VariantValue::class, 'variant_code', 'variant_code');
    }
}
