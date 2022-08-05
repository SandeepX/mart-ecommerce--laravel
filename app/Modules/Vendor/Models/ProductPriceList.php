<?php

namespace App\Modules\Vendor\Models;

use App\Modules\Product\Models\ProductVariant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductPriceList extends Model
{
    use SoftDeletes;
    protected $table = 'product_price_lists';

    protected $primaryKey = 'product_price_list_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'product_code',
        'product_variant_code',
        'mrp',
        'admin_margin_type',
        'admin_margin_value',
        'wholesale_margin_type',
        'wholesale_margin_value',
        'retail_store_margin_type',
        'retail_store_margin_value',
    ];

    public function generateCode()
    {
        $prefix = 'PPL';
        $initialIndex = '1000';
        $productPrice= self::withTrashed()->latest('id')->first();
        if($productPrice){
            $codeTobePad = (int)str_replace($prefix,"",$productPrice->product_price_list_code) +1 ;
           // $paddedCode = str_pad($codeTobePad, 5, '0', STR_PAD_LEFT);
            $latestCode = $prefix.$codeTobePad;
        }else{
            $latestCode = $prefix.$initialIndex;
        }
        return $latestCode;
    }


    public function productVariant(){
        return $this->belongsTo(ProductVariant::class,'product_variant_code');
    }
}