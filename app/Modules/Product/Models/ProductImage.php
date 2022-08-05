<?php

namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductImage extends Model
{
    use SoftDeletes;
    protected $table = 'product_images';
    protected $primaryKey = 'product_image_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'product_image_code',
        'product_code',
        'image',
    ];

    protected $hidden = [
        'backup_image'
    ];
    const IMAGE_PATH = "uploads/products/";

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->backup_image = $model->image;
        });

       static::updating(function ($model) {
           $model->backup_image = $model->image;
       });

    }



    public static function generateProductImageCode()
    {
        $productImagePrefix = 'PIC';
        $initialIndex = '1000';
        $productImage = self::withTrashed()->latest('id')->first();
        if($productImage){
            $codeTobePad = (int) (str_replace($productImagePrefix,"",$productImage->product_image_code) +1 );
           // $paddedCode = str_pad($codeTobePad,5, '0', STR_PAD_LEFT);
            $latestProductImageCode = $productImagePrefix.$codeTobePad;
        }else{
            $latestProductImageCode = $productImagePrefix.$initialIndex;
        }
        return $latestProductImageCode;
    }
}
