<?php

namespace App\Modules\Product\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductPriceList extends Model
{
    use SoftDeletes,ModelCodeGenerator;
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
        return $this->generateModelCodeWithOutZeroPadding($this,$this->primaryKey,'PPL','1000',true);

        // $prefix = 'PPL';
        // $initialIndex = '00001';
        // $productPrice= self::withTrashed()->latest('id')->first();
        // if($productPrice){
        //     $codeTobePad = str_replace($prefix,"",$productPrice->product_price_list_code) +1 ;
        //     $paddedCode = str_pad($codeTobePad, 5, '0', STR_PAD_LEFT);
        //     $latestCode = $prefix.$paddedCode;
        // }else{
        //     $latestCode = $prefix.$initialIndex;
        // }
        // return $latestCode;
    }

    public function productVariant(){
        return $this->belongsTo(ProductVariant::class,'product_variant_code');
    }

    public function product(){
        return $this->belongsTo(ProductMaster::class,'product_code');
    }

}
