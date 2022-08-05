<?php

namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductWarrantyDetail extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'product_warranty_detail_code';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [ 
        'product_code',
        'warranty_code',
        'warranty_policy',
        
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->product_warranty_detail_code = $model->generateProductWarrantyDetailCode();
        });
    }

    public static function generateProductWarrantyDetailCode()
    {
        $productWarrantyCodeDetailPrefix = 'PWD';
        $initialIndex = '1000';
        $productWarrantyCodeDetail = self::withTrashed()->latest('id')->first();
        if($productWarrantyCodeDetail){
            $codeTobePad = (int)(str_replace($productWarrantyCodeDetailPrefix,"",$productWarrantyCodeDetail->product_warranty_detail_code) +1 );
           // $paddedCode = str_pad($codeTobePad, 4, '0', STR_PAD_LEFT);
            $latestProductWarrantyCodeDetailCode = $productWarrantyCodeDetailPrefix.$codeTobePad;
        }else{
            $latestProductWarrantyCodeDetailCode = $productWarrantyCodeDetailPrefix.$initialIndex;
        }
        return $latestProductWarrantyCodeDetailCode;
    }

    public function productWarranty(){
        return $this->belongsTo(ProductWarranty::class, 'warranty_code');
    }
}
